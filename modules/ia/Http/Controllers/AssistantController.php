<?php

namespace Diji\Ia\Http\Controllers;

use App\Http\Controllers\Controller;
use Diji\Ia\Models\Assistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssistantController extends Controller
{
    /**
     * Afficher tous les assistants associés au tenant.
     */
    public function index()
    {
        // Recherche du tenant dans la base principale (mysql)
        $userTenant = DB::connection('mysql')->table('user_tenants')
            ->where('user_id', auth()->user()->id)  // Utilise l'ID de l'utilisateur pour récupérer son tenant
            ->first();

        if (!$userTenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        // Récupérer les assistants associés au tenant via la table de relation 'assistant_user_tenant'
        $assistants = DB::connection('tenant')->table('assistant_user_tenant')
            ->join('assistants', 'assistant_user_tenant.assistant_id', '=', 'assistants.id')
            ->where('assistant_user_tenant.user_tenant_id', $userTenant->id)
            ->select('assistants.*')  // Sélectionner uniquement les colonnes de la table 'assistants'
            ->get();

        return response()->json($assistants);
    }

    /**
     * Créer un nouvel assistant et l'associer à l'utilisateur connecté.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'required|string',
            'model' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        // Récupérer l'utilisateur authentifié via le token JWT
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Créer l'assistant
        $assistant = Assistant::create([
            'name' => $request->name,
            'description' => $request->description,
            'module' => $request->module,
            'model' => $request->model,
            'instructions' => $request->instructions,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Recherche du tenant dans la base principale (mysql)
        $userTenant = DB::connection('mysql')->table('user_tenants')
            ->where('user_id', $user->id)  // Utilise l'ID de l'utilisateur pour récupérer son tenant
            ->first();

        if (!$userTenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        // Associer l'assistant au tenant dans la table pivot 'assistant_user_tenant'
        DB::connection('tenant')->table('assistant_user_tenant')->insertOrIgnore([
            'assistant_id' => $assistant->id,
            'user_tenant_id' => $userTenant->id,  // Associe l'assistant au tenant de l'utilisateur
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json($assistant, 201);
    }

    /**
     * Afficher un assistant spécifique.
     */
    public function show($id)
    {
        // Recherche du tenant dans la base principale (mysql)
        $userTenant = DB::connection('mysql')->table('user_tenants')
            ->where('user_id', auth()->user()->id)  // Utilise l'ID de l'utilisateur pour récupérer son tenant
            ->first();

        if (!$userTenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        // Récupérer l'assistant spécifique via la table de relation 'assistant_user_tenant'
        $assistant = DB::connection('tenant')->table('assistant_user_tenant')
            ->join('assistants', 'assistant_user_tenant.assistant_id', '=', 'assistants.id')
            ->where('assistant_user_tenant.user_tenant_id', $userTenant->id)
            ->where('assistants.id', $id)  // Trouver l'assistant par son ID
            ->select('assistants.*')
            ->first();

        if (!$assistant) {
            return response()->json(['message' => 'Assistant not found'], 404);
        }

        return response()->json($assistant);
    }

    /**
     * Mettre à jour un assistant spécifique.
     */
    public function update(Request $request, $id)
    {
        // Recherche du tenant dans la base principale (mysql)
        $userTenant = DB::connection('mysql')->table('user_tenants')
            ->where('user_id', auth()->user()->id)  // Utilise l'ID de l'utilisateur pour récupérer son tenant
            ->first();

        if (!$userTenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        // Récupérer l'assistant spécifique via la table de relation 'assistant_user_tenant'
        $assistant = DB::connection('tenant')->table('assistant_user_tenant')
            ->join('assistants', 'assistant_user_tenant.assistant_id', '=', 'assistants.id')
            ->where('assistant_user_tenant.user_tenant_id', $userTenant->id)
            ->where('assistants.id', $id)  // Trouver l'assistant par son ID
            ->select('assistants.*')
            ->first();

        if (!$assistant) {
            return response()->json(['message' => 'Assistant not found'], 404);
        }

        // Valider les données de la requête
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'required|string',
            'model' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        // Mettre à jour l'assistant avec les nouvelles données
        $updatedAssistant = DB::connection('tenant')->table('assistants')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'description' => $request->description,
                'module' => $request->module,
                'model' => $request->model,
                'instructions' => $request->instructions,
                'updated_at' => now(),
            ]);

        if ($updatedAssistant) {
            return response()->json(['message' => 'Assistant updated successfully']);
        }

        return response()->json(['message' => 'Failed to update assistant'], 400);
    }

    /**
     * Supprimer un assistant spécifique.
     */
    public function destroy($id)
    {
        // Recherche du tenant dans la base principale (mysql)
        $userTenant = DB::connection('mysql')->table('user_tenants')
            ->where('user_id', auth()->user()->id)  // Utilise l'ID de l'utilisateur pour récupérer son tenant
            ->first();

        if (!$userTenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        // Récupérer l'assistant spécifique via la table de relation 'assistant_user_tenant'
        $assistant = DB::connection('tenant')->table('assistant_user_tenant')
            ->join('assistants', 'assistant_user_tenant.assistant_id', '=', 'assistants.id')
            ->where('assistant_user_tenant.user_tenant_id', $userTenant->id)
            ->where('assistants.id', $id)  // Trouver l'assistant par son ID
            ->select('assistants.*')
            ->first();

        if (!$assistant) {
            return response()->json(['message' => 'Assistant not found'], 404);
        }

        // Supprimer l'assistant de la table 'assistants'
        $deleted = DB::connection('tenant')->table('assistants')->where('id', $id)->delete();

        if ($deleted) {
            // Supprimer également l'association dans la table pivot 'assistant_user_tenant'
            DB::connection('tenant')->table('assistant_user_tenant')
                ->where('assistant_id', $id)
                ->where('user_tenant_id', $userTenant->id)
                ->delete();

            return response()->json(['message' => 'Assistant deleted successfully']);
        }

        return response()->json(['message' => 'Failed to delete assistant'], 400);
    }
}
