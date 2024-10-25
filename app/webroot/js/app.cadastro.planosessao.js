jQuery(document).ready(function () {
    $('#PlanoSessaoIdTipoFinanceiro').select2({
        autocomplete: true,
        width: "100%"
    });

    $('.div-radios').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });

    $("#PlanoSessaoValor").maskMoney({ symbol: "R$", decimal: ",", thousands: "." });

    $("#PlanoSessaoTipoQuantidadeSessoesDeterminar").on('ifChecked', function (event) {
        $('#PainelPlanoSessaoQuantidadeSessoes').show('slow');
        if ($('#PlanoSessaoQuantidadeSessoes').val() == '0') {
            $('#PlanoSessaoQuantidadeSessoes').val('');
        }
        $('#PlanoSessaoQuantidadeSessoes').data("required", true);
        $('#PlanoSessaoQuantidadeSessoes').prop('required', true);
        $('#PlanoSessaoQuantidadeSessoes').prop('min', 1);
    });

    $("#PlanoSessaoTipoQuantidadeSessoesMax").on('ifChecked', function (event) {
        $('#PainelPlanoSessaoQuantidadeSessoes').hide('slow');
        $('#PlanoSessaoQuantidadeSessoes').val('0');
        $('#PlanoSessaoQuantidadeSessoes').data("required", false);
        $('#PlanoSessaoQuantidadeSessoes').prop('required', false);
        $('#PlanoSessaoQuantidadeSessoes').prop('min', 0);
    });

    if ($('#PlanoSessaoTipoQuantidadeSessoesMax').prop("checked")) {
        $('#PlanoSessaoQuantidadeSessoes').val('0');
        $('#PlanoSessaoQuantidadeSessoes').data("required", false);
        $('#PlanoSessaoQuantidadeSessoes').prop('required', false);
        $('#PlanoSessaoQuantidadeSessoes').prop('min', 0);
    }

    jQuery('#PlanoSessaoCadastrarForm').validate({
        onKeyup: true,
        eachInvalidField: function () {
            jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
        },

        eachValidField: function () {
            jQuery(this).closest('div').removeClass('has-error').addClass('has-success');
            if (jQuery(this).val() == '0') {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            }
        }
    });
});