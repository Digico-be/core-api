<?php

namespace Diji\Ia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    protected $fillable = [
        'id',
        'openai_id',
        'filename',
        'size',
        'mime_type',
        'assistant_id', // nullable
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function fileMessages(): HasMany
    {
        return $this->hasMany(
            FileMessage::class,
            'file_openai_id',   // FK dans file_messages
            'openai_id'         // PK dans files
        );
    }
}
