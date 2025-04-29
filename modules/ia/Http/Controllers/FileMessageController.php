<?php

namespace Diji\Ia\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Diji\Ia\Models\FileMessage;

class FileMessageController extends Controller
{
    /** POST /api/file-messages */
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

    /** GET /api/file-messages?thread_openai_id=xxx */
    public function index(Request $request)
    {
        $query = FileMessage::with('file');

        if ($request->filled('thread_openai_id')) {
            $query->where('thread_openai_id', $request->thread_openai_id);
        }

        return response()->json(['data' => $query->get()]);
    }
}
