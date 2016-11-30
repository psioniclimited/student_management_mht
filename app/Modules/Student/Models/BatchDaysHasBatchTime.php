<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class BatchDaysHasBatchTime extends Model
{

    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'batch_days_id',
        'batch_times_id'
    ];

    public function batch()
    {
        return $this->belongsToMany('App\Modules\Student\Models\Batch', 'batch_has_days_and_times', 'batch_days_has_batch_times_id','batch_id');
    }
    
}
