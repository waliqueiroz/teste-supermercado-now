<?php

namespace App\Services;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class LoginService
{
    const REFRESH_TOKEN = 'loadSession';

    private $userService;
    private $router;

    public function __construct(UserService $userService, Router $router)
    {
        $this->router = $router;
        $this->userService = $userService;
    }

    public function login($email, $password)
    {
        $grantType = 'password';
        $user = $this->userService->getByEmail($email);
        if (!is_null($user)) {
            return $this->proxy($grantType, [
                'username' => $email,
                'password' => $password,
            ]);
        } else {
            return $this->getJsonUnauthenticated($grantType);
        }
    }

    public function logout($accessToken)
    {

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        Cookie::queue(Cookie::forget(self::REFRESH_TOKEN));

        return response()->json(null, 204);
    }

    public function refresh()
    {
        $refreshToken = Cookie::get(self::REFRESH_TOKEN);
        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken,
        ]);
    }

    private function proxy($grantType, array $data = [])
    {
        $client = DB::table('oauth_clients')->where('name', 'Laravel Password Grant Client')->first();
        $data = array_merge($data, [
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'grant_type' => $grantType,
        ]);

        $request = Request::create('/oauth/token', 'POST', $data);
        $response = $this->router->prepareResponse($request, app()->handle($request));

        if (!$response->isSuccessful()) {
            return $this->getJsonUnauthenticated($grantType);
        }

        $data = json_decode($response->getContent());
        $response = Response([
            'access_token' => $data->access_token,
            'expires_in' => $data->expires_in,
        ]);

        $response->cookie(Cookie::make(
            self::REFRESH_TOKEN,
            $data->refresh_token,
            1440, // 1 dia
            null,
            null,
            false,
            true // HttpOnly
        ));

        return $response;
    }

    private function getJsonUnauthenticated($grantType)
    {
        $json = [
            'code' => 401,
            'message' => 'Unauthenticated.',
            'grant_type' => $grantType,
        ];

        $response = Response($json, 401);
        return $response;
    }
}
