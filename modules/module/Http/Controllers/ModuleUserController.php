<?php

namespace Diji\Module\Http\Controllers;

use App\Http\Controllers\Controller;
use Diji\Module\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleUserController extends Controller
{
    public function attach(Request $request, $moduleId)
    {
        $module = Module::findOrFail($moduleId);

        $validated = $request->validate([
            'user_id' => 'required|exists:mysql.users,id', // <- vérifie dans la base centrale
        ]);

        DB::connection('tenant')->table('modules_users')->insertOrIgnore([
            'module_id' => $moduleId,
            'user_id' => $validated['user_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'User attached to module.']);
    }

    public function detach(Request $request, $moduleId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:mysql.users,id',
        ]);

        $deleted = DB::connection('tenant')->table('modules_users')
            ->where('module_id', (int) $moduleId)
            ->where('user_id', (int) $validated['user_id'])
            ->delete();

        return response()->json([
            'message' => 'User detached from module.',
            'deleted_rows' => $deleted,
        ]);
    }
    public function modulesForUser($userId)
    {
        // Récupère tous les modules
        $modules = \Diji\Module\Models\Module::all();

        // Récupère les IDs de modules liés à ce user
        $assignedModuleIds = DB::connection('tenant')
            ->table('modules_users')
            ->where('user_id', $userId)
            ->pluck('module_id')
            ->toArray();

        // Ajoute la propriété "assigned" à chaque module
        $modules->transform(function ($module) use ($assignedModuleIds) {
            $module->assigned = in_array($module->id, $assignedModuleIds);
            return $module;
        });

        return response()->json($modules);
    }

}
