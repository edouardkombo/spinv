<?php namespace App\Http\Controllers;

use App\Http\Requests\InvoiceFromRequest;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use App\Invoicer\Repositories\Contracts\ProductInterface as Product;
use App\Invoicer\Repositories\Contracts\ClientInterface as Client;
use App\Invoicer\Repositories\Contracts\TaxSettingInterface as Tax;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use App\Invoicer\Repositories\Contracts\InvoiceItemInterface as InvoiceItem;
use App\Invoicer\Repositories\Contracts\SettingInterface as Setting;
use App\Invoicer\Repositories\Contracts\NumberSettingInterface as Number;
use App\Invoicer\Repositories\Contracts\InvoiceSettingInterface as InvoiceSetting;
use App\Invoicer\Repositories\Contracts\TemplateInterface as Template;


class InvoicesController extends Controller {

   protected $product;
   protected $client;
   protected $tax;
   protected $currency;
   protected $invoice;
   protected $items;
   protected $setting,$number,$invoiceSetting, $template;


   public function __construct(Invoice $invoice, Product $product, Client $client,  Tax $tax, Currency $currency, InvoiceItem $items, Setting $setting, Number $number, InvoiceSetting $invoiceSetting, Template $template){
       $this->middleware('auth');
       $this->invoice   = $invoice;
       $this->product   = $product;
       $this->client    = $client;
       $this->tax       = $tax;
       $this->currency  = $currency;
       $this->items     = $items;
       $this->setting   = $setting;
       $this->number    = $number;
       $this->invoiceSetting = $invoiceSetting;
       $this->template  = $template;
   }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$invoices = $this->invoice->all();
        foreach($invoices as $count => $invoice){
            $invoices[$count]['totals'] = $this->invoice->invoiceTotals($invoice->id);
        }
		return view('invoices.index', compact('invoices'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $settings     = $this->invoiceSetting->first();
        if($settings){
            $start = $settings->start_number;
            $invoice_num = $this->number->prefix('invoice_number', $this->invoice->generateInvoiceNum($start));
        }
        else
            $invoice_num = $this->number->prefix('invoice_number', $this->invoice->generateInvoiceNum());

        $products   = $this->product->productSelect();
        $clients    = $this->client->clientSelect();
        $taxes      = $this->tax->taxSelect();
        $currencies = $this->currency->currencySelect();
        $statuses     = statuses();

		return view('invoices.create', compact('invoice_num','products', 'clients','taxes','currencies', 'statuses', 'settings'));
	}

    /**
     * Store a newly created resource in storage.
     * @param InvoiceFromRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(InvoiceFromRequest $request)
	{
        $invoiceData = array(
            'client_id'     => $request->get('client'),
            'number'        => $request->get('number'),
            'invoice_date'  => date('Y-m-d', strtotime($request->get('invoice_date'))),
            'due_date'      => date('Y-m-d', strtotime($request->get('due_date'))),
            'notes'         => $request->get('notes'),
            'terms'         => $request->get('terms'),
            'currency'      => $request->get('currency'),
            'status'        => $request->get('status'),
            'discount'      => $request->get('discount') != '' ? $request->get('discount') : 0
        );

        $invoice = $this->invoice->create($invoiceData);
        if($invoice){
            $items = json_decode($request->get('items'));
            foreach($items as $item){
                $itemsData = array(
                    'invoice_id'  => $invoice->id,
                    'product_id'  => $item->product,
                    'quantity'    => $item->quantity,
                    'price'       => $item->rate,
                    'tax_id'      => $item->tax != '' ? $item->tax : null ,
                );
               $this->items->create($itemsData);
            }
            return response()->json(array('success' => true,'redirectTo'=>route('invoices.show', $invoice->id), 'msg' => 'Invoice created'), 201);
        }
        return response()->json(array('success' => false, 'msg' => 'create failed'), 400);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $invoice = $this->invoice->with('items')->getById($id);
        if($invoice){
            $settings = $this->setting->first();
            $invoiceSettings = $this->invoiceSetting->first();
            $invoice->totals = $this->invoice->invoiceTotals($id);
            return view('invoices.show', compact('invoice','settings', 'invoiceSettings'));
        }
        return redirect('invoices');
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $invoice   = $this->invoice->with('items')->getById($id);
        if($invoice){
            $products   = $this->product->productSelect();
            $clients    = $this->client->clientSelect();
            $taxes      = $this->tax->taxSelect();
            $currencies = $this->currency->currencySelect();
            $statuses     = statuses();
            $invoice->totals = $this->invoice->invoiceTotals($id);
            return view('invoices.edit', compact('invoice', 'clients', 'products', 'taxes', 'currencies', 'statuses'));
        }
        return redirect('invoices');
	}

    /**
     * Update the specified resource in storage.
     * @param InvoiceFromRequest $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(InvoiceFromRequest $request, $id)
	{
        $invoiceData = array(
            'client_id'     => $request->get('client'),
            'number'        => $request->get('number'),
            'invoice_date'  => date('Y-m-d', strtotime($request->get('invoice_date'))),
            'due_date'      => date('Y-m-d', strtotime($request->get('due_date'))),
            'notes'         => $request->get('notes'),
            'terms'         => $request->get('terms'),
            'currency'      => $request->get('currency'),
            'status'        => $request->get('status'),
            'discount'      => $request->get('discount') != '' ? $request->get('discount') : 0
        );

        $invoice = $this->invoice->updateById($id, $invoiceData);
        if($invoice){
            $items = json_decode($request->get('items'));
            foreach($items as $item){
                $itemsData = array(
                    'invoice_id'  => $invoice->id,
                    'product_id'  => $item->product,
                    'quantity'    => $item->quantity,
                    'price'       => $item->rate,
                    'tax_id'      => $item->tax != '' ? $item->tax : null ,
                );

                if(isset($item->itemId))
                    $this->items->updateById($item->itemId,$itemsData);
                else
                    $this->items->create($itemsData);
            }
            $this->invoice->changeStatus($id);
            return response()->json(array('success' => true,'redirectTo'=>route('invoices.show', $invoice->id), 'msg' => 'Invoice created'), 201);
        }
        return response()->json(array('success' => false, 'msg' => 'create failed'), 400);
	}

    /**
     * @return mixed
     */

    public function ajaxSearch(){
        return $this->invoice->ajaxSearch();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function deleteItem(){
        $id = \Input::get('id');
        if($this->items->deleteById($id))
            return response()->json(array('success' => true, 'msg' => 'item deleted'), 201);

        return response()->json(array('success' => false, 'msg' => 'delete failed'), 400);
    }

    /**
     * @param $id
     * @return mixed
     */

    public function invoicePdf($id){
        $invoice = $this->invoice->with('items')->getById($id);
        if($invoice){
            $settings = $this->setting->first();
            $invoiceSettings = $this->invoiceSetting->first();
            $invoice->totals = $this->invoice->invoiceTotals($id);
            $pdf = \PDF::loadView('invoices.pdf', compact('settings', 'invoice', 'invoiceSettings'));
            return $pdf->download('invoice_'.$invoice->number.'_'.date('Y-m-d').'.pdf');
        }
        return redirect('invoices');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function send($id){
        $invoice = $this->invoice->with('items','client')->getById($id);
        if($invoice){
            $settings = $this->setting->first();
            $invoiceSettings = $this->invoiceSetting->first();
            $invoice->totals = $this->invoice->invoiceTotals($id);
            $pdf = \PDF::loadView('invoices.pdf', compact('settings', 'invoice', 'invoiceSettings'));

            $data['emailBody'] = 'An invoice has been generated';
            $data['emailTitle'] = 'An invoice has been generated';
            $template = $this->template->where('name','invoice')->first();
            if($template)
            {
                $data_object = new \stdClass();
                $data_object->invoice   = $invoice;
                $data_object->settings  = $settings;
                $data_object->client    = $invoice->client;

                $data['emailBody'] = parse_template($data_object, $template->body);
                $data['emailTitle'] = $template->subject;
            }
            $data['logo'] =  $settings->logo;

            \Mail::send(['html'=>'emails.layout'], $data, function($message) use($pdf,$invoice,$settings)
            {
                $message->sender($settings->email, $settings->name);
                $message->to($invoice->client->email, $invoice->client->name);
                $message->subject('Invoice Generated');
                $message->attachData($pdf->output(), 'invoice_'.$invoice->number.'_'.date('Y-m-d').'.pdf');
            });
            \Flash::success('Invoice has been emailed to the client');
        }
        return redirect('invoices');
    }
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if($this->invoice->deleteById($id)){
            flash()->success('Invoice record deleted');
            return redirect('invoices');
        }
        flash()->error('Invoice record not deleted');
        return redirect('invoices');
	}

}
