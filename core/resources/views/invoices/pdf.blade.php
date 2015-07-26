<div class="container">
        <div style="width:300px;height:150px;float:left;overflow: hidden">
            @if($invoiceSettings && $invoiceSettings->logo != '')
            <img src="{{ asset('assets/img/'.$invoiceSettings->logo) }}" alt="logo" width="50%"/>
            @endif
        </div>
        <div class="text-right" style="width:300px;height:150px;float:right;">
            <div class="text-right"> <h2>INVOICE</h2></div>

            <table style="width: 100%">
                <tr>
                    <td class="text-right" style="width: 40%">REFERENCE</td>
                    <td class="text-right">{{ $invoice->number }}</td>
                </tr>
                <tr>
                    <td class="text-right">INVOICE DATE</td>
                    <td class="text-right">{{ $settings ? date($settings->date_format, strtotime($invoice->invoice_date)) : $invoice->date_format }}</td>
                </tr>
                <tr>
                    <td class="text-right">DUE DATE</td>
                    <td class="text-right">{{ $settings ? date($settings->date_format, strtotime($invoice->due_date)) : $invoice->due_date }}</td>
                </tr>
                @if($settings && $settings->vat != '')
                <tr>
                    <td class="text-right">VAT NUMBER</td>
                    <td class="text-right">{{ $settings ? $settings->vat : '' }}</td>
                </tr>
                @endif
            </table>
        </div>
    <div style="clear: both"></div>
    <div class="col-md-12">
    <div class="from_address">
        <h4 class="invoice_title">OUR INFORMATION</h4><hr class="separator"/>
        @if($settings)
        <h4>{{ $settings->name }}</h4>
        {{ $settings->address1 ? $settings->address1.',' : '' }} {{ $settings->state ? $settings->state : '' }}<br/>
        {{ $settings->city ? $settings->city.',' : '' }} {{ $settings->postal_code ? $settings->postal_code.','  : ''  }}<br/>
        {{ $settings->country }}<br/>
        Phone: {{ $settings->phone }}<br/>
        Email: {{ $settings->email }}.
        @endif
    </div>
    <div class="to_address">
            <h4 class="invoice_title">BILLING TO </h4><hr class="separator"/>
            <h4>{{ $invoice->client->name }}</h4>
            {{ $invoice->client->address1 ? $invoice->client->address1.',' : '' }} {{ $invoice->client->state ? $invoice->client->state : '' }}<br/>
            {{ $invoice->client->city ? $invoice->client->city.',' : '' }} {{ $invoice->client->postal_code ? $invoice->client->postal_code.','  : ''  }}<br/>
            {{ $invoice->client->country }}<br/>
            Phone: {{ $invoice->client->phone }}<br/>
            Email: {{ $invoice->client->email }}.
    </div>
    </div>
    <div style="clear: both"></div>
    <div class="col-md-12">
        <table class="table">
            <thead style="margin-bottom:30px;background: #2e3e4e;color: #fff;">
            <tr>
                <th style="width:50%">PRODUCT</th>
                <th style="width:10%" class="text-center">QTY</th>
                <th style="width:15%" class="text-right">PRICE</th>
                <th style="width:10%" class="text-center">TAX</th>
                <th style="width:15%" class="text-right">TOTAL</th>
            </tr>
            </thead>
            <tbody id="items">
            @foreach($invoice->items as $item)
            <tr>
                <td>{!! $item->product->category != '' ? $item->product->category.' - '.$item->product->name : $item->product->name  !!}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ $item->price }}</td>
                <td class="text-center">{{ $item->tax ? $item->tax->value.'%' : '' }}</td>
                <td class="text-right">{{ $invoice->totals[$item->id]['itemTotal'] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.col -->
   <div class="col-md-12">
       <div class="col-md-6" style="padding: 7% 25% 0 10%;width: 30%; text-transform: uppercase">
           <div class="{{ getStatus('label', 'paid') == $invoice->status ? 'invoice_status_paid' : 'invoice_status_cancelled' }}">
               {{ statuses()[$invoice->status]['label']  }}
           </div>
           </div>
             <table class="table">
                 <tbody>
                 <tr>
                     <th style="width:75%" class="text-right">Subtotal</th>
                     <td class="text-right">
                         <span id="subTotal">{{ $invoice->currency.' '.$invoice->totals['subTotal'] }}</span>
                     </td>
                 </tr>
                 <tr>
                     <th class="text-right">Tax</th>
                     <td class="text-right">
                         <span id="taxTotal">{{ $invoice->currency.' '.$invoice->totals['taxTotal'] }}</span>
                     </td>
                 </tr>
                 @if($invoice->totals['discount'] > 0)
                 <tr>
                     <th class="text-right">Discount</th>
                     <td class="text-right">
                         <span id="taxTotal">{{ $invoice->currency.' '.$invoice->totals['discount'] }}</span>
                     </td>
                 </tr>
                 @endif
                 <tr>
                     <th class="text-right">Total</th>
                     <td class="text-right">
                         <span id="grandTotal">{{ $invoice->currency.' '.$invoice->totals['grandTotal'] }}</span>
                     </td>
                 </tr>
                 <tr>
                     <th class="text-right">Paid</th>
                     <td class="text-right">
                         <span id="grandTotal">{{ $invoice->currency.' '.$invoice->totals['paidFormatted'] }}</span>
                     </td>
                 </tr>
                 <tr class="amount_due">
                     <th class="text-right">Amount Due:</th>
                     <td class="text-right">
                         <span id="grandTotal">{{ $invoice->currency.' '.$invoice->totals['amountDue'] }}</span>
                     </td>
                 </tr>
                 </tbody>
             </table>
     </div><!-- /.col -->
    <div class="col-md-12">
        @if($invoice->notes)
        <h4 class="invoice_title">NOTES</h4><hr class="separator"/>
        {!! $invoice->notes !!} <br/><br/>
        @endif
        @if($invoice->terms)
        <h4 class="invoice_title">TERMS</h4><hr class="separator"/>
        {!! $invoice->terms !!}
        @endif
    </div>
</div>


<style>
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
        font-weight: 300;
        overflow-x: hidden;
        overflow-y: auto;
        font-size: 13px;
    }
    .amount_due {
        font-size: 20px;
        font-weight: 500;
    }
    .invoice_title{
        color: #2e3e4e;
        font-weight: bold;
    }
    .text-right{
        text-align: right;
    }
    .text-center{
        text-align: center;
    }
    .from_address{
        width: 300px;
        float: left;
        height: 200px;
    }
    .to_address{
        width: 300px;
        height: 200px;
        float: right;
    }
    .col-md-12{
        width: 100%;
    }
    .col-md-6{
        width: 50%;
        float: left;
    }
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }
    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
    }
    hr.separator{
        border-color:  #2e3e4e;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    tbody#items > tr > td{
        border: 3px solid #fff !important;
        vertical-align: middle;
    }
    #items{
        background-color: #f1f1f1;
    }

    .invoice_status_cancelled
    {
        font-size : 20px;
        text-align : center;
        color: #cc0000;
        border: 1px solid #cc0000;
    }
    .invoice_status_paid
    {
        font-size : 25px;
        text-align : center;
        color: #82b440;
        border: 1px solid #82b440;
    }

</style>