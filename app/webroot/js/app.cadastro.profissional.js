var optionsCategoria = [];

jQuery(function() {
    //Cadastro de Profissional  
    $("#ProfissionalIdCargo").select2({
        width: "100%"
    });
    
    $("#Usuarios").select2({
        width: "100%"
    });
    
    $('#div-radios-sex').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });

    jQuery("#foto").fileinput({
        initialPreview: [
            "<img src='" + $NOME_APLICACAO + "/img/no-image.gif' class='file-preview-image' alt='The Moon' title='The Moon'>"
        ],
        overwriteInitial: true,
        previewFileType: "image",
        browseClass: "btn btn-success",
        browseLabel: "Pick Image",
        browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
        removeClass: "btn btn-danger",
        removeLabel: "Delete",
        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
        showUpload: false,
        showCaption: false
    });

    jQuery('#aba-1,#aba-2,#aba-3,#aba-4').click(function() {
        if (validarDadosBasicos()) {
            jQuery('.barra .active').removeClass('active');
            jQuery(this).closest('li').addClass('active');
            var id = jQuery(this).attr('data-ref');
            jQuery('.tab').hide();
            jQuery('#' + id).show();
        }
    });

    jQuery('#ProfissionalMaiorIdade1').click(function() {
        jQuery("#div-nomeresp1,#div-nomeresp2").removeClass("active");
        jQuery("#div-nomeresp1,#div-nomeresp2").hide();
    });
    jQuery('#ProfissionalMaiorIdade2').click(function() {
        jQuery("#div-nomeresp1,#div-nomeresp2").addClass("active");
        jQuery("#div-nomeresp1,#div-nomeresp2").show();
    });

    var checkout = jQuery("#ProfissionalDataNascimento").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function() {
        checkout.hide();
    }).data('datepicker');

    var checkout2 = jQuery("#ProfissionalDataAdmissao").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function() {
        checkout2.hide();
    }).data('datepicker');

    var checkout3 = jQuery("#ProfissionalDataDemissao").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function() {
        checkout3.hide();
    }).data('datepicker');

    jQuery("#ProfissionalDataNascimento").mask("99/99/9999");
    jQuery("#ProfissionalCpf").mask("999.999.999-99");
    jQuery("#ProfissionalRg").mask("99.999.999-*");
    jQuery("#ProfissionalTelefoneFixo").mask("(99) 9999-9999");
    jQuery("#ProfissionalTelefoneCelular").mask("(99) 99999-9999");
    jQuery("#EnderecoCep").mask("99999-999");
    jQuery(".input-mask-money").maskMoney({symbol: "R$", decimal: ",", thousands: "."});


    jQuery('#ProfissionalCadastrarForm').validate({
        onKeyup: true,
        eachInvalidField: function() {
            jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
        },
        eachValidField: function() {
            jQuery(this).closest('div').removeClass('has-error').addClass('has-success');

            if (jQuery(this).val() === "__/__/____" || jQuery(this).val() === "___.___.___-__" || jQuery(this).val() === "__.___.___-_" || jQuery(this).val() === "_____-___" || jQuery(this).val() === "") {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            }
        }
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

    jQuery('#EnderecoCep').blur(function() {
        jQuery.ajax({
            url: $NOME_APLICACAO + '/profissional/ajax_cep?cep=' + $('#EnderecoCep').val(),
            type: 'POST', /* Tipo da requisição */
            success: function(data) {
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

    optionsCategoria = jQuery(".last-row td select option");

});

function validarDadosBasicos() {
    var retorno = true;
    if (jQuery("#ProfissionalNome").val() === "") {
        jQuery("#ProfissionalNome").closest('div').addClass('has-error').removeClass('has-success');
        retorno = false;
    } else {
        jQuery("#ProfissionalNome").closest('div').addClass('has-success').removeClass('has-error');
    }
    if (jQuery("#ProfissionalSobrenome").val() === "") {
        jQuery("#ProfissionalSobrenome").closest('div').addClass('has-error').removeClass('has-success');
        retorno = false;
    } else {
        jQuery("#ProfissionalSobrenome").closest('div').addClass('has-success').removeClass('has-error');
    }
    if (jQuery("#ProfissionalDataNascimento").val() === "") {
        jQuery("#ProfissionalDataNascimento").closest('div').addClass('has-error').removeClass('has-success');
        retorno = false;
    } else {
        jQuery("#ProfissionalDataNascimento").closest('div').addClass('has-success').removeClass('has-error');
    }
    if (jQuery("#div-nomeresp1,#div-nomeresp2").hasClass("active") && jQuery("#ProfissionalNomeResponsavel1").val() === "") {
        jQuery("#ProfissionalNomeResponsavel1").closest('div').addClass('has-error').removeClass('has-success');
        retorno = false;
    } else {
        jQuery("#ProfissionalNomeResponsavel1").closest('div').addClass('has-success').removeClass('has-error');
    }
    return retorno;
}

function adicionarComissao() {
    var lastSelect = jQuery(".last-row td .select-categorias").val();
    var lastInput = jQuery(".last-row td .input-mask-money").val();
    var selects = jQuery(".select-categorias");
    var isValid = true;
    var mensagem = '';
    if (lastSelect == 0) {
        isValid = false;
        mensagem = 'Nenhuma categoria selecionada';
    } else if (lastInput == '') {
        isValid = false;
        mensagem = 'Nenhum valor de comissão informado';
    } else if (selects.length > 1) {
        length = selects.length - 1;
        for (var i = 0; i < length; i++) {
            if (jQuery(selects[i]).val() == lastSelect) {
                isValid = false;
                mensagem = 'Categoria já selecionada anteriormente';
                break;
            }
        }
    }

    if (isValid) {
        var lastRow = jQuery(".last-row");
        var indice = parseInt(lastRow.attr("data-indice"));
        var clone = lastRow.clone();
        indice = indice + 1;
        clone.attr("data-indice", indice);
        clone.children("td").children("select").attr("name", "data[CategoriaAula][" + indice + "][idcategoriaaula]");
        clone.children("td").children("input").attr("name", "data[CategoriaAula][" + indice + "][porcentagem]");
        jQuery("#tabela-comissao tbody").append(clone);
        lastRow.removeClass("last-row");
        lastRow.find("td div .btn-add").hide();
        lastRow.find("td div .btn-remove").show();
        jQuery(".last-row td input").val("");
        jQuery(".input-mask-money").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
    } else {
        $.jsPanel({
            paneltype: 'hint',
            theme: 'danger',
            position: {top: 70, right: 0},
            size: {width: 400, height: 'auto'},
            content: "<div style='padding: 20px;'>" + mensagem + "</div>"
        });
    }
}

function removerComissao(event) {
    jQuery(event).parent("div").parent("td").parent("tr").remove();
}
//Fim Cadastro de Profissional