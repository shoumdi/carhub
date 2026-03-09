<?php

namespace App\Services\Auth;

class Authenticate
{

    public function execute(array $credentials)
    {
        return $token = auth()->attempt($credentials);
        
    }
}
