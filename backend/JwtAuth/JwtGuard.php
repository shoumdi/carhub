<?php

namespace JwtAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use JwtAuth\Exceptions\InvalidTokenException;

class JwtGuard implements Guard
{
    private Authenticatable | null $user = null;
    public function __construct(
        private UserProvider $provider,
        private JwtProvider $jwt,
        private Request $request
    ) {}
    public function attempt(array $credentials): null | string
    {
        $user = $this->provider->retrieveByCredentials($credentials);
        
        if (!$user) return null;

        $payload = [
            'sub' => $user->id,
            'iat' => now(),
            'exp' => now() + env('JWT_TTL', 60) * 60,
        ];
        
        return $this->jwt->encode($payload);
    }

    public function check()
    {
        return $this->user !== null;
    }

    /**
     * Get the currently authenticated user.
     * @return $user or null if not found
     * @throws InvalidTokenException if token is not valid
     */
    public function user()
    {
        
        if ($this->user) return $this->user;

        $token = $this->request->bearerToken();

        if (!$this->jwt->verify($token)) return throw new InvalidTokenException('invalid token');

        $payload = $this->jwt->decode($token);

        return $this->provider->retrieveById($payload->sub);
    }

    public function guest()
    {
        return $this->user === null;
    }
    public function id()
    {
        return $this->user->id ?? null;
    }
    public function validate(array $credentials = [])
    {
        $validCredentials = Schema::getColumnListing('users');
        return count(array_intersect($validCredentials)) === count($credentials);
    }
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
    public function hasUser()
    {
        return $this->user !== null;
    }
}
