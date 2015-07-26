@extends('app')


@section('content')

<div class="col-md-12 content-header" >

    <h1><i class="fa fa-line-chart"></i> Reports</h1>

</div>

<link rel="stylesheet" href="{{ asset('assets/css/morris.min.css') }}">

<section class="content">

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">

            <div class="box-header with-border">

                <h3 class="box-title">Invoices Generated</h3>

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

                            <div class="alert legend"></div>

                        </div><!-- /.chart-responsive -->

                    </div><!-- /.col -->



                </div><!-- /.row -->

            </div><!-- ./box-body -->

        </div>

    </div>

</div>



    <div class="row">

        <div class="col-md-12">

            <div class="box box-success">

                <div class="box-header with-border">

                    <h3 class="box-title pull-left">Payments Received</h3>

                    <ul class="nav nav-pills ranges pull-right">

                        <li class="active"><a href="#" data-range="7" data-rel="payments">7 Days</a></li>

                        <li class=""><a href="#" data-range="30" data-rel="payments">30 Days</a></li>

                        <li class=""><a href="#" data-range="60" data-rel="payments">60 Days</a></li>

                        <li class=""><a href="#" data-range="90" data-rel="payments">90 Days</a></li>

                    </ul>

                </div><!-- /.box-header -->

                <div class="box-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="chart-responsive">

                                <div id="payments-chart" style="height: 350px;"></div>

                                <div class="alert legend"></div>

                            </div><!-- /.chart-responsive -->

                        </div><!-- /.col -->



                    </div><!-- /.row -->

                </div><!-- ./box-body -->

            </div>

         </div>

    </div>



    <div class="row">

        <div class="col-md-12">

            <div class="box box-warning">

                <div class="box-header with-border">

                    <h3 class="box-title">Estimates Generated</h3>

                    <ul class="nav nav-pills ranges pull-right">

                        <li class="active"><a href="#" data-range="7" data-rel="estimates">7 Days</a></li>

                        <li class=""><a href="#" data-range="30" data-rel="estimates">30 Days</a></li>

                        <li class=""><a href="#" data-range="60" data-rel="estimates">60 Days</a></li>

                        <li class=""><a href="#" data-range="90" data-rel="estimates">90 Days</a></li>

                    </ul>

                </div><!-- /.box-header -->

                <div class="box-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="chart-responsive">

                                <div id="estimates-chart" style="height: 350px;"></div>

                                <div class="alert legend"></div>

                            </div><!-- /.chart-responsive -->

                        </div><!-- /.col -->



                    </div><!-- /.row -->

                </div><!-- ./box-body -->

            </div>

        </div>

    </div>



    <div class="row">

        <div class="col-md-12">

            <div class="box box-danger">

                <div class="box-header with-border">

                    <h3 class="box-title">Expenses Incurred</h3>

                    <ul class="nav nav-pills ranges pull-right">

                        <li class="active"><a href="#" data-range="7" data-rel="expenses">7 Days</a></li>

                        <li class=""><a href="#" data-range="30" data-rel="expenses">30 Days</a></li>

                        <li class=""><a href="#" data-range="60" data-rel="expenses">60 Days</a></li>

                        <li class=""><a href="#" data-range="90" data-rel="expenses">90 Days</a></li>

                    </ul>

                </div><!-- /.box-header -->

                <div class="box-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="chart-responsive">

                                <div id="expenses-chart" style="height: 350px;"></div>

                                <div class="alert legend"></div>

                            </div><!-- /.chart-responsive -->

                        </div><!-- /.col -->

                    </div><!-- /.row -->

                </div><!-- ./box-body -->

            </div>

        </div>

    </div>



</section>

<style>

    .alert{

        padding:10px;

        text-transform: uppercase;

    }

</style>

@endsection



@section('scripts')

<script src="{{ asset('assets/js/raphael.min.js') }}"></script>

<script src="{{ asset('assets/js/morris.min.js') }}"></script>

<script src="{{ asset('assets/js/spin.min.js') }}"></script>

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

            Morris.Line({

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

            $('#'+category+'-chart').siblings('.legend').html(category + ' in the last <strong>'+days+'</strong> days : <strong>'+total+'</strong>');

        })

        .fail(function() {

            alert( "error occured" );

        }).always(function () {

            // No matter if request is successful or not, stop the spinner

            spinner.stop();

        });;

}

    // Request initial data for the past 7 days:

    requestData(7, 'invoices');

    requestData(7, 'payments');

    requestData(7, 'estimates');

    requestData(7, 'expenses');



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