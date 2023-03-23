<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class AuthRegisterRequest extends BaseRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[*#.!@$%^&:;<>,.]).{8,32}$/',
            'c_password' => 'required|same:password',
        ];
    }
}
