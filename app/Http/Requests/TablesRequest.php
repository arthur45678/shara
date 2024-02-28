<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TablesRequest extends FormRequest
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
            //
        ];
    }

    /**
     * set request parameters
     *
     * @return array
     */
    public function inputs()
    {
        $inputs = $this->all();
        if( !isset($inputs['count']) ) { $inputs['count'] = 0; }
        if( !isset( $inputs['order'] ) ){ $inputs['order'] = 'desc'; }
        return $inputs;
    }
}
