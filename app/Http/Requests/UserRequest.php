<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $commonData = [
            'name'=>'required',
            'password'=>'required',
        ];
        switch ($this->method()) {
            case 'POST':
                return array_merge($commonData, ['email' => "email|required|unique:users,email"]);
                break;
            case 'PATCH':
            case 'PUT':
                return array_merge($commonData, [
                    "id"=>"required|numeric",
                    'email'=>"email|required|unique:users,email,{$this->id}",
                ]);
                break;
        }
    }

    public function failedValidation(Validator $validator)
    {
        return response()->sendValidationError($validator->errors());
    }
}
