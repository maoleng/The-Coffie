<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Config extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'key', 'value',
    ];



}
