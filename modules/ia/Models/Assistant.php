<?php

namespace Diji\Ia\Models;

use App\Models\UserTenant;
use Illuminate\Database\Eloquent\Model;

class Assistant extends Model
{
    protected $fillable = [
        'openai_id',
        'name',
        'description',
        'module',
        'model',
        'instructions',
        'type',
        'temperature',
        'max_tokens_output',
        'rules',
        'persona',
        'suggested_prompts',
    ];

    protected $casts = [
        'rules' => 'array',
        'suggested_prompts' => 'array',
        'temperature' => 'float',
        'max_tokens_output' => 'integer',
    ];

    /**
     * Les tenants associés à cet assistant (relation many-to-many).
     */
    public function userTenants()
    {
        return $this->belongsToMany(UserTenant::class, 'assistant_user_tenant', 'assistant_id', 'user_tenant_id')
            ->withTimestamps();
    }
}
