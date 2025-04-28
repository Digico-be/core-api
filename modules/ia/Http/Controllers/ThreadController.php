<?php

namespace Diji\Ia\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Diji\Ia\Models\Thread;
use Diji\Ia\Models\Assistant;
use Illuminate\Support\Facades\Log;

class ThreadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'openai_id' => 'required|string|unique:threads,openai_id',
            'assistant_openai_id' => 'required|string',
            'module' => 'nullable|string',
        ]);

        // Retrouver l'assistant avec openai_id
        $assistant = Assistant::where('openai_id', $request->assistant_openai_id)->first();

        if (!$assistant) {
            return response()->json(['message' => 'Assistant not found'], 404);
        }

        $thread = Thread::create([
            'openai_id' => $request->openai_id,
            'assistant_id' => $assistant->id,
            'module' => $request->module,
        ]);

        return response()->json($thread, 201);
    }

    public function destroy($openaiId)
    {
        $thread = Thread::where('openai_id', $openaiId)->first();

        if (!$thread) {
            return response()->json(['message' => 'Thread not found'], 404);
        }

        $thread->delete();

        return response()->json(['message' => 'Thread deleted']);
    }

    public function show($openaiId)
    {
        $thread = Thread::where('openai_id', $openaiId)->first();

        if (!$thread) {
            return response()->json(['message' => 'Thread not found'], 404);
        }

        return response()->json($thread);
    }

    public function find(Request $request)
    {
        $request->validate([
            'assistant_openai_id' => 'required|string',
            'module' => 'nullable|string',
        ]);

        $assistant = \Diji\Ia\Models\Assistant::on('tenant')
            ->where('openai_id', $request->assistant_openai_id)
            ->first();

        if (!$assistant) {
            Log::info('[ThreadController@find] Assistant introuvable pour openai_id=' . $request->assistant_openai_id);
            return response()->json(['message' => 'Assistant not found'], 404);
        }

        $query = \Diji\Ia\Models\Thread::on('tenant')
            ->where('assistant_id', $assistant->id);

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $thread = $query->first();

        if (!$thread) {
            return response()->json(['message' => 'Thread not found'], 404);
        }

        Log::info('[ThreadController@find] Thread trouvé', [
            'thread_id' => $thread->id,
            'thread_openai_id' => $thread->openai_id,
            'thread_module' => $thread->module,
        ]);

        return response()->json($thread);
    }

}
