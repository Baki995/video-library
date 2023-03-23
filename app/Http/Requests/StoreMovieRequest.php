<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class StoreMovieRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:3|max:255',
            'studio' => 'required|min:3|max:255',
            'runtime' => ['required','regex:/^([0-1]?\d|2[0-3])(?::([0-5]?\d))?(?::([0-5]?\d))?$/'],
            'genre' => 'required|min:3|max:15',
            'description' => 'required|max:4000',
        ];
    }
}
