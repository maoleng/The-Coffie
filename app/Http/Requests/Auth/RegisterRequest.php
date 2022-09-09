<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'same:retype_password',
            ],
            'retype_password' => [
                'required',
            ],
            'name' => [
                'nullable',
            ],
            'device_id' => [
                'required',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => explode('@', $this->email)[0],
        ]);
    }

}
