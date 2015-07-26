<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('invoice_id', 'Invoice*') !!}
            @if(isset($invoice))
                {!! Form::select('invoice_id',array($invoice->id => $invoice->number.'( Bal '.$invoice->totals['amountDue'].') |'. $invoice->client->name), $invoice->id, ['class' => 'form-control', 'required']) !!}
            @else
                @if(isset($payment->invoice_id)) <strong> {{ $payment->invoice->number }} </strong> @endif
                {!! Form::select('invoice_id',array(), null, ['class' => 'form-control ajaxChosen', !isset($payment->invoice_id) ? 'required' : '', 'data-placeholder' => 'Type atleast 2 characters of the invoice number']) !!}
            @endif
        </div>
        <div class="form-group">
            {!! Form::label('payment_date', 'Received On*') !!}
            {!! Form::text('payment_date', null, ['class' => 'form-control datepicker', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('method', 'Payment Method*') !!}
            {!! Form::select('method',$methods, null, ['class' => 'form-control chosen']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('amount', 'Amount*') !!}
            {!! Form::input('number','amount',null, ['class' => 'form-control','step'=>'any','min'=>1, 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('notes', 'Notes') !!}
            {!! Form::textarea('notes',null, ['class' => 'form-control', 'rows'=> '5']) !!}
        </div>
    </div>
</div>
<script src="{{ asset('assets/plugins/chosen/chosen.ajaxaddition.jquery.js') }}"></script>

<script type="text/javascript">
    $('.ajaxChosen').ajaxChosen({
        dataType: 'json',
        type: 'POST',
        url:'/search',
        data: {'_token':"{{ csrf_token() }}"}, //Or can be [{'name':'keyboard', 'value':'cat'}]. chose your favorite, it handles both.
        success: function(data, textStatus, jqXHR){  }
    },{
        processItems: function(data){return data;},
        generateUrl: function(q){ return '{{ url("invoices/ajaxSearch") }}' },
        loadingImg: '{{ asset("assets/plugins/chosen/loading.gif") }}',
        minLength: 2
    });
</script>
