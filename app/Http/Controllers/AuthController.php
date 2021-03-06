<?php

namespace App\Http\Controllers;

use L5Swagger\L5SwaggerFacade as L5Swagger;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    const HOST = '127.0.0.1:8000';
    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Авторизация",
     * description="Login by email, password",
     * operationId="login",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Возращает JWT",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="nfrwkJFJIOWE.FJIO")
     *      )
     *),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * ),

     */
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

        Bouncer::allow('simple-user')->to('can-search');
        $dbUser->assign('simple-user');

        return response()->json(JWTAuth::fromUser($dbUser));
    }



    /**
     * @OA\get (
     * path="/api/me",
     * summary="Возвращет данные user",
     * description="Возвращет данные user по jwt",
     * operationId="me",
     * tags={"auth"},
     * security={ {"bearer": {} }},
     *    @OA\Parameter(
     *       name="Authorization",
     *       in="header",
     *       required=true,
     *       @OA\Schema(
     *           type="string"
     *       )
     *   ),
     * @OA\Response(
     *    response=200,
     *    description="Возращает пользователя",
     *    @OA\JsonContent(
     *    @OA\Property(property="User", type="object", ref="#/components/schemas/User"),
     *      )
     *),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * ),

     * )
     */
    public function me()
    {
        return response(auth()->user());
    }


    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Регистрация",
     * description="регистрация нового пользователя",
     * operationId="register",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="передавать данные пользователя",
     *    @OA\JsonContent(
     *       required={
     *     "email",
     *     "first_name",
     *     "last_name",
     *     "middle_name",
     *     "inn",
     *     "password",
     *    },
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="first_name", type="string", example="Денис"),
     *       @OA\Property(property="last_name", type="string",  example="Бугаков"),
     *       @OA\Property(property="middle_name", type="string", example="Юрьевич"),
     *       @OA\Property(property="inn", type="string", format="inn", example="1092364000695"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345")
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Пользователь зарегистрирован",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Пользователь зарегистрирован")
     * ),
     *      ),
     *
     * @OA\Response(
     *    response=422,
     *    description="Регистрация не удалась",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Такой пользователь уже существует"),
     *        ),
     *     ),
     * )
     *
     */
    public function register(Request $request)
    {
        $http = new Client();
        $response = $http->post(
            sprintf('%s/api/registry', self::HOST),
            [
                'form_params' => [
                    'email' => $request->get('email'),
                    'password' => $request->get('password'),
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]
        );
        $result = json_decode((string) $response->getBody(), true);
        return $result;
    }
}
