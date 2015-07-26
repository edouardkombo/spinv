<?php namespace App\Invoicer\Repositories\Eloquent;

use App\Invoicer\Repositories\Contracts\NumberSettingInterface;

class NumberSettingRepository extends BaseRepository implements NumberSettingInterface{

    public function model() {
        return 'App\Models\NumberSetting';
    }

    public function prefix($type, $num){
        $prefix = $this->first();
        if($prefix){
            return $prefix->$type.str_pad($num, 3, '0', STR_PAD_LEFT);
        }
        return str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}