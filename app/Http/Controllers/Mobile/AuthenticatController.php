<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticatController extends Controller
{
    use ResponseTrait;
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = User::create($request->all());
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->returnData($user, true,200);
        
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), 500);
        }
    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return $this->returnError(__('messages.Unauthorized'), 401);
            
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->returnData($token, true,200);

    }

    // method for user logout and delete token
  

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->returnSuccess('logout success', 200);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), 500);
        }


    }
}
