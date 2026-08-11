<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}]+\s+[\p{L}]+/u',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'max:255',
                'unique:'.User::class,
                'regex:/^[^@\s]+@[^\s]+\.com$/i',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:7',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Please enter your first and last name (e.g. Yara Mohammad).',

            'email.regex' => 'Please enter a valid email address that contains "@" and ends with ".com".',

            'password.min' => 'Password must be at least 7 characters.',
            'password.regex' => 'Password must contain both letters and numbers.',
        ];
    }
}
