<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Redis as Redis;

use App\User;

use Auth;
use Log;
use Exception;

class RedisGuard implements Guard
{
    protected $user;
    protected $provider;

    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
        $this->user = null;
    }

        /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return (!is_null($this->user()) || !is_null(Auth::user()));
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return ! $this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        try {
            $redisConnection = Redis::connection();
            if (session_id() === '') {
                session_start();
            }
            $sessionData = $redisConnection->get('ci_session:'.session_id());
            session_decode($sessionData);
            if (array_key_exists('account_id', $_SESSION)) {
                return User::where('account_id', '=', $_SESSION['account_id'])->first();
            }
        } catch (Exception $e) {
            Log::warning($e->getMessage());
        }
        if (! is_null($this->user)) {
            return $this->user;
        }
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        if (! is_null($this->user)) {
            return $this->user->id;
        }
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }
}
