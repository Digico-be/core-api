<?php

namespace Diji\Ia\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'openai_id',
        'thread_id',
        'role',
        'raw_text',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
