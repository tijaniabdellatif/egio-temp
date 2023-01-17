<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdsUserCheck extends FormRequest
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
     * @return array
     */
    public function rules()
    {

            return [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'password2' => 'required|min:8|same:password',
                'firstname' => 'required',
                'lastname' => 'required',
                'phone' => 'required',
                'username' => 'required|unique:users',
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:5000',
                'price' => 'required|numeric',
                'loccity' => 'required|integer',
                'locdept' => 'required|integer',
               
            ];


    }

    public function messages()
    {
        return [
            'email.required' => 'le champ email est requis',
            "password2.same" => "la confirmation et le mot de passe sont pas identiques"
            // ..
        ];
    }

            protected function failedValidation(Validator $validator)
            {
                throw new HttpResponseException(response()->json([
                'errors' => $validator->errors(),
                'status' => true
                ], 422));
         }
}
