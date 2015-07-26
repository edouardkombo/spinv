<?php namespace App\Invoicer\Repositories\Eloquent;

use App\Invoicer\Repositories\Contracts\ExpenseInterface;

class ExpenseRepository extends BaseRepository implements ExpenseInterface{

    public function model() {
        return 'App\Models\Expense';
    }
    /**
     * @param $range
     * @return mixed
     */

    public function report($range){
        $invoice = $this->model();
        $stats = $invoice::where('expense_date', '>=', $range)
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get([
                \DB::raw('Date(expense_date) as date'),
                \DB::raw('SUM(amount) as value')
            ]);
        return $stats;
    }
}