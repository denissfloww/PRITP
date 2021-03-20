<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const DOMAIN = "127.0.0.1:8000";

    public function login()
    {
        $http = new GuzzleHttp\Client();
        $response = $http->post(
           self::DOMAIN.'/api/auth/login',
            [
                'form_params' => [
                        'email' => 'test@test',//$request->email, //
                        'password' => 'test',//$request->password, //
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ],
            ]
        );

       //dd($response);
        $loh = json_decode((string) $response->getBody(), true);
        $token='Bearer '.$loh['access_token'];
        //d($token);
        $response = $http->post(
            self::DOMAIN.'/api/auth/me',
            [

                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization'=> $token

                ],
            ]
        );
        dd($response);
        //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE2MTU2NDEyNDcsImV4cCI6MTYxNTY0NDg0NywibmJmIjoxNjE1NjQxMjQ3LCJqdGkiOiJVYVhDT1ZLclJQNEx2NFpQIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.tDolsyMKjBW-ADCTPVwmZ2-urBDB9TpenpaRDTWiv4A
    }
}
