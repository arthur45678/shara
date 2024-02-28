<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\UserInterface;
use App\Contracts\RoleInterface;
use App\Contracts\CountryInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\CompanyInterface;
use App\Contracts\ContactInterface;
use App\Contracts\JobInterface;
use App\Contracts\LanguageInterface;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UpdateInfoRequest;
use Illuminate\Validation\Rule;
use Sentinel;
use Validator;
use Activation;

class UserController extends Controller
{
	/**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Object of RoleInterface class
     *
     * @var roleRepo
     */
    private $roleRepo;

    /**
     * Object of CountryInterface class
     *
     * @var countryRepo
     */
    private $countryRepo;

    /**
     * Object of CategoryInterface class
     *
     * @var categoryRepo
     */
    private $categoryRepo;

    /**
     * Object of CompanyInterface class
     *
     * @var categoryRepo
     */
    private $companyRepo;

    /**
     * Object of ContactInterface class
     *
     * @var categoryRepo
     */
    private $contactRepo;

    /**
     * Object of JobInterface class
     *
     * @var jobRepo
     */
    private $jobRepo;

    /**
     * Object of LanguageInterface class
     *
     * @var languageRepo
     */
    private $languageRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param UserInterface $userRepo
     * @return void
     */
	public function __construct(UserInterface $userRepo, CountryInterface $countryRepo, RoleInterface $roleRepo, CategoryInterface $categoryRepo, CompanyInterface $companyRepo, ContactInterface $contactRepo, JobInterface $jobRepo, LanguageInterface $languageRepo)
	{
		$this->userRepo = $userRepo;
		$this->roleRepo = $roleRepo;
        $this->countryRepo = $countryRepo;
        $this->categoryRepo = $categoryRepo; 
        $this->companyRepo = $companyRepo;
        $this->contactRepo = $contactRepo;
        $this->jobRepo = $jobRepo;
        $this->languageRepo = $languageRepo;
        $this->middleware("genericAdmin");
        $this->middleware("admin");

	}

    /**
     * users screen, which allows to create, read, update, delete users. 
     * GET /admin/users
     * 
     * @return view
     */
    public function getUsers($route, $sort, $type, Request $request)
    {

        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if the user has permission to view users
    	if($loggedUser->hasAccess('user.view')){
            // if yes
            if($request->page){
                $page = $request->page;
            }else{
                $page = 1;
            }
            // get admins and users created from admin panel
            if($route == 'admins'){
                $users = $this->userRepo->getUsersFromAdmin();
                // get the superadmin role
                $role = Sentinel::findRoleBySlug('superadmin');

                // get the superadmins
                $superadmins = $this->roleRepo->getUsersInRole($role);
                // count superadmins: if there is only one superadmin, it is not possible to edit or delete the superadmin
                $superadminsCount = count($superadmins);
                $data = [
                     'users' => $users,
                     'superadmins_count' => $superadminsCount,
                     'role' => $route,
                     'count' => count($users),
                     'page' => $page
                    ];
            }elseif($route == 'users'){
                $searchDetails = $request->except('page');
                if(count($searchDetails) > 0){
                    $users = $this->userRepo->searchUsers($searchDetails, $sort, $type);
                }else{
                    $users = $this->userRepo->getRegisteredUsers();

                }
                // reverse the sorting type
                if($type == 'asc')
                    $newType = 'desc';
                else
                    $newType = 'asc';
                foreach ($users as $user) {
                    $username = strtolower($user->first_name).'-'.strtolower($user->last_name).'-'.$user->id;
                    $ip = $request->ip();
                    $record = geoip_record_by_name($ip);
                    $countryCode = strtolower( $record['country_code'] );
                    if($countryCode) {
                        $locale = $countryCode;
                    }else {
                        $locale = 'it';
                    }
                    $user->redirectLink = url('/').'/'.$locale."/public-profile/".$username;
                }
                $superadminsCount = '';
                $countries = $this->countryRepo->getAllCountries();
                $categories = $this->categoryRepo->getOrderedCategories();
                $count = $users->total();
                $data = [
                     'users' => $users,
                     'role' => $route,
                     'countries' => $countries,
                     'categories' => $categories,
                     'type' => $newType,
                     'count' => $count,
                     'filteredUsers' => json_encode($users->pluck('id')->toArray()),
                     'page' => $page
                    ];
            }

            // return the users management page
	    	return view('admin.users', $data);
	    }else{
            // if no
            // redirect back
	    	return redirect()->back();
	    }
    	
    }

