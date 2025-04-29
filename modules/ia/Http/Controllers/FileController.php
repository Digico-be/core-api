<?php

namespace Diji\Ia\Http\Controllers;

use App\Http\Controllers\Controller;
use Diji\Ia\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        return response()->json(['data' => File::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'openai_id' => ['required', 'string'],
            'filename' => ['required', 'string'],
            'size' => ['required', 'integer'],
            'mime_type' => ['required', 'string'],
            'assistant_id' => ['nullable', 'string'],
        ]);

        $file = File::create($validated);

        return response()->json(['data' => $file], 201);
    }

    public function show(string $id)
    {
        return response()->json(['data' => File::findOrFail($id)]);
    }

    public function destroy(string $id)
    {
        File::findOrFail($id)->delete();
        return response()->json(['message' => 'File deleted']);
    }
}
