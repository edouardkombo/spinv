<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model {

    protected  $fillable = ['invoice_id', 'product_id', 'quantity',  'price', 'tax_id' ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tax(){
        return $this->belongsTo('App\Models\TaxSetting');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }

}
