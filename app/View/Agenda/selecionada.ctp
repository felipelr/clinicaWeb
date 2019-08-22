<?php $this->assign('title', 'Agenda ' . $Profissional['nome'] . " " . $Profissional['sobrenome']) ?>
<script type="text/javascript">
    var _paq = _paq || [];
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function () {
        var u = "//cluster-piwik.locaweb.com.br/";
        _paq.push(['setTrackerUrl', u + 'piwik.php']);
        _paq.push(['setSiteId', 1906]);
        var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
        g.type = 'text/javascript';
        g.async = true;
        g.defer = true;
        g.src = u + 'piwik.js';
        s.parentNode.insertBefore(g, s);
    })();
</script>
<div class="">
    <?php if (isset($Profissional['foto_bytes'])) { ?>
        <img src="data:<?php echo $Profissional['foto_tipo'] ?>;base64,<?php echo $Profissional['foto_bytes'] ?>" class="img-circle pull-left" alt="" width="100" style="margin-right: 20px; min-height: 100px;">
    <?php } else { ?>
        <img src="<?php echo $this->Html->url("/img/no-image.gif"); ?>" class="img-circle pull-left" alt="" width="100" style="margin-right: 20px; min-height: 100px;">
    <?php } ?>
    <h3 class=""><?php echo $Profissional['nome'] . " " . $Profissional['sobrenome'] ?></h3>
    <hr/>

    <form class="form-inline">        
        <input id="IdAgenda" type="hidden" value="<?php echo $Agenda['idagenda']; ?>"/>
        <div class="form-group">
            <label for="DataAgenda" class="control-label">Selecione a data da agenda: </label>
        </div>        
        <div class="form-group">
            <div class="input-group">                                            
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input id="DataAgenda" value="<?php echo $DataAtual ?>" name="data[data_selecionada]" placeholder="dia/mes/ano" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <button id="btn-pesquisar" type="button" class="btn btn-primary"><i class="fa fa-search"></i> Pesquisar</button>            
        </div>
    </form>
    <div class="clearfix">
        <br/>
        <button type="button" id="btn-cadastrar-paciente" class="btn btn-warning" onclick="openPacienteModal()"><i class="fa fa-arrow-right fa-lg"></i> Cadastrar paciente</button>
        <button type="button" id="btn-abrir-modal-excluir" onclick="openExcluirPeriodoModal()" class="btn btn-danger"><i class="fa fa-trash fa-lg"></i> Excluir por periodo</button>
        <button type="button" onclick="openReposicaoModal()" class="btn bg-navy"><i class="fa fa-exchange fa-lg"></i> Agendar Reposição</button>
        <button type="button"  id="btn-confirmar-presencas" class="btn btn-success" onclick="clickCompareceu()" style="display: none;"><i class="fa fa-check"></i> Confirmar presenças</button>
        <div class="pull-right">
            <button type="button"  id="btn-relatorio-pacientes" class="btn bg-aqua" onclick="openModalRelatorioPaciente()" ><i class="fa fa-bar-chart-o"></i> Relatório Paciente</button>
            <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "aula_experimental", "action" => "index")); ?>"><i class="fa fa-download fa-lg"></i> Importar Pacientes</a>
        </div>

    </div>    
    <hr/>
    <br/>
    <div class="">
        <div>
            <div id="loading">
                <h4 id="loading-texto" class="text-center">Carregando...</h4>
            </div>
            <div id="calendar" style="min-height: 600px !important;">

            </div>  
        </div>
    </div>
    <br/><br/>
    <!-- MODAL SALVANDO AGUARDE -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalSalvando" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Aguarde</h4>
                </div>
                <div class="modal-body">
                    <p>Salvando ...</p>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
    <!-- MODAL ADICIONAR EVENTO -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModalDayClick" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Adicionar evento</h4>
                </div>
                <div id="bodyAdicionar" class="modal-body">                    
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li role="presentation" class="active"><a href="#tab1" aria-controls="normal" role="tab" data-toggle="tab">Normal</a></li>
                        <li role="presentation"><a href="#tab2" aria-controls="experimental" role="tab" data-toggle="tab">Experimental</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tab1" style="box-shadow: 0px 1px 3px 1px #E3F2FD;">
                            <div class='' style='padding: 15px;'>
                                <form id='form-salvar-evento' class='form-horizontal'>
                                    <input name="data[Evento][id_agenda]" type="hidden" value="<?php echo $Agenda['idagenda']; ?>"/>
                                    <div class='form-group'>
                                        <label class='col-md-2 control-label'>título:</label>
                                        <div class='col-md-10'>
                                            <input id="descricaoEvento" name='data[Evento][descricao]' class='form-control'/>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 col-xs-12 control-label'>começa:</label>
                                        <div class='col-md-5 col-xs-6'>
                                            <input id='dataInicioEvento' name='data[Evento][data_inicio]' class='form-control' value='' />    
                                        </div>
                                        <div class='col-md-2 col-xs-6'>
                                            <input id='horaInicioEvento' name='data[Evento][hora_inicio]' class='form-control' value='' />    
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 col-xs-12 control-label'>termina:</label>
                                        <div class='col-md-5 col-xs-6'>
                                            <input id='dataFimEvento' name='data[Evento][data_fim]' class='form-control' value='' />    
                                        </div>
                                        <div class='col-md-2 col-xs-6'>
                                            <input id='horaFimEvento' name='data[Evento][hora_fim]' class='form-control' value='' />    
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="EventoIdPaciente" class="control-label col-md-2">paciente:</label>
                                        <div class="col-md-10">
                                            <select id="EventoIdPaciente" name="data[Evento][id_paciente]" class="form-control">
                                                <option value="0">Nenhum</option>
                                                <?php
                                                $total_ = count($Pacientes);
                                                if ($total_ > 0):
                                                    for ($i_ = 0; $i_ < $total_; $i_++) :
                                                        ?>
                                                        <option value="<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>"><?php echo $Pacientes[$i_]["p"]["nome"] . ' ' . $Pacientes[$i_]["p"]["sobrenome"]; ?></option>
                                                        <?php
                                                    endfor;
                                                endif;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="content-plano-sessao" class="form-group">
                                        <label for="EventoIdRecebimento" class="control-label col-md-2">recebimento:</label>
                                        <div class="col-md-10">
                                            <select id="EventoIdRecebimento" name="data[Evento][id_recebimento]" class="form-control">
                                                <option value="0">Selecione</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">evento fixo ?</label>
                                        <div class="col-md-10 div-checks">                                    
                                            <label for="EventoFixoNao" class="text-danger">Não</label>
                                            <input id="EventoFixoNao" type="radio" name="data[Evento][fixo]" value="0" checked/> 
                                            <label for="EventoFixoSim" class="text-primary">Sim</label>
                                            <input id="EventoFixoSim" type="radio" name="data[Evento][fixo]" value="1" /> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">indisponível ?</label>
                                        <div class="col-md-10 div-checks">                                    
                                            <label for="EventoIndisponivelNao" class="text-danger">Não</label>
                                            <input id="EventoIndisponivelNao" type="radio" name="data[Evento][indisponivel]" value="0" checked/> 
                                            <label for="EventoIndisponivelSim" class="text-primary">Sim</label>
                                            <input id="EventoIndisponivelSim" type="radio" name="data[Evento][indisponivel]" value="1" /> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">repetir ?</label>
                                        <div class="col-md-10 div-checks">                                    
                                            <label for="EventoRepetirNao" class="text-danger">Não</label>
                                            <input id="EventoRepetirNao" type="radio" name="data[Evento][repetir]" value="0" checked/> 
                                            <label for="EventoRepetirSim" class="text-primary">Sim</label>
                                            <input id="EventoRepetirSim" type="radio" name="data[Evento][repetir]" value="1" />
                                        </div>
                                    </div>
                                    <div class="form-group hidden-check-repetir" style="display: none;">
                                        <label class="control-label col-md-2">todo</label>
                                        <div class="col-md-10 div-checks">
                                            <label for="EventoRepetirDia" class="radio-inline">dia</label>
                                            <input id="EventoRepetirDia" type="radio" name="data[Evento][tipo_repetir]" value="dia" checked/>

                                            <label for="EventoRepetirSemana" class="radio-inline">semana</label>
                                            <input id="EventoRepetirSemana" type="radio" name="data[Evento][tipo_repetir]" value="semana"/>

                                            <label for="EventoRepetirMes" class="radio-inline">mês</label>
                                            <input id="EventoRepetirMes" type="radio" name="data[Evento][tipo_repetir]" value="mes"/>

                                            <label for="EventoRepetirAno" class="radio-inline">ano</label>
                                            <input id="EventoRepetirAno" type="radio" name="data[Evento][tipo_repetir]" value="ano"/>
                                        </div>                                        
                                    </div>
                                    <div class="form-group hidden-check-repetir">
                                        <label class="control-label col-xs-12 col-md-2">até</label>
                                        <div class="col-md-5 col-xs-6">                                    
                                            <input id="dataFimRepetirEvento" name="data[Evento][data_fim_repetir]" class="form-control"/>  
                                        </div>
                                        <div class="col-md-2 col-xs-6">                                    
                                            <input id="horaFimRepetirEvento" name="data[Evento][hora_fim_repetir]" class="form-control"/>  
                                        </div>
                                    </div>
                                    <div class="form-group hidden-check-repetir" style="display: none;">
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-2">configuração</label>
                                            <div class="col-md-10 div-checks">
                                                <input id="ConfiguracaoConfigurarEvento" type="checkbox" name="data[Configuracao][configurarEvento]" value="1" /> Utilizar configuração de eventos
                                            </div>
                                        </div>
                                        <div id="divConfigurarEventos" style="display: none;">
                                            <div class="form-group">
                                                <label for="ConfiguracaoDataInicioEventos" class="control-label col-md-2">Data Início Eventos:</label>
                                                <div class="col-md-10">
                                                    <input id="ConfiguracaoDataInicioEventos" name="data[Configuracao][data_inicio_eventos]" placeholder="__/__/____" class="form-control" />
                                                </div>                        
                                            </div>
                                            <div class="form-group ">
                                                <label class="control-label col-md-2">Conflito de horário:</label>
                                                <div class="col-md-10 div-checks" style="padding-top: 5px">                   
                                                    <input name="data[Configuracao][conflito]" class="radios_check" type="radio" value="1" id="ConfiguracaoConflito1" checked />
                                                    <label for="ConfiguracaoConflito1" style="font-weight: normal;">Permitir eventos no mesmo horário</label>
                                                    &nbsp;&nbsp;
                                                    <input name="data[Configuracao][conflito]"  class="radios_check" type="radio" value="0" id="ConfiguracaoConflito0" />
                                                    <label for="ConfiguracaoConflito0" style="font-weight: normal;">Adiar para semana seguinte</label>
                                                </div>                        
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-2">Evento fixo?</label>
                                                <div class="col-md-10 div-checks" style="padding-top: 5px">                   
                                                    <input name="data[Configuracao][fixo]" class="radios_check" type="radio" value="1" id="ConfiguracaoEventoFixoSim"  />
                                                    <label for="ConfiguracaoEventoFixoSim" style="font-weight: normal;">Sim</label>
                                                    &nbsp;&nbsp;
                                                    <input name="data[Configuracao][fixo]"  class="radios_check" type="radio" value="0" id="ConfiguracaoEventoFixoNao" checked/>
                                                    <label for="ConfiguracaoEventoFixoNao" style="font-weight: normal;">Não</label>
                                                </div>                        
                                            </div>
                                            <div class="">
                                                <table id='tabela-eventos' class='table table-responsive table-bordered'>
                                                    <thead>
                                                        <tr>
                                                            <th class='text-center'>Dia da Semana</th>
                                                            <th class='text-center'>Horário Início</th>
                                                            <th class='text-center'>Horário Término</th>
                                                            <th class='text-center'>Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="last-row" data-indice="0">
                                                            <td>
                                                                <select name="data[Configuracao][eventos][0][dia]" class="form-control select-dia">
                                                                    <option value="segunda">Segunda-feira</option>
                                                                    <option value="terca">Terça-feira</option>
                                                                    <option value="quarta">Quarta-feira</option>
                                                                    <option value="quinta">Quinta-feira</option>
                                                                    <option value="sexta">Sexta-feira</option>
                                                                    <option value="sabado">Sábado</option>
                                                                    <option value="domingo">Domingo</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input name="data[Configuracao][eventos][0][horario_inicio]" type="text" class="form-control input-hora-inicio"/>
                                                            </td>
                                                            <td>
                                                                <input name="data[Configuracao][eventos][0][horario_termino]" type="text" class="form-control input-hora-termino"/>
                                                            </td>
                                                            <td class="center">
                                                                <div>
                                                                    <button type="button" onclick="adicionarEventoDia()" class="btn btn-success center-block btn-add"><i class="fa fa-lg fa-plus"></i></button>
                                                                    <button type="button" onclick="removerEventoDia(this, event)" style="display: none;" class="btn btn-danger center-block btn-remove"><i class="fa fa-lg fa-close"></i></button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </form>                                
                            </div>
                            <div class="modal-footer">
                                <button id='btn-salvar-evento' class='btn btn-success' type='button'><i class="fa fa-save fa-lg"></i> Adicionar</button>
                                &nbsp; ou &nbsp;
                                <button id='btn-cancelar-evento' class='btn btn-white' type='button'>Cancelar</button>
                                <button id="btn-salvar-reset" class="hidden" type="reset"></button>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab2" style="box-shadow: 0px 1px 3px 1px #E3F2FD;">
                            <div class='' style='padding: 15px;'>
                                <form id='form-salvar-evento-experimental' class='form-horizontal'>
                                    <input name="data[Evento][id_agenda]" type="hidden" value="<?php echo $Agenda['idagenda']; ?>"/>
                                    <div class='form-group'>
                                        <label class='col-md-2 control-label'>título:</label>
                                        <div class='col-md-10'>
                                            <input id="descricaoEventoExperimetal" name='data[Evento][descricao]' class='form-control'/>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 col-xs-12 control-label'>começa:</label>
                                        <div class='col-md-5 col-xs-6'>
                                            <input id='dataInicioEventoExperimetal' name='data[Evento][data_inicio]' class='form-control' value='' />    
                                        </div>
                                        <div class='col-md-2 col-xs-6'>
                                            <input id='horaInicioEventoExperimetal' name='data[Evento][hora_inicio]' class='form-control' value='' />    
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 col-xs-12 control-label'>termina:</label>
                                        <div class='col-md-5 col-xs-6'>
                                            <input id='dataFimEventoExperimetal' name='data[Evento][data_fim]' class='form-control' value='' />    
                                        </div>
                                        <div class='col-md-2 col-xs-6'>
                                            <input id='horaFimEventoExperimetal' name='data[Evento][hora_fim]' class='form-control' value='' />    
                                        </div>
                                    </div>
                                    <hr/>
                                    <h4><i class="fa fa-user"></i> Paciente</h4>
                                    <div class='form-group'>
                                        <label class='col-md-2 control-label'>Nome:</label>
                                        <div class='col-md-10'>
                                            <input id="aulaExperimentalNome" name='data[AulaExperimental][nome]' class='form-control'/>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 control-label'>Sobrenome:</label>
                                        <div class='col-md-10'>
                                            <input id="aulaExperimentalSobrenome" name='data[AulaExperimental][sobrenome]' class='form-control'/>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 control-label'>Data de Nascimento:</label>
                                        <div class='col-md-10'>
                                            <input id="aulaExperimentalDataNascimento" name='data[AulaExperimental][data_nascimento]' class='form-control'/>
                                        </div>
                                    </div>
                                    <div class="form-group " id="div-radios-sex-exp">
                                        <label class="control-label col-md-2">*Sexo:</label>                    
                                        <input name="data[AulaExperimental][sexo]" type="radio" value="M" id="AulaExperimentalSexoM" checked/>
                                        <label for="AulaExperimentalSexoM">Masculino</label>
                                        <input name="data[AulaExperimental][sexo]" type="radio" value="F" id="AulaExperimentalSexoF" />
                                        <label for="AulaExperimentalSexoF">Feminino</label>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 control-label'>Email:</label>
                                        <div class='col-md-10'>
                                            <input id="aulaExperimentalDataEmail" name='data[AulaExperimental][email]' type="email" class='form-control'/>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 control-label'>Telefone Fixo:</label>
                                        <div class='col-md-10'>
                                            <input id="aulaExperimentalTelefoneFixo" name='data[AulaExperimental][telefone_fixo]' class='form-control'/>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='col-md-2 control-label'>Celular:</label>
                                        <div class='col-md-10'>
                                            <input id="aulaExperimentalTelefoneCelular" name='data[AulaExperimental][telefone_celular]' class='form-control'/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="div-radios-civil-exp">
                                        <label class="control-label col-md-2">Estado Civil:</label>
                                        <input name="data[AulaExperimental][estado_civil]" type="radio" value="Solteiro" id="AulaExperimentalEstadoCivilSolteiro" checked/>
                                        <label for="AulaExperimentalEstadoCivilSolteiro">Solteiro</label>
                                        <input name="data[AulaExperimental][estado_civil]" type="radio" value="Casado" id="AulaExperimentalEstadoCivilCasado" />
                                        <label for="AulaExperimentalEstadoCivilCasado">Casado</label>
                                    </div>
                                    <button id="btn-salvar-reset-experimental" class="hidden" type="reset"></button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button id='btn-salvar-evento-experimental' class='btn btn-success' type='button'><i class="fa fa-save fa-lg"></i> Adicionar</button>
                                &nbsp; ou &nbsp;
                                <button id='btn-cancelar-evento-experimental' class='btn btn-white' type='button'>Cancelar</button>                                
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
    <!-- MODAL ATUALIZAR EVENTO -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModalEventClick" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Atualizar evento</h4>
                </div>
                <div id="bodyAlterar" class="modal-body">
                    <div class='' style='padding: 15px;'>
                        <form id='form-editar-evento' class='form-horizontal'>
                            <input name="data[Evento][id_agenda]" type="hidden" value="<?php echo $Agenda['idagenda']; ?>"/>
                            <input id="idEvento" name="data[Evento][idevento]" type="hidden" value=""/>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>título:</label>
                                <div class='col-md-10'>
                                    <input id="descricaoEvento2" name='data[Evento][descricao]' class='form-control'/>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 col-xs-12 control-label'>começa:</label>
                                <div class='col-md-5 col-xs-6'>
                                    <input id='dataInicioEvento2' name='data[Evento][data_inicio]' class='form-control' value='' />    
                                </div>
                                <div class='col-md-2 col-xs-6'>
                                    <input id='horaInicioEvento2' name='data[Evento][hora_inicio]' class='form-control' value='' />    
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 col-xs-12 control-label'>termina:</label>
                                <div class='col-md-5 col-xs-6'>
                                    <input id='dataFimEvento2' name='data[Evento][data_fim]' class='form-control' value='' />    
                                </div>
                                <div class='col-md-2 col-xs-6'>
                                    <input id='horaFimEvento2' name='data[Evento][hora_fim]' class='form-control' value='' />    
                                </div>
                            </div>

                            <div class="form-group hidden">
                                <label for="EventoIdPaciente2" class="control-label col-md-2">paciente:</label>
                                <div class="col-md-10">
                                    <select id="EventoIdPaciente2" name="data[Evento][id_paciente]" class="form-control" title="Paciente">
                                        <option value="0">Nenhum</option>
                                        <?php
                                        $total_ = count($Pacientes);
                                        if ($total_ > 0):
                                            for ($i_ = 0; $i_ < $total_; $i_++) :
                                                ?>
                                                <option class="paciente-<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>" value="<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>"><?php echo $Pacientes[$i_]["p"]["nome"] . ' ' . $Pacientes[$i_]["p"]["sobrenome"]; ?></option>
                                                <?php
                                            endfor;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='col-md-2 control-label'>status:</label>
                                <div class="col-md-10 div-checks">
                                    <label for="EventoIdStatus21" class="text-primary">
                                        <input name="data[Evento][id_evento_status]" type="radio" value="1" id="EventoIdStatus21" />
                                        aguardando
                                    </label>
                                    <label for="EventoIdStatus22" class="text-danger">
                                        <input name="data[Evento][id_evento_status]" type="radio" value="2" id="EventoIdStatus22" />
                                        não compareceu
                                    </label>
                                    <label for="EventoIdStatus23" class="text-warning">
                                        <input name="data[Evento][id_evento_status]" type="radio" value="3" id="EventoIdStatus23" />
                                        adiou
                                    </label>                                        
                                    <label for="EventoIdStatus24" class="text-roxo">
                                        <input name="data[Evento][id_evento_status]" type="radio" value="4" id="EventoIdStatus24" />
                                        adiou novamente
                                    </label>                                        
                                    <label for="EventoIdStatus25" class="text-success">
                                        <input name="data[Evento][id_evento_status]" type="radio" value="5" id="EventoIdStatus25" />
                                        compareceu
                                    </label>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Observação:</label>
                                <div class='col-md-10'>
                                    <input id="observacaoEvento2" name='data[Evento][observacao]' class='form-control'/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id='btn-editar-evento' class='btn btn-success' type='button'><i class="fa fa-refresh fa-lg"></i> Atualizar</button>
                    &nbsp; ou &nbsp;
                    <button id='btn-excluir-evento' class='btn btn-danger' type='button'><i class="fa fa-trash fa-lg"></i></button>
                    <button id='btn-cancelar-editar-evento' class='btn btn-white' type='button'>Cancelar</button>
                    <button id="btn-editar-reset" class="hidden" type="reset"></button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->

    <!-- MODAL SALVAR PACIENTE -->
    <div class="modal fade" id="myModalPaciente" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Paciente</h4>
                </div>
                <div class="modal-body">
                    <div id="modalBodyPaciente" style="padding-left: 15px; padding-right: 15px;">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EXCLUIR POR PERIODO -->
    <div class="modal fade" id="myModalExcluirPeriodo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Excluir por periodo</h4>
                </div>
                <div class="modal-body">
                    <form id="form-excluir-periodo" class='form-horizontal'>
                        <input type="hidden" name='data[id_agenda]' value="<?php echo $Agenda['idagenda']; ?>"/>
                        <div class="form-group">
                            <label for="idPacienteExcluir" class="control-label col-md-3">paciente:</label>
                            <div class="col-md-9">
                                <select id="idPacienteExcluir" name="data[id_paciente]" class="form-control" title="Paciente">
                                    <option value="0">Nenhum</option>
                                    <?php
                                    $t_ = count($PacientesExcluirEventos);
                                    if ($t_ > 0):
                                        for ($i_ = 0; $i_ < $t_; $i_++) :
                                            ?>
                                            <option class="paciente-<?php echo $PacientesExcluirEventos[$i_]["p"]["idpaciente"]; ?>" value="<?php echo $PacientesExcluirEventos[$i_]["p"]["idpaciente"]; ?>"><?php echo $PacientesExcluirEventos[$i_]["p"]["nome"] . ' ' . $PacientesExcluirEventos[$i_]["p"]["sobrenome"]; ?></option>
                                            <?php
                                        endfor;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class='col-md-3 col-xs-12 control-label'>começa:</label>
                            <div class='col-md-4 col-xs-6'>
                                <input id='dataInicioExcluir' name='data[data_inicio]' class='form-control' value='' />    
                            </div>
                            <div class='col-md-3 col-xs-6'>
                                <input id='horaInicioExcluir' name='data[hora_inicio]' class='form-control' value='00:00' />    
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class='col-md-3 col-xs-12 control-label'>termina:</label>
                            <div class='col-md-4 col-xs-6'>
                                <input id='dataFimExcluir' name='data[data_fim]' class='form-control' value='' />    
                            </div>
                            <div class='col-md-3 col-xs-6'>
                                <input id='horaFimExcluir' name='data[hora_fim]' class='form-control' value='00:00' />    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">excluir comparecidos?</label>
                            <div class="col-md-9 div-checks">                                    
                                <label for="ExcluirComparecidosNao" class="text-danger">Não</label>
                                <input id="ExcluirComparecidosNao" type="radio" name="data[excluir_comparecido]" value="0" checked/> 
                                <label for="ExcluirComparecidosSim" class="text-primary">Sim</label>
                                <input id="ExcluirComparecidosSim" type="radio" name="data[excluir_comparecido]" value="1" /> 
                            </div>
                        </div>
                        <button id="btn-excluir-periodo-reset" class="hidden" type="reset"></button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id='btn-excluir-periodo' class='btn btn-danger' type='button'><i class="fa fa-trash fa-lg"></i> Excluir</button>
                    &nbsp; ou &nbsp;
                    <button id='btn-cancelar-excluir-periodo' class='btn btn-white' type='button'>Cancelar</button>                    
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REPOSICAO -->
    <div class="modal fade" id="myModalReposicaoModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Reposição de Aulas</h4>
                </div>
                <div class="modal-body">
                    <form id="form-repor-aulas" class='form-horizontal'>
                        <input type="hidden" name='data[id_agenda]' value="<?php echo $Agenda['idagenda']; ?>"/>
                        <div class="form-group">
                            <label for="idPacienteRepor" class="control-label col-md-2">paciente:</label>
                            <div class="col-md-10">
                                <select id="idPacienteReposicao" name="data[id_paciente]" class="form-control" title="Paciente">
                                    <option value="0">Nenhum</option>
                                    <?php
                                    $t_ = count($Pacientes);
                                    if ($t_ > 0):
                                        for ($i_ = 0; $i_ < $t_; $i_++) :
                                            ?>
                                            <option class="paciente-<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>" value="<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>"><?php echo $Pacientes[$i_]["p"]["nome"] . ' ' . $Pacientes[$i_]["p"]["sobrenome"]; ?></option>
                                            <?php
                                        endfor;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div style="text-align: center">
                            <button id='btn-buscar-aulas' class='btn btn-primary' type='button'><i class="fa fa-search fa-lg"></i> Buscar</button>
                        </div>
                        <div class="form-group" id="conteudo_reposicao">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">                    
                    <button id='btn-cancelar-reposicao-aula' class='btn btn-white' type='button'>Cancelar</button>                    
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REGISTRO DIARIO -->
    <div class="modal fade" id="myModalSalvarAcompanhamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Registro diário do atendimento</h4>
                </div>
                <div class="modal-body">  
                    <form id="formAdiocionarAcompanhamento" action="<?php echo $this->Html->url(array("controller" => "agenda", "action" => "adicionar_registro_diario")); ?>" method="post">
                        <input id="AcompanhamentoId" value="0" name="AcompanhamentoFisioterapia[idacompanhamentofisioterapia]" type="hidden"/>
                        <input id="AcompanhamentoIdEvento" value="0" name="AcompanhamentoFisioterapia[id_evento]" type="hidden"/>

                        <div class="bg-success" style="padding: 10px;">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Paciente</label>
                                        <p id="AcompanhamentoEventoPaciente"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Titulo</label>
                                        <p id="AcompanhamentoEventoTitulo"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Data de inicio</label>
                                        <p id="AcompanhamentoEventoDataInicio"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Data de término</label>
                                        <p id="AcompanhamentoEventoDataFim"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="padding: 10px;">
                            <div class="form-group">
                                <label class="control-label">Selecione a ficha de fisioterapia</label>
                                <select onchange="changeFichaFisioterapia(this)" id="AcompanhamentoIdFichaFisioterapia" name="AcompanhamentoFisioterapia[id_ficha_fisioterapia]" class="form-control" title="Ficha Fisioterapia">
                                    <option value="0">Nenhuma</option>
                                </select>
                            </div>
                            <div class="form-group" id="contentGroupAcompanhamentoDescricao" style="display: none;">
                                <label class="control-label">Descreva o registro</label>
                                <div id="contentAcompanhamentoDescricao">
                                    <textarea id="AcompanhamentoDescricao" name="AcompanhamentoFisioterapia[descricao]" rows="12" class="form-control">                                    
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="salvarAcompanhamentoFisioterapia()">Salvar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL RELATORIO PACIETNE AGUARDE -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModalRelatorioPaciente" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Relátorio de Paciente</h4>
                </div>
                <div class="modal-body">
                    <form id="form-relatorio-paciente" class='form-horizontal'>
                        <input id="idAgendaRelatorio" type="hidden" name='data[id_agenda]' value="<?php echo $Agenda['idagenda']; ?>"/>
                        <div class="form-group">
                            <label for="idPacienteRelatorio" class="control-label col-md-2">paciente:</label>
                            <div class="col-md-10">
                                <select id="idPacienteRelatorio" name="data[id_paciente]" class="form-control" title="Paciente">
                                    <option value="0">Nenhum</option>
                                    <?php
                                    $t_ = count($Pacientes);
                                    if ($t_ > 0):
                                        for ($i_ = 0; $i_ < $t_; $i_++) :
                                            ?>
                                            <option class="paciente-<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>" value="<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>"><?php echo $Pacientes[$i_]["p"]["nome"] . ' ' . $Pacientes[$i_]["p"]["sobrenome"]; ?></option>
                                            <?php
                                        endfor;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">status:</label>
                            <div class="col-md-10">
                                <label class="checkbox-inline text-primary">
                                    <input type="checkbox" id="check1" value="aguardando" class="checks"> aguardando
                                </label>
                                <label class="checkbox-inline text-danger">
                                    <input type="checkbox" id="check2" value="nao_compareceu" class="checks"> não compareceu
                                </label>
                                <label class="checkbox-inline text-warning">
                                    <input type="checkbox" id="check3" value="adiou" class="checks"> adiou
                                </label>
                                <label class="checkbox-inline text-roxo">
                                    <input type="checkbox" id="check4" value="adiou_novamente" class="checks"> adiou novamente
                                </label>
                                <label class="checkbox-inline text-success">
                                    <input type="checkbox" id="check5" value="compareceu" class="checks"> compareceu
                                </label>
                            </div>
                        </div>
                        <div style="text-align: center">
                            <br/>
                            <a href="" id="relatorio-link" target="_blank" data-original-title="PDF"></a>
                            <button id='btn-gerar-relatorio' class='btn btn-primary' type='button' onclick="gerarRelatorioPaciente()"><i class="fa fa-lg fa-file-pdf-o"></i> Gerar PDF</button>
                            &nbsp; ou &nbsp;
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- /modal -->
</div>

