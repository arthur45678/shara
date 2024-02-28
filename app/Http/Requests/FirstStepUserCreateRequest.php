<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FirstStepUserCreateRequest extends FormRequest
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
            // 'first_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            // 'last_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            // 'email' => 'required|email|max:255',
            // 'password' => 'required_without:registeredByFacebook|min:6|alpha_dash',
        ];
    }

    // public function validationData()
    // {
    //     return json_decode($this->get('user'), true);
    // }

    // public function response(array $errors)
    // {
    //     return response()->json($errors);
    // }
}
