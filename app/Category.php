<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Dimsav\Translatable\Translatable;

class Category extends Model
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
        'activation', 'restrict', 'icon', 'image'
    ];

    public function companies()
    {
        return $this->hasMany('App\Company', 'category_id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job', 'category_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
