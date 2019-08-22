var panelHover = null;
var isclickCompareceu = false;
var currentTimezone = 'America/Sao_Paulo';
var currentLangCode = 'pt-br';
var stringFormat = "yyyy-MM-dd";
var idagenda = $("#IdAgenda").val();
var startDateString = $("#DataAgenda").val();
var res = startDateString.split("/", 3);
var startDate = new Date(res[2], res[1] - 1, res[0], 0, 0, 0);

jQuery(function () {
    //Inicio - componentes gerais
    var checkout = $("#DataAgenda").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkout.hide();
    }).data('datepicker');
    $("#DataAgenda").mask("99/99/9999");
    $("#horaInicioEvento").mask("99:99");
    $("#horaFimEvento").mask("99:99");
    $("#horaFimRepetirEvento").mask("99:99");
    $("#horaInicioEventoExperimetal").mask("99:99");
    $("#horaFimEventoExperimetal").mask("99:99");
    $("#horaInicioEvento2").mask("99:99");
    $("#horaFimEvento2").mask("99:99");
    $("#horaInicioExcluir").mask("99:99");
    $("#horaFimExcluir").mask("99:99");


    $("#ConfiguracaoDataInicioEventos").mask("99/99/9999");
    $(".input-hora-inicio").mask("99:99");
    $(".input-hora-termino").mask("99:99");
    //FIM - componentes gerais

    //INICIO - COMPONENTES MODAL ADICIONAR EVENTO
    var checkinInicio = jQuery("#dataInicioEvento").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinInicio.hide();
    }).data('datepicker');
    var checkinFim = jQuery("#dataFimEvento").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinFim.hide();
    }).data('datepicker');
    var checkinRepetir = jQuery("#dataFimRepetirEvento").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinRepetir.hide();
    }).data('datepicker');
    $('.div-checks input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal',
        increaseArea: '20%' // optional
    });
    $('#EventoRepetirSim').on('ifClicked', function () {
        $("#dataFimRepetirEvento").val($("#dataFimEvento").val());
        $('.hidden-check-repetir').slideDown('slow');
    });
    $('#EventoRepetirNao').on('ifClicked', function () {
        $('.hidden-check-repetir').slideUp('slow');
    });
    jQuery("#dataInicioEvento").mask("99/99/9999");
    jQuery("#dataFimEvento").mask("99/99/9999");
    jQuery("#dataFimRepetirEvento").mask("99/99/9999");
    jQuery("#horaInicioEvento").mask("99:99");
    jQuery("#horaFimEvento").mask("99:99");
    jQuery("#horaFimRepetirEvento").mask("99:99");
    //FIM - COMPONENTES MODAL ADICIONAR EVENTO

    //INICIO - COMPONENTES MODAL ALTERAR EVENTO    
    var checkinInicio2 = jQuery("#dataInicioEvento2").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinInicio2.hide();
    }).data('datepicker');
    var checkinFim2 = jQuery("#dataFimEvento2").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinFim2.hide();
    }).data('datepicker');
    jQuery("#dataInicioEvento2").mask("99/99/9999");
    jQuery("#dataFimEvento2").mask("99/99/9999");
    jQuery("#horaInicioEvento2").mask("99:99");
    jQuery("#horaFimEvento2").mask("99:99");
    $('#EventoTipoRepetir').select2({
        autocomplete: true,
        width: "100%"
    });
    $('#EventoIdPaciente').select2({
        autocomplete: true,
        width: "100%"
    });
    //FIM - COMPONENTES MODAL ALTERAR EVENTO

    //INICIO - COMPONENTES MODAL ADICIONAR EVENTO
    var checkinInicioExperimental = jQuery("#dataInicioEventoExperimetal").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinInicioExperimental.hide();
    }).data('datepicker');
    var checkinFimExperimental = jQuery("#dataFimEventoExperimetal").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinFimExperimental.hide();
    }).data('datepicker');
    var checkinNascimentoExperimental = jQuery("#aulaExperimentalDataNascimento").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinNascimentoExperimental.hide();
    }).data('datepicker');
    jQuery("#dataInicioEventoExperimetal").mask("99/99/9999");
    jQuery("#dataFimEventoExperimetal").mask("99/99/9999");
    jQuery("#aulaExperimentalDataNascimento").mask("99/99/9999");
    jQuery("#horaInicioEventoExperimetal").mask("99:99");
    jQuery("#horaFimEventoExperimetal").mask("99:99");
    jQuery("#aulaExperimentalTelefoneFixo").mask("(99)9999-9999");
    jQuery("#aulaExperimentalTelefoneCelular").mask("(99)99999-9999");

    $('#div-radios-sex-exp').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
    $('#div-radios-civil-exp').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
    //FIM - COMPONENTES MODAL ADICIONAR EVENTO

    /******************************************************
     * INICIO - HANDLE CHANGE PACIENTE SELECIONADO
     *****************************************************/
    $('#EventoIdPaciente').change(function () {
        var idpaciente = this.value;
        $.ajax({
            url: $NOME_APLICACAO + "/agenda/ajax_recebimentos_validos?idpaciente=" + idpaciente,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    $("#EventoIdRecebimento").html(data);
                } else {
                    $("#EventoIdRecebimento").html("<option = value='0'>Selecione</option>");
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Não há recebimentos deste paciente.</div>"
                    });
                }
                $('#EventoIdRecebimento').select2({
                    autocomplete: true,
                    width: "100%"
                });
            },
            error: function () {
                $("#EventoIdRecebimento").html("<option = value='0'>Selecione</option>");
                $('#EventoIdRecebimento').select2({
                    autocomplete: true,
                    width: "100%"
                });

                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'danger',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Falha ao retornar planos de sessão</div>"
                });
            }
        });
    });
    /******************************************************
     * FIM - HANDLE CHANGE PACIENTE SELECIONADO
     *****************************************************/

    /******************************************************
     * INICIO - HANDLE SALVAR EVENTO
     *****************************************************/
    $("#btn-salvar-evento").click(function () {
        if (jQuery('#EventoIdPaciente').val() == 0 && jQuery('#EventoIndisponivelNao').is(":checked")) {
            $.jsPanel({
                selector: "#myModalDayClick",
                paneltype: 'hint',
                theme: 'warning',
                position: {top: 70, right: 0},
                size: {width: 400, height: 'auto'},
                content: "<div style='padding: 20px;'>Selecione um Paciente para concluir.<br/>Ou marque indisponível SIM.</div>"
            });
        } else if (jQuery("#EventoIdRecebimento").val() == 0 && jQuery('#EventoIndisponivelNao').is(":checked")) {
            $.jsPanel({
                selector: "#myModalDayClick",
                paneltype: 'hint',
                theme: 'warning',
                position: {top: 70, right: 0},
                size: {width: 400, height: 'auto'},
                content: "<div style='padding: 20px;'>Selecione um Plano de Sessão para concluir.<br/>Ou marque indisponível SIM.</div>"
            });
        } else {
            var view = $('#calendar').fullCalendar('getView');
            $("#evento-visao-cadastrar").val(view.name);

            var elementform = $("form#form-salvar-evento");
            elementform.ajaxSubmit({
                url: $NOME_APLICACAO + "/agenda/ajax_evento_salvar",
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = $.parseJSON(data);
                        if (json.result) {
                            if (json.mensagem !== undefined) {
                                $.jsPanel({
                                    paneltype: 'hint',
                                    theme: 'success',
                                    position: {top: 70, right: 0},
                                    size: {width: 400, height: 'auto'},
                                    content: "<div style='padding: 20px;'>" + json.mensagem + "</div>"
                                });
                            } else {
                                $.jsPanel({
                                    paneltype: 'hint',
                                    theme: 'success',
                                    position: {top: 70, right: 0},
                                    size: {width: 400, height: 'auto'},
                                    content: "<div style='padding: 20px;'>Evento salvo.</div>"
                                });
                            }

                            if (json.conflito !== undefined) {
                                $.jsPanel({
                                    title: 'Atenção - Conflito de horários',
                                    paneltype: 'modal',
                                    theme: 'warning',
                                    autoclose: false,
                                    controls: {buttons: "closeonly"},
                                    position: {top: 140, right: 0},
                                    size: {width: 500, height: 'auto'},
                                    content: "<div style='padding: 20px;'><h3>" + json.conflito + "</h3></div>"
                                });
                            }

                            if (parseInt(json.repetir) === 1) {
                                $('#calendar').fullCalendar('destroy');
                                renderCalendar();
                            } else {
                                var evento = {
                                    id: json.idevento,
                                    title: json.paciente.nome + ". " + json.descricao,
                                    allDay: json.allday,
                                    start: $.fullCalendar.moment(json.data_inicio),
                                    end: $.fullCalendar.moment(json.data_fim),
                                    color: json.color,
                                    description: json.description
                                };
                                $('#calendar').fullCalendar('renderEvent', evento);
                            }
                        } else {
                            $.jsPanel({
                                paneltype: 'hint',
                                theme: 'danger',
                                position: {top: 70, right: 0},
                                size: {width: 400, height: 'auto'},
                                content: "<div style='padding: 20px;'>" + json.error + "</div>"
                            });
                        }

                        $("#ConfiguracaoConfigurarEvento").iCheck('uncheck');
                        $('#divConfigurarEventos').hide();
                        $("#tabela-eventos").html('<thead>' +
                                '<tr>' +
                                '<th class="text-center">Dia da Semana</th>' +
                                '<th class="text-center">Horário Início</th>' +
                                '<th class="text-center">Horário Término</th>' +
                                '<th class="text-center">Ações</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>' +
                                '<tr class="last-row" data-indice="0">' +
                                '<td>' +
                                '<select name="data[Configuracao][eventos][0][dia]" class="form-control select-dia">' +
                                '<option value="segunda">Segunda-feira</option>' +
                                '<option value="terca">Terça-feira</option>' +
                                '<option value="quarta">Quarta-feira</option>' +
                                '<option value="quinta">Quinta-feira</option>' +
                                '<option value="sexta">Sexta-feira</option>' +
                                '<option value="sabado">Sábado</option>' +
                                '<option value="domingo">Domingo</option>' +
                                '</select>' +
                                '</td>' +
                                '<td>' +
                                '<input name="data[Configuracao][eventos][0][horario_inicio]" type="text" class="form-control input-hora-inicio"/>' +
                                '</td>' +
                                '<td>' +
                                '<input name="data[Configuracao][eventos][0][horario_termino]" type="text" class="form-control input-hora-termino"/>' +
                                '</td>' +
                                '<td class="center">' +
                                '<div>' +
                                '<button type="button" onclick="adicionarEventoDia()" class="btn btn-success center-block btn-add"><i class="fa fa-lg fa-plus"></i></button>' +
                                '<button type="button" onclick="removerEventoDia(this, event)" style="display: none;" class="btn btn-danger center-block btn-remove"><i class="fa fa-lg fa-close"></i></button>' +
                                '</div>' +
                                '</td>' +
                                '</tr>' +
                                '</tbody>');

                        jQuery(".input-hora-inicio").mask("99:99");
                        jQuery(".input-hora-termino").mask("99:99");
                    } else {
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'danger',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>Não foi possível salvar o evento.</div>"
                        });
                    }
                },
                error: function () {
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Não foi possível salvar evento.</div>"
                    });
                }
            });
            $("#btn-salvar-reset").trigger("click");
            $("#myModalDayClick").modal('hide');
        }
    });
    /******************************************************
     * FIM - HANDLE SALVAR EVENTO
     *****************************************************/

    /******************************************************
     * INICIO - HANDLE FECHAR MODAL ADICIONAR EVENTO
     *****************************************************/
    $("#btn-cancelar-evento").click(function () {
        $("#myModalDayClick").modal('hide');
    });
    /******************************************************
     * FIM FECHAR MODAL ADICIONAR EVENTO
     *****************************************************/

    /******************************************************
     * INICIO - HANDLE SALVAR EVENTO EXPERIMENTAL
     *****************************************************/
    $("#btn-salvar-evento-experimental").click(function () {
        var view = $('#calendar').fullCalendar('getView');

        var elementform = $("form#form-salvar-evento-experimental");
        elementform.ajaxSubmit({
            url: $NOME_APLICACAO + "/agenda/ajax_evento_salvar_experimental",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    if (json.result) {
                        if (parseInt(json.repetir) === 1) {
                            $('#calendar').fullCalendar('destroy');
                            renderCalendar();
                        } else {
                            var evento = {
                                id: json.idevento,
                                title: json.paciente.nome + ". " + json.descricao,
                                allDay: json.allday,
                                start: $.fullCalendar.moment(json.data_inicio),
                                end: $.fullCalendar.moment(json.data_fim),
                                color: json.color,
                                description: json.description
                            };
                            $('#calendar').fullCalendar('renderEvent', evento);
                        }

                        if (json.mensagem !== undefined) {
                            $.jsPanel({
                                paneltype: 'hint',
                                theme: 'success',
                                position: {top: 70, right: 0},
                                size: {width: 400, height: 'auto'},
                                content: "<div style='padding: 20px;'>" + json.mensagem + "</div>"
                            });
                        } else {
                            $.jsPanel({
                                paneltype: 'hint',
                                theme: 'success',
                                position: {top: 70, right: 0},
                                size: {width: 400, height: 'auto'},
                                content: "<div style='padding: 20px;'>Evento salvo.</div>"
                            });
                        }
                    } else {
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'danger',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>" + json.error + "</div>"
                        });
                    }
                } else {
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Não foi possível salvar o evento.</div>"
                    });
                }
            },
            error: function () {
                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'danger',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Não foi possível salvar evento.</div>"
                });
            }
        });
        $("#btn-salvar-reset-experimental").click();
        $("#myModalDayClick").modal('hide');
    });
    /******************************************************
     * FIM - HANDLE SALVAR EVENTO EXPERIMENTAL
     *****************************************************/

    /******************************************************
     * INICIO - HANDLE FECHAR MODAL ADICIONAR EVENTO EXPERIMENTAL
     *****************************************************/
    $("#btn-cancelar-evento-experimental").click(function () {
        $("#btn-salvar-reset-experimental").click();
        $("#myModalDayClick").modal('hide');
    });
    /******************************************************
     * FIM FECHAR MODAL ADICIONAR EVENTO EXPERIMENTAL
     *****************************************************/

    /******************************************************
     * INICIO - HANDLE ALTERAR EVENTO
     *****************************************************/
    $("#btn-editar-evento").click(function () {
        var view = $('#calendar').fullCalendar('getView');
        $("#evento-visao-editar").val(view.name);

        var elementform = $("form#form-editar-evento");
        elementform.ajaxSubmit({
            url: $NOME_APLICACAO + "/agenda/ajax_evento_editar",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    if (json.result) {
                        var evento = {
                            id: json.idevento,
                            title: json.paciente.nome + ". " + json.descricao,
                            allDay: json.allday,
                            start: json.data_inicio,
                            end: json.data_fim,
                            color: json.color,
                            description: json.description
                        };

                        //$('#calendar').fullCalendar('updateEvent', evento);
                        $('#calendar').fullCalendar('removeEvents', evento.id);
                        $('#calendar').fullCalendar('renderEvent', evento);
                        $("#idEvento").val('');

                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'success',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>Evento alterado.</div>"
                        });
                    } else {
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'danger',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>" + json.error + "</div>"
                        });
                    }
                } else {
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Não foi possível alterar evento.</div>"
                    });
                }

            },
            error: function () {
                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'danger',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Não foi possível alterar evento.</div>"
                });
            }
        });
        $("#btn-editar-reset").trigger("click");
        $("#myModalEventClick").modal('hide');
    });
    /******************************************************
     * FIM - HANDLE ALTERAR EVENTO
     *****************************************************/

    /******************************************************
     * INICIO - HANDLE EXCLUIR EVENTO
     *****************************************************/
    $("#btn-excluir-evento").click(function () {
        var idEvento = $('#idEvento').val();
        $.ajax({
            url: $NOME_APLICACAO + "/agenda/ajax_evento_excluir/" + idEvento,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    if (json.result) {
                        $('#calendar').fullCalendar('removeEvents', idEvento);
                        $("#idEvento").val('');

                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'success',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>Evento excluído.</div>"
                        });
                    } else {
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'danger',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>" + json.error + "</div>"
                        });
                    }
                } else {
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Não foi possível excluir evento.</div>"
                    });
                }

            },
            error: function () {
                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'danger',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Não foi possível excluir evento.</div>"
                });
            }
        });
        $("#myModalEventClick").modal('hide');
    });
    /******************************************************
     * FIM - HANDLE EXCLUIR EVENTO
     *****************************************************/

    /******************************************************
     * INICIO - HANDLE FECHAR MODAL ALTERAR EVENTO
     *****************************************************/
    $("#btn-cancelar-editar-evento").click(function () {
        $("#myModalEventClick").modal('hide');
    });
    /******************************************************
     * FIM - HANDLE FECHAR MODAL ALTERAR EVENTO
     *****************************************************/

    /******************************************************
     * INICIO - HANDLE PESQUISAR EVENTO POR DATA ESPECIFICA
     ******************************************************/
    $("#btn-pesquisar").click(function () {
        startDateString = $("#DataAgenda").val();
        var res = startDateString.split("/", 3);
        startDate = new Date(res[2], res[1] - 1, res[0], 0, 0, 0);
        $('#calendar').fullCalendar('destroy');
        renderCalendar();
    });
    /******************************************************
     * FIM - HANDLE PESQUISAR EVENTO POR DATA ESPECIFICA
     ******************************************************/


    /******************************************************
     * AÇÕES MODAL EXCLUIR POR PERIODO
     *****************************************************/

    //Inicio - Inicioalizando componentes
    var checkinExcluir = jQuery("#dataInicioExcluir").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkinExcluir.hide();
    }).data('datepicker');

    var checkouExcluir = jQuery("#dataFimExcluir").datepicker({
        format: "dd/mm/yyyy",
        orientation: "top auto"
    }).on('changeDate', function () {
        checkouExcluir.hide();
    }).data('datepicker');

    jQuery("#idPacienteExcluir").select2({
        autocomplete: true,
        width: "100%"
    });
    //Fim - Inicioalizando componentes

    jQuery("#btn-excluir-periodo").click(function () {
        if (jQuery("#dataInicioExcluir").val() !== "" && jQuery("#horaInicioExcluir").val() !== ""
                && jQuery("#dataFimExcluir").val() !== "" && jQuery("#horaFimExcluir").val() !== ""
                && jQuery("#idPacienteExcluir").val() != 0) {
            var dataForm = $("#form-excluir-periodo").serialize();
            jQuery.ajax({
                url: $NOME_APLICACAO + "/agenda/ajax_evento_excluir_periodo",
                type: 'POST',
                data: dataForm,
                success: function (data, textStatus, jqXHR) {
                    var json = jQuery.parseJSON(data);
                    if (json.status) {
                        jQuery("#btn-excluir-periodo-reset").trigger("click");
                        jQuery("#idPacienteExcluir").val(0).trigger("change");
                        jQuery("#myModalExcluirPeriodo").modal("hide");
                        $('#calendar').fullCalendar('destroy');
                        renderCalendar();
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'success',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>" + json.mensagem + "</div>"
                        });
                    } else {
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'danger',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>" + json.mensagem + "</div>"
                        });
                    }
                },
                error: function () {
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Problemas ao excluir.</div>"
                    });
                }
            });
        } else {
            $.jsPanel({
                paneltype: 'hint',
                theme: 'danger',
                position: {top: 70, right: 0},
                size: {width: 400, height: 'auto'},
                content: "<div style='padding: 20px;'>Informe todos os campos.</div>"
            });
        }
    });

    jQuery("#btn-cancelar-excluir-periodo").click(function () {
        jQuery('#ExcluirComparecidosNao').iCheck('check');
        jQuery("#btn-excluir-periodo-reset").trigger("click");
        jQuery("#idPacienteExcluir").val(0).trigger("change");
        jQuery("#myModalExcluirPeriodo").modal("hide");
    });

    /******************************************************
     * FIM - AÇÕES MODAL EXCLUIR POR PERIODO
     *****************************************************/


    /******************************************************
     * AÇÕES MODAL REPOSICAO
     *****************************************************/
    //Inicioalizando componentes
    jQuery("#idPacienteReposicao").select2({
        autocomplete: true,
        width: "100%"
    });
    jQuery("#btn-buscar-aulas").click(function () {
        debugger;
        if (jQuery("#idPacienteReposicao").val() != 0) {
            var dataForm = $("#form-repor-aulas").serialize();
            jQuery.ajax({
                url: $NOME_APLICACAO + "/agenda/ajax_evento_reposicao_aulas",
                type: 'POST',
                data: dataForm,
                success: function (data, textStatus, jqXHR) {
                    $('#conteudo_reposicao').html("");
                    $('#conteudo_reposicao').html(data);
                    jQuery('.data').datepicker({
                        format: "dd/mm/yyyy",
                        orientation: "top auto"
                    }).on('changeDate', function () {
                        checkinExcluir.hide();
                    }).data('datepicker');
                    jQuery(".hora").mask("99:99");
                },
                error: function () {
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Problemas ao repor aula.</div>"
                    });
                }
            });
        } else {
            $.jsPanel({
                paneltype: 'hint',
                theme: 'danger',
                position: {top: 70, right: 0},
                size: {width: 400, height: 'auto'},
                content: "<div style='padding: 20px;'>Informe o paciente.</div>"
            });
        }
    });

    jQuery("#btn-cancelar-reposicao-aula").click(function () {
        jQuery("#myModalReposicaoModal").modal("hide");
    });
    /******************************************************
     * FIM - AÇÕES MODAL REPOSICAO
     *****************************************************/

    $('#ConfiguracaoConfigurarEvento').on('ifClicked', function () {
        if ($('#ConfiguracaoConfigurarEvento').attr('checked')) {
            $('#divConfigurarEventos').hide();
        } else {
            $("#ConfiguracaoDataInicioEventos").val($("#dataInicioEvento").val());
            $('#divConfigurarEventos').slideDown('slow');
        }
    });

    renderCalendar();
    loadPacienteCadastro();
});

