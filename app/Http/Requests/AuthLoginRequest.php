<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class AuthLoginRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|regex:/^.+@.+$/i',
            'password' => 'required|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[*#.!@$%^&:;<>,.]).{8,32}$/'
        ];
    }
}
