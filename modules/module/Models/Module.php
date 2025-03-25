<?php

namespace Diji\Module\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Les utilisateurs associés à ce module (relation many-to-many).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'modules_users')
            ->withTimestamps();
    }
}
