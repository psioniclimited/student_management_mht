<?php

namespace App\Modules\Directory\Models;

use Illuminate\Database\Eloquent\Model;
use URL;

class MembersDetail extends Model {

	protected $table = 'members_details';

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_image',
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Modules\User\Models\User');
    }

    public function member_type()
    {
        return $this->belongsTo('App\Modules\Directory\Models\MembersDetail');
    }

    public function setDobAttribute($value) 
    {
        $this->attributes['dob'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->toDateTimeString();
    }

    public function getDobAttribute($value) 
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

    public function getUserImageAttribute($value) 
    {
        return URL::to("/") . '/' . $value;
    }
}

