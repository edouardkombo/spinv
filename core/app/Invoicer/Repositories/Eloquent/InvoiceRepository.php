<?php namespace App\Invoicer\Repositories\Eloquent;

use App\Invoicer\Repositories\Contracts\InvoiceInterface;

class InvoiceRepository extends BaseRepository implements InvoiceInterface{


    /**
     * @return string
     */

    public function model() {
        return 'App\Models\Invoice';
    }

    /**
     * @return string
     */

    public function generateInvoiceNum($start = 0){
        $invoice = $this->model();
        $last = $invoice::orderBy('created_at', 'desc')->first();
        if($last){
            $next_id = $last->id+1;
        }else{
            $next_id = $start;
        }
        return $next_id;
    }

    /**
     * @param $id
     * @return array
     */
    public function invoiceTotals($id){
        $invoice = $this->with('items', 'payments')->getById($id);
        $items = $invoice->items;
        $payments =  $invoice->payments;

        $totals     = array();
        $subTotal   = 0;
        $taxTotal   = 0;
        $paid       = 0;
        $discount    = $invoice->discount;
        foreach($items as $item){
            $tax = $item->tax;
            $itemTotal = $item->quantity * $item->price;
            $itemTax = $tax ? $itemTotal * $tax->value/100 : 0;
            $totals[$item->id]['itemTotal'] = number_format($itemTotal,2);
            $totals[$item->id]['tax']       = $itemTax ;
            $subTotal += $itemTotal;
            $taxTotal += $itemTax;
        }
        foreach($payments as $payment){
            $paid += $payment->amount;
        }

        $totals['subTotal'] = number_format($subTotal, 2);
        $totals['taxTotal'] = number_format($taxTotal, 2);
        $totals['discount'] = $discount > 0 ? number_format($discount, 2) : 0 ;
        $totals['paidFormatted']     = number_format($paid, 2);
        $totals['paid']     = $paid;
        $totals['grandTotal'] = number_format($subTotal + $taxTotal - $discount, 2);
        $totals['amountDue'] = number_format($subTotal + $taxTotal - $discount - $paid, 2);

       return $totals;
    }

    /**
     * @return array
     */
    public function ajaxSearch(){
        $term = \Input::get('data')['q'];
        $invoices = $this->where('number', '%'.$term.'%','LIKE')->with('client')->get();
        $results = array();
        foreach($invoices as $invoice){
            $totals  = $this->invoiceTotals($invoice->id);
            $record = [
                'id'     => $invoice->id,
                'text'   => $invoice->number.' ('.$invoice->currency.$totals['grandTotal'].') - '.strtoupper($invoice->client->name)
            ];
            array_push($results, $record);
        }
        return $results;
    }

    /**
     * @param $range
     * @return mixed
     */

    public function report($range){
        $invoice = $this->model();
        $stats = $invoice::where('invoice_date', '>=', $range)
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get([
                \DB::raw('Date(invoice_date) as date'),
                \DB::raw('COUNT(*) as value')
            ]);
            return $stats;
    }

    /**
     * @param $id
     */

    public function changeStatus($id){
        $invoice = $this->with('items', 'payments')->getById($id);
        $items = $invoice->items;
        $payments =  $invoice->payments;

        $totals     = array();
        $subTotal   = 0;
        $taxTotal   = 0;
        $paid       = 0;
        $discount    = $invoice->discount;
        foreach($items as $item){
            $tax = $item->tax;
            $itemTotal = $item->quantity * $item->price;
            $itemTax = $tax ? $itemTotal * $tax->value/100 : 0;
            $totals[$item->id]['itemTotal'] = number_format($itemTotal,2);
            $totals[$item->id]['tax']       = $itemTax ;
            $subTotal += $itemTotal;
            $taxTotal += $itemTax;
        }
        foreach($payments as $payment){
            $paid += $payment->amount;
        }
        $grandTotal = $subTotal + $taxTotal - $discount;
        $amountDue = $subTotal + $taxTotal - $discount - $paid;

        $due_date = new \DateTime($invoice->due_date);
        $today = new \DateTime();

        if($paid <= 0){ //invoice is unpaid
            $status = getStatus('label', 'unpaid');
        }
        elseif($paid > 0 && $paid < $grandTotal && $amountDue > 0 && $due_date > $today){// Invoice is partially paid
            $status =  getStatus('label', 'partially paid');
        }
        elseif($paid >= $grandTotal && $amountDue <= 0){//Invoice is fully paid
            $status =  getStatus('label', 'paid');
        }
        elseif(($paid <= 0 || $paid > 0 && $paid < $grandTotal && $amountDue > 0) && $due_date < $today  ){//Invoice is overdue
            $status =  getStatus('label', 'overdue');
        }
        else{
            $status =  getStatus('label', 'unpaid');
        }
        $this->updateById($id, array('status' => $status));
    }
}