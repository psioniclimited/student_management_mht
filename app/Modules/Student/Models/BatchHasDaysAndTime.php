<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class BatchHasDaysAndTime extends Model
{

    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'batch_days_has_batch_times_id',
        'batch_days_has_batch_times_batch_days_id',
        'batch_days_has_batch_times_batch_times_id'
    ];
    
}
