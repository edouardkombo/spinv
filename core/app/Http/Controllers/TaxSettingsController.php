<?php namespace App\Http\Controllers;

use App\Http\Requests\TaxSettingFormRequest;
use App\Invoicer\Repositories\Contracts\TaxSettingInterface as Tax;


class TaxSettingsController extends Controller {

    private $tax;

    public function __construct(Tax $tax){
        $this->middleware('auth');
        $this->tax = $tax;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $taxes = $this->tax->all();
		return view('settings.tax.index', compact('taxes'));
	}

    /**
     * Store a newly created resource in storage.
     * @param TaxSettingFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TaxSettingFormRequest $request)
	{
        $data = array('name' => $request->name, 'value' => $request->value);
		if($this->tax->create($data)){
            flash()->success('Tax settings saved');
        }else{
            flash()->error('Error saving tax settings, try again');
        }
        return redirect('settings/tax');
	}


	/**
	 * Show the form for editing the specified resource.
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$tax = $this->tax->getById($id);
        return view('settings.tax.edit', compact('tax'));
	}

    /**
     * Update the specified resource in storage.
     * @param TaxSettingFormRequest $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(TaxSettingFormRequest $request, $id)
	{
		$data   =  array('name'=>$request->name, 'value'=>$request->value, 'selected' => $request->selected);
        if($request->selected)
            $this->tax->resetDefault();

        if($this->tax->updateById($id, $data)){
            flash()->success('Tax details saved successfully');
            return response()->json(array('success' => true, 'msg' => 'tax updated'), 201);
        }

        return response()->json(array('success' => false, 'msg' => 'update failed', 'errors' => $this->errorBag()), 422);
	}


	/**
	 * Remove the specified resource from storage.
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->tax->deleteById($id);
        flash()->success('Tax record deleted');
        return redirect('settings/tax');
	}

}