/******************************************************
 * HANDLER CLICKCOMPARECEU
 *****************************************************/
function clickCompareceu() {
    var events = $(".check-event.checado");
    var stringcompleta = "";
    jQuery.each(events, function (i, item) {
        stringcompleta = stringcompleta + $(item).val() + ",";
    });
    $.ajax({
        url: $NOME_APLICACAO + "/agenda/ajax_evento_compareceu_oneclick",
        type: 'POST',
        data: {"check": stringcompleta},
        success: function (data, textStatus, jqXHR) {
            if (data !== null) {
                $('#calendar').fullCalendar('destroy');
                renderCalendar();

                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'success',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Evento alterado.</div>"
                });
            } else {
                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'danger',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Problema ao alterar evento.</div>"
                });
            }
        }, error: function () {
            $.jsPanel({
                paneltype: 'hint',
                theme: 'danger',
                position: {top: 70, right: 0},
                size: {width: 400, height: 'auto'},
                content: "<div style='padding: 20px;'>Problema ao alterar evento.</div>"
            });
        }
    });
    $("#btn-confirmar-presencas").hide();
}

/******************************************************
 * INICIO - CONFIGURANDO FULLCALENDAR
 *****************************************************/
var $azul = "#158cba";
var $verde = "#28b62c";
var $vermelho = "#ff4136";
var $laranja = "#ff851b";
var $roxo = "#9e00a6";
var $azul_escuro = "#001f3f";
var $cinza = "#cecece";
var $cyan = "#607D8B";

