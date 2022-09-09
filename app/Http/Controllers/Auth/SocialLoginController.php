<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{

    public function redirect($social = 'twitter'): string
    {
        if ($social !== 'twitter') {
            return Socialite::driver($social)->stateless()->redirect()->getTargetUrl();
        }

        return Socialite::driver($social)->redirect()->getTargetUrl();
    }

    public function callback($social): array
    {
        $user = $social !== 'twitter' ? (Socialite::driver($social)->stateless()->user()) : (Socialite::driver($social)->user());
        $user = services()->userService()->updateOrCreate(
            [
                'email' => $user->email,
            ],
            [
                'name' => $user->name,
                'email' => $user->email,
                $social . '_id' => $user->id,
                'avatar' => $user->avatar,
                'is_active' => true,
            ],
        );
        $device = (new DeviceController())->createDevice($user, $user->id);

        return [
            'status' => true,
            'id' => $user->id,
            'avatar' => $user->avatar,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $device->token,
            'is_active' => $user->is_active,
        ];

    }
}
