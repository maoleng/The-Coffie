<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class Base extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected array $raw = [];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(static function ($model) {
            if (empty($model->id)) {
                $model->id = Str::random(25);
            }
        });
    }

    public function fill(array $attributes): Base
    {
        $this->raw = $attributes;
        return parent::fill($attributes);
    }

    public function getRaw(string $name, $default = null)
    {
        return $this->raw[$name] ?? $default;
    }

}
