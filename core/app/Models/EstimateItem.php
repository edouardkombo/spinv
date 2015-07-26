<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateItem extends Model {

	protected  $fillable = ['estimate_id', 'product_id', 'quantity',  'price', 'tax_id' ];

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

    public function Estimate(){
        return $this->belongsTo('App\Models\Estimate');
    }
}
