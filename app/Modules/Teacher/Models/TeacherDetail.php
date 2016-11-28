<?php

namespace App\Modules\Teacher\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherDetail extends Model
{
    protected $table = 'teacher_details';

    public $timestamps = false;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'users_id',
        'subjects_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Modules\User\Models\User', 'users_id');
    }

    public function subject()
    {
        return $this->belongsTo('App\Modules\Student\Models\Subject', 'subjects_id');
    }

}
