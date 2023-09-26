<?php

namespace Orvital\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Orvital\Auth\AuthService;

class LoginRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'email' => ['required', 'max:192', 'email'],
            'password' => ['required', 'max:192'],
        ];
    }

    public function after(AuthService $auth): array
    {
        return [
            function (Validator $validator) use ($auth) {
                if (! $auth->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                    $validator->errors()->add('email', trans('auth.failed'));
                }
                // if (! $auth->once($this->only('email', 'password'))) {
                //     $validator->errors()->add('email', trans('auth.failed'));
                // }
            },
        ];
    }

    protected function passedValidation()
    {
        if ($this->hasSession()) {
            $this->session()->regenerate();
        }
    }
}
