<?php namespace App\Http\Controllers;

use App\Http\Requests\EstimateFormRequest;
use App\Invoicer\Repositories\Contracts\EstimateInterface as Estimate;
use App\Invoicer\Repositories\Contracts\EstimateItemInterface as EstimateItem;
use App\Invoicer\Repositories\Contracts\ProductInterface as Product;
use App\Invoicer\Repositories\Contracts\TaxSettingInterface as Tax;
use App\Invoicer\Repositories\Contracts\ClientInterface as Client;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use App\Invoicer\Repositories\Contracts\SettingInterface as Setting;
use App\Invoicer\Repositories\Contracts\NumberSettingInterface as Number;
use App\Invoicer\Repositories\Contracts\TemplateInterface as Template;

class EstimatesController extends Controller {
    protected $product;
    protected $tax;
    protected $client;
    protected $currency;
    protected $estimate;
    protected $estimateItem;
    protected $setting, $number,$template;

    public function __construct(Product $product,Tax $tax, Client $client, Currency $currency, Estimate $estimate, EstimateItem $estimateItem, Setting $setting, Number $number,Template $template ){
        $this->middleware('auth');
        $this->product = $product;
        $this->client = $client;
        $this->currency = $currency;
        $this->tax = $tax;
        $this->estimate = $estimate;
        $this->estimateItem = $estimateItem;
        $this->setting = $setting;
        $this->number = $number;
        $this->template = $template;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $estimates = $this->estimate->with('client')->all();
        foreach($estimates as $count => $estimate){
            $estimates[$count]['totals'] = $this->estimate->estimateTotals($estimate->id);
        }
		return view('estimates.index', compact('estimates'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $estimate_num = $this->number->prefix('estimate_number', $this->estimate->generateEstimateNum());
        $products = $this->product->productSelect();
        $clients = $this->client->clientSelect();
        $taxes = $this->tax->taxSelect();
        $currencies = $this->currency->currencySelect();

		return view('estimates.create', compact('products', 'taxes', 'currencies', 'clients', 'estimate_num'));
	}

    /**
     * Store a newly created resource in storage.
     * @param EstimateFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(EstimateFormRequest $request)
	{
        $estimateData = array(
            'client_id'     => $request->get('client'),
            'estimate_no'   => $request->get('estimate_no'),
            'estimate_date' => date('Y-m-d', strtotime($request->get('estimate_date'))),
            'notes'         => $request->get('notes'),
            'terms'         => $request->get('terms'),
            'currency'      => $request->get('currency')
        );

        $estimate = $this->estimate->create($estimateData);
        if($estimate){
            $items = json_decode($request->get('items'));
            foreach($items as $item){
                $itemsData = array(
                    'estimate_id' => $estimate->id,
                    'product_id'  => $item->product,
                    'quantity'    => $item->quantity,
                    'price'       => $item->rate,
                    'tax_id'      => $item->tax != '' ? $item->tax : null ,
                );
                $this->estimateItem->create($itemsData);
            }
            return response()->json(array('success' => true,'redirectTo'=>route('estimates.show', $estimate->id), 'msg' => 'Estimate created'), 201);
        }
        return response()->json(array('success' => false, 'msg' => 'create failed'), 400);
	}

    /**
     * Display the specified resource.
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
	{
        $estimate = $this->estimate->with('items')->getById($id);
        if($estimate){
            $settings = $this->setting->first();
            $estimate->totals = $this->estimate->estimateTotals($id);
            return view('estimates.show', compact('estimate', 'settings'));
        }
        return redirect('estimates');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $estimate = $this->estimate->with('items')->getById($id);
        if($estimate){
            $products = $this->product->productSelect();
            $clients = $this->client->clientSelect();
            $taxes = $this->tax->taxSelect();
            $currencies = $this->currency->currencySelect();
            $estimate->totals = $this->estimate->estimateTotals($id);
            return view('estimates.edit', compact('estimate','products', 'taxes', 'currencies', 'clients'));
        }
        return redirect('estimates');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(EstimateFormRequest $request, $id)
	{
        $estimateData = array(
            'client_id'     => $request->get('client'),
            'estimate_no'   => $request->get('estimate_no'),
            'estimate_date' => date('Y-m-d', strtotime($request->get('estimate_date'))),
            'notes'         => $request->get('notes'),
            'terms'         => $request->get('terms'),
            'currency'      => $request->get('currency')
        );

        $estimate = $this->estimate->updateById($id, $estimateData);
        if($estimate){
            $items = json_decode($request->get('items'));
            foreach($items as $item){
                $itemsData = array(
                    'estimate_id' => $estimate->id,
                    'product_id'  => $item->product,
                    'quantity'    => $item->quantity,
                    'price'       => $item->rate,
                    'tax_id'      => $item->tax != '' ? $item->tax : null ,
                );

                if(isset($item->itemId))
                    $this->estimateItem->updateById($item->itemId,$itemsData);
                else
                    $this->estimateItem->create($itemsData);
            }
            return response()->json(array('success' => true,'redirectTo'=>route('estimates.show', $estimate->id), 'msg' => 'Estimate updated'), 201);
        }
        return response()->json(array('success' => false, 'msg' => 'update failed'), 400);

	}

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function deleteItem(){
        $id = \Input::get('id');
        if($this->estimateItem->deleteById($id))
            return response()->json(array('success' => true, 'msg' => 'item deleted'), 201);

        return response()->json(array('success' => false, 'msg' => 'delete failed'), 400);
    }

    /**
     * @param $id
     * @return mixed
     */

    public function estimatePdf($id){
        $estimate = $this->estimate->with('items')->getById($id);
        if($estimate){
            $settings = $this->setting->first();
            $estimate->totals = $this->estimate->estimateTotals($id);
            $pdf = \PDF::loadView('estimates.pdf', compact('settings', 'estimate'));
            return $pdf->download('invoice_'.$estimate->estimate_no.'_'.date('Y-m-d').'.pdf');
        }
        return redirect('estimates');
    }

    public function send($id){
        $estimate = $this->estimate->with('items','client')->getById($id);
        if($estimate){
            $settings = $this->setting->first();
            $estimate->totals = $this->estimate->estimateTotals($id);
            $pdf = \PDF::loadView('estimates.pdf', compact('settings', 'estimate'));

            $data['emailTitle'] = 'An estimate has been generated';
            $data['emailBody'] = 'An estimate has been generated';
            $template = $this->template->where('name','estimate')->first();
            if($template)
            {
                $data_object = new \stdClass();
                $data_object->settings  = $settings;
                $data_object->client    = $estimate->client;

                $data['emailBody'] = parse_template($data_object, $template->body);
                $data['emailTitle'] = $template->subject;
            }
            $data['logo'] =  $settings->logo;

            \Mail::send(['html'=>'emails.layout'], $data, function($message) use($pdf,$estimate,$settings)
            {
                $message->sender($settings->email, $settings->name);
                $message->to($estimate->client->email, $estimate->client->name);
                $message->subject('Estimate Generated');
                $message->attachData($pdf->output(), 'estimate_'.$estimate->estimate_no.'_'.date('Y-m-d').'.pdf');
            });
            \Flash::success('Estimate has been emailed to the client');
        }
        return redirect('estimates');
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if($this->estimate->deleteById($id)){
            flash()->success('Estimate record deleted');
            return redirect('invoices');
        }
        flash()->error('Estimate record not deleted');
        return redirect('estimates');
	}

}
