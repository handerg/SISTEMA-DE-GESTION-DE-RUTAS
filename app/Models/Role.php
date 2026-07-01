<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'id_rol';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nombre_rol',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'id_rol');
    }
}
