<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    						'company_name',
                            'name',
                            'surname',
    						'email',
    						'location',
    						'country',
    						'city',
    						'web_site',
    						'seen',
                            'message'
    					];
}
