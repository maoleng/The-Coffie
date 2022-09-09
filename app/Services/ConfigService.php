<?php

namespace App\Services;

use App\Models\Config;

class ConfigService extends ApiService
{
    protected $model = Config::class;
    protected $fieldsName = '_fields';

    protected function getOrderbyableFields()
    {

    }
}
