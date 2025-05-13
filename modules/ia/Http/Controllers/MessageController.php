<?php

namespace Diji\Ia\Http\Controllers;

use App\Http\Controllers\Controller;
use Diji\Ia\Models\File;
use Diji\Ia\Models\FileMessage;
use Illuminate\Http\Request;
use Diji\Ia\Models\Message;
use Diji\Ia\Models\Thread;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $payload = $request->validate([
            'openai_id'        => 'required|string|unique:messages,openai_id',
            'thread_openai_id' => 'required|string|exists:threads,openai_id',
            'role'             => 'required|in:user,assistant',
            'raw_text'         => 'nullable|string',
            'attachments'      => 'nullable|array',
        ]);

        $thread = Thread::where('openai_id', $payload['thread_openai_id'])->firstOrFail();
        $payload['thread_id'] = $thread->id;
        unset($payload['thread_openai_id']);

        $msg = Message::create($payload);

        return response()->json(['data' => $msg], 201);
    }

    public function index(Request $request)
    {
        $request->validate(['thread_openai_id' => 'required|string']);

        $thread = Thread::where('openai_id', $request->thread_openai_id)->firstOrFail();

        return response()->json([
            'data' => $thread->messages()->orderBy('created_at')->get()
        ]);
    }

    public function destroy(string $openaiId)
    {
        $msg = Message::where('openai_id', $openaiId)->first();

        if (!$msg) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        // Récupère tous les liens file ↔ message
        $links = FileMessage::where('message_openai_id', $openaiId)->get();

        foreach ($links as $link) {
            $fileId = $link->file_openai_id;
            $link->delete();

            // Supprime le fichier s’il n’est plus lié à d’autres messages
            $stillUsed = FileMessage::where('file_openai_id', $fileId)->exists();
            if (!$stillUsed) {
                File::where('openai_id', $fileId)->delete();
            }
        }

        // Supprime le message (puis cascade FK threads)
        $msg->delete();

        return response()->json(['message' => 'Message deleted']);
    }
}
