/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {

    $('.combos').select2({
        autocomplete: true,
        width: "100%"
    });
    $('.radios_check').iCheck({
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
    $('#div-checks').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        increaseArea: '20%' // optional
    });

    jQuery('#CategoriaDespesaCadastrarForm').validate({
        onKeyup: true,
        eachInvalidField: function () {
            jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
        },
        eachValidField: function () {
            jQuery(this).closest('div').removeClass('has-error').addClass('has-success');

            if (jQuery(this).val() === "__/__/____") {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            }
        }
    });

    $('#RepetirSim, #RepetirNao').iCheck({
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
    $('#RepetirSim, #RepetirNao').on('ifChecked', function (event) {
        $('.div_prazo').toggle($(".lancamento").eq(0).is(':checked'));
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    loadFavorecidoCadastro();
    loadTipoFinanceiroCadastro();
});

//Favorecido
function loadFavorecidoCadastro() {
    jQuery.ajax({
        url: $NOME_APLICACAO + "/favorecido/cadastrar",
        type: 'GET',
        data: {"layout": "ajax"},
        success: function (data, textStatus, jqXHR) {
            if (data !== null) {
                jQuery("#modalBodyFavorecido").html(data);
                jQuery("<script/>", {
                    type: "text/javascript",
                    src: $NOME_APLICACAO + "/js/app.cadastro.favorecido.js"
                }).appendTo("body");

                jQuery("#content-btn-salvar").html('<button onclick="cancelarCadastroFavorecido()" type="button" class="btn btn-danger">Cancelar</button> <button onclick="salvarFavorecidoAjax()" type="button" class="btn btn-primary">Salvar</button> <button id="btn-reset-favorecido" type="reset" style="display: none;"/>');
            }
        },
        error: function () {
        }
    });
}

function openFavorecidoModal() {
    jQuery("#myModalFavorecido").modal("show");
}

function salvarFavorecidoAjax() {
    if (jQuery("#FavorecidoNome").val() !== "") {
        var dataForm = $("#FavorecidoCadastrarForm").serialize();
        jQuery.ajax({
            url: $NOME_APLICACAO + "/favorecido/cadastrar?layout=ajax",
            type: 'POST',
            data: dataForm,
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = jQuery.parseJSON(data);
                    jQuery("<option value='" + json.idfavorecido + "'>" + json.nome + "</option>").appendTo("#CategoriaDespesaIdFavorecido");
                    jQuery('#CategoriaDespesaIdFavorecido').select2({
                        autocomplete: true,
                        width: "100%"
                    });
                }
                jQuery("#btn-reset-favorecido").trigger("click");
                jQuery("#myModalFavorecido").modal("hide");
            },
            error: function () {
            }
        });
    }
}

function cancelarCadastroFavorecido() {
    jQuery("#btn-reset-favorecido").trigger("click");
    jQuery("#myModalFavorecido").modal("hide");
}

//TipoFinanceiro
function loadTipoFinanceiroCadastro() {
    jQuery.ajax({
        url: $NOME_APLICACAO + "/tipo_financeiro/cadastrar",
        type: 'GET',
        data: {"layout": "ajax"},
        success: function (data, textStatus, jqXHR) {
            if (data !== null) {
                jQuery("#modalBodyTipoFinanceiro").html(data);
                jQuery("<script/>", {
                    type: "text/javascript",
                    src: $NOME_APLICACAO + "/js/app.tipo.financeiro.js"
                }).appendTo("body");

                jQuery("#content-btn-salvar-tipo-financeiro").html('<button onclick="cancelarCadastroTipoFinanceiro()" type="button" class="btn btn-danger">Cancelar</button> <button onclick="salvarTipoFinanceiroAjax()" type="button" class="btn btn-primary">Salvar</button> <button id="btn-reset-tipo-financeiro" type="reset" style="display: none;"/>');
            }
        },
        error: function () {
        }
    });
}

function openTipoFinanceiroModal() {
    jQuery("#myModalTipoFinanceiro").modal("show");
}

function salvarTipoFinanceiroAjax() {
    if (jQuery("#TipoFinanceiroDescricao").val() !== "") {
        var dataForm = $("#TipoFinanceiroCadastrarForm").serialize();
        jQuery.ajax({
            url: $NOME_APLICACAO + "/tipo_financeiro/cadastrar?layout=ajax",
            type: 'POST',
            data: dataForm,
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = jQuery.parseJSON(data);
                    jQuery("<option value='" + json.idfinanceirotipo + "'>" + json.tipo + "</option>").appendTo("#CategoriaDespesaIdTipoFinanceiro");
                    jQuery('#CategoriaDespesaIdTipoFinanceiro').select2({
                        autocomplete: true,
                        width: "100%"
                    });
                }
                jQuery("#btn-reset-tipo-financeiro").trigger("click");
                jQuery("#myModalTipoFinanceiro").modal("hide");
            },
            error: function () {
            }
        });
    }
}

function cancelarCadastroTipoFinanceiro() {
    jQuery("#btn-reset-tipo-financeiro").trigger("click");
    jQuery("#myModalTipoFinanceiro").modal("hide");
}

