<?php

namespace Orvital\Auth\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Orvital\Auth\AuthService;
use Orvital\Auth\Limiters\LoginLimiter;

class LoginLimitedRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * @var LoginLimiter
     */
    protected $limiter;

    public function rules(): array
    {
        return [
            'email' => ['required', 'max:192', 'email'],
            'password' => ['required', 'max:192'],
        ];
    }

    public function after(AuthService $auth, LoginLimiter $limiter): array
    {
        $this->limiter = $limiter->for($this);

        return [
            function (Validator $validator) {
                // Check if the request has too many failed login attempts.
                if ($this->limiter->tooManyAttempts()) {
                    event(new Lockout($this));

                    $seconds = $this->limiter->availableIn();

                    $validator->errors()->add('email', trans('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ]));
                }
            },
            function (Validator $validator) use ($auth) {
                if (! $auth->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                    $this->limiter->increment();
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

        $this->limiter->clear();
    }
}
