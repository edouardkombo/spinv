<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

	protected $fillable = ['client_id', 'number', 'invoice_date', 'due_date', 'status', 'discount', 'terms', 'notes', 'currency'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function items(){
        return $this->hasMany('App\Models\InvoiceItem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }

}