    /**
     * get create user page
     * GET /admin/create-user
     * 
     * @return view
     */
    public function getCreateUser()
    {
        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if the user has permission to create a user
    	if($loggedUser->hasAccess('user.create')){
            // if yes
            // get the roles
    		$roles = $this->roleRepo->getAllRoles();
            $companies = $this->companyRepo->getAllGenerics();
	    	$data = [ 
                    'roles' => $roles,
                    'companies' => $companies
                    ];
            // return the user create page
	    	return view('admin.create_user', $data);
    	}else{
            // if no
            // redirect back
    		return redirect()->back();
    	}
    	
    }

    /**
     * create user
     * POST /admin/create-user
     *
     * @param UserCreateRequest $request
     * @return redirect
     */
    public function postCreateUser(UserCreateRequest $request)
    {
        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if the user has permission to create a user
    	if($loggedUser->hasAccess('user.create')){
            // if yes
            // get the request data
    		$firstName = $request->first_name;
	    	$lastName = $request->last_name;
	    	$email = $request->email;
            $password = $request->password;
            $adminType = $request->admin_type;
	    	$credentials = [
                            'email' => $email,
	    					'password' => $password,
	    					'first_name' => $firstName,
	    					'last_name' => $lastName,
                            'role' => 'from_admin',
                            'admin_type' => $adminType,
                            'restrict' => 'false'
                            ];
            $activation = $request->activation;
            if($activation)
            {
                $credentials['activation'] = 'activated';
            }else{
                $credentials['activation'] = 'deactivated';
            }

            // create and activate the user
			$user = Sentinel::registerAndActivate($credentials);

            // if there are roles in the request data
			if(count($request->roles) > 0){
				foreach($request->roles as $roleName){
                    // get the role
		    		$role = Sentinel::findRoleByName($roleName);
                    // attach it to the created user
		    		$role->users()->attach($user);
		    	}
			}

            // if there are companies in the request data
            if($adminType == 'company_admin'){
                $companies = $request->companies;
                if($companies && count($companies) > 0){
                    foreach ($companies as $company) {
                        $company = $this->companyRepo->getCompanyById($company);
                        $company->admins()->attach($user);
                    }
                }
            }

	    	// return to the users management page
	    	return redirect()->action('Admin\UserController@getUsers', ['admins', 'date', 'asc']);
    	}else{
            // if no
            // redirect back
    		return redirect()->back();
    	}
    	
    }

    /**
     * delete user
     * GET /admin/delete-user/{id}
     *
     * @param int $id
     * @return redirect
     */
    public function getDeleteUser($id)
    {
        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if the user has permission to delete the user
    	if($loggedUser->hasAccess('user.delete')){
            // if yes
            // get the user
    		$user = Sentinel::findById($id);
            // delete the user
    		$user->delete();
    	}

        // redirect back
    	return redirect()->back();
    }

    /**
     * get edit user page
     * GET /admin/edit-user/{id}
     *
     * @param int $id
     * @return view
     */
    public function getEditUser($id)
    {
        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if the user has permission to edit the user
    	if($loggedUser->hasAccess('user.update')){
            // if yes
            // get the user
    		$user = Sentinel::findById($id);

            // //user activated or not
            $activation = Activation::completed($user);
            //get all roles
	    	$roles = $this->roleRepo->getAllRoles();

            //get the user role
	    	$userRoles = [];
	    	foreach($user->roles as $role)
	    	{
	    		$userRoles[] = $role->slug;
	    	}

            $companies = $this->companyRepo->getAllGenerics();
	    	$data = [
                    'user' => $user, 
                    'roles' => $roles, 
                    'user_roles' => $userRoles,
                    'companies' => $companies
                    ];
            if($activation) {
                $data['activated'] = true;
            }else {
                $data['activated'] = false;
            }
            // return edit user page
	    	return view('admin.edit_user', $data);
    	}else{
            // if no
            // redirect back
    		return redirect()->back();
    	}
    	
    }

