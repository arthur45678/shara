<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyCityRequest extends FormRequest
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
            'subsidiary_city' => 'required',
            'city_latitude' => 'required', 
            'city_longtitude' => 'required',
            'country_parent' => 'required',
            'country' => 'required'
        ];
    }
}
