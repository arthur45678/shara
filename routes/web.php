<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin/login', 'Admin\AdminController@getLogin');
Route::get('/admin', 'Admin\AdminController@getDashboard');
Route::post('admin/login', 'Admin\AdminController@postLogin'); 
Route::get('admin/logout', 'Admin\AdminController@getLogout');
Route::get('admin/location-details', 'Admin\AdminController@getLocationDetails');
Route::get('admin/alerts', 'Admin\AdminController@getAlerts');
Route::get('admin/remove-alert/{id}', 'Admin\AdminController@getRemoveAlert');

Route::get('/admin/users/{role}/{sort}/{type}', 'Admin\UserController@getUsers');
Route::get('/admin/create-user', 'Admin\UserController@getCreateUser');
Route::post('admin/create-user', 'Admin\UserController@postCreateUser');
Route::get('/admin/delete-user/{user_id}', 'Admin\UserController@getDeleteUser');
Route::get('/admin/edit-user/{user_id}', 'Admin\UserController@getEditUser');
Route::post('admin/edit-user', 'Admin\UserController@postEditUser');
Route::get('/admin/show-user/{id}', 'Admin\UserController@getShowUser');
Route::get('admin/contacts', 'Admin\UserController@getContacts');
Route::get('admin/delete-contact/{id}', 'Admin\UserController@getDeleteContact');
Route::get('/admin/change-restriction/{id}', 'Admin\UserController@getChangeRestriction');
Route::get('/admin/applicants/{param}/{order}', 'Admin\UserController@getApplicants');
Route::get('/admin/export-csv', 'Admin\UserController@getExportCsv');
Route::get('/admin/update-user-info/{user_id}', 'Admin\UserController@getUpdateUserInfo');
Route::post('/admin/update-user-info', 'Admin\UserController@postUpdateUserInfo');

Route::get('/admin/roles', 'Admin\RoleController@getRoles');
Route::get('/admin/create-role', 'Admin\RoleController@getCreateRole');
Route::post('admin/create-role', 'Admin\RoleController@postCreateRole');
Route::get('/admin/delete-role/{role_id}', 'Admin\RoleController@getDeleteRole');
Route::get('/admin/edit-role/{role_id}', 'Admin\RoleController@getEditRole');
Route::post('admin/edit-role', 'Admin\RoleController@postEditRole');
Route::get('/admin/show-role/{role_id}', 'Admin\RoleController@getShowRole');
Route::get('/admin/get-users-role/{role_id}', 'Admin\RoleController@getUsersInRole');


Route::get('/admin/sectors', 'Admin\SectorController@getSectors');
Route::get('/admin/create-sector', 'Admin\SectorController@getCreateSector');
Route::post('admin/create-sector', 'Admin\SectorController@postCreateSector');
Route::get('/admin/edit-sector/{sector_id}', 'Admin\SectorController@getEditSector');
Route::post('admin/edit-sector', 'Admin\SectorController@postEditSector');
Route::get('/admin/show-sector/{sector_id}', 'Admin\SectorController@getShowSector');
Route::get('/admin/delete-sector/{sector_id}', 'Admin\SectorController@getDeleteSector');

Route::get('/admin/categories', 'Admin\CategoryController@getCategories');
Route::get('/admin/create-category', 'Admin\CategoryController@getCreateCategory');
Route::post('admin/create-category', 'Admin\CategoryController@postCreateCategory');
Route::get('/admin/edit-category/{category_id}', 'Admin\CategoryController@getEditCategory');
Route::post('admin/edit-category', 'Admin\CategoryController@postEditCategory');
Route::get('/admin/show-category/{category_id}', 'Admin\CategoryController@getShowCategory');
Route::get('/admin/delete-category/{category_id}', 'Admin\CategoryController@getDeleteCategory');

Route::get('/admin/countries', 'Admin\CountryController@getCountries');
Route::get('/admin/add-country', 'Admin\CountryController@getAddCountry');
Route::post('admin/add-country', 'Admin\CountryController@postAddCountry');
Route::get('/admin/show-country/{country_id}', 'Admin\CountryController@getShowCountry');
Route::get('/admin/edit-country/{country_id}', 'Admin\CountryController@getEditCountry');
Route::post('admin/edit-country', 'Admin\CountryController@postEditCountry');
Route::get('/admin/delete-country/{country_id}', 'Admin\CountryController@getDeleteCountry');

Route::get('/admin/cities', 'Admin\CityController@getCities');
Route::get('/admin/add-city', 'Admin\CityController@getAddCity');
Route::post('admin/add-city', 'Admin\CityController@postAddCity');
Route::get('/admin/edit-city/{city_id}', 'Admin\CityController@getEditCity');
Route::post('admin/edit-city', 'Admin\CityController@postEditCity');
Route::get('/admin/show-city/{city_id}', 'Admin\CityController@getShowCity');
Route::get('/admin/delete-city/{city_id}', 'Admin\CityController@getDeleteCity');
Route::get('/admin/add-cities', 'Admin\CityController@getAddCities');
Route::post('admin/add-cities', 'Admin\CityController@postAddCities');
Route::get('/admin/get-country-cities/{country}', 'Admin\CityController@getCountryCities');
Route::post('admin/search-city','Admin\CityController@postSearchCity');

