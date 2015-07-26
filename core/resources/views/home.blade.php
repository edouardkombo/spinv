@extends('app')

@section('content')
<div class="col-md-12 content-header" >
    <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clients</span>
                    <span class="info-box-number">{{ $clients }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-file-pdf-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Invoices</span>
                    <span class="info-box-number">{{ $invoices }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-quote-left"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Estimates</span>
                    <span class="info-box-number">{{ $estimates }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-puzzle-piece"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Products</span>
                    <span class="info-box-number">{{ $products }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    </div>
    <div class="row">
        <div class="col-lg-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-usd fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <p>{{ $invoice_stats['partiallyPaid'] }}</p>
                            <p>Invoices Partially Paid</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-3">
            <div class="panel bg-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <p>{{ $invoice_stats['unpaid'] }}</p>
                            <p>Unpaid Invoices</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-3">
            <div class="panel bg-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-times fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <p>{{  $invoice_stats['overdue'] }}</p>
                            <p>Invoices Overdue </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="panel bg-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-check fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <p>{{ $invoice_stats['paid'] }}</p>
                            <p>Invoices Paid</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title">INVOICES GENERATED</h3>
                    <ul class="nav nav-pills ranges pull-right">
                        <li class="active"><a href="#" data-range="7" data-rel="invoices">7 Days</a></li>
                        <li class=""><a href="#" data-range="30" data-rel="invoices">30 Days</a></li>
                        <li class=""><a href="#" data-range="60" data-rel="invoices">60 Days</a></li>
                        <li class=""><a href="#" data-range="90" data-rel="invoices">90 Days</a></li>
                    </ul>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="chart-responsive">
                                <div id="invoices-chart" style="height: 350px;"></div>
                                <div class="alert" id="legend"></div>
                            </div><!-- /.chart-responsive -->
                        </div><!-- /.col -->

                    </div><!-- /.row -->
                </div><!-- ./box-body -->

            </div>
        </div>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title"> RECENT INVOICES</h3>
                </div>
                <div class="box-body">

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Invoice No.</th>
                            <th>Status</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th width="20%">Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentInvoices as $count=>$invoice)
                        <tr>
                            <td>{{ $count+1 }}</td>
                            <td><a href="{{ route('invoices.show', $invoice->id) }}">{{ $invoice->number }}</a> </td>
                            <td><span class="label {{ statuses()[$invoice->status]['class'] }}">{{ ucwords(statuses()[$invoice->status]['label']) }} </span></td>
                            <td><a href="{{  route('clients.show', $invoice->client_id) }}">{{ $invoice->client->name }}</a> </td>
                            <td>{{ $invoice->invoice_date }} </td>
                            <td>{{ $invoice->due_date }} </td>
                            <td>{{ $invoice->currency.''.$invoice->totals['grandTotal'] }} </td>
                            <td>
                                <a href="{!! route('invoices.show',$invoice->id) !!}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View </a>
                                <a href="{!! route('invoices.edit',$invoice->id) !!}" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> Edit </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title"> RECENT ESTIMATES</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Estimate No.</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th width="20%">Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentEstimates as $count=>$estimate)
                        <tr>
                            <td>{{ $count+1 }}</td>
                            <td><a href="{{ route('estimates.show', $estimate->id) }}">{{ $estimate->estimate_no }} </a></td>
                            <td><a href="{{ route('clients.show', $estimate->client_id) }}">{{ $estimate->client->name }}</a> </td>
                            <td>{{ $estimate->estimate_date }} </td>
                            <td>{{ $estimate->currency.''.$estimate->totals['grandTotal'] }} </td>
                            <td>
                                <a href="{!! route('estimates.show',$estimate->id) !!}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View </a>
                                <a href="{!! route('estimates.edit',$estimate->id) !!}" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> Edit </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>
<style>
    .alert{
        padding:5px;
        text-transform: uppercase;
    }
</style>
@endsection
@section('scripts')
<script src="{{ asset('assets/js/raphael.min.js') }}"></script>
<script src="{{ asset('assets/js/morris.min.js') }}"></script>
<script src="{{ asset('assets/js/spin.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/morris.min.css') }}">
<script>
$(function() {
    function requestData(days,category){
        $('#'+category+'-chart').empty();
        var spinTarget = document.getElementById(category+'-chart');
        var spinner = new Spinner().spin(spinTarget);
        $.ajax({
            type: "POST",
            url: "{{ url('reports/ajaxData') }}",
            data: { days: days, chart:category, _token:'{{ csrf_token() }}' }
        })
            .done(function( data ) {
                var total = 0;
               var chart = Morris.Line({
                    element: category+'-chart',
                    data: data.length > 0 ? data : [{"date":0, "value":0}],
                    xkey: 'date',
                    ykeys: ['value'],
                    labels: [category],
                    resize:true
                });

                for (var i = 0; i < data.length; i++) {
                    total += parseFloat(data[i].value);
                }
                $('#legend').html(category + ' in the last <strong>'+days+'</strong> days : <strong>'+total+'</strong>');

            })
            .fail(function() {
                alert( "error occured" );
            }).always(function () {
                spinner.stop();
            });;
    }
    // Request initial data for the past 7 days:
    requestData(7, 'invoices');

    $('ul.ranges a').click(function(e){
        e.preventDefault();

        var el = $(this);
        days = el.attr('data-range');
        chart = el.attr('data-rel');

        requestData(days,chart);

        el.parent().addClass('active');
        el.parent().siblings().removeClass('active');
    });

});
</script>
@endsection

