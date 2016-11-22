<?php

namespace App\Modules\Directory\Models;

use Illuminate\Database\Eloquent\Model;

class EventsDetail extends Model {

	protected $table = 'events';
    
    public $timestamps = false;

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_image',
    ];

    
    public function setStartDateAttribute($value) {
        $this->attributes['start_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->toDateTimeString();
    }

    public function setEndDateAttribute($value) {
        $this->attributes['end_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->toDateTimeString();
    }

    public function getStartDateAttribute($value) {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }
    
    public function getEndDateAttribute($value) {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

}

