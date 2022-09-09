<?php

namespace App\Lib\Helper;

use App\Services\ConfigService;
use App\Services\DeviceService;
use App\Services\UserService;
use Psr\Container\ContainerInterface;

class MapService
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function userService(): UserService
    {
        return c(UserService::class);
    }

    public function deviceService(): DeviceService
    {
        return c(DeviceService::class);
    }

    public function configService(): ConfigService
    {
        return c(ConfigService::class);
    }
}
