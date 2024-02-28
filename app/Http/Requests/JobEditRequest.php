<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobEditRequest extends FormRequest
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

        if ($is_published) {
            return [
                'name' => 'required|max:255',
            ];
        } else {
                return [
                'name' => 'required|max:255',
                'requirement' => 'required',
                'sector' => 'required',
                'category' => 'required',
                'url_to_redirect' => 'url|required_if:job_applying,redirect|max:255',
                'job_applying' => 'required',
                'company' => 'required',
                // 'city_country' => 'same:country'
            ];
        }
    }
}
