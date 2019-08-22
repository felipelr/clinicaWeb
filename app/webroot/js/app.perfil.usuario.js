/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    $("#UsuarioCpf").mask("999.999.999-99");
    $("#UsuarioIdTipoUsuario").select2({
        width: "100%"
    });

    $('#CadastroForm').validate({
        onKeyup: true,
        eachInvalidField: function () {
            $(this).closest('div').removeClass('has-success').addClass('has-error');
        },
        eachValidField: function () {
            $(this).closest('div').removeClass('has-error').addClass('has-success');
        }
    });
    
    $('#AlterarSenhaForm').validate({
        onKeyup: true,
        eachInvalidField: function () {
            $(this).closest('div').removeClass('has-success').addClass('has-error');
        },
        eachValidField: function () {
            $(this).closest('div').removeClass('has-error').addClass('has-success');
        }
    });

    jQuery.validateExtend({
        emaill: {
            required: true,
            pattern: /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/
        }
    });
});

