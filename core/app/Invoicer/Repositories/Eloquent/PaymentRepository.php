<?php namespace App\Invoicer\Repositories\Eloquent;

use App\Invoicer\Repositories\Contracts\PaymentInterface;

class PaymentRepository extends BaseRepository implements PaymentInterface{


    /**
     * @return string
     */

    public function model() {
        return 'App\Models\Payment';
    }

    /**
     * @param $range
     * @return mixed
     */

    public function report($range){
        $invoice = $this->model();
        $stats = $invoice::where('payment_date', '>=', $range)
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get([
                \DB::raw('Date(payment_date) as date'),
                \DB::raw('SUM(amount) as value')
            ]);
        return $stats;
    }
}