Route::get('/admin/companies/{sort}/{type}', 'Admin\CompanyController@getCompanies');
Route::get('/admin/create-company', 'Admin\CompanyController@getCreateCompany');
Route::post('admin/create-company', 'Admin\CompanyController@postCreateCompany');
Route::post('admin/create-city', 'Admin\CompanyController@postCreateCity');
Route::post('/admin/file-upload', 'Admin\CompanyController@postFileUpload');
Route::get('/admin/edit-company/{company_id}/{company}', 'Admin\CompanyController@getEditCompany');
Route::post('admin/edit-company', 'Admin\CompanyController@postEditCompany');
Route::post('admin/edit-subsidiary', 'Admin\CompanyController@postEditSubsidiary');
Route::get('/admin/show-company/{company_id}', 'Admin\CompanyController@getShowCompany');
Route::get('/admin/restrict-status/{company_id}/{type}/{restrictStatus}', 'Admin\CompanyController@getChangeRestrictStatus');
Route::get('/admin/delete-company/{company_id}/{type}', 'Admin\CompanyController@getDeleteCompany');
Route::get('/admin/delete-company-city/{city_id}', 'Admin\CompanyController@deleteCityCompany');
Route::get('/admin/subsidiaries/{company_id}', 'Admin\CompanyController@getSubsidiaries');
Route::get('/admin/add-subsidiary/{company_id}', 'Admin\CompanyController@getAddSubsidiary');
Route::post('admin/add-subsidiary', 'Admin\CompanyController@postAddSubsidiary');
Route::post('admin/clone-subsidiary', 'Admin\CompanyController@postCloneSubsidiary');
Route::post('admin/add-city-subsidiary', 'Admin\CompanyController@postAddCitySubsidiary');
Route::post('admin/search-company', 'Admin\CompanyController@postSearchCompany');
Route::get('/admin/get-remove-image/{company_id}', 'Admin\CompanyController@getRemoveImage');
Route::get('/admin/get-company-cities/{company}/{country}', 'Admin\CompanyController@getCompanyCities');
Route::get('/admin/get-company-countries/{company}', 'Admin\CompanyController@getCompanyCountries');
Route::get('admin/make-publish/{company_id}', 'Admin\CompanyController@getMakeCompanyPublish');
Route::get('admin/make-unpublish/{company_id}', 'Admin\CompanyController@getMakeCompanyUnpublished');
Route::get('admin/make-subsidary-publish/{subsidary_id}', 'Admin\CompanyController@getMakeSubsidaryPublish');
Route::get('admin/make-subsidary-unpublish/{subsidary_id}', 'Admin\CompanyController@getMakeSubsidaryUnpublish');

Route::get('admin/change-urls', 'Admin\CompanyController@getChangeName');
Route::get('admin/empty-cities', 'Admin\CompanyController@getCompaniesWithNullCity');
Route::get('admin/delete-spaces', 'Admin\CompanyController@getDeleteSpacesFromCompaniesNames');
Route::get('admin/description-meta-tag', 'Admin\CompanyController@getSetShortDescriptionMeta');

Route::get('/admin/jobs/{sort}/{type}', 'Admin\JobController@getJobs');
Route::get('/admin/create-job/{type}', 'Admin\JobController@getCreateJob');
Route::post('admin/create-job', 'Admin\JobController@postCreateJob');
Route::get('/admin/edit-job/{job_id}', 'Admin\JobController@getEditJob');
Route::post('admin/edit-job', 'Admin\JobController@postEditJob');
Route::get('/admin/show-job/{job_id}', 'Admin\JobController@getShowJob');
Route::get('/admin/delete-job/{job_id}', 'Admin\JobController@getDeleteJob');
Route::post('admin/search-job', 'Admin\JobController@postSearchJob');
Route::get('/admin/create-company-job/{company_id}/{type}', 'Admin\JobController@getCreateCompanyJob');
Route::post('admin/clone-job', 'Admin\JobController@postCloneJob');
Route::get('/admin/detach-job/{job_id}', 'Admin\JobController@getDetachJob');
Route::get('admin/make-publish-job/{job_id}', 'Admin\JobController@getMakeJobPublish');
Route::get('admin/make-unpublish-job/{job_id}', 'Admin\JobController@getMakeJobUnpublished');
Route::get('admin/get-company-jobs/{company_id}', 'Admin\JobController@getCompanyJobs');



Route::get('/email/activate/{token}/{lang}', 'User\RegistrationController@getActivateUser');
Route::get('/email/welcome/{user_id}', 'User\RegistrationController@getWelcome');
// Route::resourse('admin', 'Admin\AdminController');

// Route::resourse('admin', 'Admin\AdminController');
Route::get('/email/{id}', 'User\UserController@getDownloadDetails');
Route::get('/email/remove-alert/{id}', 'User\JobController@getRemoveAlertFromEmail');
Route::get('/admin/translations-json-save', 'Admin\AdminController@getSaveTranslationsJson');
Route::post('/admin/publish-angular-translations', 'Admin\AdminController@postPublishTranslations');

Route::get('/admin/phone-code', 'Admin\AdminController@getPhoneCode');
Route::get('/admin/username-generate', 'Admin\AdminController@getUsernameGenerate');
Route::get('/email/superadmin-info-change', 'Admin\AdminController@getChangeAdminInfo');

Route::get('/{lang}/company/{company_name}/{country_name}', 'User\CompanyController@getCompanyDetailsStatic');


