<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberSetting extends Model {

    protected $fillable = ['invoice_number','client_number','estimate_number'];

}
