<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SubsidiaryEditRequest extends FormRequest
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
        $parentId = $request->this_parent_id;
        if($request->city_sub == 'city_sub'){
            return [
                'name' => 'required',
                // 'name' => '|unique:companies,name,'.$id,
                // 'url' => 'required',
                // 'description' => 'required',
                // 'short_description' => 'required',
                'country' => 'required|same:city_country', 
                // 'category' => 'required',
                // 'looking_for' => 'required',
                // 'requirement' => 'required',
                'compensation' => 'max:255',
                // 'why_us' => 'required',
                'url_to_redirect' => 'required_if:job_applying,redirect|url',
                'logo_url' => 'url',
                'job_applying' => 'required',
                'city_country' => 'same:country'

            ];
        }else{
            return [
                'name' => 'required|unique:companies,name,'.$parentId,
                // 'name' => '|unique:companies,name,'.$id,
                // 'url' => 'required',
                // 'description' => 'required',
                // 'short_description' => 'required',
                'country' => 'required|same:city_country',
                // 'category' => 'required',
                // 'looking_for' => 'required',
                // 'requirement' => 'required',
                // 'compensation' => 'required',
                // 'why_us' => 'required',
                'url_to_redirect' => 'required_if:job_applying,redirect|url',
                'logo_url' => 'url',
                'job_applying' => 'required',
                'city_country' => 'same:country'

            ];
        }
        
    }
}
