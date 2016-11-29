<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    // public $timestamps = false;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'fathers_name',
        'mothers_name',
        'phone_home',
        'phone_away',
        'schools_id',
        'batch_id'
    ];
    
    public function school()
    {
        return $this->belongsTo('App\Modules\Student\Models\School', 'schools_id');
    }

    // public function batch()
    // {
    //     return $this->belongsTo('App\Modules\Student\Models\Batch', 'batch_id');
    // }

    public function batch()
    {
        return $this->belongsToMany('App\Modules\Student\Models\Batch', 'batch_has_students', 'students_id', 'batch_id');
    }

    public function subject()
    {
        return $this->belongsToMany('App\Modules\Student\Models\Subject', 'students_has_subjects', 'students_id', 'subjects_id');
    }
}
