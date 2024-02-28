<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    
    $api->group(['middleware' => 'language'], function ($api) {
        $api->get('/dashboard', 'App\Http\Controllers\User\UserController@getDashboard');
        $api->get('/registration/{country}', 'App\Http\Controllers\User\RegistrationController@getRegistration');
        $api->post('/registration', 'App\Http\Controllers\User\RegistrationController@postRegistration');
        $api->post('/registration-first-step', 'App\Http\Controllers\User\RegistrationController@postRegistrationFirstStep');
        $api->post('/login', 'App\Http\Controllers\User\RegistrationController@postLogin');   
        // $api->get('/activate/{email}', 'App\Http\Controllers\User\UserController@getActivateUser');
        $api->post('/facebook-login', 'App\Http\Controllers\User\RegistrationController@postFacebookLogin');
        $api->post('/reset-password', 'App\Http\Controllers\User\RegistrationController@postResetPassword');
        $api->get('/reset-password/{email}/{token}', 'App\Http\Controllers\User\RegistrationController@getResetPass');
        $api->post('/change-pass', 'App\Http\Controllers\User\RegistrationController@postChangePass');
        $api->get('/country-languages', 'App\Http\Controllers\User\UserController@getCountryLanguages');
        $api->get('/country-language', 'App\Http\Controllers\User\UserController@getCountryLanguage');
        $api->get('/check-email', 'App\Http\Controllers\User\UserController@getCheckEmail');    
        $api->get('/search', 'App\Http\Controllers\User\SearchController@getSearchByLocation');
        $api->get('/popular-categories', 'App\Http\Controllers\User\SearchController@getPopularCategories');
        $api->get('/phone-code', 'App\Http\Controllers\User\UserController@getPhoneCode');
        $api->get('/phone-code-name', 'App\Http\Controllers\User\UserController@getPhoneCodeByName');
        $api->get('/job-details', 'App\Http\Controllers\User\JobController@getJobDetails');
        $api->get('/company-details', 'App\Http\Controllers\User\CompanyController@getCompanyDetails');    
        $api->get('/company-subsidiary-info', 'App\Http\Controllers\User\CompanyController@getCompanySubsidiaryInfo');    
        $api->get('/top-sector-jobs', 'App\Http\Controllers\User\UserController@getTopSectorJobs');
        $api->get('/categories', 'App\Http\Controllers\User\UserController@getCategories');    
        $api->get('/resend-activation-email', 'App\Http\Controllers\User\RegistrationController@getResendActivationMail');    
        $api->get('/browse-jobs-gigs', 'App\Http\Controllers\User\SearchController@getBrowseJobsGigs');
        $api->get('/user-language', 'App\Http\Controllers\User\UserController@getUserLanguage');
        $api->post('/logout', 'App\Http\Controllers\User\RegistrationController@postLogout');
        $api->post('/contact-us', 'App\Http\Controllers\User\UserController@postContactUs');
        $api->get('/user-details-register', 'App\Http\Controllers\User\UserController@getUserDetailsRegister');
        $api->get('/apply-job', 'App\Http\Controllers\User\JobController@getApplyForJob');
        $api->get('/job-company-info', 'App\Http\Controllers\User\JobController@getJobCompanyInfo');
        $api->get('/top-sectors', 'App\Http\Controllers\User\SearchController@getTopSectors');
        $api->get('/get-subscribtion', 'App\Http\Controllers\User\JobController@getSubscribtion');
        $api->get('/subscribe-for-jobs', 'App\Http\Controllers\User\JobController@getSubscribeForJobs');
        $api->get('/remove-alert/{alertId}', 'App\Http\Controllers\User\JobController@getRemoveAlert');
        $api->get('/change-language', 'App\Http\Controllers\User\UserController@getChangeLanguage');
        $api->get('/all-categories', 'App\Http\Controllers\User\UserController@getAllCategories');
        $api->get('/main-details', 'App\Http\Controllers\User\UserController@getUserLatLng');
        $api->get('/apply-company', 'App\Http\Controllers\User\CompanyController@getApplyForCompany');
        $api->get('/countries', 'App\Http\Controllers\User\UserController@getCountries');
        $api->get('/public-profile', 'App\Http\Controllers\User\UserController@getPublicProfile');
        $api->get('/return-country-code', 'App\Http\Controllers\User\UserController@getReturnCountryCode');
        
        
    });

    $api->group(['middleware' => ['jwt.logout', 'language']], function ($api) {
        $api->get('/my-profile', 'App\Http\Controllers\User\UserController@getMyProfile');
        $api->post('/edit-my-profile-first', 'App\Http\Controllers\User\UserController@postEditMyProfileFirstTab');
        $api->post('/edit-my-profile-second', 'App\Http\Controllers\User\UserController@postEditMyProfileSecondTab');
        $api->post('/edit-my-profile-third', 'App\Http\Controllers\User\UserController@postEditMyProfileThirdTab');        
        $api->get('/user-applied-job', 'App\Http\Controllers\User\JobController@getUserAppliedJob');
        $api->get('/generate-token', 'App\Http\Controllers\User\UserController@getGenerateToken');
        $api->get('/token-refresh', 'App\Http\Controllers\User\UserController@refreshToken');
        $api->get('/user-details', 'App\Http\Controllers\User\UserController@getUserDetails');
        

    });
});
// Route::resource('/authenticate', 'AuthenticateController@authenticate');