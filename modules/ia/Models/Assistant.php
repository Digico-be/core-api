<?php

namespace Diji\Ia\Models;

use App\Models\UserTenant;
use Illuminate\Database\Eloquent\Model;

class Assistant extends Model
{
    protected $fillable = [
        'name',
        'description',
        'module',
        'model',
        'instructions',
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