function renderCalendar() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        eventAfterRender: function (event, element, view) {
            element.draggable();
            element.selectable();
            element.addClass("check-event-" + event.id);
            if (event.color !== $cinza && event.color !== $verde && event.color !== $vermelho) {
                element.find('.fc-time').before($('<input value="' + event.id + '" class="check-event" type="checkbox"/>'));
            }
        },
        eventAfterAllRender: function (view) {
            $('.check-event').iCheck({
                checkboxClass: 'icheckbox_flat',
                radioClass: 'iradio_flat'
            });
            $('.fc-time').css("margin-left", "5px");
            $('.check-event').on('ifChecked', function () {
                $("#btn-confirmar-presencas").show();
                $(this).addClass("checado");
            });
            $('.check-event').on('ifUnchecked', function () {
                $(this).removeClass("checado");
                var check = $(".check-event.checado");
                if (!check.length) {
                    $("#btn-confirmar-presencas").hide();
                }
            });
        },
        timeFormat: 'H:mm',
        timezone: currentTimezone,
        defaultDate: $.format.date(startDate, stringFormat),
        lang: currentLangCode,
        editable: true,
        eventLimit: false, // allow "more" link when too many events
        loading: function (bool) {
            $("#loading").toggle(bool);
        },
        events: {
            url: $NOME_APLICACAO + '/agenda/ajax_eventos',
            type: 'POST',
            timeout: 600000,
            data: {
                idagenda: idagenda
            },
            error: function () {
                alert('there was an error while fetching events!');
            }
        },
        eventResize: function (event, delta, revertFunc) {
            revertFunc();
        },
        eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {
            if (event !== null) {
                //roxo, vermelho ou verde
                if (event.start !== null && event.color !== $roxo && event.color !== $vermelho && event.color !== $verde && event.color !== $cyan) {
                    var r = confirm("Deseja alterar este evento?");
                    if (r == true) {
                        $.ajax({
                            url: $NOME_APLICACAO + "/agenda/ajax_evento_drop",
                            type: 'POST',
                            data: {idevento: event.id, start: event.start.format(), view: view.name},
                            success: function (data, textStatus, jqXHR) {
                                if (data !== null) {
                                    var json = $.parseJSON(data);
                                    if (json.result) {
                                        $.jsPanel({
                                            paneltype: 'hint',
                                            theme: 'success',
                                            position: {top: 70, right: 0},
                                            size: {width: 400, height: 'auto'},
                                            content: "<div style='padding: 20px;'>Evento alterado.</div>"
                                        });
                                    } else {
                                        revertFunc();
                                        $.jsPanel({
                                            paneltype: 'hint',
                                            theme: 'danger',
                                            position: {top: 70, right: 0},
                                            size: {width: 400, height: 'auto'},
                                            content: "<div style='padding: 20px;'>" + json.error + "</div>"
                                        });
                                    }
                                } else {
                                    revertFunc();
                                    $.jsPanel({
                                        paneltype: 'hint',
                                        theme: 'danger',
                                        position: {top: 70, right: 0},
                                        size: {width: 400, height: 'auto'},
                                        content: "<div style='padding: 20px;'>Problema ao alterar evento.</div>"
                                    });
                                }
                            },
                            error: function () {
                                revertFunc();
                                $.jsPanel({
                                    paneltype: 'hint',
                                    theme: 'danger',
                                    position: {top: 70, right: 0},
                                    size: {width: 400, height: 'auto'},
                                    content: "<div style='padding: 20px;'>Problema ao alterar evento.</div>"
                                });
                            }
                        });
                    } else {
                        revertFunc();
                    }
                } else {
                    revertFunc();
                }
            } else {
                revertFunc();
            }
        },
        eventMouseover: function (event, jsEvent, view) {
            if (panelHover !== null && panelHover !== undefined) {
                panelHover.close();
            }
            var offset = jQuery(".check-event-" + event.id).offset();
            var contentOffset = jQuery(".content-wrapper").offset();
            var x = offset.left;
            var y = offset.top + 30;
            if (contentOffset !== undefined) {
                x = x - contentOffset.left - 15;
                y = y - contentOffset.top - 15;
            }
            if (event.description == "") {
                event.description = "<div style='padding:10px;'><p>Não há paciente.</p></div>";
            }
            panelHover = $.jsPanel({
                id: "panelTooltip",
                position: {top: y, left: x},
                selector: "#calendar",
                title: 'Informações do paciente',
                resizable: "disabled",
                bootstrap: 'primary',
                controls: {buttons: "closeonly"},
                size: {width: 300, height: "auto"},
                content: event.description
            });
        },
        eventMouseout: function (event, jsEvent, view) {
            if (panelHover !== null && panelHover !== undefined) {
                panelHover.close();
            }
        },
        dayClick: function (date, jsEvent, view) {
            $('#myModalDayClick').modal({
                backdrop: false
            });

            $("#EventoIdRecebimento").html("<option = value='0'>Selecione</option>");
            $("#EventoIdRecebimento").val(0);
            $('#EventoIdPaciente').val(0);
            $("#EventoIdPlanoSessao, #EventoIdRecebimento").trigger('change');

            var dataT = date.format();
            var dataC = dataT.split("T");
            var dmy = dataC[0].split("-");
            var dataFormatada = "";
            var horaFormatada = "";
            var dataFormatadaFim = "";
            var horaFormatadaFim = "";
            if (dataC[1] !== undefined) {
                var splitHours = dataC[1].split(":");
                dataFormatada = dmy[2] + "/" + dmy[1] + "/" + dmy[0];
                horaFormatada = splitHours[0] + ":" + splitHours[1];
                dataFormatadaFim = dmy[2] + "/" + dmy[1] + "/" + dmy[0];
                horaFormatadaFim = splitHours[0] + ":" + splitHours[1];
            } else {
                dataFormatada = dmy[2] + "/" + dmy[1] + "/" + dmy[0];
                horaFormatada = "06:00";
                dataFormatadaFim = dmy[2] + "/" + dmy[1] + "/" + dmy[0];
                horaFormatadaFim = "06:30";
            }

            $("#dataInicioEvento").val(dataFormatada);
            $("#horaInicioEvento").val(horaFormatada);
            $("#dataFimEvento").val(dataFormatadaFim);
            $("#horaFimEvento").val(horaFormatadaFim);
            $("#dataFimRepetirEvento").val('');
            $("#horaFimRepetirEvento").val('06:00');
            $('#descricaoEvento').val('');

            $("#dataInicioEventoExperimetal").val(dataFormatada);
            $("#horaInicioEventoExperimetal").val(horaFormatada);
            $("#dataFimEventoExperimetal").val(dataFormatadaFim);
            $("#horaFimEventoExperimetal").val(horaFormatadaFim);

            $('.hidden-check-repetir').hide();
            $('#EventoRepetirNao').iCheck('check');
            $('#EventoFixoNao').iCheck('check');

            $("#myModalDayClick").modal('show');
        },
        eventClick: function (calEvent, jsEvent, view) {
            if (!isclickCompareceu) {
                //SE O EVENTO ESTIVER CONCLUIR - ABRIR MODAL PARA REGISTRO DIARIO
                if (calEvent.color == $verde) {
                    $('#AcompanhamentoIdEvento').val(calEvent.id);
                    $('#contentAcompanhamentoDescricao').html('<textarea id="AcompanhamentoDescricao" name="AcompanhamentoFisioterapia[descricao]" rows="12" class="form-control"></textarea>');
                    //$("#AcompanhamentoDescricao").wysihtml5();
                    $("#contentGroupAcompanhamentoDescricao").hide();
                    $.ajax({
                        url: $NOME_APLICACAO + "/agenda/ajax_retornar_fichas_fisioterapia?idevento=" + calEvent.id,
                        type: 'GET',
                        success: function (data, textStatus, jqXHR) {
                            if (data !== null) {
                                var json = $.parseJSON(data);
                                if (json.status) {
                                    $('#AcompanhamentoEventoTitulo').html(json.evento.descricao);
                                    $('#AcompanhamentoEventoDataInicio').html(json.evento.data_inicio);
                                    $('#AcompanhamentoEventoDataFim').html(json.evento.data_fim);
                                    $('#AcompanhamentoEventoPaciente').html(json.paciente.nome + ' ' + json.paciente.sobrenome);
                                    var html = '<option value="0">Nenhuma</option>';
                                    $.each(json.fichas, function (i, item) {
                                        html += '<option value="' + item.ficha_fisioterapia.idfichafisioterapia + '">' + item.ficha_fisioterapia.descricao + '</option>';
                                    });
                                    $('#AcompanhamentoIdFichaFisioterapia').html(html);
                                    $('#AcompanhamentoIdFichaFisioterapia').select2({
                                        width: "100%"
                                    });
                                    $("#myModalSalvarAcompanhamento").modal("show");
                                } else {
                                    $.jsPanel({
                                        paneltype: 'hint',
                                        theme: 'danger',
                                        position: {top: 70, right: 0},
                                        size: {width: 400, height: 'auto'},
                                        content: "<div style='padding: 20px;'>" + json.mensagem + "</div>"
                                    });
                                }
                            }
                        },
                        error: function () {
                            $.jsPanel({
                                paneltype: 'hint',
                                theme: 'danger',
                                position: {top: 70, right: 0},
                                size: {width: 400, height: 'auto'},
                                content: "<div style='padding: 20px;'>Problemas ao retornar fichas de fisioterapia</div>"
                            });
                        }
                    });
                } else {
                    $.ajax({
                        url: $NOME_APLICACAO + "/agenda/ajax_evento_retornar_id/" + calEvent.id,
                        type: 'GET',
                        success: function (data, textStatus, jqXHR) {
                            if (data !== null) {
                                var json = $.parseJSON(data);
                                var evento = {
                                    id: json.idevento,
                                    title: json.descricao,
                                    allDay: json.allday,
                                    start: json.data_inicio,
                                    end: json.data_fim
                                };
                                //$('option[selected="selected"]').attr("selected", false);
                                //$('.paciente-' + json.id_paciente).attr("selected", true);
                                $("#EventoIdPaciente2").val(json.id_paciente).trigger("change");
//                                $('#EventoIdPaciente2').select2({
//                                    width: "100%"
//                                });
                                var dataInicio = evento.start;
                                var dataInicioS = dataInicio.split("T");
                                var dataInicioDate = dataInicioS[0].split("-");
                                var dataFormatadaI = dataInicioDate[2] + "/" + dataInicioDate[1] + "/" + dataInicioDate[0];
                                var horaFormatadaI = dataInicioS[1];

                                var dataFim = evento.end;
                                var dataFimS = dataFim.split("T");
                                var dataFimDate = dataFimS[0].split("-");
                                var dataFormatadaF = dataFimDate[2] + "/" + dataFimDate[1] + "/" + dataFimDate[0];
                                var horaFormatadaF = dataFimS[1];

                                $("#idEvento").val(evento.id);
                                $("#descricaoEvento2").val(evento.title);
                                $("#observacaoEvento2").val(json.observacao);
                                $("#dataInicioEvento2").val(dataFormatadaI);
                                $("#horaInicioEvento2").val(horaFormatadaI);
                                $("#dataFimEvento2").val(dataFormatadaF);
                                $("#horaFimEvento2").val(horaFormatadaF);

                                if (parseInt(json.id_evento_status) === 1) {
                                    $("#EventoIdStatus21").iCheck('check').iCheck('enable');
                                    $("#EventoIdStatus22").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus23").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus24").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus25").iCheck('uncheck').iCheck('enable');
                                } else if (parseInt(json.id_evento_status) === 2) {
                                    $("#EventoIdStatus21").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus22").iCheck('check').iCheck('enable');
                                    $("#EventoIdStatus23").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus24").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus25").iCheck('uncheck').iCheck('enable');
                                } else if (parseInt(json.id_evento_status) === 3) {
                                    $("#EventoIdStatus21").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus22").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus23").iCheck('check').iCheck('enable');
                                    $("#EventoIdStatus24").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus25").iCheck('uncheck').iCheck('enable');
                                } else if (parseInt(json.id_evento_status) === 4) {
                                    $("#EventoIdStatus21").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus22").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus23").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus24").iCheck('check').iCheck('enable');
                                    $("#EventoIdStatus25").iCheck('uncheck').iCheck('enable');
                                } else if (parseInt(json.id_evento_status) === 5) {
                                    $("#EventoIdStatus21").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus22").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus23").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus24").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus25").iCheck('check').iCheck('enable');
                                } else if (parseInt(json.id_evento_status) === 7) {
                                    $("#EventoIdStatus21").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus22").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus23").iCheck('uncheck').iCheck('disable');
                                    $("#EventoIdStatus24").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus25").iCheck('uncheck').iCheck('enable');
                                } else {
                                    $("#EventoIdStatus21").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus22").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus23").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus24").iCheck('uncheck').iCheck('enable');
                                    $("#EventoIdStatus25").iCheck('uncheck').iCheck('enable');
                                }

                                $('#myModalEventClick').modal({
                                    backdrop: false
                                });
                                $("#myModalEventClick").modal('show');


                            }
                        }, error: function () {
                        }
                    });
                }
            }
        }
    });
}
/******************************************************
 * FIM - CONFIGURANDO FULLCALENDAR
 *****************************************************/

