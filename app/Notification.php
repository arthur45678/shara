<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    						'job_id',
    						'user_email',
    						'is_sent',
                            'subscribtion_id'
    					];
}
