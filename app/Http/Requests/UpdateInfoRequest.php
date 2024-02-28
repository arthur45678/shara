<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInfoRequest extends FormRequest
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
            'first_name' => 'max:255|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'max:255|regex:/^[\pL\s\-]+$/u',
            'phone_number' => 'numeric|different:phone_code',
            'user_experience' => 'max:2000'
        ];
    }
}
