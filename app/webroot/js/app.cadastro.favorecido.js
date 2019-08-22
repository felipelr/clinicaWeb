/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(function () {
    //Cadastro de Favorecido
    
    jQuery("#FavorecidoTelefone").mask("(99) 9999-9999");
    jQuery("#EnderecoCep").mask("99999-999");

    $('.radios_check').iCheck({
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });


    jQuery.validateExtend({
        emaill: {
            required: true,
            pattern: /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/
        },
        numeroo: {
            required: true,
            pattern: /^[0-9]+$/
        }
    });
    
    jQuery('#EnderecoCep').blur(function () {
        jQuery.ajax({
            url: $NOME_APLICACAO + '/favorecido/ajax_cep?cep=' + $('#EnderecoCep').val(),
            type: 'POST', /* Tipo da requisição */
            success: function (data) {
                if (data !== null) {
                    var json = jQuery.parseJSON(data);
                    if (json.sucesso === 1) {
                        jQuery('#EnderecoLogradouro').val(json.rua);
                        jQuery('#EnderecoBairro').val(json.bairro);
                        jQuery('#EnderecoCidade').val(json.cidade);
                        jQuery('#EnderecoUf').val(json.estado);
                        jQuery('#EnderecoNumero').focus();
                    }
                }
            }
        });
    });

});

//Fim Cadastro de Favorecido

