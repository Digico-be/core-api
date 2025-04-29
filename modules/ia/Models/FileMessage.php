<?php

namespace Diji\Ia\Models;

use Diji\Ia\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileMessage extends Model
{
    protected $fillable = [
        'file_openai_id',
        'message_openai_id',
        'thread_openai_id',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(
            File::class,
            'file_openai_id',   // clé étrangère dans file_messages
            'openai_id'         // clé primaire dans files
        );
    }
}

