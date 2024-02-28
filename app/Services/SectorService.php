<?php

namespace App\Services;

use App\Contracts\SectorInterface;
use App\Sector;
use App\SectorTranslation;

class SectorService implements SectorInterface
{

	/**
	 * Object of Sector class.
	 *
	 * @var $sector 
	 */
	private $sector;

	/**
	 * Create a new instance of SectorService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->sector = new Sector();
		$this->sectorTranslation = new SectorTranslation();
	}

	/**
	 * get all sectors
	 *
	 * @return collection
	 */
	public function getAllSectors()
	{
		return $this->sector->all();
	}

	/**
	 * get all jobs ordered alphabetically
	 * 
	 * get sectors object
	 */
	public function getOrderedSectors()
	{
		$sectors = $this->sector->join('sector_translations as t', 't.sector_id', '=', 'sectors.id')
							    ->where('locale', 'en')
							    ->orderBy('t.name', 'asc')
							    ->select(['*', 't.name as sectorName'])
							    ->paginate(50);

		return $sectors;
	}

	/**
	 * get all jobs ordered alphabetically
	 * 
	 * get sectors object
	 */
	public function getActiveOrderedSectors()
	{
		$sectors = $this->sector->join('sector_translations as t', 't.sector_id', '=', 'sectors.id')
							    ->where('locale', 'en')
							    ->where('activation', 'activated')
							    ->orderBy('t.name', 'asc')
							    ->select(['*', 't.name as sectorName'])
							    ->paginate(50);

		return $sectors;
	}

	public function getAllSectorsPaginate()
	{
		$sectors = $this->sector->with('translations')
								->join('sector_translations as t', 't.sector_id', '=', 'sectors.id')
							    ->where('locale', 'en')
							    ->orderBy('t.name', 'asc')
							    ->select(['*', 't.name as sectorName'])
							    ->paginate(50);

		return $sectors;	
	}

	/**
	 * get industry translations
	 */
	public function getTranslations()
	{
		$sectors = $this->sectorTranslation->where('locale', 'en')->orderBy('name', 'asc')->paginate(50);
		return $sectors;
	}

	public function getAllActivatedSectors()
	{
		return $this->sector->where('activation', 'activated')->get();
	}

	/**
	 * get a sector by id
	 *
	 * @param int $id
	 * @return object
	 */
	public function getSectorById($id)
	{
		return $this->sector->where('id', $id)->first();
	}

	/**
	 * create sector
	 *
	 * @param array $data
	 * @return object
	 */
	public function createSector($data)
	{
		return $this->sector->create($data);
	}

	/**
	 * update sector
	 *
	 * @param object $object
	 * @param array $data
	 * @return object
	 */
	public function updateSector($object, $data)
	{
		return $object->update($data);
	}

	/**
	 * delete sector
	 *
	 * @param object $object
	 */
	public function deleteSector($object)
	{
		return $object->delete();
	}

	public function getSectorByName($name)
	{
		$sector = $this->sector->whereHas('translations', function ($query) use ($name)  {
			    $query->where('name',  $name)->where('locale', 'en');
			})->first();
		return $sector;
	}

	public function getSectorsOrder()
	{
		return $this->sector->withCount('companies')->withCount('jobs')->limit(8)->get();
	}

	/**
	 * get sector translations by sector id
	 */
	public function getSectorTranslations($id)
	{
		return $this->sector->where('id', $id)->with('translations')->first();
	}

	public function getSectorsEnLocale()
	{
		$sectors = $this->sector->whereHas('translations', function ($query) {
			    $query->where('locale', 'en');
			})->get();
		return $sectors;
	}
}