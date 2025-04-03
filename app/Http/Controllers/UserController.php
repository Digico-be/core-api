<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserController
{
    public function index(Request $request): JsonResponse
    {
        Log::debug('Query Params test', $request->query());

        $tenantId = $request->header('X-Tenant');

        if (!$tenantId) {
            return response()->json([
                'message' => 'Tenant manquant dans les en-têtes.'
            ], 400);
        }

        $tenant = \App\Models\Tenant::where('id', $tenantId)->first();

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant introuvable.'
            ], 404);
        }

        tenancy()->end();

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        Log::debug('Pagination', ['page' => $page, 'limit' => $limit]);

        // Pagination SQL
        $paginator = User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        })
            ->with(['tenants' => function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)->withPivot('role');
            }])
            ->paginate($limit, ['*'], 'page', $page);

        // Transformation propre des données paginées
        $users = $paginator->getCollection()->map(function ($user) {
            $tenant = $user->tenants->first();
            return [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'role' => $tenant?->pivot->role,
            ];
        });

        return response()->json([
            'data' => [
                'users' => $users,
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }
    public function show(User $user)
    {
        return response()->json($user);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $tenantId = $request->header('X-Tenant');

        if (!$tenantId) {
            return response()->json(['message' => 'Tenant manquant dans les en-têtes.'], 400);
        }

        $tenant = \App\Models\Tenant::find($tenantId);

        if (!$tenant) {
            return response()->json(['message' => 'Tenant introuvable.'], 404);
        }

        // Vérifie que l'utilisateur est bien associé à ce tenant
        if (!$user->tenants->contains('id', $tenantId)) {
            return response()->json(['message' => 'Cet utilisateur n\'appartient pas à ce tenant.'], 403);
        }

        // Détache l'utilisateur du tenant uniquement
        $user->tenants()->detach($tenantId);

        // Si l'utilisateur n'a plus aucun tenant, on peut le supprimer complètement (optionnel)
        if ($user->tenants()->count() === 0) {
            $user->delete();
        }

        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }
}
