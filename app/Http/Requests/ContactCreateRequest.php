<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactCreateRequest extends FormRequest
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
            'companyName' => 'required|max:256',
            'email' => 'email|required|max:256',
            'location' => 'max:256',
            'country' => 'max:256',
            'city' => 'max:256',
            'webSite' => 'max:256',
        ];
    }

    public function response(array $response)
    {
        $data = [
            'errors' => $response,
            'success' => '0'
        ];
        if($this->ajax())
            return response()->json($data);
        else
            return redirect()->back()->withErrors($response);
    }
}
