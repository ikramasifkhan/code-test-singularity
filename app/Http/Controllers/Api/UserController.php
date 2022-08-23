<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResouce;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = User::all();
            $data = UserResouce::collection($users);
            return response()->sendSuccess($data, 'Users List');
        }catch (\Exception $exception){
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password)
            ]);
            $company = new UserResouce($user);
            return response()->sendSuccess($company, 'User Created Successfully');
        }catch (\Exception $exception){
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $user->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password)
            ]);
            return response()->sendSuccess(new UserResouce($user), 'User Updated Successfully');
        }catch (\Exception $exception){
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try{
            $user->delete();
            return \response()->sendSuccess($user, 'User Deleted Successfully', 200);
        }catch (\Exception $exception){
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }
}
