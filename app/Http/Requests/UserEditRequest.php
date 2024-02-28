<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserEditRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $id = $request->user_id;
        return [
            'first_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'new_password' => 'min:6',
            'email' => 'required|unique:users,email,'.$id,
            'admin_type' => 'required_if:role,from_admin',
            'companies' => 'required_if:admin_type,company_admin'
        ];
    }
}
