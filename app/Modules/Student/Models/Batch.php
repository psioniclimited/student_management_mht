<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batch';

    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'price',
        'batch_types_id',
        'grades_id'
    ];
    
    // public function student()
    // {
    //     return $this->hasmany('App\Modules\Student\Models\Student');
    // }

    public function student()
    {
        return $this->belongsToMany('App\Modules\Student\Models\Student', 'batch_has_students', 'batch_id', 'students_id');
    }

    public function batchType()
    {
        return $this->belongsTo('App\Modules\Student\Models\BatchType', 'batch_types_id');
    }

    public function grade()
    {
        return $this->belongsTo('App\Modules\Student\Models\Grade','grades_id');
    }
}
