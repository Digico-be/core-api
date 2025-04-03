<?php

namespace App\Http\Controllers;

use App\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController
{
    public function index(Request $request): JsonResponse
    {
        $tenantId = tenant()?->id;

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $paginator = (new User())
            ->setConnection('mysql') // forcer l'utilisation de la base centrale
            ->whereHas('tenants', function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })
            ->with(['tenants' => function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)->withPivot('role');
            }])
            ->paginate($limit, ['*'], 'page', $page);


        return response()->json([
            'data' => [
                'users' => UserResource::collection($paginator->getCollection()),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $tenantId = tenant()?->id;

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:guest,customer,personnal,admin',
        ]);

        $emailExists = DB::connection('mysql')
            ->table('users')
            ->where('email', $request->input('email'))
            ->exists();

        if ($emailExists) {
            $validator->errors()->add('email', 'Cet email est déjà utilisé par un autre utilisateur.');
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $user = (new User())
            ->setConnection('mysql')
            ->create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

        $user = User::on('mysql')->find($user->id); // recharge avec la bonne connexion
        $user->tenants()->attach($tenantId, ['role' => $validated['role']]);

        return response()->json(['message' => 'Utilisateur créé avec succès.', 'user' => $user], 201);
    }


    public function destroy(Request $request, User $user): JsonResponse
    {
        $tenantId = tenant()?->id;

        if (!$user->tenants->contains('id', $tenantId)) {
            return response()->json(['message' => 'Cet utilisateur n\'appartient pas à ce tenant.'], 403);
        }

        $user->tenants()->detach($tenantId);

        if ($user->tenants()->count() === 0) {
            $user->delete();
        }

        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $tenantId = tenant()?->id;

        if (!$user->tenants->contains('id', $tenantId)) {
            return response()->json(['message' => 'Cet utilisateur n\'appartient pas à ce tenant.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:guest,customer,personnal,admin',
        ]);

        $exists = DB::connection('mysql')
            ->table('users')
            ->where('email', $request->input('email'))
            ->where('id', '<>', $user->id)
            ->exists();

        if ($exists) {
            $validator->errors()->add('email', 'Cet email est déjà utilisé par un autre utilisateur.');
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $user->update([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            ...(isset($validated['password']) ? ['password' => bcrypt($validated['password'])] : []),
        ]);

        $user->tenants()->updateExistingPivot($tenantId, ['role' => $validated['role']]);

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès.',
            'user' => $user->load(['tenants' => fn ($q) => $q->where('tenant_id', $tenantId)])
        ]);

    }
}
