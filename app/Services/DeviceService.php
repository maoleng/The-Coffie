<?php

namespace App\Services;

use App\Models\Device;

class DeviceService extends ApiService
{
    protected $model = Device::class;
    protected $fieldsName = '_fields';

    protected function getOrderbyableFields()
    {

    }
}
