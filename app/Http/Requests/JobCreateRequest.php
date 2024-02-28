<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class JobCreateRequest extends FormRequest
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
                'url_to_redirect' => 'url|required_if:job_applying,redirect',
                'job_applying' => 'required',
                'schedule' => 'max:255',
                'company' => 'required'
            ];
        }
        
    }

    public function inputs(Request $request)
    {
        $name = $request->name;
        $companyId = isset($request->company) &&  $request->company != ""? $request->company : null;
        $countryName = $request->country;
        $country = $this->countryRepo->getCountryByName($countryName);
        $countryId = isset($country) ? $country->id : null;
        $city = $request->city;
        $cityLatitude = $request->city_latitude;
        $cityLongtitude = $request->city_longtitude;
        $region = $request->region;
        $sectorId = isset($request->sector) &&  $request->sector != ""? $request->sector : null;
        $categoryId = isset($request->category) &&  $request->category != ""? $request->category : null;
        $description = $request->description;
        $aboutCompany = $request->about_company;
        $requirement = $request->requirement;
        $schedule = $request->schedule;
        $whyUs = $request->why_us;
        $benefits = $request->benefits;
        $jobApplying = $request->job_applying;
        $redirectUrl = $request->url_to_redirect;
        $activation = isset($request->activation) && $request->activation == 'activated' ? 'activated' : 'deactivated';
        $restrict = isset($request->is_published) && $request->is_published != '' ? 'true' : '';
        
        if($countryId && $city && $sectorId && $companyId)
                $type = 'generic';
        else
                $type = 'specific';

        $data = [
                'name' => $name,
                'company_id' => $companyId,
                'country_id' => $countryId,
                'city_name' => $city,
                'city_latitude' => $cityLatitude,
                'city_longtitude' => $cityLongtitude,
                'region' => $region,
                'sector_id' => $sectorId,
                'category_id' => $categoryId,
                'description' => $description,
                'about_company' => $aboutCompany,
                'requirement' => $requirement,
                'schedule' => $schedule,
                'why_us' => $whyUs,
                'benefits' => $benefits,
                'job_applying' => $jobApplying,
                'url_to_redirect' => $redirectUrl,
                'type' => $type,
                'activation' => $activation,
                'restrict' => $restrict,
                ];
        return $data;
    }
}
