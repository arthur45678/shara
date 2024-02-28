<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyCreateRequest extends FormRequest
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
    public function rules(\Illuminate\Http\Request $request)
    {
        $is_published = $request->is_published;
        $type = $request->type;
        $subType = $request->sub_type;
        if($type == 'generic'){
            if ($is_published) {
                return [
                    'name' => 'required|max:255|unique:companies,name,id'
                ];
            } else {
                return [
                    'name' => 'required|max:255|unique:companies,name,id',
                    'url' => 'required|max:255',
                    'description' => 'required',
                    'short_description' => 'required',
                    // 'logo' => 'required_without:logo_url',
                    'logo_url' => 'url',
                    'country' => 'required|',
                    'city' => 'exists:cities,name',
                    'industry' => 'required',
                    'category' => 'required',
                    'compensation' => 'max:255',
                    // 'why_us' => 'max:255',
                    // 'job_applying' => 'required|max:255',
                    'url_to_redirect' => 'required_if:job_applying,redirect|max:255|url',
                    'city_country' => 'same:country'

                ];
            }
        }else{
            if($subType == 'country_subsidiary'){
                return [
                        'country' => 'required',
                    ];
            }elseif($subType == 'city_subsidiary'){
                 return [
                        'subsidiary_city' => 'required',
                        'city_country' => 'same:country'
                    ];
            }
        }
       
        
    }
}
