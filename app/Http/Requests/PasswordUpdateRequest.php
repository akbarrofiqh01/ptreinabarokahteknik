<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Password saat ini tidak sesuai.');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password'
            ],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.different' => 'Password baru harus berbeda dengan password lama.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.min' => 'Konfirmasi password minimal 8 karakter.',
        ];
    }
}
