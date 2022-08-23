<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
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
    public function login(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'email' => 'required|string',
//            'password' => 'required',
//        ]);
//        if ($validator->fails()) {
//            // return send_error('Validation Error',$validator->errors(), 422);
//            return response()->json(['success' => false, 'errors' => $validator->errors(),], Response::HTTP_UNPROCESSABLE_ENTITY);
//        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where('email', $request->email)->first();
            $accessToken = $user->createToken('authToken')->accessToken;
            $data = [
                'access_token' => $accessToken,
                'userData' => $user,
            ];
            return response()->sendSuccess($data, 'User Info');
        }

        return response()->json(['success' => false, 'errors' => 'email or password incorrect',], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function logout(){
        \request()->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Logout successfully'
        ]);
    }
}
