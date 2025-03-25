<?php

namespace Diji\Module\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ModuleUser extends Pivot
{
    protected $table = 'modules_users';

    protected $fillable = [
        'user_id',
        'module_id',
    ];
}