/******************************************************
 * INICIO - ABRIR MODAL PACIENTE
 *****************************************************/
function openPacienteModal() {
    jQuery("#myModalPaciente").modal("show");
}
/******************************************************
 * FIM - ABRIR MODAL PACIENTE
 *****************************************************/

/******************************************************
 * INICIO - LOAD TEMPLATE CADASTRO DE PACIENTE
 *****************************************************/
function loadPacienteCadastro() {
    jQuery.ajax({
        url: $NOME_APLICACAO + "/paciente/cadastrar",
        type: 'GET',
        data: {"layout": "ajax"},
        success: function (data, textStatus, jqXHR) {
            if (data !== null) {
                jQuery("#modalBodyPaciente").html(data);
                jQuery("<script/>", {
                    type: "text/javascript",
                    src: $NOME_APLICACAO + "/js/app.cadastro.paciente.js"
                }).appendTo("body");

                jQuery("#PacienteDataNascimento").mask("99/99/9999");
                jQuery("#PacienteCpf").mask("999.999.999-99");
                jQuery("#PacienteRg").mask("99.999.999-*");

                jQuery("#content-btn-salvar-paciente").html('<button onclick="salvarPacienteAjax()" type="button" class="btn btn-primary">Salvar</button> &nbsp; ou &nbsp; <button onclick="cancelarCadastroPaciente()" type="button" class="btn btn-danger">Cancelar</button> <button id="btn-reset-paciente" type="reset" style="display: none;"/> ');
                jQuery(".hidden-modal").hide();
                jQuery(".big-modal").addClass("col-md-12").removeClass("col-md-6");
            }
        },
        error: function () {
        }
    });
}
/******************************************************
 * FIM - LOAD TEMPLATE CADASTRO DE PACIENTE
 *****************************************************/

