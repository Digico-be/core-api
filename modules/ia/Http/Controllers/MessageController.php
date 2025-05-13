<?php

namespace Diji\Ia\Http\Controllers;

use App\Http\Controllers\Controller;
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
}