    /**
     * edit user
     * POST /admin/edit-user
     *
     * @param UserEditRequest $request
     * @return redirect
     */
    public function postEditUser(UserEditRequest $request)
    {
        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if the user has permission to edit the user
    	if($loggedUser->hasAccess('user.update')){
            // if yes
            // get the user
    		$userId = $request->user_id;
	    	$user = Sentinel::findUserById($userId);
            // get request data
	    	$firstName = $request->first_name;
	    	$lastName = $request->last_name;
	    	$email = $request->email;
            $roles = $request->roles;
            $adminType = $request->admin_type;
            $suspended = $request->suspended;
            $oldPassword = $request->old_password;
            $requestCompanies = $request->companies;
            $newPassword = "";

            if($oldPassword){
                // check if there is a user with the oldpassword and email
                $validated = Sentinel::validateCredentials($user, ['email' => $email, 'password' => $oldPassword]);
                if($validated){
                    //if yes, set the new password
                    $newPassword = $request->new_password;
                }else{
                    // if no, redirect back with the validation message
                    return redirect()->back()->with('old_password', 'The password confirmation does not match');
                }
            }
            
	 		// get the user roles
	 		$userRoles = [];
	 		if(count($user->roles) > 0){
	 			foreach($user->roles as $role){
		    		$userRoles[] = $role->name;
		    	}
	 		}

            

	    	$credentials = [
	    				'first_name' => $firstName,
	    				'last_name' => $lastName,
                        'admin_type' => $adminType,
	    					];

            if($newPassword){
                $credentials['password'] = $newPassword;
            }

	    	if($suspended){
                $credentials['activation'] = 'deactivated';
            }else{
                $credentials['activation'] = 'activated';
            }

            // if the user is a registered user, it is possible to edit location
            if($request->location){
                $credentials['location'] = $request->location;
                $credentials['country'] = $request->country;
                $credentials['city'] = $request->city;
                $credentials['email'] = $request->email;
            }

            $adminCompanies = $user->companies;
            //if admin type is company admin
            if($adminType == 'company_admin'){
                
                if(count($adminCompanies) > 0){
                    foreach($adminCompanies as $oldCompany){
                    if(!in_array($oldCompany->name, $requestCompanies)){
                        $oldCompany->admins()->detach($user);
                        }

                    }
                }
                foreach ($requestCompanies as $newCompany) {
                    $company = $this->companyRepo->getCompanyById($newCompany);
                    if (!$company->admins->contains($user->id)) {
                        $company->admins()->attach($user);
                    }
                    
                }

                //detach all roles
                foreach ($user->roles as $userRole) {
                     $userRole->users()->detach($user);
                }
                
            }elseif($adminType == 'generic'){
                // if there are roles in request data
                // attach new roles to the user
                if(count($roles) > 0){
                    foreach($roles as $role){
                        if(!in_array($role, $userRoles)){
                            $newRole = Sentinel::findRoleByName($role);
                            $newRole->users()->attach($user);
                        }
                    }
                }
                
                // detach the roles from user, that don't exist in the request roles data
                if(count($userRoles) > 0){
                    foreach($userRoles as $role){
                        if(count($roles) > 0 ){
                            if(!in_array($role, $roles)){
                                
                                $detachRole = Sentinel::findRoleByName($role);
                                $detachRole->users()->detach($user);
                            }
                        }else{
                            $detachRole = Sentinel::findRoleByName($role);
                            $detachRole->users()->detach($user);
                        }
                        
                    }
                }

                //detach all companies
                foreach ($adminCompanies as $adminCompany) {
                        $adminCompany->admins()->detach($user);
                }
            }
            
            
            // edit the user
	    	$user = Sentinel::update($user, $credentials);
            // retur to the users management page
            if($user->role == 'from_registration'){
                return redirect()->action('Admin\UserController@getUsers', ['users', 'date', 'asc']);
            }else{
                return redirect()->action('Admin\UserController@getUsers', ['admins', 'date', 'asc']);
            }
	    	
    	}else{
            // if no
            // redirect back
    		return redirect()->back();
    	}
    	
    }

