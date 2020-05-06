<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getCurrentUser()
    {
        $currentUser = Auth::guard('api')->user();
        $user = $this->userService->getCurrentUser($currentUser);
        return $user;
    }

    public function index(Request $request)
    {
        $filters = $request->all();
        $response = $this->userService->index($filters);
        return $response;
    }

    public function store(UserRequest $request)
    {
        $userData = $request->all();
        $response = $this->userService->store($userData);
        return $response;
    }

    public function update($id, UpdateUserRequest $request)
    {
        $userData = $request->all();
        $response = $this->userService->update($id, $userData);
        return $response;
    }

    public function show($id)
    {
        $response = $this->userService->show($id);
        return $response;
    }

    public function destroy($id)
    {
        $response = $this->userService->destroy($id);
        return $response;
    }
}
