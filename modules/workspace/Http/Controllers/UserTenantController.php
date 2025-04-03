<?php

namespace Diji\Workspace\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserTenantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Récupérer tous les tenants avec le pivot (role)
        $tenants = $user->tenants()->withPivot('role')->get()->map(function ($tenant) {
            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'role' => $tenant->pivot->role,
            ];
        });

        return response()->json([
            'tenants' => $tenants,
        ]);
    }
}
