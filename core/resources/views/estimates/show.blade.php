@extends('app')

@section('content')
<div class="col-md-12 content-header" >
    <h1><i class="fa fa-quote-left"></i> Estimates</h1>
</div>
<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="row">
                <div class="col-md-6" style="width:880px;margin-left:20px"><br/>
                    <a href="{{ route('estimates.index') }}" class="btn btn-info btn-sm"> <i class="fa fa-chevron-left"></i> Back</a>
                    <a href="{{ url('estimates/send',$estimate->id) }}" class="btn btn-success btn-sm pull-right" style="margin-left: 5px"> <i class="fa fa-share"></i> Send</a>
                    <a href="{{ url('estimates/pdf', $estimate->id) }}" class="btn btn-primary btn-sm pull-right" style="margin-left: 5px"> <i class="fa fa-download"></i> Download</a>
                    <a href="{{ route('estimates.edit', $estimate->id) }}" class="btn btn-warning btn-sm pull-right" > <i class="fa fa-pencil"></i> Edit</a>
                </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="invoice">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="panel-body">
                                        @if($settings && $settings->logo != '')
                                        <img src="{{ asset('assets/img/'.$settings->logo) }}" alt="logo" width="100%"/>
                                        @endif
                                     </div>
                                </div>
                                <div class="col-md-9 text-right">
                                    <div class="panel-body">
                                        <div class="col-xs-12 text-right"> <h1>ESTIMATE</h1></div>
                                        <div class="col-xs-9 text-right invoice_title">REFERENCE</div>
                                        <div class="col-xs-3 text-right">{{ $estimate->estimate_no }}</div>
                                        <div class="col-xs-9 text-right invoice_title">DATE</div>
                                        <div class="col-xs-3 text-right">{{ $estimate->estimate_date }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                        <div class="panel-body">
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
                                </div>
                                <div class="col-xs-6">
                                        <div class="panel-body">
                                            <h4 class="invoice_title">BILLING TO </h4><hr class="separator"/>
                                            <h4>{{ $estimate->client->name }}</h4>
                                            {{ $estimate->client->address1 ? $estimate->client->address1.',' : '' }} {{ $estimate->client->state ? $estimate->client->state : '' }}<br/>
                                            {{ $estimate->client->city ? $estimate->client->city.',' : '' }} {{ $estimate->client->postal_code ? $estimate->client->postal_code.','  : ''  }}<br/>
                                            {{ $estimate->client->country }}<br/>
                                            Phone: {{ $estimate->client->phone }}<br/>
                                            Email: {{ $estimate->client->email }}.
                                        </div>
                                </div>
                            </div>
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

                            <div class="col-md-6"></div><!-- /.col -->
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th style="width:50%">Subtotal</th>
                                            <td class="text-right">
                                                <span id="subTotal">{{ $estimate->currency.' '.$estimate->totals['subTotal'] }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tax</th>
                                            <td class="text-right">
                                                <span id="taxTotal">{{ $estimate->currency.' '.$estimate->totals['taxTotal'] }}</span>
                                            </td>
                                        </tr>
                                        <tr class="amount_due">
                                            <th>Total:</th>
                                            <td class="text-right">
                                                <span id="grandTotal">{{ $estimate->currency.' '.$estimate->totals['grandTotal'] }}</span>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </div>
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
                    </div>
                </div>
            </div>

        </div>
        <div class="cleafix"></div>
    </div>
</section>

<style>
    .invoice_title{
        color: #2e3e4e;
        font-weight: bold;
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

    .invoice_amount_due{
        background-color: #82b440;
        color:#fff;
    }
    .invoice_status_cancelled
    {
        font-size : 30px;
        text-align : center;
        color: #cc0000;
        margin-bottom : 30px;
    }
    .invoice_status_paid
    {
        font-size : 30px;
        text-align : center;
        color: #82b440;
        margin-bottom : 30px;
    }
</style>
@endsection


