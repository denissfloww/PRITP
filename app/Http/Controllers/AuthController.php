<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Token;

class AuthController extends Controller
{
    const HOST = '127.0.0.1:8000';

    public function login(Request $request)
    {
        $http = new Client();

        $response = $http->post(sprintf('%s/oauth/token', self::HOST),
            [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '2',
                    'client_secret' => env('AUTH_CLIENT_ID'),
                    'username' => $request->get('email'),
                    'password' => $request->get('password'),
                    'scope' => '*',
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

        $result = json_decode((string) $response->getBody(), true);
        $token = 'Bearer' . $result['access_token'];
        dd($token);

        $response = $http->post(sprintf('%s/api/user', self::HOST),
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

        return response()->json(auth()->login($dbUser));
    }
}
