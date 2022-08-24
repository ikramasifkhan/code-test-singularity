<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OutletRequest extends FormRequest
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
            'latitude'=>'required|numeric|min:-90|max:90',
            'longitude'=>'required|numeric|min:-180|max:180',
            'image'=>"required|image"
        ];
        switch ($this->method()) {
            case 'POST':
                return array_merge($commonData, ['phone' => "required|unique:outlets,phone"]);
                break;
            case 'PATCH':
            case 'PUT':
                return array_merge($commonData, [
                    "id"=>"required|numeric",
                    'phone'=>"required|unique:outlets,phone,{$this->id}",
                ]);
                break;
        }
    }

    public function failedValidation(Validator $validator)
    {
        return response()->sendValidationError($validator->errors());
    }
}
