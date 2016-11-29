<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class BatchTime extends Model
{

    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'time',
    ];
    
    public function batchDay()
    {
        return $this->belongsToMany('App\Modules\Student\Models\BatchDay', 'batch_days_has_batch_times', 'batch_times_id','batch_days_id');
    }
}
