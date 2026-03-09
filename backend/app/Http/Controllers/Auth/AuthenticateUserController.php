<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthRessource;
use App\Services\Auth\Authenticate;

class AuthenticateUserController extends Controller
{
    public function store(LoginRequest $request, Authenticate $auth)
    {
        $result = $auth->execute($request->credentials());

        if (!$result) return response()->json(
            data: [
                'success' => false,
                'error' => 'invalide credentials'
            ],
            status: 404
        );
        
        return response()->json(
            data: new AuthRessource($result),
            status: 201
        );
    }
}
