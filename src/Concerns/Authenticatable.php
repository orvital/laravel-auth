<?php

namespace Orvital\Auth\Concerns;

use Illuminate\Support\Facades\Hash;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 *
 * @see \Illuminate\Contracts\Auth\Authenticatable
 */
trait Authenticatable
{
    /**
     * The name of the "remember me" token column.
     *
     * @var string|null
     */
    const REMEMBER_ME = 'remember_token';

    /**
     * Initializer called on each new model instance.
     */
    public function initializeAuthenticatable(): void
    {
        $this->mergeFillable(['email', 'password']);
        $this->makeHidden(['password', $this->getRememberTokenName()]);
        $this->mergeCasts(['password' => 'hashed']);
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Set the user password.
     */
    public function setAuthPassword($value): void
    {
        $this->password = $value;
    }

    /**
     * Check if the user password matches the provided value.
     */
    public function checkAuthPassword(string $value): bool
    {
        return Hash::check($value, $this->password);
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        if (! empty($this->getRememberTokenName())) {
            return (string) $this->{$this->getRememberTokenName()};
        }
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        if (! empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return static::REMEMBER_ME;
    }
}
