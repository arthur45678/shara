<?php

namespace App\Contracts;

interface CategoryInterface
{
	/**
	 * get all categories
	 *
	 * @return collection
	 */
	public function getAllCategories();

	/**
	 * get category by id
	 *
	 * @param int $id
	 * @return object
	 */
	public function getCategoryById($id);
	/**
	 * create category
	 *
	 * @param array $data
	 * @return object
	 */
	public function createCategory($data);

	/**
	 * update category
	 *
	 * @param object $object
	 * @param array $data
	 * @return object
	 */
	public function updateCategory($object, $data);

	/**
	 * delete category
	 *
	 * @param object $object
	 */
	public function deleteCategory($object);
}