<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Base
{

    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }

    public function verify($password): bool
    {
        return password_verify($password, $this->password);
    }

    public function device(): HasMany
    {
        return $this->hasMany(Device::class, 'admin_id', 'id');
    }

}
