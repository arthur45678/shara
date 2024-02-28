<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Dimsav\Translatable\Translatable;

class Sector extends Model
{
    use Translatable;
	/**
	 * The attributes that will be translated
	 */
    public $translatedAttributes = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activation', 'restrict'
    ];

    public function companies()
    {
        return $this->hasMany('App\Company', 'sector_id');
    }

     public function jobs()
    {
        return $this->hasMany('App\Job', 'sector_id');
    }
}
