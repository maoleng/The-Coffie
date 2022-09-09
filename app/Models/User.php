<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Base
{
    public const TIME_VERIFY = 180; //minutes
    public const MAX_SYSTEM_MAIL_PER_DAY = 10;

    protected $fillable = [
        'name', 'email', 'avatar', 'password', 'role', 'email_verified_at', 'token_verify',
        'is_active', 'count_system_mail_daily',
        'facebook_id', 'google_id', 'github_id', 'gitlab_id', 'twitter_id', 'linkedin_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean'
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
        return $this->hasMany(Device::class, 'user_id', 'id');
    }
}
