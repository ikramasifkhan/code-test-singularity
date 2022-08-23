<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'latitude'=>'required',
            'longitude'=>'required',
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
}
