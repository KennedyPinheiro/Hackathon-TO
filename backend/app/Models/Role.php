<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guard_name = 'api';
}
