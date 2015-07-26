<?php namespace App\Http\Controllers;

use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use App\Invoicer\Repositories\Contracts\ProductInterface as Product;
use App\Invoicer\Repositories\Contracts\ClientInterface as Client;
use App\Invoicer\Repositories\Contracts\EstimateInterface as Estimate;

class HomeController extends Controller {
    protected $invoice,$product,$client,$estimate;

    /**
     * Create a new controller instance.
     */
    public function __construct(Invoice $invoice, Product $product, Client $client, Estimate $estimate)
	{
		$this->middleware('auth');
        $this->invoice = $invoice;
        $this->product = $product;
        $this->client  = $client;
        $this->estimate = $estimate;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $clients = $this->client->count();
        $invoices = $this->invoice->count();
        $estimates = $this->estimate->count();
        $products = $this->product->count();
        $recentInvoices = $this->invoice->with('client')->limit(10)->get();
        foreach($recentInvoices as $count => $invoice){
            $recentInvoices[$count]['totals'] = $this->invoice->invoiceTotals($invoice->id);
        }
        $recentEstimates = $this->estimate->with('client')->limit(10)->get();
        foreach($recentEstimates as $count => $invoice){
            $recentEstimates[$count]['totals'] = $this->estimate->estimateTotals($invoice->id);
        }



        $invoice_stats['unpaid'] = $this->invoice->where('status', getStatus('label', 'unpaid'))->count();
        $invoice_stats['paid'] = $this->invoice->where('status', getStatus('label', 'paid'))->count();
        $invoice_stats['partiallyPaid'] = $this->invoice->where('status', getStatus('label', 'partially paid'))->count();
        $invoice_stats['overdue'] = $this->invoice->where('status', getStatus('label', 'overdue'))->count();
		return view('home', compact('clients','invoices','products','estimates','recentInvoices','recentEstimates', 'invoice_stats'));
	}

}
