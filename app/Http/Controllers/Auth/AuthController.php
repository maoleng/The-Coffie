<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\VerifyNewLocationRequest;
use App\Http\Requests\Auth\VerifyRegisterRequest;
use App\Http\Requests\Auth\VerifyResetPasswordRequest;
use App\Jobs\SystemSendMail;
use App\Mail\Register;
use App\Mail\VerifyNewLocation;
use App\Mail\VerifyResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends ApiController
{
    public function processRegister(RegisterRequest $request): array
    {
        $data = $request->validated();
        $token_verify = random_int(1000, 9999);
        $user = services()->userService()->create([
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['name'],
            'token_verify' => $token_verify,
            'email_verified_at' => now(),
        ]);
        (new DeviceController())->createDevice($user->get('model'), $data['device_id']);

        $send_mail = new SystemSendMail([
            'email' => $data['email'],
            'mail_content' => new Register($token_verify),
        ]);
        if ( !plusOneSystemMail($user->get('model')) ) {
            return [
                'status' => false,
                'message' => trans('messages.maxed_email_daily'),
            ];
        }
        dispatch($send_mail);

        return [
            'status' => true,
            'message' => trans('messages.system_send_mail_success'),
        ];
    }

    public function verifyRegister(VerifyRegisterRequest $request): array
    {
        $data = $request->validated();
        $user = services()->userService()->where('email', $data['email'])->first();
        if ($this->checkExpireVerifyToken($user, $data['email'], 'register') === 'max_mail_per_day') {
            return [
                'status' => false,
                'message' => trans('messages.maxed_email_daily'),
            ];
        }
        if ($this->checkExpireVerifyToken($user, $data['email'], 'register')) {
            return [
                'status' => false,
                'message' => trans('messages.otp_is_expired'),
            ];
        }
        if ($user->token_verify !== $data['token_verify']) {
            return [
                'status' => false,
                'message' => trans('messages.wrong_otp'),
            ];
        }
        $user->update(['is_active' => true]);
        $device = (new DeviceController())->createDevice($user, $data['device_id']);

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

    public function verifyNewLocation(VerifyNewLocationRequest $request): array
    {
        $data = $request->validated();
        $user = services()->userService()->where('email', $data['email'])->first();
        if ($this->checkExpireVerifyToken($user, $data['email'], 'new_location') === 'max_mail_per_day') {
            return [
                'status' => false,
                'message' => trans('messages.maxed_email_daily'),
            ];
        }
        if ($this->checkExpireVerifyToken($user, $data['email'], 'new_location')) {
            return [
                'status' => false,
                'message' => trans('messages.otp_is_expired'),
            ];
        }
        if ($user->token_verify !== $data['token_verify']) {
            return [
                'status' => false,
                'message' => trans('messages.otp_is_expired'),
            ];
        }
        $device = (new DeviceController())->createDevice($user, $data['device_id']);

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

    public function processLogin(LoginRequest $request): array
    {
        $data = $request->validated();
        $auth = $this->auth($data['email'], $data['password']);
        $status = $auth['status'];
        $user = $auth['user'];
        if (empty($status) && $status !== false) {
            return [
                'status' => false,
                'message' => trans('messages.wrong_email_or_password'),
            ];
        }
        $device = services()->deviceService()
            ->where('device_id', $data['device_id'])
            ->where('user_id', $user->id)->first();
        if (empty($device)) {
            $token_verify = random_int(1000, 9999);
            $user->update([
                'token_verify' => $token_verify,
                'email_verified_at' => now(),
            ]);
            $send_mail = new SystemSendMail([
                'email' => $data['email'],
                'mail_content' => new VerifyNewLocation($token_verify),
            ]);
            if ( !plusOneSystemMail($user) ) {
                return [
                    'status' => false,
                    'message' => trans('messages.login_from_another_device_but_maximum_mail_can_receive'),
                ];
            }
            dispatch($send_mail);
            return [
                'status' => false,
                'message' => trans('messages.your_account_is_logged_in_from_weird_location'),
            ];
        }

        if (!$status) {
            $token_verify = random_int(1000, 9999);
            $user->update([
                'token_verify' => $token_verify,
                'email_verified_at' => now(),
            ]);
            $send_mail = new SystemSendMail([
                'email' => $data['email'],
                'mail_content' => new Register($token_verify),
            ]);
            if ( !plusOneSystemMail($user) ) {
                return [
                    'status' => false,
                    'message' => trans('messages.maxed_email_daily'),
                ];
            }
            dispatch($send_mail);
            return [
                'status' => false,
                'message' => trans('messages.your_account_is_not_active'),
            ];
        }
        $device = (new DeviceController())->createDevice($user, $data['device_id']);

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

    public function auth($email, $password): array
    {
        $user = services()->userService()->where('email', $email)->first();
        if (empty($user)) {
            return [
                'status' => null,
                'user' => $user,
            ];
        }
        if (!$user->verify($password)) {
            return [
                'status' => null,
                'user' => $user,
            ];
        }
        if (!$user->is_active) {
            return [
                'status' => false,
                'user' => $user,
            ];
        }
        return [
            'status' => true,
            'user' => $user,
        ];
    }

    public function processResetPassword(ResetPasswordRequest $request): array
    {
        $data = $request->validated();
        $user = services()->userService()->where('email', $data['email'])->first();
        $token_verify = random_int(1000, 9999);
        $user->update([
            'token_verify' => $token_verify,
            'email_verified_at' => now(),
        ]);
        $send_mail = new SystemSendMail([
            'email' => $data['email'],
            'mail_content' => new VerifyResetPassword($token_verify),
        ]);
        if ( !plusOneSystemMail($user) ) {
            return [
                'status' => false,
                'message' => trans('messages.maxed_email_daily'),
            ];
        }
        dispatch($send_mail);

        return [
            'status' => true,
            'message' => trans('messages.system_send_mail_success'),
        ];
    }

    public function verifyResetPassword(VerifyResetPasswordRequest $request): array
    {
        $data = $request->validated();
        $user = services()->userService()->where('email', $data['email'])->first();
        if ($this->checkExpireVerifyToken($user, $data['email'], 'new_location') === 'reset_password') {
            return [
                'status' => false,
                'message' => trans('messages.maxed_email_daily'),
            ];
        }
        if ($this->checkExpireVerifyToken($user, $data['email'], 'reset_password')) {
            return [
                'status' => false,
                'message' => trans('messages.otp_is_expired'),
            ];
        }
        if ($user->token_verify !== $data['token_verify']) {
            return [
                'status' => false,
                'message' => trans('messages.wrong_otp'),
            ];
        }
        (new DeviceController())->createDevice($user, $data['device_id']);

        $user->update(['token_verify' => Str::random(20)]);

        return [
            'status' => true,
            'message' => trans('messages.system_send_mail_success'),
            'data' => [
                'token_verify' => $user->token_verify
            ],
        ];
    }

    public function updatePassword(UpdatePasswordRequest $request): array
    {
        $data = $request->validated();
        $user = services()->userService()
            ->where('email', $data['email'])
            ->where('token_verify', $data['token_verify'])
            ->first();
        if (empty($user)) {
            return [
                'status' => false,
                'message' => trans('messages.wrong_otp'),
            ];
        }
        $user->password = $data['password'];
        $user->token_verify = null;
        $user->save();

        return [
            'status' => true,
            'message' => trans('messages.change_password_successfully'),
        ];
    }

    public function checkExpireVerifyToken($user, $email, $type): bool|string
    {
        $expire_verify_time = Carbon::make($user->email_verified_at)->addMinutes(User::TIME_VERIFY);
        if ($expire_verify_time->lt(now())) {
            $token_verify = random_int(1000, 9999);
            if ($type === 'register') {
                $mail_content = new Register($token_verify);
            }
            if ($type === 'new_location') {
                $mail_content = new VerifyNewLocation($token_verify);
            }
            if ($type === 'reset_password') {
                $mail_content = new VerifyResetPassword($token_verify);
            }
            $user->update([
                'token_verify' => $token_verify,
                'email_verified_at' => now(),
            ]);
            $send_mail = new SystemSendMail([
                'email' => $email,
                'mail_content' => $mail_content,
            ]);
            if ( !plusOneSystemMail($user) ) {
                return 'max_mail_per_day';
            }
            dispatch($send_mail);
            return true;
        }

        return false;
    }

    protected function getService()
    {
        // TODO: Implement getService() method.
    }
}
