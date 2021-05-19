<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    const HOST = '127.0.0.1:8000';

    public function login(Request $request)
    {
        $http = new Client();
        $response = $http->post(
            sprintf('%s/oauth/token', self::HOST),
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
            ]
        );

        $result = json_decode((string) $response->getBody(), true);


        $token = 'Bearer ' . $result['access_token'];
        $response = $http->get(
            sprintf('%s/api/user', self::HOST),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $token,
                ]
            ]
        );

        $user = json_decode((string) $response->getBody(), true);
        $dbUser = User::updateOrCreate(
            [
                'uuid' => $user['uuid'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name'=> $user['last_name'],
                'middle_name'=> $user['middle_name'],
                'inn'=> $user['inn'],
            ]
        );

        return response()->json(JWTAuth::fromUser($dbUser));
    }

    public function me()
    {
        return response(auth()->user());
    }
}
