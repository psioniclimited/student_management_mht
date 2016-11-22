<?php

namespace App\Modules\Directory\Models;

use Illuminate\Database\Eloquent\Model;

class BatchType extends Model
{
    protected $table = 'types';

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
    
    public function batch()
    {
        return $this->hasmany('App\Modules\Student\Models\Student');
    }

}
