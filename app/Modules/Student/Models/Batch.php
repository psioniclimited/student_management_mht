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
        'price',
        'batch_types_id',
        'grades_id',
        'teacher_details_id',
        'teacher_details_users_id',
        'start_date',
        'end_date',
        'schedule',
        'subjects_id'
    ];
    
    public function student()
    {
        return $this->belongsToMany('App\Modules\Student\Models\Student', 'batch_has_students', 'batch_id', 'students_id')->withPivot('last_paid_date');
    }

    public function batchType()
    {
        return $this->belongsTo('App\Modules\Student\Models\BatchType', 'batch_types_id');
    }

    public function grade()
    {
        return $this->belongsTo('App\Modules\Student\Models\Grade','grades_id');
    }

    public function teacherDetail()
    {
        return $this->belongsTo('App\Modules\Teacher\Models\TeacherDetail','teacher_details_id');
    }

    public function invoiceDetail()
    {
        return $this->hasmany('App\Modules\Student\Models\InvoiceDetail');
    }

    public function dayAndtime()
    {
        return $this->belongsToMany('App\Modules\Student\Models\BatchDaysHasBatchTime', 'batch_has_days_and_times', 'batch_id', 'batch_days_has_batch_times_id');
    }

    
    public function setStartDateAttribute($value) {
        $this->attributes['start_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function setEndDateAttribute($value) {
        $this->attributes['end_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function getStartDateAttribute($value) {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }
    
    public function getEndDateAttribute($value) {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }
}
