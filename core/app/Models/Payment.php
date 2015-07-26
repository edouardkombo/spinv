<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

	protected $fillable = ['invoice_id','payment_date','amount','notes','method'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }

}
