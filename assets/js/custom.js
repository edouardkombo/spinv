$(document).ready(function () {
    $('form').validator();
        var t = $('.datatable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]],
            "bLengthChange": false,
            "bInfo" : false,
            "filter" : false,
            "oLanguage": { "sSearch": ""}
        } );

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

    $('.datepicker').datepicker({format:'yyyy-mm-dd', autoclose:true});
    $(document).ajaxStart(function() { Pace.restart(); });
    $(".chosen").chosen({ width: '100%' });
    $('[data-rel="tooltip"]').tooltip();
    /*======================================
       CUSTOM SCRIPTS
    ========================================*/
    var $modal = $('#ajax-modal');
    $('[data-toggle="ajax-modal"]').bind('click',function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        if (url.indexOf('#') === 0) {
            $('#mainModal').modal('open');
        } else {
            $.get(url, function(data) {
                $modal.modal();
                $modal.html(data);
                $('.datepicker').datepicker({format:'yyyy-mm-dd', autoclose:true });
                $('form').validator().on('submit', function (e) {
                    if (e.isDefaultPrevented()) {
                        return false;
                    }});
                $(".chosen").chosen({ width: '100%' });
            });
        }
    });

    $(document).on('submit', '.ajax-submit', function(e){
        e.preventDefault();
        var $form = $(this),$modal = $form.closest('.modal'),$modalBody = $form.find('.modal-body');
        $modalBody.find('.alert-danger').remove();
        $modal.addClass('spinner');
        $.post($form.attr('action'), $form.serialize(), 'json').done(function(data){
            if(data.errors)
            {
                $modalBody.prepend(data.errors);
                return;
            }
            if(data.debug) return;
            $modal.modal('hide');
            // Refresh page, or redirect if url has been passed
            if(data.url) {
                window.location = data.url;
            } else {
                location.reload();
            }
        }).fail(function(jqXhr, json, errorThrown){
            var errors = jqXhr.responseJSON;
            var errorStr = '';
            $.each( errors, function( key, value ) {
                $('input[name="'+key+'"]').parents('.form-group').addClass("has-error");
                errorStr += '<li class="error">' + value[0] + '</li>';
            });
            var errorsHtml= '<div class="alert alert-danger">\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\
                        <strong>Whoops!</strong> There were some problems with your input. <ul>' + errorStr + '\
                        </ul></div>';
            $modalBody.prepend(errorsHtml);
        }).always(function(){
            $modal.removeClass('spinner');
        });
    });
 /*-----------------------------------------------------------
Delete Button clicked
--------------------------------------------------------------*/
    $(document).on('click', '.btn-delete', function (e){e.preventDefault(); confirm_dialog($(this).parent('form')); });
    function confirm_dialog(form){
        BootstrapDialog.show({
            title: 'Deleting a Record',
            message: 'You are about to delete a record. This action cannot be undone. Do you want to proceed?',
            buttons: [ {
                icon: 'fa fa-check',
                label: ' Yes',
                cssClass: 'btn-success btn-sm',
                action: function(){
                    form.submit();
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
        return false;
    }

    $(".animsition").animsition({
        inClass               :   'fade-in',
        outClass              :   'fade-out',
        inDuration            :    1500,
        outDuration           :    800,
        linkElement           :   '.animsition-link',
        // e.g. linkElement   :   'a:not([target="_blank"]):not([href^=#])'
        loading               :    true,
        loadingParentElement  :   'body', //animsition wrapper element
        loadingClass          :   'animsition-loading',
        unSupportCss          : [ 'animation-duration',
            '-webkit-animation-duration',
            '-o-animation-duration'
        ],
        //"unSupportCss" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
        //The default setting is to disable the "animsition" in a browser that does not support "animation-duration".

        overlay               :   false,
        overlayClass          :   'animsition-overlay-slide',
        overlayParentElement  :   'body'
    });

});
/*-----------------------------------------------------------
 Template select navigation
 --------------------------------------------------------------*/
$(document).on('change', '#template_select', function(){
    window.location.href = this.value;
});

$(document).on('click', '#btn_add_row', function()
{
    cloneRow('item_table');
});
$( document ).on('change', '#currency', function() {
    if ( $(this).val() != '') {
        $( '.currencySymbol' ).text( $( "[name='currency']").val() );
    }
});



      