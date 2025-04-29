<?php

namespace Diji\Ia\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = [
        'openai_id',
        'assistant_id',
        'module',
    ];

    public function assistant()
    {
        return $this->belongsTo(Assistant::class);
    }
}
