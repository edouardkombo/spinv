<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model {

    protected  $fillable = ['client_id', 'estimate_no', 'estimate_date',  'currency', 'notes', 'terms' ];

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
        return $this->hasMany('App\Models\EstimateItem');
    }

}
