<?php

use App\Lib\Helper\MapService;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

if (!function_exists('c')) {
    function c(string $key)
    {
        return App::make($key);
    }
}

if (!function_exists('mysqlBeginSession')) {
    function mysqlBeginSession()
    {
        DB::beginTransaction();
    }
}

if (!function_exists('mysqlCommit')) {
    function mysqlCommit()
    {
        DB::commit();
    }
}

if (!function_exists('mysqlRollBack')) {
    function mysqlRollBack()
    {
        DB::rollBack();
    }
}

if (!function_exists('services')) {
    function services(): MapService
    {
        return c(MapService::class);
    }
}

if (!function_exists('plusOneSystemMail')) {
    function plusOneSystemMail($user): bool
    {
        $max = User::MAX_SYSTEM_MAIL_PER_DAY;
        $current = $user->count_system_mail_daily;
        if ($current >= $max ) {
            return false;
        }
        $user->increment('count_system_mail_daily');
        return true;
    }
}

if (!function_exists('getConfig')) {
    function getConfig($key)
    {
        $config = services()->configService()->where('key', $key)->first();
        return $config->value ?? null;
    }
}
