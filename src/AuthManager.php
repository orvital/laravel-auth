<?php

namespace Orvital\Auth;

use Illuminate\Auth\AuthManager as BaseAuthManager;

/**
 * Auth Manager Decorator / Extender
 *
 * `Guards` define how users are authenticated for each request.
 * `Providers` define how users are retrieved from the persistent storage.
 *
 * when retrieving a user by credentials with `retrieveByCredentials($credentials)`
 * all the provided attributes are used in the query except for the `password` attribute!
 * the retrieved user is then validated cheking the hashed password against the provided plain password.
 */
class AuthManager extends BaseAuthManager
{
    public function __construct($app)
    {
        parent::__construct($app);
    }
}
