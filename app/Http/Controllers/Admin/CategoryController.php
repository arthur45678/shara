<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\CategoryInterface;
use App\Http\Requests\CreateCategoryRequest;
use Sentinel;
use Validator;

class CategoryController extends Controller
{
	/**
     * Object of CategoryInterface class
     *
     * @var categoryRepo
     */
    private $categoryRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param CategoryInterface $CategoryRepo
     * @return void
     */
	public function __construct(CategoryInterface $categoryRepo)
	{
		$this->categoryRepo = $categoryRepo;
		$this->middleware("admin");
        $this->middleware("genericAdmin");

	}

    /**
     * get all categories page ordered alpabetically
     * that allows tp create, delete, edit categories
     * GET /admin/categories
     *
     * @return view
     */
    public function getCategories()
    {
        // get the auth user
    	$loggedUser = Sentinel::getUser();
        // check if tyhe user has access to view categories
    	if($loggedUser->hasAccess('category.view')){
    		$categories = $this->categoryRepo->getAllCategoriesPaginate();

            // get locales to get the sector translations
            $locales = config('translatable.locales');

            // get the translations for each category
            foreach ($categories as $key => $value) {
                $category = $this->categoryRepo->getCategoryById($value->category_id);
                $translatedNames = [];
                foreach($locales as $locale){
                    if(isset($category->translate($locale)->name))
                    $translatedNames[$locale] = $category->translate($locale)->name;
                }
                $value->translations = $translatedNames;
            }

	    	$data = [
                    'categories' => $categories,
                    ];
	    	return view('admin.categories', $data);
    	}else{
    		return redirect()->back();
    	}
    	
    }

    /**
     * get create category page
     * GET /admin/create-category
     *
     * @return view
     */
    public function getCreateCategory()
    {
    	$loggedUser = Sentinel::getUser();
    	if($loggedUser->hasAccess('category.create')){
    		$locales = config('translatable.locales');
	        $icons = $this->categoryRepo->getIcons();
            $data = [
                    'locales' => $locales,
                    'icons' => $icons
                    ];
	    	return view('admin.create_category', $data);
    	}else{
    		return redirect()->back();
    	}
        
    }

    /**
     * create a category
     * POST /admin/create-category
     * 
     * @param CreateCategoryRequest $request
     * @return redirect
     */
    public function postCreateCategory(CreateCategoryRequest $request)
    {
        //get translated names
        $translatedNames = $request->translated_names;
        $createData = [];
        foreach($translatedNames as $key => $name){

            $createData[$key] = ['name' => $name];
        }

        //get activaton status
        $activation = $request->activate;
        if($activation){
            $createData['activation'] = 'activated';
        }else{
            $createData['activation'] = 'deactivated';
        }
        $icon = $request->icon;
        if($icon) {
            $createData['icon'] = $icon;
        }
        //create category
        $newCategory = \App\Category::create($createData);

        //redirect to management page
        return redirect()->action('Admin\CategoryController@getCategories');
    }

    /**
     * get edit category page
     * GET /admin/edit-category/{categoryId}
     *
     * @param in $categoryId
     * @return view 
     */
    public function getEditCategory($categoryId)
    {
    	$loggedUser = Sentinel::getUser();
        //check if the user has access to edit the category
    	if($loggedUser->hasAccess('category.update')){

            //if the user has access return  category edit page with category details
    		$locales = config('translatable.locales');
	        $category = $this->categoryRepo->getCategoryById($categoryId);
            $icons = $this->categoryRepo->getIcons();
            //get category's name translations
	        $translatedNames = [];
            foreach($locales as $locale){
                if($category->translate($locale)){
                    $translatedNames[$locale] = $category->translate($locale)->name;
                }
                
            }
            	        
	        $data = [
	            'category' => $category,
	            'locales' => $locales,
	            'translated_names' => $translatedNames,
                'icons' => $icons
	        ];
	        return view('admin.edit_category', $data);
    	}else{
            //if the user hasn't access redirect back
    		return redirect()->back();
    	}
        
    }

    /**
     * edit a category
     * POST /admin/edit-category
     * 
     * @param CreateCategoryRequest $request
     * @return redirect
     */
    public function postEditCategory(CreateCategoryRequest $request)
    {
        //get category id
        $id = $request->category_id;
        
        //get translated names
        $translatedNames = $request->translated_names;
        $updateData = [];
        foreach($translatedNames as $key => $name){
            $updateData[$key] = ['name' => $name];
        }

        //get activation status
        $activation = $request->activate;
        if($activation){
            $updateData['activation'] = 'activated';
        }else{
            $updateData['activation'] = 'deactivated';
        }

        $icon = $request->icon;
        if($icon) {
            $updateData['icon'] = $icon;
        }

        $image = $request->image;
        if($image) {
            $updateData['image'] = $image;
        }

        //get category object
        $category = $this->categoryRepo->getCategoryById($id);

        //update category
        $updatedCategory = $this->categoryRepo->updateCategory($category, $updateData);

        //redirect category mamangement page
        return redirect()->action('Admin\CategoryController@getCategories');
    }

    /**
     * get show category details
     * GET /admin/show-category/{categoryId}
     *
     * @param int $categoryId
     * @return view
     */
    public function getShowCategory($categoryId)
    {
    	$loggedUser = Sentinel::getUser();
        //check if the use has access to view category details
    	if($loggedUser->hasAccess('category.view')){
            //if yes, return category details page

            //get category object
    		$category = $this->categoryRepo->getCategoryById($categoryId);
            //get locales for translation
	        $locales = config('translatable.locales');

            //get category's name translation
	        $translatedNames = [];
	        foreach($locales as $locale){

                if(isset($category->translate($locale)->name))
	            $translatedNames[$locale] = $category->translate($locale)->name;
	        }

	        $data = [
                     'category' => $category,
	                 'locales' => $locales,
	                 'translated_names' => $translatedNames
                     ];
	        return view('admin.show_category', $data);
    	}else{
            //if no, return back
    		return redirect()->back();
    	}
    }

    /**
     * delete a category
     * GET /admin/delet-category/{categoryId}
     *
     * @param int $category_id
     * @return redirect
     */
    public function getDeleteCategory($categoryId)
    {
    	$loggedUser = Sentinel::getUser();
        //check if the user has access to delete the category
    	if($loggedUser->hasAccess('category.delete')){
            //if yes,
            //get the category
    		$category = $this->categoryRepo->getCategoryById($categoryId);
            //delete the category
	        $this->categoryRepo->deleteCategory($category);
            //return category manaement page
	        return redirect()->action('Admin\CategoryController@getCategories');
    	}else{
            //if no, return back
    		return redirect()->back();
    	}
        
    }
}
