<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use App\Invoicer\Repositories\Contracts\PaymentInterface as Payment;
use App\Invoicer\Repositories\Contracts\EstimateInterface as Estimate;
use App\Invoicer\Repositories\Contracts\ExpenseInterface as Expense;
use Carbon\Carbon;


class ReportsController extends Controller {

    protected $invoices,$payments,$estimates,$expenses;

    /**
     * @param Invoice $invoice
     * @param Payment $payment
     * @param Estimate $estimate
     * @param Expense $expense
     */
    public function __construct(Invoice $invoice, Payment $payment, Estimate $estimate,Expense $expense){
        $this->middleware('auth');
        $this->invoices = $invoice;
        $this->payments = $payment;
        $this->estimates = $estimate;
        $this->expenses = $expense;
    }


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        return view('reports.index');
	}

    public function ajaxData(){
        $days = \Input::get('days');
        $chart = \Input::get('chart');

        $range = Carbon::now()->subDays($days);
        $invoices = $this->$chart->report($range);
        return response()->json($invoices);

    }
}
