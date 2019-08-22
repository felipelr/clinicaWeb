/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {

    $('#DespesaIdCaixa').select2({
        autocomplete: true,
        width: "100%"
    });
    $('#DespesaIdTipoFinanceiro').select2({
        autocomplete: true,
        width: "100%"
    });
    $('#DespesaIdFavorecido').select2({
        autocomplete: true,
        width: "100%"
    });
    $('#div-radios').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
    $("#DespesaValor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});

    jQuery('#DespesaAlterarForm').validate({
        onKeyup: true,
        eachInvalidField: function () {
            jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
        },
        eachValidField: function () {
            jQuery(this).closest('div').removeClass('has-error').addClass('has-success');
        }
    });
});
