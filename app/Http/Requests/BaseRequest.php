<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

abstract class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        $response = new Response([
            'status' => false,
            'message' => $validator->errors()->first(),
        ], 200);
        throw new ValidationException($validator, $response);
    }

    public function messages(): array
    {
        return [
            'required' => 'Bạn chưa điền :attribute',
            'email' => ':attribute chưa đúng định dạng',
            'same' => ':attribute không khớp',
            'unique' => 'Đã có người chọn :attribute này',
            'exists' => ':attribute không tồn tại',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Địa chỉ email',
            'password' => 'Mật khẩu',
            'retype_password' => 'Mật khẩu nhập lại',
            'device_id' => 'Mã của thiết bị',
        ];
    }

    abstract public function rules();
}
