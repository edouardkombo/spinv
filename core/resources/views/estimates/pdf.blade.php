<div class="container">
    <div style="width:300px;height:150px;float:left;">
        @if($settings && $settings->logo != '')
         <img src="{{ asset('assets/img/'.$settings->logo) }}" alt="logo" width="50%"/>
        @endif
    </div>
    <div class="text-right" style="width:300px;height:150px;float:right;">
        <div class="text-right"> <h2>ESTIMATE</h2></div>

        <table style="width: 100%">
            <tr>
                <td class="text-right" style="width: 40%">REFERENCE</td>
                <td class="text-right">{{ $estimate->estimate_no }}</td>
            </tr>
            <tr>
                <td class="text-right">DATE</td>
                <td class="text-right">{{ $estimate->estimate_date }}</td>
            </tr>
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
            <h4 class="invoice_title">ESTIMATE TO </h4><hr class="separator"/>
            <h4>{{ $estimate->client->name }}</h4>
            {{ $estimate->client->address1 ? $estimate->client->address1.',' : '' }} {{ $estimate->client->state ? $estimate->client->state : '' }}<br/>
            {{ $estimate->client->city ? $estimate->client->city.',' : '' }} {{ $estimate->client->postal_code ? $estimate->client->postal_code.','  : ''  }}<br/>
            {{ $estimate->client->country }}<br/>
            Phone: {{ $estimate->client->phone }}<br/>
            Email: {{ $estimate->client->email }}.
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
            @foreach($estimate->items as $item)
            <tr>
                <td>{!! $item->product->category != '' ? $item->product->category.' - '.$item->product->name : $item->product->name  !!}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ $item->price }}</td>
                <td class="text-center">{{ $item->tax ? $item->tax->value.'%' : '' }}</td>
                <td class="text-right">{{ $estimate->totals[$item->id]['itemTotal'] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.col -->
    <div class="col-md-12">
        <table class="table">
            <tbody>
            <tr>
                <th style="width:75%" class="text-right">Subtotal</th>
                <td class="text-right">
                    <span id="subTotal">{{ $estimate->currency.' '.$estimate->totals['subTotal'] }}</span>
                </td>
            </tr>
            <tr>
                <th class="text-right">Tax</th>
                <td class="text-right">
                    <span id="taxTotal">{{ $estimate->currency.' '.$estimate->totals['taxTotal'] }}</span>
                </td>
            </tr>

            <tr class="amount_due">
                <th class="text-right">Total:</th>
                <td class="text-right">
                    <span id="grandTotal">{{ $estimate->currency.' '.$estimate->totals['grandTotal'] }}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div><!-- /.col -->
    <div class="col-md-12">
        @if($estimate->notes)
        <h4 class="invoice_title">NOTES</h4><hr class="separator"/>
        {{ $estimate->notes }} <br/><br/>
        @endif
        @if($estimate->terms)
        <h4 class="invoice_title">TERMS</h4><hr class="separator"/>
        {{ $estimate->terms }}
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
</style>