/******************************************************
 * INICIO - HANDLE SALVAR PACIENTE
 *****************************************************/
function salvarPacienteAjax() {
    if (jQuery("#PacienteNome").val() !== "") {
        var dataForm = $("#PacienteCadastrarForm").serialize();
        jQuery.ajax({
            url: $NOME_APLICACAO + "/paciente/cadastrar?layout=ajax",
            type: 'POST',
            data: dataForm,
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = jQuery.parseJSON(data);
                    jQuery("<option value='" + json.idpaciente + "'>" + json.nome + " " + json.sobrenome + "</option>").appendTo("#EventoIdPaciente");
                    jQuery("<option value='" + json.idpaciente + "'>" + json.nome + " " + json.sobrenome + "</option>").appendTo("#EventoIdPaciente2");
                    jQuery('#EventoIdPaciente, #EventoIdPaciente2').select2({
                        autocomplete: true,
                        width: "100%"
                    });
                }
                jQuery("#btn-reset-paciente").trigger("click");
                jQuery("#myModalPaciente").modal("hide");
            },
            error: function () {
            }
        });
    }
}
/******************************************************
 * FIM - HANDLE SALVAR PACIENTE
 *****************************************************/

//FECHAR MODAL PACIENTE
function cancelarCadastroPaciente() {
    jQuery("#btn-reset-paciente").trigger("click");
    jQuery("#myModalPaciente").modal("hide");
}

