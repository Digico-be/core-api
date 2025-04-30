<?php

namespace Diji\Ia\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Diji\Ia\Models\FileMessage;

class FileMessageController extends Controller
{
    public function store(Request $request)
    {
        $payload = $request->validate([
            'file_openai_id'    => 'required|string|exists:files,openai_id',
            'message_openai_id' => 'required|string',
            'thread_openai_id'  => 'nullable|string',
        ]);

        $fm = FileMessage::create($payload);

        return response()->json(['data' => $fm], 201);
    }

    public function index(Request $request)
    {
        $query = FileMessage::with('file');

        if ($request->filled('thread_openai_id')) {
            $query->where('thread_openai_id', $request->thread_openai_id);
        }

        return response()->json(['data' => $query->get()]);
    }

    public function destroyByMessage(string $messageId)
    {
        // On récupère tous les liens file ↔ message pour ce message
        $links = FileMessage::where('message_openai_id', $messageId)->get();

        foreach ($links as $link) {
            $fileId = $link->file_openai_id;

            // On supprime le lien
            $link->delete();

            // Vérifie s’il reste encore des messages liés à ce fichier
            $stillUsed = FileMessage::where('file_openai_id', $fileId)->exists();

            if (!$stillUsed) {
                // Supprime aussi le fichier de la table files
                \Diji\Ia\Models\File::where('openai_id', $fileId)->delete();
            }
        }

        return response()->json(['message' => 'Fichiers et liens supprimés']);
    }

}
