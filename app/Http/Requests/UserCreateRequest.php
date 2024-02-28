<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'first_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'admin_type' => 'required',
            'companies' => 'required_if:admin_type,company_admin'
        ];
    }

    public function inputs()
    {
        $inputs = $this->all();
        $inputs['password'] = bcrypt($inputs['password']);
        return $inputs;
    }

}