    /**
     * show user details
     * GET /admin/show-user/{id}
     *
     * @param int $id
     * @return view
     */
    public function getShowUser($id)
    {
        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if the user has permission to view the user
    	if($loggedUser->hasAccess('user.view')){
            // if yes
            // get the user by id
    		$user = $this->userRepo->getUserById($id);
            $workingAreas = json_decode($user->working_area);
            $area = '';
            if(count($workingAreas) > 0){
                foreach($workingAreas as $areaKey => $workingArea){
                    if($areaKey != 0){
                        $area .= ', ';
                    }
                    $area .= str_replace("_"," ",$workingArea);
                }
                $user->area = $area;
            }

            $education = json_decode($user->education);
            $educationString = '';
            if(count($education) > 0){
                foreach($education as $edKey => $ed){
                    if($edKey != 0){
                        $educationString .= ', ';
                    }
                    $educationString .= str_replace("_"," ",$ed);
                }
                $user->education = $educationString;
            }
            
            $transportaions = json_decode($user->transport);
            $transport = '';
            if(count($transportaions) > 0){
                foreach($transportaions as $transKey => $trans){
                    if($transKey != 0){
                        $transport .= ', ';
                    }
                    $transport .= str_replace("_"," ",$trans);
                }
                $user->transportation = $transport;
            }

            //available days
            $weekDays = json_decode($user->week_days);
            $days = '';
            if(count($weekDays) > 0){
                foreach($weekDays as $dayKey => $day){
                    if($dayKey != 0){
                        $days .= ', ';
                    }
                    $days .= str_replace("_"," ",$day);
                }
                $user->days = $days;
            }

            //available hours
            $hours = json_decode($user->hours);
            $workingHours = '';
            if(count($hours) > 0){
                foreach($hours as $hourKey => $hour){
                    if($hourKey != 0){
                        $workingHours .= ', ';
                    }
                    $workingHours .= str_replace("_"," ",$hour);
                }
                $user->hours = $workingHours;
            }

            // get the user roles
	    	$userRoles = [];
	    	if(count($user->roles) > 0){
	    		foreach($user->roles as $role){
	    			$userRoles[] = $role->name;
	    		}
	    	}

            //get user registration date
            $regDate = $user->created_at->toDateString();
            $regTime = $user->created_at->toTimeString();

            $user->regDate = $regDate;
            $user->regTime = $regTime;

	    	$data = [
                    'user' => $user, 
                    'user_roles' => $userRoles
                    ];
            // return the user details page
	    	return view('admin.show_user', $data);
    	}else{
            // if no
            // redirect back
    		return redirect()->back();
    	}
    	
    }

    /**
     * get Contacts
     * GET /admin/contacts
     * 
     * return contacts view
     */
    public function getContacts()
    {
        $contacts = $this->contactRepo->getAll();
        $data = ['contacts' => $contacts];

        return view('admin.contacts', $data);
    }

    /**
     * delete contact
     * GET /admin/delete-contact/{id}
     * 
     * @param int $id
     * @return redirect
     */
    public function getDeleteContact($id)
    {
        $this->contactRepo->deleteContact($id);

        return redirect()->back();
    }

    /**
     * change user retsriction status
     */
    public function getChangeRestriction($id)
    {
        $this->userRepo->changeRestriction($id);
        return redirect()->back();
    }

    /**
     * get applicatns page
     *
     * @param Request $request
     * @return view
     */
    public function getApplicants(Request $request, $param, $order)
    {
        $filterDetails = $request->except('_token', 'page');
        $companies = $this->companyRepo->getCountrySubsidiaries();
        $countries = $this->countryRepo->getAllCountries();
        $categories = $this->categoryRepo->getOrderedCategories();
        $data = [
            'companies' => $companies,
            'countries' => $countries,
            'categories' => $categories,
            'param' => $param,
            'order' => $order
        ];
        switch ($param) {
            case 'name':
                $paramOrder = 'users.first_name';
                break;
            case 'email':
                $paramOrder = 'users.email';
                break;    
            case 'country':
                $paramOrder = 'users.country';
                break;
            case 'city':
                $paramOrder = 'users.city';
                break;
            case 'company':
                $paramOrder = 'companies.name';
                break;
            case 'job':
                $paramOrder = 'jobs.name';
                break; 
            case 'date':
                $paramOrder = 'job_user.created_at';
                break;            
            default:
                $paramOrder = 'job_user.created_at';
                break;
        }
        if($filterDetails) {
            $data['filteredCompany'] = isset($filterDetails['company']) ? $filterDetails['company'] : '';
            $data['filteredJob'] = isset($filterDetails['job']) ? $filterDetails['job'] : '';
            $data['filteredCategory'] = isset($filterDetails['category']) ? $filterDetails['category'] : '';
            $data['filteredCountry'] = isset($filterDetails['country']) ? $filterDetails['country'] : '';
            $results = $this->userRepo->getFilteredApplicants($filterDetails, $paramOrder, $order, $request);
            $applications = $results['applications'];
            $count = $results['count'];
        }else {
            $results = $this->userRepo->getAllApplicants($paramOrder, $order);
            $applications = $results['applications'];
            $count = $results['count'];
        }
        $data['applicants'] = $applications;
        $data['count'] = $count;
        $data['applicantsIds'] = json_encode($applications->pluck('userId')->toArray());
        return view('admin.applicants', $data);
    }