//ABRIR MODAL EXLUIR POR PERIODO
function openExcluirPeriodoModal() {
    $('#ExcluirComparecidosNao').iCheck('check');
    jQuery("#myModalExcluirPeriodo").modal("show");
}

//ABRIR MODAL DE REPOSICAO
function openReposicaoModal() {
    jQuery("#myModalReposicaoModal").modal("show");
}

/******************************************************
 * INICIO - HANDLE GERAR REPOSICAO
 *****************************************************/
function gerarReposicao(idEvento) {
    var dataForm = jQuery(".linha" + idEvento).serialize();

    jQuery.ajax({
        url: $NOME_APLICACAO + "/agenda/ajax_evento_confirmar_reposicao",
        type: 'POST',
        data: dataForm,
        success: function (data, textStatus, jqXHR) {
            jQuery('.linha_inteira' + idEvento).hide();
            $('#calendar').fullCalendar('destroy');
            renderCalendar();
        },
        error: function () {
            $.jsPanel({
                paneltype: 'hint',
                theme: 'danger',
                position: {top: 70, right: 0},
                size: {width: 400, height: 'auto'},
                content: "<div style='padding: 20px;'>Problemas ao repor aula.</div>"
            });
        }
    });
}
/******************************************************
 * FIM - HANDLE GERAR REPOSICAO
 *****************************************************/

