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
        </div>
    </div>
<div class="panel-body">
<div class="row">
<div class="invoice">
    {!! Form::open(['route' => ['invoices.store'], 'id' => 'invoice_form', 'data-toggle'=>"validator", 'role' =>"form"]) !!}
    <div class="col-md-12">
      <div class="text-right"><h1>INVOICE</h1></div>
        <div class="col-md-7" style="padding: 0px">
            <div class="contact to">
                <div class="form-group">
                {!! Form::label('client', 'Client') !!}
                    <div class="input-group col-md-7">
                        {!! Form::select('client',$clients,null, ['class' => 'form-control chosen', 'id' => 'client', 'required']) !!}
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
                    {!! Form::text('number',$invoice_num, ['class' => 'form-control', 'id' => 'number', 'required']) !!}
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
                        <option value="{{ $key }}"> {{ strtoupper($status['label']) }} </option>
                    @endforeach
                </select>
            </div>
        </div>
</div>
<div class="col-md-12">
    <table class="table table-striped" id="item_table">
        <thead style="background: #2e3e4e;color: #fff;">
        <tr>
            <th width="5%"></th>
            <th width="35%">PRODUCT</th>
            <th width="15%" class="text-center">QTY</th>
            <th width="15%" class="text-center">PRICE</th>
            <th width="15%" class="text-center">TAX</th>
            <th width="15%"class="text-right">AMOUNT</th>
        </tr>
        </thead>
        <tbody>
            <tr class="item">
                <td></td>
                <td><div class="form-group">{!! Form::customSelect('product',$products,null, ['class' => 'form-control product', 'id'=>"product", 'required']) !!}</div></td>
                <td><div class="form-group">{!! Form::input('number','quantity',null, ['class' => 'form-control calcEvent quantity', 'id'=>"quantity" , 'required', 'step' => 'any', 'min' => '1']) !!}</div></td>
                <td><div class="form-group">{!! Form::input('number','rate',null, ['class' => 'form-control calcEvent rate', 'id'=>"rate", 'required','step' => 'any', 'min' => '1']) !!}</div></td>
                <td><div class="form-group">{!! Form::customSelect('tax',$taxes,null, ['class' => 'form-control calcEvent tax', 'id'=>"tax"]) !!}</div></td>
                <td class="text-right"><span class="currencySymbol"></span><span class="itemTotal">0.00</span></td>
            </tr>
        </tbody>
    </table>
</div><!-- /.col -->

<div class="col-md-6"><span id="btn_add_row" class="btn btn-sm btn-info "><i class="fa fa-plus"></i> Add Row</span></div><!-- /.col -->
<div class="col-md-6">
    <table class="table">
        <tbody>
        <tr>
            <th style="width:50%">Subtotal</th>
            <td class="text-right">
                <span class="currencySymbol"></span>
                <span id="subTotal">0.00</span>
            </td>
        </tr>
        <tr>
            <th>Tax</th>
            <td class="text-right">
                <span class="currencySymbol"></span>
                <span id="taxTotal">0.00</span>
            </td>
        </tr>
        <tr>
            <th style="vertical-align: middle">Discount</th>
            <td class="text-right">
                <div class="form-group">
                {!! Form::input('number','discount', null,['class' => 'form-control text-right calcEvent', 'id' => 'discount', 'step'=>'any', 'min'=>'0']) !!}
               </div>
            </td>
        </tr>
        <tr class="amount_due">
            <th>Total:</th>
            <td class="text-right">
                <span class="currencySymbol"></span>
                <span id="grandTotal">0.00</span>
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
        {!! Form::textarea('terms',$settings ? $settings->terms : '', ['class' => 'form-control', 'id' => 'invoice_terms', 'rows' =>  '2']) !!}
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

 </div>
</div>
</section>
@endsection
@section('scripts')
    @include('invoices.partials._invoices_js')
@endsection

