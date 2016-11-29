<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class BatchDay extends Model
{
    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    
    public function batchTime()
    {
        return $this->belongsToMany('App\Modules\Student\Models\BatchTime', 'batch_days_has_batch_times', 'batch_days_id', 'batch_times_id');
    }
}