    /**
     * export filtered users detials in csv file
     * Get
     *
     * @param Request $request
     * @return view
     */
    public function getExportCsv(Request $request)
    {
        $filteredIds = json_decode($request->filtered_users);
        if(count($filteredIds) > 0){

            $users = [];
            foreach ($filteredIds as $id) {
                $user = $this->userRepo->getUserById($id);
                $users[] = $user;
            }
        }else{
            $users = $this->userRepo->getRegisteredUsers();
        }

        $csvArray = [];
        $csvTitles = [
                    'First name',
                    'Last name',
                    'Email', 
                    'Birth date',
                    'Phone number',
                    'Location',
                    'Skills',
                    'Languages',
                    'Applications',
                    'Currently Student',
                    'Education',
                    'Driving license',
                    'Tranport',
                    'Schedule',
                    'Daily availabality',
                    'Hourly availabality',
                    'Working area',
                    ];

        $csvArray[] = $csvTitles;
        // file_put_contents('file.csv', $csvTitles);
        $fp = fopen('file.csv', 'w');

            fputcsv($fp, $csvTitles);

        foreach ($users as $key => $user) {


            $skills = '';
            if($user->skills){
                foreach ($user->skills as $skillKey => $skill) {
                    if($skillKey != 0){
                        $skills .= ' / ';
                    }
                    $skills .= $skill->name;
                    
                }

            }

            $languages = '';
            if($user->languages){
                foreach ($user->languages as $langKey => $lang) {
                    if($langKey != 0){
                        $languages .= ' / ';
                    }
                    $languages .= $lang->language;
                    
                }

            }

            $applications = '';
            if($user->applications){
                foreach ($user->applications as $appKey => $app) {
                    if($appKey != 0){
                        $applications .= ' / ';
                    }
                    $applications .= $app->name;
                    
                }

            }

            $workingAreas = json_decode($user->working_area);
            $area = '';
            if(count($workingAreas) > 0){
                foreach($workingAreas as $areaKey => $workingArea){
                    if($areaKey != 0){
                        $area .= ' / ';
                    }

                    $area .= str_replace("_"," ",$workingArea);
                }
            }
            
            $transportaions = json_decode($user->transport);
            $transport = '';
            if(count($transportaions) > 0){
                foreach($transportaions as $transKey => $trans){
                    if($transKey != 0){
                        $transport .= ' / ';
                    }
                    $transport .= str_replace("_"," ",$trans);
                }
            }
             
            $drivingLicense = '';
            if($user->driving_license == true){
                $drivingLicense = 'Yes';
            }elseif($user->driving_license == false){
                $drivingLicense = 'No';
            }

            $education = json_decode($user->education);
            $educated = '';
            if(count($education) > 0){
                foreach($education as $edKey => $ed){
                    if($edKey != 0){
                        $educated .= ' / ';
                    }
                    $educated .= str_replace("_"," ",$ed);
                }
            }

            $student = '';
            if($user->currently_student == true){
                $student = 'Yes';
            }elseif($user->currently_student == false){
                $student = 'No';
            }

            //available days
            $weekDays = json_decode($user->week_days);
            $days = '';
            if(count($weekDays) > 0){
                foreach($weekDays as $dayKey => $day){
                    if($dayKey != 0){
                        $days .= ', ';
                    }
                    $days .= str_replace("_"," ",$day);
                }
            }

            //available hours
            $hours = json_decode($user->hours);
            $workingHours = '';
            if(count($hours) > 0){
                foreach($hours as $hourKey => $hour){
                    if($hourKey != 0){
                        $workingHours .= ', ';
                    }
                    $workingHours .= str_replace("_"," ",$hour);
                }
            }

            // get the user roles
            $roles = '';
            if(count($user->roles) > 0){
                foreach($user->roles as $roleKey => $role){
                    if($roleKey != 0){
                        $roles .= ', ';
                    }
                    $roles .= str_replace("_"," ",$role->name);
                }
            }

            $array = [
                    $user->first_name,
                    $user->last_name,
                    $user->email,
                    $user->birth_date,
                    $user->phone_number,
                    $user->country.' '.$user->city,
                    $skills,
                    $languages,
                    $applications,
                    $student,
                    $educated,
                    $drivingLicense,
                    $transport,
                    $user->schedule,
                    $days,
                    $workingHours,
                    $roles,
            ];
            $csvArray[] = $array;
            fputcsv($fp, $array);
        }

        fclose($fp);

        return response()->download('file.csv')->deleteFileAfterSend(true);

    }

