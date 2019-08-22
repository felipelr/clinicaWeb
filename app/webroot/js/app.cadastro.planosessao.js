/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

    $("#PlanoSessaoValor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});

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

