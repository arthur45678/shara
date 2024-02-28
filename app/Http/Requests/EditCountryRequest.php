<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EditCountryRequest extends FormRequest
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
        $id = $request->get('country_id');
        return [
            
            'abbreviation' => 'required|max:255',
            'language' => 'required|max:255',
            'currency' => 'required|max:255',
            'metric' => 'required',
            'name' => 'required|unique:countries,name,'.$id,
        ];
    }
}
