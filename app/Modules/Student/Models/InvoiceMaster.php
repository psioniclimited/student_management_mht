<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceMaster extends Model
{
    
	public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_date',
        'students_id',
        'total'
   ];

   	public function student() {
        return $this->belongsTo('App\Modules\Student\Models\Student', 'students_id');
    }

    public function invoiceDetail()
    {
        return $this->hasmany('App\Modules\Student\Models\InvoiceDetail');
    }
    
    public function setPaymentDateAttribute($value) {
        $this->attributes['payment_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function getPaymentDateAttribute($value) {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

}
