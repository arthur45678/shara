<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\SectorInterface;
use  App\Http\Requests\CreateIndustryRequest;
use Sentinel;
use Validator;


class SectorController extends Controller
{
	/**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $sectorRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param SectorInterface $SectorRepo
     * @return void
     */
	public function __construct(SectorInterface $sectorRepo)
	{
		$this->sectorRepo = $sectorRepo;
        $this->middleware("admin");
        $this->middleware("genericAdmin");

	}

    /**
     * get all sectors page
     * GET /admin/sectors
     *
     * @return view
     */
    public function getSectors()
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();

        // check if the user has permission to view the sectors
        if($loggedUser->hasAccess('industry.view')){
            // if yes
            // get all sectors english translation ordered alphabetically with pagination 
            $sectors = $this->sectorRepo->getAllSectorsPaginate();

            // get locales to get the sector translations
            $locales = config('translatable.locales');

            // get the translations for each sector
            foreach ($sectors as $key => $value) {
                $sector = $this->sectorRepo->getSectorById($value->sector_id);
                $translatedNames = [];
                foreach($locales as $locale){
                    if(isset($sector->translate($locale)->name))
                    $translatedNames[$locale] = $sector->translate($locale)->name;
                }
                $value->translations = $translatedNames;
            }
            
            // get all sectors to get the sectors' details
            $data = [
                'sectors' => $sectors,
                    ];
            // return sectors page
            return view('admin.sectors', $data);
        }else{
            // if no
            return redirect()->back();
        }
    	
    }

    /**
     * get create sector page
     * GET /admin/create-sector
     *
     * @return view
     */
    public function getCreateSector()
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to create a new sector
        if($loggedUser->hasAccess('industry.create')){
            // if yes
            // get the locales from config
            $locales = config('translatable.locales');
            $data = [
                    'locales' => $locales
                    ];
            // return the sector create page      
            return view('admin.create_sector', $data);
        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
        
    }

    /**
     * create a sector
     * POST /admin/cerate-sector
     *
     * @param CreateIndustryRequest $request
     * @return redirect
     */
    public function postCreateSector(CreateIndustryRequest $request)
    {
        // get the sector translations
        $translatedNames = $request->translated_names;
        // get the sector activation status
        $activation = $request->activate;
        
        $createData = [];
        foreach($translatedNames as $key => $name){
            $createData[$key] = ['name' => $name];
        }
        if($activation){
            $createData['activation'] = 'activated';
        }else{
            $createData['activation'] = 'deactivated';
        }

        // create the sector
        $newSector = \App\Sector::create($createData);
        // redirect to the sector management page
        return redirect()->action('Admin\SectorController@getSectors');
    }

    /**
     * get edit sector page
     * GET /admin/edit-sector/{sectorId}
     *
     * @param in $sector_id
     * @return view 
     */
    public function getEditSector($sectorId)
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to edit the sector
        if($loggedUser->hasAccess('industry.update'))
        {
            // if yes
            // get the locales to get the sector translations
            $locales = config('translatable.locales');
            // get the sector object
            $sector = $this->sectorRepo->getSectorById($sectorId);

            // get the sector translations
            $translatedNames = [];
            foreach($locales as $locale){
                if($sector->translate($locale)){
                    $translatedNames[$locale] = $sector->translate($locale)->name;
                }
                
            }
                      
            $data = [
                'sector' => $sector,
                'locales' => $locales,
                'translated_names' => $translatedNames
            ];
            // return sector edit page
            return view('admin.edit_sector', $data);
        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
        
    }

    /**
     * edit a sector
     * POST /admin/edit-sector
     *
     * @param CreateIndustryRequest $request
     * @return redirect
     */
    public function postEditSector(CreateIndustryRequest $request)
    {
        // get the sector id and new details for sector
        $id = $request->sector_id;
        $activation = $request->activate;
        $translatedNames = $request->translated_names;

        $updateData = [];
        foreach($translatedNames as $key => $name){
            $updateData[$key] = ['name' => $name];
        }

        if($activation){
            $updateData['activation'] = 'activated';
        }else{
            $updateData['activation'] = 'deactivated';
        }
        // /get the sector by id
        $sector = $this->sectorRepo->getSectorById($id);
        // update the sector
        $updatedSector = $this->sectorRepo->updateSector($sector, $updateData);
        // redirect to sector management page
        return redirect()->action('Admin\SectorController@getSectors');
    }

    /**
     * get show sector page
     * GET /admin/show-sector/{sectorId}
     *
     * @param int $sectorId
     * @return view
     */
    public function getShowSector($sectorId)
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        //check if the user has permission to show the sector
        if($loggedUser->hasAccess('industry.view')){
            // if yes
            // get sector object by id
            $sector = $this->sectorRepo->getSectorById($sectorId);
            // get locales to get the sector translations
            $locales = config('translatable.locales');

            // get the sector translations
            $translatedNames = [];
            foreach($locales as $locale){
                if(isset($sector->translate($locale)->name))
                $translatedNames[$locale] = $sector->translate($locale)->name;
            }

            $data = [
                    'sector' => $sector,
                     'locales' => $locales,
                     'translated_names' => $translatedNames
                     ];
            // return the sector details page
            return view('admin.show_sector', $data);
        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
        

    }

    /**
     * delete the sector
     * GET /admin/delete-sector/{sectorId}
     *
     * @param int $sector_id
     * @return redirect
     */
    public function getDeleteSector($sectorId)
    {
        //get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to delete the sector
        if($loggedUser->hasAccess('industry.delete')){
            // if yes
            // get the sector object
            $sector = $this->sectorRepo->getSectorById($sectorId);
            // delete the sector
            $this->sectorRepo->deleteSector($sector);
            //return the sectgor management page
            return redirect()->action('Admin\SectorController@getSectors');
        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
        
    }
}
