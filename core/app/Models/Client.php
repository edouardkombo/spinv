<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model {

    protected $fillable = ['client_no', 'name', 'email', 'address1', 'address2', 'city', 'state', 'postal_code', 'country', 'phone', 'mobile', 'website', 'notes'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices(){
        return $this->hasMany('App\Models\Invoice');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estimates(){
        return $this->hasMany('App\Models\Estimate');
    }

}
