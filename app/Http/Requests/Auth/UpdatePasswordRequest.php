<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Models\Device;
use App\Models\User;

class UpdatePasswordRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:App\Models\User,email',
            ],
            'password' => [
                'required',
                'same:retype_password',
            ],
            'retype_password' => [
                'required',
            ],
            'token_verify' => [
                'required',
                'exists:App\Models\User,token_verify'
            ],
        ];
    }
}
