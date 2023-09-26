<?php

namespace Orvital\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class RegisterRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:192'],
            'email' => ['required', 'max:192', 'email', Rule::unique('users')],
            'password' => ['required', 'max:192', 'confirmed', PasswordRule::default()],
        ];
    }
}
