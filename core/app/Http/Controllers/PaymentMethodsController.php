<?php namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodFromRequest;
use App\Invoicer\Repositories\Contracts\PaymentMethodInterface as Payment;


class PaymentMethodsController extends Controller {

    private $payment;

    public function __construct(Payment $payment){
        $this->payment = $payment;
        $this->middleware('auth');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $payment_methods = $this->payment->all();
        return view('settings.payment.index', compact('payment_methods'));
	}
    /**
     * Store a newly created resource in storage.
     * @param PaymentMethodFromRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PaymentMethodFromRequest $request)
	{
		$data = array('name' => $request->name);
        if($this->payment->create($data))
            flash()->success('Payment method  saved');
        else
            flash()->error('Error creating record, please try again');

        return redirect('settings/payment');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$payment = $this->payment->getById($id);
        return view('settings.payment.edit', compact('payment'));
	}

    /**
     * Update the specified resource in storage.
     * @param PaymentMethodFromRequest $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(PaymentMethodFromRequest $request, $id)
	{
		$data = array('name' => $request->name, 'selected' => $request->selected);

        if($request->selected)
            $this->payment->resetDefault();

        if($this->payment->updateById($id, $data)){
            flash()->success('Payment method saved successfully');
            return response()->json(array('success' => true, 'msg' => 'payment method updated'), 201);
        }
        return response()->json(array('success' => false, 'msg' => 'update failed'), 422);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($this->payment->deleteById($id))
            flash()->success('Record deleted successfully');
        else
            flash()->error('Error deleting record, please try again');
        return redirect('settings/payment');
	}

}
