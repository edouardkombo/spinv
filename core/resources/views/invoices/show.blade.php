@extends('app')

@section('content')
<div class="col-md-12 content-header" >
    <h1><i class="fa fa-quote-left"></i> Invoices</h1>
</div>
<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="row">
                <div class="col-md-6" style="width:880px;margin-left:20px"><br/>
                    <a href="{{ route('invoices.index') }}" class="btn btn-info btn-sm"> <i class="fa fa-chevron-left"></i> Back</a>
                    <a href="{{ url('invoices/send', $invoice->id) }}" class="btn btn-success btn-sm pull-right" style="margin-left: 5px"> <i class="fa fa-share"></i> Send</a>
                    <a href="{{ url('invoices/pdf', $invoice->id) }}" target="_blank" class="btn btn-primary btn-sm pull-right" style="margin-left: 5px"> <i class="fa fa-download"></i> Download</a>
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm pull-right" > <i class="fa fa-pencil"></i> Edit</a>
                </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="invoice">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="panel-body">
                                        @if($invoiceSettings && $invoiceSettings->logo != '')
                                        <img src="{{ asset('assets/img/'.$invoiceSettings->logo) }}" alt="logo" width="100%"/>
                                        @endif
                                     </div>
                                </div>
                                <div class="col-md-9 text-right">
                                    <div class="panel-body">
                                        <div class="col-xs-12 text-right"> <h1>INVOICE</h1></div>
                                        <div class="col-xs-9 text-right invoice_title">REFERENCE</div>
                                        <div class="col-xs-3 text-right">{{ $invoice->number }}</div>
                                        <div class="col-xs-9 text-right invoice_title">INVOICE DATE</div>
                                        <div class="col-xs-3 text-right">{{ $settings ? date($settings->date_format, strtotime($invoice->invoice_date)) : $invoice->invoice_date }}</div>
                                        <div class="col-xs-9 text-right invoice_title">DUE DATE</div>
                                        <div class="col-xs-3 text-right">{{ $settings ? date($settings->date_format, strtotime($invoice->due_date)) : $invoice->due_date }}</div>
                                        @if($settings && $settings->vat != '')
                                        <div class="col-xs-9 text-right invoice_title">VAT NUMBER</div>
                                        <div class="col-xs-3 text-right">{{ $settings ? $settings->vat : '' }}</div>
                                        @endif
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
                                            <h4>{{ $invoice->client->name }}</h4>
                                            {{ $invoice->client->address1 ? $invoice->client->address1.',' : '' }} {{ $invoice->client->state ? $invoice->client->state : '' }}<br/>
                                            {{ $invoice->client->city ? $invoice->client->city.',' : '' }} {{ $invoice->client->postal_code ? $invoice->client->postal_code.','  : ''  }}<br/>
                                            {{ $invoice->client->country }}<br/>
                                            Phone: {{ $invoice->client->phone }}<br/>
                                            Email: {{ $invoice->client->email }}.
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

                            <div class="col-md-6" style="padding: 7% 12% 0 15%; text-transform: uppercase">
                              <div class="{{ getStatus('label', 'paid') == $invoice->status ? 'invoice_status_paid' : 'invoice_status_cancelled' }}">
                                    {{ statuses()[$invoice->status]['label']  }}
                            </div>

                            </div><!-- /.col -->
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th style="width:50%">Subtotal</th>
                                            <td class="text-right">
                                                <span id="subTotal">{{ $invoice->currency.' '.$invoice->totals['subTotal'] }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tax</th>
                                            <td class="text-right">
                                                <span id="taxTotal">{{ $invoice->currency.' '.$invoice->totals['taxTotal'] }}</span>
                                            </td>
                                        </tr>
                                        @if($invoice->totals['discount'] > 0)
                                        <tr>
                                            <th>Discount</th>
                                            <td class="text-right">
                                                <span id="taxTotal">{{ $invoice->currency.' '.$invoice->totals['discount'] }}</span>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Total</th>
                                            <td class="text-right">
                                                <span id="grandTotal">{{ $invoice->currency.' '.$invoice->totals['grandTotal'] }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Paid</th>
                                            <td class="text-right">
                                                <span id="grandTotal">{{ $invoice->currency.' '.$invoice->totals['paidFormatted'] }}</span>
                                            </td>
                                        </tr>
                                        <tr class="amount_due">
                                            <th>Amount Due:</th>
                                            <td class="text-right">
                                                <span id="grandTotal">{{ $invoice->currency.' '.$invoice->totals['amountDue'] }}</span>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </div>
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
                    </div>
                </div>
            </div>

        </div>
        <div class="cleafix"></div>
    </div>
</section>

@endsection


