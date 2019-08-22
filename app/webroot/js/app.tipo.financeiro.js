jQuery(document).ready(function() {
    $('#div_carencia').toggle();
    $("#link-configuracao").closest('li').addClass('active');
    $("#link-configuracao").next('.sub-custom').show();
    $("#link-tipo-financeiro").closest('li').addClass('active');

    $('.radios_check').iCheck({
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });

    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('.carencia_radio').click(function() {
        $('#div_carencia').toggle($('.carencia_radio').eq(0).is(':checked'));
    });
});

