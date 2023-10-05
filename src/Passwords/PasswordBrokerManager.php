<?php

namespace Orvital\Auth\Passwords;

use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\PasswordBrokerFactory as PasswordBrokerFactoryContract;
use Illuminate\Contracts\Foundation\Application as App;
use InvalidArgumentException;
use Orvital\Auth\Passwords\PasswordBroker;
use Orvital\Auth\Passwords\Repositories\DatabaseTokenRepository;

class PasswordBrokerManager implements PasswordBrokerFactoryContract
{
    /**
     * Create a new PasswordBroker manager instance.
     */
    public function __construct(
        protected App $app,
        protected array $brokers = []
    ) {
    }

    /**
     * Get a password broker instance by name.
     */
    public function broker($name = null): PasswordBrokerContract
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->brokers[$name] ?? ($this->brokers[$name] = $this->resolve($name));
    }

    /**
     * Resolve the given broker.
     *
     * @throws InvalidArgumentException
     */
    protected function resolve(string $name): PasswordBrokerContract
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Password resetter [{$name}] is not defined.");
        }

        // The password broker uses a token repository to validate tokens and send user
        // password e-mails, as well as validating that password reset process as an
        // aggregate service of sorts providing a convenient interface for resets.
        return new PasswordBroker(
            $this->app['auth']->createUserProvider($config['provider'] ?? null),
            $this->createTokenRepository($config),
        );
    }

    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];

        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $connection = $config['connection'] ?? null;

        return new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire'],
            $config['throttle'] ?? 0
        );
    }

    /**
     * Get the password broker configuration.
     */
    protected function getConfig(string $name): array
    {
        return $this->app['config']["auth.passwords.{$name}"];
    }

    /**
     * Get the default password broker name.
     */
    public function getDefaultDriver(): string
    {
        return $this->app['config']['auth.defaults.passwords'];
    }

    /**
     * Set the default password broker name.
     */
    public function setDefaultDriver(string $name): void
    {
        $this->app['config']['auth.defaults.passwords'] = $name;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->broker()->{$method}(...$parameters);
    }
}
