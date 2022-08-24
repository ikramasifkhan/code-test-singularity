<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * @param UserRequest $request
     * @return mixed
     */
    public function register(UserRequest $request){
        DB::beginTransaction();

        try {
            $data = $request->except('password');
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $accessToken = $user->createToken('authToken')->accessToken;
            DB::commit();
            $userData = [
                "access_token"=>$accessToken,
                "user"=>$user
            ];
            return response()->sendSuccess($userData, 'Registration Successful');
        } catch (\Exception $exception) {
            DB::rollback();
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);;
        }
    }

    /**
     * @param LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        try{
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = User::where('email', $request->email)->first();
                $accessToken = $user->createToken('authToken')->accessToken;
                $data = [
                    'access_token' => $accessToken,
                    'userData' => $user,
                ];
                return response()->sendSuccess($data, 'User Info');
            }else{
                return response()->sendError("Invalid credentials");
            }
        }catch (\Exception $exception){
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }

    public function logout(){
        \request()->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Logout successfully'
        ]);
    }
}
