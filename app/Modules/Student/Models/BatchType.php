<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class BatchType extends Model
{
    protected $table = 'batch_types';

    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];
    
    public function batch()
    {
        return $this->hasmany('App\Modules\Student\Models\Batch');
    }

}
