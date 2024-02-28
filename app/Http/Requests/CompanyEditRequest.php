<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CompanyEditRequest extends FormRequest
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
        $id = $request->get('id');
        $is_published = $request->is_published;
        $type = $request->type;
        $subType = $request->sub_type;
        if ($is_published) {
            return [
                'name' => 'required|max:255'
            ];
        } else {
            if($type == 'generic'){

                return [
                        'name' => 'required|max:255', 
                        'url' => 'required',
                        'description' => 'required',
                        'short_description' => 'required',
                        'logo_url' => 'url|max:255',
                        'country' => 'required',
                        'city' => 'exists:cities,name',
                        'industry' => 'required',
                        'category' => 'required',
                        'compensation' => 'max:255',
                        // 'job_applying' => 'required|max:255',
                        'url_to_redirect' => 'required_if:job_applying,redirect|max:255|url',
                        ];

            }else{
                if($subType == 'city_subsidiary'){
                    return [
                        'name' => 'required',
                        'country' => 'required', 
                        'compensation' => 'max:255',
                        'url_to_redirect' => 'required_if:job_applying,redirect|url',
                        'logo_url' => 'url',
                        'job_applying' => 'required',
                        // 'city_country' => 'same:country'

                    ];
                }else{
                    return [
                        'name' => 'required',
                        'country' => 'required',
                        'url_to_redirect' => 'required_if:job_applying,redirect|url',
                        'logo_url' => 'url',
                        'job_applying' => 'required',
                        'description' => 'required',
                        'short_description' => 'required',
                        'url' => 'required',

                    ];
                }
            }
            
        }
    }
}
