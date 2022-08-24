<?php

namespace App\Providers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Success response
        Response::macro('sendSuccess', function ($data, $message= '', $code= 200) {
            return \response()->json([
                'success'=>true,
                'error'=>false,
                'message'=>$message,
                'data' =>$data,
            ], $code);
        });

        //Error response
        Response::macro('sendError', function ($error, $code= 404) {
            return \response()->json([
                'success'=>false,
                'error'=>'true',
                'errorMessage' =>$error,
            ], $code);
        });

        //Error message when exception occur
        Response::macro('sendErrorWithException', function ($exception, $error, $code= 404) {
            Log::info($exception->getMessage());
            return \response()->json([
                'success'=>false,
                'error'=>'true',
                'errorMessage' =>$error,
            ], $code);
        });

        Response::macro('sendValidationError', function ($errors) {
//            Log::info($exception->getMessage());
//            return \response()->json([
//                'success'=>false,
//                'error'=>'true',
//                'errorMessage' =>$error,
//            ], $code);
            throw new HttpResponseException(response()->json([
                'success'=>false,
                'error'=>true,
                'errorMessage'=>$errors,
            ], 422));
        });
    }
}