/******************************************************
 * INICIO - HANDLE NAO COMPARECEU
 *****************************************************/
function naoCompareceu(idEvento) {
    var dataForm = jQuery(".linha" + idEvento).serialize();

    jQuery.ajax({
        url: $NOME_APLICACAO + "/agenda/ajax_evento_nao_compareceu",
        type: 'POST',
        data: dataForm,
        success: function (data, textStatus, jqXHR) {
            jQuery('.linha_inteira' + idEvento).hide();
            $('#calendar').fullCalendar('destroy');
            renderCalendar();
        },
        error: function () {
            $.jsPanel({
                paneltype: 'hint',
                theme: 'danger',
                position: {top: 70, right: 0},
                size: {width: 400, height: 'auto'},
                content: "<div style='padding: 20px;'>Problemas ao marcar não compareceu.</div>"
            });
        }
    });
}
/******************************************************
 * FIM - HANDLE NAO COMPARECEU
 *****************************************************/

/******************************************************
 * INICIO - HANDLE CHANGE FICHA FISIOTERAPIA
 *****************************************************/
function changeFichaFisioterapia(elem) {
    if (elem.value != 0) {
        var idevento = $("#AcompanhamentoIdEvento").val();
        jQuery.ajax({
            url: $NOME_APLICACAO + "/agenda/ajax_retornar_acompanhamento_fisioterapia?idfichafisioterapia=" + elem.value + "&idevento=" + idevento,
            type: 'GET',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    if (json.status && json.acompanhamento.idacompanhamentofisioterapia !== undefined) {
                        $("#AcompanhamentoId").val(json.acompanhamento.idacompanhamentofisioterapia);
                        $('#contentAcompanhamentoDescricao').html('<textarea id="AcompanhamentoDescricao" name="AcompanhamentoFisioterapia[descricao]" rows="12" class="form-control"></textarea>');
                        $("#AcompanhamentoDescricao").html(json.acompanhamento.descricao);
                        //$("#AcompanhamentoDescricao").wysihtml5();
                        $("#contentGroupAcompanhamentoDescricao").slideDown();
                    } else {
                        $("#AcompanhamentoId").val(0);
                        $('#contentAcompanhamentoDescricao').html('<textarea id="AcompanhamentoDescricao" name="AcompanhamentoFisioterapia[descricao]" rows="12" class="form-control"></textarea>');
                        $("#AcompanhamentoDescricao").html('');
                        //$("#AcompanhamentoDescricao").wysihtml5();
                        $("#contentGroupAcompanhamentoDescricao").slideDown();
                    }
                }
            },
            error: function () {
                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'danger',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Problemas retornar registro diário.</div>"
                });
            }
        });
    } else {
        $("#contentGroupAcompanhamentoDescricao").slideUp();
    }
}
/******************************************************
 * FIM - HANDLE CHANGE FICHA FISIOTERAPIA
 *****************************************************/

