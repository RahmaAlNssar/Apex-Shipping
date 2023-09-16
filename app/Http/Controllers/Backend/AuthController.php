<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseTrait;
    public function login_admin(Request $request)
    {

        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_token' => 'required',
           
        ]);
        if ($validate) {

            $admin = Admin::where('token', $request->device_token)->first();
           
            if (!$admin || !Hash::check($request->password, $admin->password)) {

                return $this->returnError('Login invalid', 503);
            } else {

               
                $token = $admin->createToken($request->device_token)->plainTextToken;

                return $this->returnData($token, true, 200);
            }
        } else {
            return $this->returnErrorData(false, $validate, 422);
        }
    }

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
