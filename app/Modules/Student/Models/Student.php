<?php

namespace App\Modules\Directory\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    
    public function school()
    {
        return $this->belongsTo('App\Modules\Student\Models\School');
    }

    public function batch()
    {
        return $this->belongsTo('App\Modules\Student\Models\Batch');
    }
}
