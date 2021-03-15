<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Token;

class AuthController extends Controller
{
    const HOST = 'auth.pritr.loc';

    public function login(Request $request)
    {
        $http = new Client();

        $response = $http->post(sprintf('%s/api/auth/login', self::HOST),
        [
            'form_params' => [
                'username' => $request->get('email'),
                'password' => $request->get('password'),
            ],
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);

        $result = json_decode((string) $response->getBody(), true);
        $token = 'Bearer' . $result['access_token'];

        $response = $http->post(sprintf('%s/api/auth/me', self::HOST),
        [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $token,
            ]
        ]);

        $user = json_decode((string) $response->getBody(), true);

        $dbUser = User::updateOrCreate(
            [
                'email' => $user['user']['email'],
                'name' => $user['user']['name'],
            ]
        );

        $token = auth()->login($dbUser);

        return ['token' => $token];
    }
}
