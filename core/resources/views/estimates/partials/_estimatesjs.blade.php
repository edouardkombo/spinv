<script type="text/javascript">

$(function(){

    $('tr.item select').chosen({width:'100%'});



    $( document ).on("click change paste keyup", ".calcEvent", function() {

        calcTotals();

    });

    $('#terms').wysihtml5({image:false,link:false});

    $(document).on('click', '.delete_row', function(){

        $(this).parents('tr').remove();

        calcTotals();

    });



    $( document ).on('click', '.deleteItem', function() {

        var $this = $(this);

        BootstrapDialog.show({

            title: 'Deleting a Record',

            message: 'You are about to delete a record. This action cannot be undone. Do you want to proceed?',

            buttons: [ {

                icon: 'fa fa-check',

                label: ' Yes',

                cssClass: 'btn-success btn-sm',

                action: function(dialogItself){

                    $.post("{{url('estimates/deleteItem') }}", { "_token": "{{ csrf_token() }}", id : $this.attr('data-id') } , 'json').done(function(data){

                        $this.parents('tr').remove();

                        calcTotals();

                    }).fail(function(jqXhr, json, errorThrown){



                    }).always(function(){

                        dialogItself.close();

                    });

                }

            }, {

                icon: 'fa fa-remove',

                label: 'No',

                cssClass: 'btn-danger btn-sm',

                action: function(dialogItself){

                    dialogItself.close();

                }

            }]

        });

    });



        $( document ).on('change', '.product', function() {

            var productRate = $(this).closest('tr').find("[name='rate']");

            var productPrice = $(this).find("option[value='" + $(this).val() + "']").attr('data-value');

            productRate.val(productPrice);

            calcTotals();

        });



        $('#estimate_form').validator().on('submit', function (e) {

            if (!e.isDefaultPrevented()) {

                // e.preventDefault();

                $('.invoice').find('.alert-danger').remove();

                $('.invoice').find('.loading').fadeIn();

                var $form = $('#estimate_form');

                var data = $('#estimate_form select, input, textarea').not('#product, #tax, #quantity, #rate, #itemId').serializeArray();

                var items = [];

                var item_order = 1;

                $('table tr.item').each(function() {

                    var row = {};

                    $(this).find('input,select,textarea').each(function()

                    {

                        if($(this).attr('name'))

                            row[$(this).attr('name')] = $(this).val();

                    });

                    items.push(row);

                });

                data.push({name : 'items', value: JSON.stringify(items)});

                $.post($form.attr('action'), data , 'json').done(function(data){

                    if(data.errors)

                    {

                        return;

                    }

                    if(data.redirectTo){

                        window.location = data.redirectTo;

                    }else {

                        // location.reload();

                    }

                    // Refresh page, or redirect if url has been passed



                }).fail(function(jqXhr, json, errorThrown){

                    var errors = jqXhr.responseJSON;

                    var errorStr = '';

                    $.each( errors, function( key, value ) {

                        $('#'+key).parents('.form-group').addClass('has-error');

                        $('.'+key).parents('.form-group').addClass('has-error');

                        errorStr += '<li class="error">' + value[0] + '</li>';

                    });

                    var errorsHtml= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops!</strong> There were some problems with your input. <ul>' + errorStr + '</ul></div>';

                    $('.invoice').prepend(errorsHtml);

                }).always(function(){



                    $('.invoice').find('.loading').fadeOut();

                });

                return false;

            }

        });

});



function calcTotals(){

    var subTotal    = 0;

    var total       = 0;

    var totalTax    = 0;



    $('tr.item').each(function(){

        var quantity    = parseFloat($(this).find("[name='quantity']").val());

        var rate        = parseFloat($(this).find("[name='rate']").val());

        var itemTax     = parseFloat($(this).find("[name='tax']").val());

        var itemTotal   = parseFloat(quantity * rate) > 0 ? parseFloat(quantity * rate) : 0;



        var taxValue = $(this).find("[name='tax'] option[value='" + itemTax + "']").attr('data-value');



        subTotal += parseFloat(rate * quantity) > 0 ? parseFloat(rate * quantity) : 0;

        totalTax += parseFloat(rate * quantity * itemTax/100) > 0 ? parseFloat(rate * quantity * taxValue/100) : 0;

        $(this).find(".itemTotal").text( itemTotal.toFixed(2) );



    });

    total    += parseFloat(subTotal+totalTax);

    $( '#subTotal' ).text( subTotal.toFixed(2) );

    $( '#taxTotal' ).text( totalTax.toFixed(2) );

    $( '#grandTotal' ).text( total.toFixed(2) );

}

var count = "1";

function cloneRow(in_tbl_name)

{

    var tbody = document.getElementById(in_tbl_name).getElementsByTagName("tbody")[0];

    // create row

    var row = document.createElement("tr");

    // create table cell 1

    var td1 = document.createElement("td");

    var strHtml1 = "<span class='btn btn-danger btn-xs delete_row'><i class='fa fa-minus'></i></span> ";

    td1.innerHTML = strHtml1.replace(/!count!/g,count);

    // create table cell 2

    var td2 = document.createElement("td");

    var strHtml2 = '<div class="form-group">{!! Form::customSelect('product',$products,null, ['class' => 'form-control product', 'id'=>"product", 'required']) !!}</div>';

    td2.innerHTML = strHtml2.replace(/!count!/g,count);

    // create table cell 3

    var td3 = document.createElement("td");

    var strHtml3 = '<div class="form-group">{!! Form::input('number','quantity',null, ['class' => 'form-control calcEvent quantity', 'id'=>"quantity" , 'required', 'step' => 'any', 'min' => '1']) !!}</div> ';

    td3.innerHTML = strHtml3.replace(/!count!/g,count);

    // create table cell 4

    var td4 = document.createElement("td");

    var strHtml4 = '<div class="form-group">{!! Form::input('number','rate',null, ['class' => 'form-control calcEvent rate', 'id'=>"rate", 'required','step' => 'any', 'min' => '1']) !!}</div> ';

    td4.innerHTML = strHtml4.replace(/!count!/g,count);

    // create table cell 5

    var td5 = document.createElement("td");

    var strHtml5 = '<div class="form-group">{!! Form::customSelect('tax',$taxes,null, ['class' => 'form-control calcEvent tax', 'id'=>"tax"]) !!}</div> ';

    td5.innerHTML = strHtml5.replace(/!count!/g,count);

    // create table cell 6

    var td6 = document.createElement("td");

    var strHtml6 = '<span class="currencySymbol"></span><span class="itemTotal">0.00</span> ';

    td6.innerHTML = strHtml6.replace(/!count!/g,count);

    td6.className = 'text-right';

    // append data to row

    row.appendChild(td1);

    row.appendChild(td2);

    row.appendChild(td3);

    row.appendChild(td4);

    row.appendChild(td5);

    row.appendChild(td6);

    // add to count variable

    count = parseInt(count) + 1;

    // append row to table

    tbody.appendChild(row);

    row.className = 'item';

    $('tr.item:last select').chosen({width:'100%'});

}

</script>

