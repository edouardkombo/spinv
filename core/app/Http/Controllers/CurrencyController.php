<?php namespace App\Http\Controllers;

use App\Http\Requests\CurrencyFormRequest;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller {

    private $currency;

    public function __construct(Currency $currency){
        $this->currency = $currency;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$currencies = $this->currency->all();
        return view('settings.currency.index', compact('currencies'));
	}


    /**
     * Store a newly created resource in storage.
     * @param CurrencyFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CurrencyFormRequest $request)
	{
		$data = array('name' => $request->name, 'symbol' => $request->symbol);

        if($this->currency->create($data))
            flash()->success('Currency saved successfully');
        else
            flash()->error('Error saving currency, please try again');

        return redirect('settings/currency');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$currency = $this->currency->getById($id);

        return view('settings.currency.edit', compact('currency'));
	}

    /**
     * Update the specified resource in storage.
     * @param CurrencyFormRequest $request
     * @param $id
     * @return Response
     */
    public function update(CurrencyFormRequest $request, $id)
	{
        $data = array('name' => $request->name, 'symbol' => $request->symbol, 'selected' => $request->selected);

        if($request->selected)
            $this->currency->resetDefault();

        if($this->currency->updateById($id, $data)){
            flash()->success('Currency record updated');
            return response()->json(array('success' => true, 'msg' => 'currency updated'), 201);
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
		if($this->currency->deleteById($id))
            flash()->success('Currency deleted successfully');
        else
            flash()->error('There was an error deleting the currency, please try again');
        return redirect('settings/currency');
	}

}
