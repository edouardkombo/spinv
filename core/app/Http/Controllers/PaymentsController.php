<?php namespace App\Http\Controllers;

use App\Http\Requests\PaymentFormRequest;
use App\Invoicer\Repositories\Contracts\PaymentInterface as Payment;
use App\Invoicer\Repositories\Contracts\PaymentMethodInterface as PaymentMethod;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;


class PaymentsController extends Controller {

    protected $payment, $invoice,$paymentmethod;

    public function __construct(Payment $payment, PaymentMethod $paymentmethod, Invoice $invoice){
        $this->middleware('auth');
        $this->payment = $payment;
        $this->paymentmethod = $paymentmethod;
        $this->invoice = $invoice;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $payments = $this->payment->with('invoice')->all();
		return view('payments.index', compact('payments'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $invoice_id = \Input::get('invoice_id');
        if($invoice_id){
            $invoice = $this->invoice->with('client')->getById($invoice_id);
            $invoice->totals = $this->invoice->invoiceTotals($invoice_id);
        }
        else
            $invoice = null;
        $methods = $this->paymentmethod->paymentMethodSelect();
		return view('payments.create', compact('methods','invoice'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PaymentFormRequest $request)
	{
		$payment = [
            'invoice_id' => $request->get('invoice_id'),
            'payment_date' => date('Y-m-d', strtotime($request->get('payment_date'))),
            'amount' => $request->get('amount'),
            'method' => $request->get('method'),
            'notes' => $request->get('notes')
        ];

        if($this->payment->create($payment)){
            $this->invoice->changeStatus($request->get('invoice_id'));
            flash()->success('Payment details saved successfully');
            return response()->json(array('success' => true, 'msg' => 'payment recorded'), 201);
        }
        return response()->json(array('success' => false, 'msg' => 'create failed'), 400);
	}



	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $methods = $this->paymentmethod->paymentMethodSelect();
		$payment = $this->payment->with('invoice')->getById($id);
        return view('payments.edit', compact('payment','methods'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(PaymentFormRequest $request, $id)
	{
        $payment = [
            'payment_date' => date('Y-m-d', strtotime($request->get('payment_date'))),
            'amount' => $request->get('amount'),
            'method' => $request->get('method'),
            'notes' => $request->get('notes')
        ];
        if($request->get('invoice_id') != ''){
            $payment['invoice_id'] = $request->get('invoice_id');
        }

        if($this->payment->updateById($id, $payment)){
            $payment = $this->payment->getById($id);
            $this->invoice->changeStatus($payment->invoice_id);
            flash()->success('Payment details saved successfully');
            return response()->json(array('success' => true, 'msg' => 'payment updated'), 201);
        }
        return response()->json(array('success' => false, 'msg' => 'create failed'), 400);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $payment = $this->payment->getById($id);
        if($this->payment->deleteById($id)){
            flash()->success('Payment deleted');
            $this->invoice->changeStatus($payment->invoice_id);
        }
        else
            flash()->error('Payment delete failed');

        return redirect('payments');
	}

}
