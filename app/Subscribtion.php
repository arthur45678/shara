<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribtion extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    						'user_id',
    						'email',
                            'keyword',
    						'country',
    						'city',
                            'latitude',
                            'longtitude',
                            'code',
                            'category_id',
                            'sector_id',
    					];

    public function notifications()
    {
        return $this->hasMany('App\Notification', 'subscribtion_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function sector()
    {
        return $this->belongsTo('App\Sector');
    }
}
