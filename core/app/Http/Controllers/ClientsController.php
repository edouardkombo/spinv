<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\ClientFormRequest;
use App\Invoicer\Repositories\Contracts\ClientInterface as Client;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use App\Invoicer\Repositories\Contracts\EstimateInterface as Estimate;
use App\Invoicer\Repositories\Contracts\NumberSettingInterface as Number;
use Symfony\Component\HttpFoundation\Response;

class ClientsController extends Controller {

    private $client, $invoice, $estimate, $number;


    public function __construct(Client $client, Invoice $invoice, Estimate $estimate, Number $number){
        $this->middleware('auth');
        $this->client = $client;
        $this->invoice = $invoice;
        $this->estimate = $estimate;
        $this->number = $number;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $clients = $this->client->all();
		return view('clients.index', compact('clients'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $client_num = $this->number->prefix('client_number', $this->client->generateClientNum());
		return view('clients.create', compact('client_num'));
	}

    /**
     * Store a newly created resource in storage.
     * @param ClientFormRequest $request
     * @return Response
     */
    public function store(ClientFormRequest $request)
	{
        if($this->client->create($request->all())){
            flash()->success('Client details saved successfully');
            return response()->json(array('success' => true, 'msg' => 'client created'), 201);
        }
        return response()->json(array('success' => false, 'msg' => 'create failed'), 422);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$client = $this->client->getById($id);
        if($client)
            return view('clients.edit',  compact('client'));
        else
            return redirect('clients');
	}

    /**
     * @param $id
     */

    public function show($id){
        $client = $this->client->with('invoices', 'estimates')->getById($id);
        if($client){
            foreach($client->invoices as $count => $invoice){
                $client->invoices[$count]['totals'] = $this->invoice->invoiceTotals($invoice->id);
            }
            foreach($client->estimates as $count => $estimate){
                $client->estimates[$count]['totals'] = $this->estimate->estimateTotals($estimate->id);
            }
            return view('clients.show', compact('client'));
        }
        return redirect('clients');
    }

    /**
     * Update the specified resource in storage.
     * @param ClientFormRequest $request
     * @param $id
     * @return Response
     */
    public function update(ClientFormRequest $request, $id)
	{
        if($this->client->updateById($id, $request->all())){
            flash()->success('Client details updated');
            return response()->json(array('success'=>true, 'msg' => 'client updated'), 201);
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
		$this->client->deleteById($id);
        flash()->success('Client record deleted');
        return redirect('clients');
	}

}
