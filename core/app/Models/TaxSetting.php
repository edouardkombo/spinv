<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSetting extends Model {

	protected $fillable = ['name', 'value', 'selected'];

    public function estimateItems(){
        return $this->hasMany('App\Models\EstimateItem');
    }

}
