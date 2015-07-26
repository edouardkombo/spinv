<?php namespace App\Invoicer\Repositories\Eloquent;

use App\Invoicer\Repositories\Contracts\TaxSettingInterface;

class TaxSettingRepository extends BaseRepository implements TaxSettingInterface{

    public function model() {
        return 'App\Models\TaxSetting';
    }

    public function resetDefault(){
    	$tax  = new $this->model();
    	$tax->update(['selected' => 0]);
    }

    /**
     * @return array
     */

    public function taxSelect(){
        $taxes = $this->all();
        $options = array();
        $options[] = ['value' => '', 'display' => 'None', 'data-value' => '' ];
        foreach($taxes as $tax){
            $option = ['value' => $tax->id, 'display' => $tax->name, 'data-value' => $tax->value ];
            array_push($options, $option);
        }
        return $options;
    }
}