@extends('app')

@section('content')
<div class="col-md-12 content-header" >
    <h1><i class="fa fa-file-pdf-o"></i> Invoices</h1>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-6" style="width:880px;margin-left:20px"><br/>
                        <a href="{{ route('invoices.index') }}" class="btn btn-info btn-sm"> <i class="fa fa-chevron-left"></i> Back</a>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-primary btn-sm pull-right"> <i class="fa fa-search"></i> Preview</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="invoice">
                            {!! Form::model($invoice, ['route' => ['invoices.update', $invoice->id],  'method' => 'PATCH', 'id' => 'invoice_form', 'data-toggle'=>"validator", 'role' =>"form"]) !!}
                            <div class="col-md-12">
                                <div class="text-right"><h1>INVOICE</h1></div>
                                <div class="col-md-7" style="padding: 0px">
                                    <div class="contact to">
                                        <div class="form-group">
                                            {!! Form::label('client', 'Client') !!}
                                            <div class="input-group col-md-7">
                                                {!! Form::select('client',$clients,$invoice->client_id, ['class' => 'form-control chosen', 'id' => 'client', 'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('currency', 'Currency') !!}
                                            <div class="input-group col-md-7">
                                                {!! Form::select('currency',$currencies,null, ['class' => 'form-control chosen', 'required']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('number', 'Invoice Number') !!}
                                            <div class="input-group col-md-7">
                                            {!! Form::text('number',null, ['class' => 'form-control', 'id' => 'invoice_no', 'required']) !!}
                                        </div>
                                       </div>
                                    </div>
                                </div>
                                <div class="col-md-5" style="padding: 0px">

                                    <div class="form-group">
                                        {!! Form::label('invoice_date', 'Invoice Date') !!}
                                        {!! Form::text('invoice_date',null, ['class' => 'form-control datepicker' , 'id' => 'invoice_date', 'required']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('due_date', 'Due Date') !!}
                                        {!! Form::text('due_date',null, ['class' => 'form-control datepicker' , 'id' => 'due_date', 'required']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('status', 'Status') !!}
                                        <select class="form-control chosen required" name="status" id="status">
                                            @foreach($statuses as $key => $status)
                                            <option value="{{ $key }}" {{ $key == $invoice->status ?  'selected' : '' }}> {{ strtoupper($status['label']) }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped" id="item_table">
                                    <thead style="background: #2e3e4e;color: #fff;">
                                    <tr>
                                        <th></th>
                                        <th width="40%">PRODUCT</th>
                                        <th width="15%">QTY</th>
                                        <th width="15%">PRICE</th>
                                        <th width="15%">TAX</th>
                                        <th width="15%"class="text-right">AMOUNT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoice->items as $item)
                                    <tr class="item">
                                        <td>
                                            <span class="btn btn-danger btn-xs deleteItem" data-id="{{ $item->id }}"><i class="fa fa-trash"></i></span>
                                            {!! Form::hidden('itemId',$item->id, ['id'=>'itemId', 'required']) !!}
                                        </td>
                                        <td>
                                            <div class="form-group">{!! Form::customSelect('product',$products,$item->product_id, ['class' => 'form-control product', 'id'=>"product", 'required' ]) !!}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                {!! Form::input('number', 'quantity',$item->quantity, ['class' => 'form-control calcEvent quantity', 'id'=>"quantity" , 'required', 'step' => 'any', 'min' => '1']) !!}
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="form-group">{!! Form::input('number','rate',$item->price, ['class' => 'form-control calcEvent rate', 'id'=>"rate", 'required', 'step' => 'any', 'min' => '1']) !!}
                                        </td>
                                        <td>
                                            <div class="form-group">{!! Form::customSelect('tax',$taxes,$item->tax_id, ['class' => 'form-control calcEvent tax', 'id'=>"tax"]) !!}</div>
                                         </td>
                                        <td class="text-right"><span class="itemTotal">{{ $invoice->totals[$item->id]['itemTotal'] }}</span></td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div><!-- /.col -->
                            <div class="col-md-6"><span id="btn_add_row" class="btn btn-sm btn-info "><i class="fa fa-plus"></i> Add Row</span></div><!-- /.col -->
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th style="width:50%">Subtotal:</th>
                                        <td class="text-right">
                                            <span class="currencySymbol">{{ $invoice->currency }}</span>
                                            <span id="subTotal">{{ $invoice->totals['subTotal'] }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tax:</th>
                                        <td class="text-right">
                                            <span class="currencySymbol">{{ $invoice->currency }}</span>
                                            <span id="taxTotal">{{ $invoice->totals['taxTotal'] }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align: middle">Discount:</th>
                                        <td class="text-right">
                                            <div class="form-group">
                                                {!! Form::input('number','discount', null,['class' => 'form-control text-right calcEvent', 'id' => 'discount', 'step'=>'any', 'min'=>'0']) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td class="text-right">
                                            <span class="currencySymbol">{{ $invoice->currency }}</span>
                                            <span id="grandTotal">{{ $invoice->totals['grandTotal'] }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Paid:</th>
                                        <td class="text-right">
                                            <span class="currencySymbol">{{ $invoice->currency }}</span>
                                            <span id="paidTotal">{{ $invoice->totals['paidFormatted'] }}</span>
                                            {!! Form::hidden('paid',$invoice->totals['paid'], ['id' => 'paidAmount']) !!}
                                        </td>
                                    </tr>
                                    <tr class="amount_due">
                                        <th>Amount Due:</th>
                                        <td class="text-right">
                                            <span class="currencySymbol">{{ $invoice->currency }}</span>
                                            <span id="amountDue">{{ $invoice->totals['amountDue'] }}</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!-- /.col -->

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('notes', 'Notes') !!}
                                    {!! Form::textarea('notes',null, ['class' => 'form-control','rows' =>  '2']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('terms', 'Terms') !!}
                                    {!! Form::textarea('terms',null, ['class' => 'form-control', 'rows' =>  '2', 'id' => 'invoice_terms']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">

                                <button type="submit" class="btn btn-success pull-right" id="saveInvoice"><i class="fa fa-save"></i> Save Invoice</button>
                            </div>
                            {!!  Form::close() !!}
                        </div>
                        </div>
                    </div>
                </div>
            </div>>
        </div>
</section>
@endsection
@section('scripts')
    @include('invoices.partials._invoices_js')
@endsection