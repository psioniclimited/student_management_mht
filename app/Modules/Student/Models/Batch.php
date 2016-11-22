<?php

namespace App\Modules\Directory\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
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
        'description'
    ];
    
    public function student()
    {
        return $this->hasmany('App\Modules\Student\Models\Student');
    }

    public function batchType()
    {
        return $this->belongsTo('App\Modules\Student\Models\School');
    }
}