    public function getUpdateUserInfo($userId)
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to edit the user
        if($loggedUser->hasAccess('user.update')){
            // if yes
            // get the user
            $user = $this->userRepo->getUserById($userId);
            $userTransport = json_decode($user->transport, true);
            $userEducation = json_decode($user->education, true);
            $userSchedule = $user->schedule;
            $userWeekDays = json_decode($user->week_days, true);
            $userHours = json_decode($user->hours, true);
            $userLanguages = [];
            foreach ($user->languages as $language) {
                $userLanguages[] = $language->id;
            }
            $categories = $this->categoryRepo->getAllCategories();
            $userCategories = [];
            foreach ($user->skills as $category) {
                $userCategories[] = $category->id;
            }
            $userWorkingArea = json_decode($user->working_area, true);
            $languages = $this->languageRepo->getAll();
            $countries = $this->countryRepo->getAllCountries();
            $data = ['user' => $user,
                     'userTransport' => $userTransport, 
                     'userEducation' => $userEducation,
                     'userSchedule' => $userSchedule,
                     'userWeekDays' => $userWeekDays,
                     'userHours' => $userHours,
                     'userWorkingArea' => $userWorkingArea,
                     'languages' => $languages,
                     'userLanguages' => $userLanguages,
                     'categories' => $categories,
                     'userCategories' => $userCategories,
                     'countries' => $countries
                     ];
            return view('admin.update_user_info', $data);
            
        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
    }

    public function postUpdateUserInfo(UpdateInfoRequest $request)
    {
        $data = $request->all();
        $firstName = $request->first_name;
        $lastName = $request->last_name;
        $birthDate = $request->birth_date;
        $gender = $request->gender;
        $nationality = $request->nationality;
        $location = $request->location;
        $city = $request->city;
        $country = $request->country;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $phoneNumber = $request->phone_number;
        $transport = json_encode($request->transport);
        $education = json_encode($request->education);
        $languages = $request->languages;
        $skills = $request->skills;
        $schedule = $request->schedule;
        $weekDays = json_encode($request->week_days);
        $hours = json_encode($request->hours);
        $workingArea = json_encode($request->working_area);
        $user = $this->userRepo->getUserById($request->user_id);
        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birth_date' => $birthDate,
            'location' => $location,
            'city' => $city,
            'country' => $country,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'phone_number' => $phoneNumber,
            'transport' => $transport,
            'education' => $education,
            'schedule' => $schedule,
            'week_days' => $weekDays,
            'hours' => $hours,
            'working_area' => $workingArea,
            'gender' => $gender,
            'nationality' => $nationality
        ];

        $userExperience = $request->user_experience;
        $facebookLink = $request->facebook_link;
        if($userExperience) {
            $updateData['user_experience'] = $userExperience;
        }

        if($facebookLink) {
            $updateData['facebook_link'] = $facebookLink;
        }

        $this->userRepo->editUser($user, $updateData);
        if(isset($languages)) {
            $user->languages()->detach();
            foreach($languages as $language) {
                $user->languages()->attach($language);
            }
        }

        if(isset($skills)) {
            $user->skills()->detach();
            foreach($skills as $category) {
                $user->skills()->attach($category);
            }
        }

        return redirect()->action('Admin\UserController@getUsers', ['users', 'date', 'asc']);
    }
}
