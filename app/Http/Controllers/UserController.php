<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Facades\Tenancy;


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

        $limit = $request->get('limit', 1);
        $page = $request->get('page', 1);

        Log::debug('Pagination', ['page' => $page, 'limit' => $limit]);

        // ✅ Pagination SQL
        $paginator = User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        })
            ->with(['tenants' => function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)->withPivot('role');
            }])
            ->paginate($limit, ['*'], 'page', $page);

        // ✅ Transformation propre des données paginées
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

}
