<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cartalyst\Sentinel\Users\EloquentUser as CartalystUser;

class User extends CartalystUser
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'permissions', 'activation', 'username', 'birth_date', 'country', 'city', 'zip_code', 'phone_number', 'image', 'transport', 'education', 'languages', 'week_days', 'hours', 'driving_license', 'currently_student', 'password_reset_token', 'facebook_id', 'restrict', 'location', 'role', 'working_area', 'verify_token', 'registration_time', 'schedule', 'step', 'admin_type', 'last_uploaded', 'latitude', 'longitude', 'ip', 'last_login','facebook_link','user_experience','gender','nationality'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function skills()
    {
        return $this->belongsToMany('App\Category');
    }

    public function languages()
    {
        return $this->belongsToMany('App\Language', 'language_user', 'user_id', 'language_id');
    }

    public function applications()
    {
        return $this->belongsToMany('App\Job', 'job_user', 'user_id', 'job_id')->withPivot('company_id', 'job_id', 'created_at')->withTimestamps();
    }

    public function companies()
    {
        return $this->belongsToMany('App\Company', 'admin_company', 'user_id', 'company_id');
    }

    public function subscribtions()
    {
        return $this->hasMany('App\Subscribtion', 'email', 'email');
    }

    public function applicationsCount()
    {
        return $this->belongsToMany('App\Job')
        ->selectRaw('count(jobs.id) as count')
        ->groupBy('jobs.id');
    }
}
