<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\UtilityApi;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $utilApi;

    function __construct(UtilityApi $utilApi){
        $this->utilApi = $utilApi;
    }
    
    public function login(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "username" => "required",
                "password" => "required",
            ]
        );
        if($validator->fails()){
            return $this->utilApi->resp($validator->errors(), 422, 422,);
        }

        $credentials = $request->only('username', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return $this->utilApi->resp(null, 401, 401);
        }

        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
        return $this->utilApi->resp($data);
    }

    public function register(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "username" => "required",
                "email" => "required",
                "password" => "required",
            ]
        );
        if($validator->fails()){
            return $this->utilApi->resp($validator->errors(), 422, 422,);
        }

        try{
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password =  Hash::make($request->password, [
                'rounds' => 10,
            ]);
            $user->save();

            $credentials = $request->only('username', 'password');
            $token = auth()->attempt($credentials);

            $data = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user'=> $user,
            ];
        }catch(Exception $e ){
            return $this->utilApi->resp($e, 500, 500, 'Internal server eroor');
        }
        return $this->utilApi->resp($data);
    }
}