/******************************************************
 * INICIO - HANDLE SALVAR ACOMPANHAMENTO
 *****************************************************/
function salvarAcompanhamentoFisioterapia() {
    if ($("#AcompanhamentoIdFichaFisioterapia").val() == 0) {
        $.jsPanel({
            paneltype: 'hint',
            theme: 'warning',
            position: {top: 70, right: 0},
            size: {width: 400, height: 'auto'},
            content: "<div style='padding: 20px;'>Selecione uma ficha de fisioterapia.</div>"
        });
    } else {
        var dataForm = $("#formAdiocionarAcompanhamento").serialize();
        jQuery.ajax({
            url: $NOME_APLICACAO + "/agenda/ajax_salvar_acompanhamento_fisioterapia",
            type: 'POST',
            data: dataForm,
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    if (json.status) {
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'success',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>" + json.mensagem + "</div>"
                        });
                    } else {
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'danger',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>" + json.mensagem + "</div>"
                        });
                    }
                }
                $("#myModalSalvarAcompanhamento").modal("hide");
            },
            error: function () {
                $("#myModalSalvarAcompanhamento").modal("hide");
                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'danger',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Problemas retornar registro diário.</div>"
                });
            }
        });
    }
}
/******************************************************
 * FIM - HANDLE SALVAR ACOMPANHAMENTO
 *****************************************************/

/******************************************************
 * INICIO - HANDLE RELATORIO PACIENTE
 *****************************************************/

function openModalRelatorioPaciente() {
    $("#dataDeRelatorio").val("");
    $("#dataAteRelorio").val("");
    $('.checks').iCheck({
        checkboxClass: 'icheckbox_minimal',
        increaseArea: '20%' // optional
    });
    $(".checks").iCheck('check');
    $("#idPacienteRelatorio").select2({
        autocomplete: true,
        width: "100%"
    });
    $("#myModalRelatorioPaciente").modal("show");
}

function gerarRelatorioPaciente() {
    if ($("#idPacienteRelatorio").val() == 0) {
        $.jsPanel({
            paneltype: 'hint',
            theme: 'warning',
            position: {top: 70, right: 0},
            size: {width: 400, height: 'auto'},
            content: "<div style='padding: 20px;'>Por favor selecione um paciente.</div>"
        });
    } else {
        var idagenda = $("#idAgendaRelatorio").val();
        var idpaciente = $("#idPacienteRelatorio").val();
        var statusEvento = "";
        statusEvento += $("#check1").is(":checked") ? "1" : "0";
        statusEvento += $("#check2").is(":checked") ? "1" : "0";
        statusEvento += $("#check3").is(":checked") ? "1" : "0";
        statusEvento += $("#check4").is(":checked") ? "1" : "0";
        statusEvento += $("#check5").is(":checked") ? "1" : "0";

        var url = $NOME_APLICACAO + "/agenda/viewpdf/" + idpaciente + "/" + idagenda + "/" + statusEvento + ".pdf";

        $("#relatorio-link").attr("href", url);
        $("#relatorio-link")[0].click();
        $("#myModalRelatorioPaciente").modal("hide");
    }
}

/******************************************************
 * FIM - HANDLE RELATORIO PACIENTE
 *****************************************************/

function adicionarEventoDia() {
    var lastSelect = jQuery(".last-row td .select-dia").val();
    var lastInput = jQuery(".last-row td .input-hora-inicio").val();
    var lastInputTermino = jQuery(".last-row td .input-hora-termino").val();
    var selects = jQuery(".select-dia");
    var inputs = jQuery(".input-hora-inicio");
    var isValid = true;
    var mensagem = '';

    if (lastInput == '') {
        isValid = false;
        mensagem = 'Nenhum horário informado';
    } else if (selects.length > 1) {
        length = selects.length - 1;
        for (var i = 0; i < length; i++) {
            if (jQuery(selects[i]).val() == lastSelect && jQuery(inputs[i]).val() == lastInput) {
                isValid = false;
                mensagem = 'Dia e horário já selecionado anteriormente';
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
        clone.children("td").children("select").attr("name", "data[Configuracao][eventos][" + indice + "][dia]");
        clone.children("td").children("input.input-hora-inicio").attr("name", "data[Configuracao][eventos][" + indice + "][horario_inicio]");
        clone.children("td").children("input.input-hora-termino").attr("name", "data[Configuracao][eventos][" + indice + "][horario_termino]");
        jQuery("#tabela-eventos tbody").append(clone);
        lastRow.removeClass("last-row");
        lastRow.find("td div .btn-add").hide();
        lastRow.find("td div .btn-remove").show();
        jQuery(".last-row td input").val("");
        jQuery(".input-hora-inicio").mask("99:99");
        jQuery(".input-hora-termino").mask("99:99");
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

function removerEventoDia(event) {
    jQuery(event).parent("div").parent("td").parent("tr").remove();
}