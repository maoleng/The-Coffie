<?php

namespace App\Http\Requests;

class UserRequest extends BaseRequest
{
    public function rules() : array
    {
        return [
            'name' => 'required',
            'email' => 'nullable'
        ];
    }
}
