<?php
namespace Diji\Ia\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AssistantUserTenant extends Pivot
{
    protected $table = 'assistant_user_tenant';

    protected $fillable = [
        'assistant_id',
        'user_tenant_id',
    ];
}
