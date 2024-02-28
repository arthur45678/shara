<?php

namespace App\Services;

use App\Contracts\CategoryInterface;
use App\Category;
use App\Job;
use App\CategoryTranslation;
use App\Icon;

class CategoryService implements CategoryInterface
{

	/**
	 * Object of Category class.
	 *
	 * @var $category 
	 */
	private $category;

	/**
	 * Create a new instance of CategoryService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->category = new Category(); 
		$this->categoryTranslation = new CategoryTranslation(); 
		$this->icon = new Icon();
	}

	/**
	 * get all categories
	 *
	 * @return collection
	 */
	public function getAllCategories()
	{
		return $this->category->all(); 
	}

	/**
	 * get all categories ordered alphabetically
	 * 
	 * @return categories object
	 */
	public function getOrderedCategories()
	{
		$categories = $this->category->join('category_translations as t', 't.category_id', '=', 'categories.id')
							    ->where('locale', 'en')
							    ->orderBy('t.name', 'asc')
							    ->select(['*', 't.name as categoryName'])
							    ->paginate(50);

		return $categories;
	}

	/**
	 * get all categories ordered alphabetically for use
	 * 
	 * @return categories object
	 */
	public function getOrderedCategoriesForUser($locale)
	{
		$categories = $this->category->join('category_translations as t', 't.category_id', '=', 'categories.id')
							    ->where('locale', $locale)
							    ->orderBy('t.name', 'asc')
							    // ->select(['*', 't.name as name'])
							    ->get();

		return $categories;
	}

	/**
	 * get all active categories ordered alphabetically
	 * 
	 * @return categories object
	 */
	public function getActiveOrderedCategories()
	{
		$categories = $this->category->join('category_translations as t', 't.category_id', '=', 'categories.id')
							    ->where('locale', 'en')
							    ->where('activation', 'activated')
							    ->orderBy('t.name', 'asc')
							    ->select(['*', 't.name as categoryName'])
							    ->paginate(50);

		return $categories;
	}

	/**
	 * get category translations
	 */
	public function getTranslations()
	{
		return 	$this->categoryTranslation->where('locale', 'en')->orderBy('name', 'asc')->paginate(50) ;
	}

	public function getAllCategoriesPaginate()
	{
		$categories = $this->category->join('category_translations as t', 't.category_id', '=', 'categories.id')
							    ->where('locale', 'en')
							    ->orderBy('t.name', 'asc')
							    ->select(['*', 't.name as categoryName'])
							    ->paginate(50);

		return $categories;	
	}

	public function getAllActivatedCategories()
	{
		return $this->category->where('activation', 'activated')->get();
	}

	/**
	 * get category by id
	 *
	 * @param int $id
	 * @return object
	 */
	public function getCategoryById($id)
	{
		return $this->category->where('id', $id)->first();
	}

	/**
	 * create category
	 *
	 * @param array $data
	 * @return object
	 */
	public function createCategory($data)
	{
		return $this->category->create($data);
	}

	/**
	 * update category
	 *
	 * @param object $object
	 * @param array $data
	 * @return object
	 */
	public function updateCategory($object, $data)
	{
		return $object->update($data);
	}

	/**
	 * delete category
	 *
	 * @param object $object
	 */
	public function deleteCategory($object)
	{
		return $object->delete();
	}

	public function getCategoryByName($name)
	{
		$category = $this->category->whereHas('translations', function ($query) use ($name)  {
			    $query->where('name',  $name)->where('locale', 'en');
			})->first();
		return $category;
	}

	public function getCategoriesOrder()
	{
		return $this->category->withCount('jobs')->limit(8)->get();
	}

	public function getCategoriesEnLocale()
	{
		$categories = $this->category->whereHas('translations', function ($query) {
			    $query->where('locale', 'en');
			})->get();
		return $categories;
	}

	public function getIcons()
	{
		return $this->icon->get();
	}
}