<?php
echo $this->Html->css(array("datepicker.min.css", "fullcalendar-2.4.0/fullcalendar.min.css",
    "jspanel/jquery-ui.min.css", "jspanel/jquery.jspanel.css", "select2/select2.min.css", "icheck/all.css", "fileinput.min.css",
    "style.agenda.selecionada.css?v=1.5"), null, array("block" => "css"));

echo $this->Html->css(array("fullcalendar-2.4.0/fullcalendar.print.css"), array("media" => "print"), array("block" => "css"));

echo $this->Html->script(array("jspanel/jquery-ui.min.js", "jquery.ui.touch-punch.min.js", "bootstrap-datepicker.min.js",
    "jquery.maskedinput.min.js", "fullcalendar-2.4.0/lib/moment.min.js", "fullcalendar-2.4.0/fullcalendar.min.js",
    "fullcalendar-2.4.0/lang-all.js", "dateFormat.js", "jquery.dateFormat.js", "jspanel/mobile-detect.min.js",
    "jspanel/jquery.jspanel.js", "moment-with-locales.min.js", "jquery.form.min.js", "select2/select2.full.min.js",
    "icheck/icheck.min.js", "fileinput.min.js", "jquery-validate.min.js"), array("block" => "script"));

echo $this->Html->script(array("app.agenda.selecionada.js?v=3.0"), array("block" => "script"));
