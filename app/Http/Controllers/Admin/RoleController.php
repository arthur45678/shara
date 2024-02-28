<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\UserInterface;
use App\Contracts\RoleInterface;
use App\Contracts\PermissionInterface;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\RoleCreateRequest;
use Sentinel;
use Validator;

class RoleController extends Controller
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
     * Object of PermissionInterface class
     *
     * @var permissionRepo
     */
    private $permissionRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param UserInterface $userRepo
     * @return void
     */
	public function __construct(UserInterface $userRepo, RoleInterface $roleRepo, PermissionInterface $permissionRepo)
	{
		$this->userRepo = $userRepo;
		$this->roleRepo = $roleRepo;
		$this->permissionRepo = $permissionRepo;
		$this->middleware("admin");
        $this->middleware("genericAdmin");

	}

 	/**
 	 * role screen, which allows to create, update, read, delete roles.
     * GET /admin/roles
 	 *
 	 * @return view
 	 */
	public function getRoles()
    {
        //get the logged user
    	$loggedUser = Sentinel::getUser();

        //check if the user has access to view roles
    	if($loggedUser->hasAccess(['role.view'])){
            //if yes, show the roles
    		$roles = $this->roleRepo->getAllRolesPaginate();
	        $data = [
                    'roles' => $roles
                    ];
	        return view('admin.roles', $data);
    	}else{
            //if no, return back
    		return redirect()->back();
    	}
        
    }

    /**
     * get create role page
     * GET /admin/create-role
     *
     * @return view 
     */
    public function getCreateRole()
    {
        //get the logged user
    	$loggedUser = Sentinel::getUser();
        //check if the user has access to create a role
    	if($loggedUser->hasAccess(['role.create'])){
            //if yes, return create role page
	    	$permissions = $this->permissionRepo->getAllPermissions();
	    	$data = ['permissions' => $permissions];
	    	return view('admin.create_role', $data);    		
    	}else{
            //if no, return back
    		return redirect()->back();
    	}

    }

    /**
     * create a role
     * POST /admin/create-role
     *
     * @param RoleCreateRequest $request
     * @return redirect
     */
    public function postCreateRole(RoleCreateRequest $request)
    {
        //get data, to create a role
    	$name = $request->name;
    	$slug = strtolower($name);
    	$role_data = [
    		'name' => $name,
    		'slug' => $slug
    	];
        //create a role
    	$role = Sentinel::getRoleRepository()->createModel()->create($role_data);
    	$permissions = $request->permissions;
    	if($permissions){
    		foreach($permissions as $permission){
	    		$role->updatePermission($permission, true, true);
	    	}
	    	$role->save();
    	}
    	
        //return role management page
    	return redirect()->action('Admin\RoleController@getRoles');
    }

    /**
     * delete the role
     * GET /admin/delete-role/{roleId}
     *
     * @param int $roleId
     * @return redirect
     */
    public function getDeleteRole($roleId)
    {
        //get the logged user
    	$loggedUser = Sentinel::getUser();
        //check if the user has access to delete the role
    	if($loggedUser->hasAccess(['role.delete'])){
            //if yes, delete the role
    		$role = Sentinel::findRoleById($roleId);
			$role->delete();
            //return to the role management page
			return redirect()->action('Admin\RoleController@getRoles');
    	}else{
            //if no, return back
    		return redirect()->back();
    	}
    	
    }

    /**
     * 
     */
    public function getUsersInRole($role_id)
    {
        $role = Sentinel::findRoleById($role_id);
        $users = $this->roleRepo->getUsersInRole($role);
        $users_role = [];
        foreach($users as $user)
        {
            $users_role[] = $user->first_name;
        }
        $data = ['role_users' => $users_role];
        return response()->json($data);
    }

    /**
     * get edit role page
     * GET /admin/edit-roe/{roleId}
     *
     * @param int $roleId
     * @return view
     */
    public function getEditRole($roleId)
    {
        //get the logged user
    	$loggedUser = Sentinel::getUser();
        //check if the user has access to edit the role
    	if($loggedUser->hasAccess(['role.update'])){
            //if yes
    		$role = Sentinel::findRoleById($roleId);
	    	$permissions = $this->permissionRepo->getAllPermissions();
	    	$rolePermissions = [];
	    	foreach($role->permissions as $key => $permission)
	    	{
	    		if($permission == 'true')
	    		{
	    			$rolePermissions[] = $key;
	    		}
	    		
	    	}
	    	$data = ['role' => $role,
	    			 'permissions' => $permissions,
	    			 'role_permissions' => $rolePermissions];
	    	return view('admin.edit-role', $data);
    	}else{
    		return redirect()->back();
    	}
    	
    }

    /**
     * edit the role
     * POST /admin/edit-role
     *
     * @param Request $request
     * @return redirect
     */
    public function postEditRole(Request $request)
    {
        //get the role by id
    	$roleId = $request->role_id;
    	$role = Sentinel::findRoleById($roleId);
    	$name = $request->name;
    	$slug = strtolower($name);
    	$permissions = $request->permissions;

        //get the role permissions
    	$rolePermissions = [];
    	if(count($role->permissions) > 0){
    		foreach($role->permissions as $key => $permission){
    			if($permission == 'true'){
    				$rolePermissions[] = $key;
    			}   			
    		}
    	}

        // the request permissions for the role make true
    	if(count($permissions) > 0){
    		foreach($permissions as $permission){
    			if(!in_array($permission, $rolePermissions)){
    				$role->updatePermission($permission, true, true);
    			}
    		}
    	}

        // if the request permissions array doesn't contain the role permission, make it false 
    	if(count($rolePermissions) > 0){
    		foreach($rolePermissions as $permission){
    			if(count($permissions) > 0){
    				if(!in_array($permission, $permissions)){
    					$role->updatePermission($permission, false, true);
    				}
    			}else{
    				$role->updatePermission($permission, false, true);
    			}
    		}
    	}

    	$roleData = [
                    'name' => $name,
    				'slug' => $slug
                    ];

        //update and save the role
    	$role->update($roleData);
    	$role->save();

        //redirect to role management page
    	return redirect()->action('Admin\RoleController@getRoles');
    }

    /**
     * show a role
     * GET /admin/show-role/{roleId} 
     *
     * @param int $role_id
     * @return view
     */
    public function getShowRole($roleId)
    {
        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if the user has permission to view the role
    	if($loggedUser->hasAccess(['role.view'])){
            // if yes
            // get the role
    		$role = Sentinel::findRoleById($roleId);
            
            // get the role's permissions in array
	    	$rolePermissions = [];
	    	if(count($role->permissions) > 0){
	    		foreach($role->permissions as $key => $permission){
	    			if($permission == 'true'){
	    				$rolePermissions[] = $key;
	    			}   			
	    		}
	    	}

	    	$data = [
	    		'role' => $role,
	    		'permissions' => $rolePermissions
	    	];

            // return the role details page
	    	return view('admin.show_role', $data);
    	}else{
            // if no
            // redirect back
    		return redirect()->back();
    	}
    	
    }
}
