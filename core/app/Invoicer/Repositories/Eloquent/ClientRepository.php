<?php namespace App\Invoicer\Repositories\Eloquent;

use App\Invoicer\Repositories\Contracts\ClientInterface;

class ClientRepository extends BaseRepository implements ClientInterface {

    /**
     * @return string
     */

    public function model() {
        return 'App\Models\Client';
    }

    /**
     * @return string
     */

    public function generateClientNum(){
        $client = $this->model();
        $last = $client::orderBy('created_at', 'desc')->first();
        if($last){
            $next_id = $last->id+1;
        }else{
            $next_id = 1;
        }

        return $next_id;
    }

    /**
     * @return array
     */
    public function clientSelect(){
        $clients = $this->all();
        $clientList = array();
        foreach($clients as $client)
        {
            $clientList[$client->id] = $client->name;
        }
        return $clientList;
    }

}