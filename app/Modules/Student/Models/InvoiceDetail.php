<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{

	public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_masters_id',
        'batch_id',
        'subjects_id',
        'price',
        'payment_from',
        'payment_to'
   ];

   	public function invoiceMaster() {
        return $this->belongsTo('App\Modules\Student\Models\InvoiceMaster');
    }

    public function subject() {
        return $this->belongsTo('App\Modules\Student\Models\Subject', 'subjects_id');
    }

    public function batch() {
        return $this->belongsTo('App\Modules\Student\Models\Batch', 'batch_id');
    }

    public function getPaymentDateAttribute($value) {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

}
