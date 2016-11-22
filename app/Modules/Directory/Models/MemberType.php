<?php

namespace App\Modules\Directory\Models;

use Illuminate\Database\Eloquent\Model;

class MemberType extends Model
{
    protected $table = 'member_type';

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
    
    public function members_detail()
    {
        return $this->hasmany('App\Modules\Directory\Models\MembersDetail');
    }
}
