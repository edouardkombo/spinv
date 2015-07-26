<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    protected $fillable = ['name', 'email', 'phone', 'address1', 'address2', 'city', 'state', 'postal_code',
        'country', 'contact', 'vat', 'website', 'logo', 'favicon','date_format'];

}
