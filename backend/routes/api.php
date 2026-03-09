<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use Illuminate\Support\Facades\Route;

Route::post('login',[AuthenticateUserController::class,'store']);