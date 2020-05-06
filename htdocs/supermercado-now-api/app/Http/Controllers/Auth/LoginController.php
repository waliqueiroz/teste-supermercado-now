<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function login(LoginRequest $request)
    {
        $username = $request->get('email');
        $password = $request->get('password');

        return $this->loginService->login($username, $password);
    }

    public function refresh()
    {
        return $this->loginService->refresh();
    }

    public function logout()
    {
        return $this->loginService->logout(Auth::user()->token());
    }

}
