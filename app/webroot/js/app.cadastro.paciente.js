jQuery(function () {
    //Cadastro de Paciente   
    $('#div-radios').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
    $('#div-radios-sex').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
    $('#div-radios-civil').iCheck({
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

    jQuery('#aba-1,#aba-2,#aba-3').click(function () {
        if (validarDadosBasicos()) {
            jQuery('.barra .active').removeClass('active');
            jQuery(this).closest('li').addClass('active');
            var id = jQuery(this).attr('data-ref');
            jQuery('.tab').hide();
            jQuery('#' + id).show();
        }
    });

    jQuery('#PacienteMaiorIdade1').on('ifChecked', function (event) {
        jQuery("#div-nomeresp1,#div-nomeresp2").removeClass("active");
        jQuery("#div-nomeresp1,#div-nomeresp2").fadeOut();
    });
    jQuery('#PacienteMaiorIdade2').on('ifChecked', function (event) {
        jQuery("#div-nomeresp1,#div-nomeresp2").addClass("active");
        jQuery("#div-nomeresp1,#div-nomeresp2").fadeIn();
    });

    var checkout = jQuery("#PacienteDataNascimento").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkout.hide();
    }).data('datepicker');
    
    var checkout2 = jQuery("#PacienteDataInicioAtendimento").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkout2.hide();
    }).data('datepicker');

    jQuery("#PacienteDataNascimento").mask("99/99/9999");
    jQuery("#PacienteCpf").mask("999.999.999-99");
    jQuery("#PacienteRg").mask("99.999.999-*");
    jQuery("#PacienteTelefoneFixo").mask("(99) 9999-9999");
    jQuery("#PacienteTelefoneCelular").mask("(99) 99999-9999");
    jQuery("#EnderecoCep").mask("99999-999");
    jQuery("#PacienteDataInicioAtendimento").mask("99/99/9999");

    jQuery('#PacienteCadastrarForm').validate({
        onKeyup: true,
        eachInvalidField: function () {
            jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
        },
        eachValidField: function () {
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

});

function validarDadosBasicos() {
    var retorno = true;
    if (jQuery("#PacienteNome").val() === "") {
        jQuery("#PacienteNome").closest('div').addClass('has-error').removeClass('has-success');
        retorno = false;
    } else {
        jQuery("#PacienteNome").closest('div').addClass('has-success').removeClass('has-error');
    }
    if (jQuery("#PacienteSobrenome").val() === "") {
        jQuery("#PacienteSobrenome").closest('div').addClass('has-error').removeClass('has-success');
        retorno = false;
    } else {
        jQuery("#PacienteSobrenome").closest('div').addClass('has-success').removeClass('has-error');
    }
    if (jQuery("#PacienteDataNascimento").val() === "" || jQuery("#PacienteDataNascimento").val() === "__/__/____") {
        jQuery("#PacienteDataNascimento").closest('div').addClass('has-error').removeClass('has-success');
        retorno = false;
    } else {
        jQuery("#PacienteDataNascimento").closest('div').addClass('has-success').removeClass('has-error');
    }
    if (jQuery("#div-nomeresp1,#div-nomeresp2").hasClass("active") && jQuery("#PacienteNomeResponsavel1").val() === "") {
        jQuery("#PacienteNomeResponsavel1").closest('div').addClass('has-error').removeClass('has-success');
        retorno = false;
    } else {
        jQuery("#PacienteNomeResponsavel1").closest('div').addClass('has-success').removeClass('has-error');
    }
    return retorno;
}
//Fim Cadastro de Paciente