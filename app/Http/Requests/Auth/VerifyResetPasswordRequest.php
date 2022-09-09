<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Models\Device;
use App\Models\User;

class VerifyResetPasswordRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'token_verify' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
                'exists:App\Models\User,email',
            ],
            'device_id' => [
                'required',
            ],
        ];
    }
}
