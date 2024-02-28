<?php

namespace App\Contracts;

interface SectorInterface
{
	/**
	 * get all sectors
	 *
	 * @return collection
	 */
	public function getAllSectors();

	/**
	 * get a sector by id
	 *
	 * @param int $id
	 * @return object
	 */
	public function getSectorById($id);

	/**
	 * create sector
	 *
	 * @param array $data
	 * @return object
	 */
	public function createSector($data);

	/**
	 * update sector
	 *
	 * @param object $object
	 * @param array $data
	 * @return object
	 */
	public function updateSector($object, $data);

	/**
	 * delete sector
	 *
	 * @param object $object
	 */
	public function deleteSector($object);
}