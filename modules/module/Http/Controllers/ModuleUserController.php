<?php

namespace Diji\Module\Http\Controllers;

use App\Http\Controllers\Controller;
use Diji\Module\Models\Module;
use Illuminate\Http\Request;

class ModuleUserController extends Controller
{
    public function attach(Request $request, Module $module)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $module->users()->attach($validated['user_id']);

        return response()->json(['message' => 'User attached to module.']);
    }

    public function detach(Request $request, Module $module)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $module->users()->detach($validated['user_id']);

        return response()->json(['message' => 'User detached from module.']);
    }

    public function users(Module $module)
    {
        return $module->users;
    }
}
