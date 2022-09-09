<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Models\Device;
use App\Models\User;

class ResetPasswordRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:App\Models\User,email',
            ],
        ];
    }
}
