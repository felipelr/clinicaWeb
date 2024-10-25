<div class="">
    <form id="RecebimentoCadastrarForm" class="form-horizontal" action="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "cadastrar")); ?>" method="post">
        <div>
            <h3>Cadastro de Recebimentos</h3>
            <hr />
            <div class="row">
                <br />
                <div class="col-md-10">
                    <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                        <li role="presentation" class="active"><a>Dados cadastrais <span class="badge"><i class="fa fa-info"></i></span></a></li>
                    </ul>
                    <div class="form-group ">
                        <label for="RecebimentoDescricao" class="control-label col-md-2">*Descrição:</label>
                        <div class="col-md-10">
                            <input autofocus="autofocus" id="RecebimentoDescricao" name="data[Recebimento][descricao]" data-required="true" placeholder="Digite a descrição" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="RecebimentoIdPaciente" class="control-label col-md-2">*Paciente:</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <select id="RecebimentoIdPaciente" name="data[Recebimento][id_paciente]" data-required="true" class="form-control combos validarChange" title="Paciente">
                                    <?php
                                    $total_p = count($Pacientes);
                                    if ($total_p > 0) :
                                        for ($ip = 0; $ip < $total_p; $ip++) :
                                    ?>
                                            <option value="<?php echo $Pacientes[$ip]["p"]["idpaciente"]; ?>"><?php echo $Pacientes[$ip]["p"]["nome"] . ' ' . $Pacientes[$ip]["p"]["sobrenome"]; ?></option>
                                    <?php
                                        endfor;
                                    endif;
                                    ?>
                                </select>
                                <div class="input-group-btn">
                                    <button type="button" onclick="openPacienteModal()" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="RecebimentoIdProfissional" class="control-label col-md-2">*Profissional:</label>
                        <div class="col-md-10">
                            <select id="RecebimentoIdProfissional" name="data[Recebimento][id_profissional]" class="form-control combos validarChange" title="Profissional">
                                <?php
                                $total_ = count($Profissionais);
                                if ($total_ > 0) :
                                    for ($i_ = 0; $i_ < $total_; $i_++) :
                                ?>
                                        <option value="<?php echo $Profissionais[$i_]["p"]["idprofissional"]; ?>"><?php echo $Profissionais[$i_]["p"]["nome"] . " " . $Profissionais[$i_]["p"]["sobrenome"]; ?></option>
                                <?php
                                    endfor;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="RecebimentoIdCategoriaAula" class="control-label col-md-2">*Categoria:</label>
                        <div class="col-md-10">
                            <select name="data[Recebimento][id_categoria_aula]" class="form-control combos" title="Categoria de Aula">
                                <?php
                                $totalCategorias = count($categorias);
                                if ($totalCategorias > 0) :
                                    for ($iCategoria = 0; $iCategoria < $totalCategorias; $iCategoria++) :
                                ?>
                                        <option value="<?php echo $categorias[$iCategoria]["c"]["idcategoriaaula"]; ?>"><?php echo $categorias[$iCategoria]["c"]["descricao"]; ?></option>
                                <?php
                                    endfor;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">*Tipo de recebimento</label>
                        <div class="col-md-10">
                            <label style="font-weight: normal;">
                                <input id="radio-tipo-plano-sessao" class="radios_check validarChange" type="radio" name="data[Recebimento][tipo]" value="PLANO" checked /> Plano de sessão
                            </label>
                            &nbsp;&nbsp;
                            <label style="font-weight: normal;">
                                <input id="radio-tipo-comum" class="radios_check validarChange" type="radio" name="data[Recebimento][tipo]" value="COMUM" /> Comum
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="div-plano-sessao">
                        <label for="RecebimentoIdPlanoSessao" class="control-label col-md-2">*Plano de Sessão:</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <select id="RecebimentoIdPlanoSessao" name="data[Recebimento][id_plano_sessao]" class="form-control combos validarChange" title="Plano Sessão">
                                    <?php
                                    $total_pl = count($PlanoSessoes);
                                    if ($total_pl > 0) :
                                        for ($ip = 0; $ip < $total_pl; $ip++) :
                                    ?>
                                            <option value="<?php echo $PlanoSessoes[$ip]["p"]["idplanosessao"]; ?>" data-tipo="<?php echo $PlanoSessoes[$ip]["p"]["tipo_quantidade_sessoes"]; ?>"><?php echo $PlanoSessoes[$ip]["p"]["descricao"]; ?></option>
                                    <?php
                                        endfor;
                                    endif;
                                    ?>
                                </select>
                                <div class="input-group-btn">
                                    <button type="button" onclick="openPlanoSessaoModal()" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="div-comum" style="display: none;">
                        <div class="form-group">
                            <label for="RecebimentoValor" class="control-label col-md-2">*Valor:</label>
                            <div class="col-md-10">
                                <input id="RecebimentoValor" name="data[Recebimento][valor]" placeholder="" class="form-control validarChange" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="RecebimentoQuantidadeParcela" class="control-label col-md-2">*Quantidade de parcelas:</label>
                            <div class="col-md-10">
                                <input id="RecebimentoQuantidadeParcela" name="data[Recebimento][quantidade_parcela]" type="number" min="1" value="1" class="form-control validarChange" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="RecebimentoQuantidadeSessoes" class="control-label col-md-2">*Quantidade de sessões:</label>
                            <div class="col-md-10">
                                <input id="RecebimentoQuantidadeSessoes" name="data[Recebimento][quantidade_sessoes]" type="number" min="1" value="1" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="RecebimentoValorReferente" class="control-label col-md-2">*Valor referente a :</label>
                            <div class="col-md-10">
                                <label style="font-weight: normal;">
                                    <input id="RecebimentoValorReferente" name="data[Recebimento][valor_referente]" type="radio" value="TOTAL" class="radios_check validarChange" checked /> Total do recebimento
                                </label>
                                &nbsp;&nbsp;
                                <label style="font-weight: normal;">
                                    <input id="RecebimentoValorReferente" name="data[Recebimento][valor_referente]" type="radio" value="PARCELA" class="radios_check validarChange" /> Parcela
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">*Clinicas:</label>
                        <div class="col-md-10" style="padding-top: 5px" id="div-checks">
                            <?php
                            $total_cli = count($Clinicas);
                            if ($total_cli > 0) :
                                for ($i_cli = 0; $i_cli < $total_cli; $i_cli++) :
                            ?>
                                    <input id="clinica-<?php echo $i_cli; ?>" name="data[Recebimento][id_clinica][<?php echo $i_cli; ?>]" type="checkbox" value="<?php echo $Clinicas[$i_cli]["c"]["idclinica"]; ?>" <?php echo ($i_cli == 0) ? 'checked' : '' ?> />&nbsp;&nbsp;
                                    <label for="clinica-<?php echo $i_cli; ?>" style="font-weight: normal;"><?php echo $Clinicas[$i_cli]["c"]["fantasia"]; ?></label>
                                    <br />
                            <?php
                                endfor;
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="AtivoSim" class="control-label col-md-2">*Ativo:</label>
                        <div class="col-md-10" style="padding-top: 5px" id="div-radios">
                            <input name="data[Recebimento][ativo]" class="radios_check" type="radio" value="1" id="AtivoSim" checked />
                            <label for="AtivoSim" style="font-weight: normal;">Sim</label>
                            &nbsp;&nbsp;
                            <input name="data[Recebimento][ativo]" class="radios_check" type="radio" value="0" id="AtivoNao" />
                            <label for="AtivoNao" style="font-weight: normal;">Não</label>
                        </div>
                    </div>
                    <ul class="nav nav-pills" role="tablist" style="margin-top: 30px; margin-bottom:20px ">
                        <li role="presentation" class="active"><a>Financeiro <span class="badge"><i class="fa fa-calculator"></i></span></a></li>
                    </ul>
                    <div class="form-group ">
                        <label for="DespesaIdDespesaCusto" class="control-label col-md-2">*Centro Custo:</label>
                        <div class="col-md-10">
                            <select id="RecebimentoIdDespesaCusto" name="data[Recebimento][id_centro_custo]" data-required="true" class="form-control combos" title="Centro Custo">
                                <?php
                                $total_cc = count($CentroCustos);
                                if ($total_cc > 0) :
                                    $idplano = $CentroCustos[0]["pl"]["idplanocontas"];
                                ?>
                                    <optgroup label="<?php echo $CentroCustos[0]["pl"]["descricao"]; ?>">
                                        <?php
                                        for ($i_cc = 0; $i_cc < $total_cc; $i_cc++) :
                                            if ($idplano != $CentroCustos[$i_cc]["pl"]["idplanocontas"]) {
                                                $idplano = $CentroCustos[$i_cc]["pl"]["idplanocontas"];
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo $CentroCustos[$i_cc]["pl"]["descricao"]; ?>">
                                        <option value="<?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"]; ?>"><?php echo $CentroCustos[$i_cc]["c"]["descricao"]; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"]; ?>"><?php echo $CentroCustos[$i_cc]["c"]["descricao"]; ?></option>
                                <?php
                                            }
                                        endfor;
                                ?>
                                    </optgroup>
                                <?php
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="RecebimentoIdCaixa" class="control-label col-md-2">*Caixa:</label>
                        <div class="col-md-10">
                            <select id="DespesaIdCaixa" name="data[Recebimento][id_caixa_loja]" data-required="true" class="form-control combos" title="Caixa">
                                <?php
                                $total_c = count($Caixas);
                                if ($total_c > 0) :
                                    for ($i_c = 0; $i_c < $total_c; $i_c++) :
                                ?>
                                        <option value="<?php echo $Caixas[$i_c]["c"]["idcaixaloja"]; ?>"><?php echo $Caixas[$i_c]["c"]["nome_caixa"]; ?></option>
                                <?php
                                    endfor;
                                endif;
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="RecebimentoDataCompetencia" class="control-label col-md-2">*Data competência:</label>
                        <div class="col-md-10">
                            <input id="RecebimentoDataCompetencia" name="data[Recebimento][data_competencia]" placeholder="__/__/____" data-required="true" class="form-control validarChange" />
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="">
                <div id="content-tabela-financeiros">
                </div>
            </div>
            <br />
            <div class="row" id="content-tabela-eventos" style="display: none;">
                <div class="col-md-10">
                    <ul class="nav nav-pills" role="tablist" style="margin-top: 30px; margin-bottom:20px ">
                        <li role="presentation" class="active"><a>Eventos <span class="badge"><i class="fa fa-calendar"></i></span></a></li>
                    </ul>
                    <div>
                        <div class="form-group">
                            <label for="RecebimentoDataInicioEventos" class="control-label col-md-2">Data Início Eventos:</label>
                            <div class="col-md-10">
                                <input id="RecebimentoDataInicioEventos" name="data[Recebimento][data_inicio_eventos]" placeholder="__/__/____" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="RecebimentoIdAgenda" class="control-label col-md-2">Agenda:</label>
                            <div class="col-md-10">
                                <select id="RecebimentoIdAgenda" name="data[Recebimento][id_agenda]" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-md-2">Conflito de horário:</label>
                            <div class="col-md-10" style="padding-top: 5px" id="div-radios">
                                <input name="data[Recebimento][conflito]" class="radios_check" type="radio" value="1" id="RecebimentoConflito1" checked />
                                <label for="RecebimentoConflito1" style="font-weight: normal;">Permitir eventos no mesmo horário</label>
                                &nbsp;&nbsp;
                                <input name="data[Recebimento][conflito]" class="radios_check" type="radio" value="0" id="RecebimentoConflito0" />
                                <label for="RecebimentoConflito0" style="font-weight: normal;">Adiar para semana seguinte</label>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-md-2">Evento fixo?</label>
                            <div class="col-md-10" style="padding-top: 5px" id="div-radios">
                                <input name="data[Recebimento][fixo]" class="radios_check" type="radio" value="1" id="RecebimentoEventoFixoSim" />
                                <label for="RecebimentoEventoFixoSim" style="font-weight: normal;">Sim</label>
                                &nbsp;&nbsp;
                                <input name="data[Recebimento][fixo]" class="radios_check" type="radio" value="0" id="RecebimentoEventoFixoNao" checked />
                                <label for="RecebimentoEventoFixoNao" style="font-weight: normal;">Não</label>
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
                                            <select name="data[Recebimento][eventos][0][dia]" class="form-control select-dia">
                                                <option value="selecione">(Selecione)</option>
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
                                            <input name="data[Recebimento][eventos][0][horario_inicio]" type="text" class="form-control input-hora-inicio" placeholder="00:00" />
                                        </td>
                                        <td>
                                            <input name="data[Recebimento][eventos][0][horario_termino]" type="text" class="form-control input-hora-termino" placeholder="00:00" />
                                        </td>
                                        <td class="center">
                                            <div>
                                                <button type="button" onclick="adicionarEventoDia()" class="btn btn-success center-block btn-add"><i class="fa fa-lg fa-check"></i></button>
                                                <button type="button" onclick="removerEventoDia(this, event)" style="display: none;" class="btn btn-danger center-block btn-remove"><i class="fa fa-lg fa-close"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <p style="color: #ff6707" class="col-md-12">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
            <p id="plano-max-label" style="color: #ff6707" class="col-md-12">Para planos sem quantidade de sessão definida é obrigatório configurar os eventos.</p>
            <br />
            <div class="col-md-12">
                <div class="btn-group">
                    <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "index")); ?>">Voltar</a>
                    <button style="display: none;" id="btn-gerar-eventos" type="button" class="btn btn-success" onclick="gerarEventos()">Configurar Eventos</button>
                    <button id="btn-continuar" type="button" class="btn btn-primary" onclick="gerarFinanceiro()">Continuar</button>
                    <button id="btn-salvar" type="button" class="btn btn-primary" onclick="salvarRecebimento()" style="display: none;">Salvar</button>
                </div>
            </div>
        </div>
    </form>
    <br /><br />
</div>
<!-- Modal -->
<div class="modal fade" id="myModal2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Cadastro de conta bancária</h4>
            </div>
            <div class="modal-body">
                <div>
                    <form id="form-conta-bancaria" data-action="<?php echo $this->Html->url(array("controller" => "conta_bancaria", "action" => "cadastrar_ajax")); ?>">
                        <div class="form-group">
                            <label for="agencia" class="control-label">Agência:</label>
                            <input id="agencia" type="number" name="data[ContaBancaria][agencia]" placeholder="Informe a agência" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="conta" class="control-label">Conta:</label>
                            <input id="conta" type="number" name="data[ContaBancaria][conta]" placeholder="Informe a conta" class="form-control" />
                        </div>
                        <div class="form-group ">
                            <label for="titular" class="control-label">Titular:</label>
                            <input id="titular" name="data[ContaBancaria][titular]" placeholder="Informe o nome do titular" class="form-control" />
                        </div>
                        <div class="form-group ">
                            <label class="control-label">Tipo Doc.:</label>
                            <div class="" id="div-radios">
                                <input name="tipodocumento" type="radio" value="CPF" id="tipocpf" checked />
                                <label for="tipocpf">CPF</label>
                                <input name="tipodocumento" type="radio" value="CNPJ" id="tipocnpj" />
                                <label for="tipocnpj">CNPJ</label>
                            </div>

                        </div>
                        <div class="form-group ">
                            <label id="lb-documento" for="documento" class="control-label">CPF/CNPJ:</label>
                            <input id="documento" name="data[ContaBancaria][documento]" placeholder="Informe o documento" class="form-control" />
                        </div>
                        <div class="form-group ">
                            <label for="id_banco" class="control-label">Banco:</label>
                            <select id="id_banco" name="data[ContaBancaria][id_banco]" class="form-control" title="Banco">
                                <?php
                                $total_banco = count($Bancos);
                                if ($total_banco > 0) :
                                    for ($i_banco = 0; $i_banco < $total_banco; $i_banco++) :
                                ?>
                                        <option value="<?php echo $Bancos[$i_banco]["b"]["idbanco"]; ?>"><?php echo $Bancos[$i_banco]["b"]["codigo"] . ' - ' . $Bancos[$i_banco]["b"]["descricao"]; ?></option>
                                <?php
                                    endfor;
                                endif;
                                ?>
                            </select>
                        </div>
                        <div class="form-group ">
                            <label for="id_paciente" class="control-label">Paciente:</label>
                            <select id="id_paciente" name="data[ContaBancaria][id_paciente]" class="form-control" title="Paciente">
                                <option value="-1" selected>Nenhum</option>
                                <?php
                                $total_ = count($Pacientes);
                                if ($total_ > 0) :
                                    for ($i_ = 0; $i_ < $total_; $i_++) :
                                ?>
                                        <option value="<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>"><?php echo $Pacientes[$i_]["p"]["nome"] . ' ' . $Pacientes[$i_]["p"]["sobrenome"]; ?></option>
                                <?php
                                    endfor;
                                endif;
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancelar</button>
                <button class="btn btn-primary" onclick="salvarContaBancaria()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerando financeiros</h4>
            </div>
            <div class="modal-body">
                <p>Aguarde...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModalPlanoSessao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Plano de sessão</h4>
            </div>
            <div class="modal-body">
                <div id="modalBodyPlanoSessao" style="padding-left: 15px; padding-right: 15px;">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModalPaciente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<div class="modal fade" id="myModalAlterarComboFinanceiro">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Atenção</h4>
            </div>
            <div class="modal-body">
                <p>Deseja aplicar esta alteração para os próximos financeiros?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
                <button type="button" class="btn btn-success" onclick="alterarCombosFinanceiro()">Sim</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalAlterarComboConta">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Atenção</h4>
            </div>
            <div class="modal-body">
                <p>Deseja aplicar esta alteração para as próximas contas?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
                <button type="button" class="btn btn-success" onclick="alterarCombosConta()">Sim</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalGerarSequenciaCheque">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Atenção</h4>
            </div>
            <div class="modal-body">
                <p>Deseja gerar números em sequência para os proxímos cheques?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
                <button type="button" class="btn btn-success" onclick="gerarSequenciaCheques()">Sim</button>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->css(array(
    "datepicker.min.css", "select2/select2.min.css", "icheck/all.css", "fileinput.min.css", "jspanel/jquery-ui.min.css",
    "jspanel/jquery.jspanel.css"
), null, array("block" => "css"));
echo $this->Html->script(array(
    "jspanel/jquery-ui.min.js",
    "bootstrap-datepicker.min.js",
    "jquery.maskMoney.min.js",
    "jquery.maskedinput.min.js",
    "jquery-validate.min.js",
    "select2/select2.full.min.js",
    "icheck/icheck.min.js",
    "jquery.form.min.js",
    "fileinput.min.js",
    "jspanel/mobile-detect.min.js",
    "jspanel/jquery.jspanel.js"
), array("block" => "script"));
$this->start('script');
?>
<style>
    .border-red {
        border-color: red !important;
    }
</style>
<script type="text/javascript">
    var gerando = false;
    var formValido = false;
    var posicaoAtualFinanceiro = 0;
    var posicaoAtualConta = 0;
    var posicaoAtualCheque = 0;
    const tipoQuantidadePlanoSessao = {
        tipo: 'DETERMINADO',
        eventosConfigurados: false
    };

    jQuery(document).ready(function() {
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('.combos').select2({
            autocomplete: true,
            width: "100%"
        });
        $('.radios_check').iCheck({
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
        $('#div-checks input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            increaseArea: '20%' // optional
        });
        $("#RecebimentoDataCompetencia").mask("99/99/9999");

        jQuery('#RecebimentoIdPlanoSessao').on("select2:select", function(event) {
            onChangeTipoQuantidadePlanoSessao(event.params.data.id);
        });

        var checkout = $("#RecebimentoDataCompetencia").datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function() {
            $("#RecebimentoDataInicioEventos").val($("#RecebimentoDataCompetencia").val());
            checkout.hide();
        }).data('datepicker');

        var checkout2 = $("#RecebimentoDataInicioEventos").datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function() {
            checkout2.hide();
        }).data('datepicker');

        jQuery('#RecebimentoCadastrarForm').validate({
            onKeyup: true,
            onChange: true,
            eachInvalidField: function() {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            },
            eachValidField: function() {
                jQuery(this).closest('div').removeClass('has-error').addClass('has-success');
                if (jQuery(this).val() === "__/__/____") {
                    jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
                }
            }
        });

        //cadastro contas
        $('#div-radios input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
        jQuery("#documento").mask("999.999.999-99");
        jQuery("#RecebimentoDataInicioEventos").mask("99/99/9999");
        jQuery(".input-hora-inicio").mask("99:99");
        jQuery(".input-hora-termino").mask("99:99");

        $('#tipocpf').on('ifChecked', function(event) {
            jQuery("#documento").mask("999.999.999-99");
        });
        $('#tipocnpj').on('ifChecked', function(event) {
            jQuery("#documento").mask("99.999.999/9999-99");
        });
        $('#id_banco').select2({
            autocomplete: true,
            width: "100%"
        });
        $('#id_paciente').select2({
            autocomplete: true,
            width: "100%"
        });

        $("#radio-tipo-plano-sessao").on('ifChecked', function(event) {
            toggleTipoRecebimento();
        });
        $("#radio-tipo-comum").on('ifChecked', function(event) {
            toggleTipoRecebimento();
        });

        $("#RecebimentoValor").maskMoney({
            symbol: "R$",
            decimal: ",",
            thousands: "."
        });

        jQuery(".validarChange").change(function() {
            $("#btn-salvar").hide();
        });
        jQuery(".validarChange").blur(function() {
            $("#btn-salvar").hide();
        });

        loadPlanoSessaoCadastro();
        loadPacienteCadastro();

        jQuery("#RecebimentoIdProfissional").change(function() {
            loadAgendas();
        });
        loadAgendas();

        const idPlanoSessao = jQuery('#RecebimentoIdPlanoSessao').val();
        if (idPlanoSessao) {
            onChangeTipoQuantidadePlanoSessao(idPlanoSessao);
        }
    });

    function onChangeTipoQuantidadePlanoSessao(idPlanoSessao) {
        const option = jQuery('#RecebimentoIdPlanoSessao option[value="' + idPlanoSessao + '"]');
        if (option && option.length > 0) {
            const tipo = jQuery(option).data('tipo');
            tipoQuantidadePlanoSessao.tipo = tipo;
            toggleBotaoSalvar();
        }
    }

    function toggleBotaoSalvar() {
        if (tipoQuantidadePlanoSessao.eventosConfigurados) {
            $('#btn-salvar').prop("disabled", false);
        } else {
            if (tipoQuantidadePlanoSessao.tipo == 'MAX')
                $('#btn-salvar').prop("disabled", true);
            else
                $('#btn-salvar').prop("disabled", false);
        }
    }

    function verificarSeHaEventosConfigurados() {
        const linhasEventos = $('#tabela-eventos tbody tr');
        tipoQuantidadePlanoSessao.eventosConfigurados = linhasEventos.length > 1;
        toggleBotaoSalvar();
    }

    function toggleTipoRecebimento() {
        $("#div-plano-sessao").toggle();
        $("#div-comum").toggle();

        $("#RecebimentoIdPlanoSessao").val('');
        $('#btn-salvar').prop("disabled", false);
        tipoQuantidadePlanoSessao.tipo = 'DETERMINADO';
    }

    function salvarRecebimento() {
        var isSaveValid = true;
        var tableInputs = $("#tabela-financeiros tbody tr td input");
        $.each(tableInputs, function(key, value) {
            if ($(value).val() == "" && !$(value).is(":disabled") && !$(value).hasClass("norequired")) {
                $(value).addClass("border-red");
                isSaveValid = false;
            } else {
                $(value).removeClass("border-red");
            }
        });
        var tableSelects = $("#tabela-financeiros tbody tr td select");
        $.each(tableSelects, function(key2, value2) {
            if ($(value2).val() == 0 && !$(value2).is(":disabled")) {
                $(value2).addClass("border-red");
                isSaveValid = false;
            } else {
                $(value2).removeClass("border-red");
            }
        });
        if (isSaveValid) {
            $("#RecebimentoCadastrarForm").submit();
        }
    }

    function showModalCadastroConta() {
        var idpaciente = jQuery("#RecebimentoIdPaciente").val();
        jQuery("#id_paciente").val(idpaciente).trigger("change");
        jQuery("#myModal2").modal("show");
    }

    function gerarFinanceiro() {
        jQuery("#RecebimentoDescricao").trigger("change");
        jQuery("#RecebimentoDataCompetencia").trigger("change");

        if (jQuery("#RecebimentoDescricao").val() == "") {
            formValido = false;
            jQuery("#RecebimentoDescricao").closest("div").addClass("has-error");
            jQuery('html, body').animate({
                scrollTop: $("#RecebimentoDescricao").offset().top - 15
            }, 2000);
        } else if (jQuery("#RecebimentoDataCompetencia").val() == "") {
            formValido = false;
            jQuery("#RecebimentoDataCompetencia").closest("div").addClass("has-error");
            jQuery('html, body').animate({
                scrollTop: $("#RecebimentoDataCompetencia").offset().top - 15
            }, 2000);
        } else if (jQuery("#RecebimentoValor").val() == "" && jQuery("#radio-tipo-comum").is(":checked")) {
            formValido = false;
            jQuery("#RecebimentoValor").closest("div").addClass("has-error");
            jQuery('html, body').animate({
                scrollTop: $("#RecebimentoValor").offset().top - 15
            }, 2000);
        } else if (jQuery("#RecebimentoQuantidadeParcela").val() == "" && jQuery("#radio-tipo-comum").is(":checked")) {
            formValido = false;
            jQuery("#RecebimentoQuantidadeParcela").closest("div").addClass("has-error");
            jQuery('html, body').animate({
                scrollTop: $("#RecebimentoQuantidadeParcela").offset().top - 15
            }, 2000);
        } else if (jQuery("#RecebimentoQuantidadeSessoes").val() == "" && jQuery("#radio-tipo-comum").is(":checked")) {
            formValido = false;
            jQuery("#RecebimentoQuantidadeSessoes").closest("div").addClass("has-error");
            jQuery('html, body').animate({
                scrollTop: $("#RecebimentoQuantidadeSessoes").offset().top - 15
            }, 2000);
        } else {
            formValido = true;
        }

        if (!gerando && formValido) {
            gerando = true;
            jQuery("#myModal").modal("show");
            var idpaciente = jQuery("#RecebimentoIdPaciente").val();
            var idplanosessao = jQuery("#RecebimentoIdPlanoSessao").val();
            var datacompetencia = jQuery("#RecebimentoDataCompetencia").val();
            var recebimentotipo = jQuery("input[name='data[Recebimento][tipo]']:checked").val();
            var recebimentovalor = jQuery("#RecebimentoValor").val();
            var recebimentoqtdparcela = jQuery("#RecebimentoQuantidadeParcela").val();
            var recebimentovalorreferente = jQuery("input[name='data[Recebimento][valor_referente]']:checked").val();
            jQuery.ajax({
                url: $NOME_APLICACAO + "/recebimento/ajax_gerar_financeiro",
                type: 'POST',
                data: {
                    "idpaciente": idpaciente,
                    "idplanosessao": idplanosessao,
                    "datacompetencia": datacompetencia,
                    "recebimentotipo": recebimentotipo,
                    "recebimentovalor": recebimentovalor,
                    "recebimentoqtdparcela": recebimentoqtdparcela,
                    "recebimentovalorreferente": recebimentovalorreferente
                },
                success: function(data, textStatus, jqXHR) {
                    if (data !== null) {
                        //gerar tabela
                        jQuery("#content-tabela-financeiros").html("");
                        jQuery("#content-tabela-financeiros").html(data);
                        jQuery("#btn-salvar").show();
                        jQuery("#btn-gerar-eventos").show();
                        jQuery("#btn-continuar").removeClass("btn-primary").addClass("btn-info").html("Gerar Financeiro Novamente");
                        jQuery('html, body').animate({
                            scrollTop: $("#content-tabela-financeiros").offset().top
                        }, 2000);

                        jQuery('.combos-financeiro, .combos-contas-f').select2({
                            autocomplete: true,
                            width: "100%"
                        });

                        jQuery(".combos-contas-f").on("select2:select", function(e) {
                            posicaoAtualConta = $(this).attr("data-posicao");
                            if (jQuery("#checkMostrarModal").is(":checked")) {
                                $("#myModalAlterarComboConta").modal('show');
                            }
                        });

                        jQuery(".combos-financeiro").on("select2:select", function(e) {
                            posicaoAtualFinanceiro = $(this).attr("data-posicao");
                            if (jQuery("#checkMostrarModal").is(":checked")) {
                                $("#myModalAlterarComboFinanceiro").modal('show');
                            }
                        });

                        jQuery(".input-cheques").blur(function() {
                            posicaoAtualCheque = $(this).attr("data-posicao");
                            if (jQuery("#checkMostrarModal").is(":checked")) {
                                $("#myModalGerarSequenciaCheque").modal('show');
                            }
                        });

                        jQuery("#checkMostrarModal").iCheck({
                            checkboxClass: 'icheckbox_minimal-blue',
                            radioClass: 'iradio_minimal-blue',
                            increaseArea: '20%' // optional
                        });

                        jQuery(".combos-financeiro").on("change", function(e) {
                            var tipocalculo = $(this).children("option[value='" + this.value + "']").attr("data-calculo");
                            var chave = $(this).children("option[value='" + this.value + "']").attr("data-chave");
                            if (tipocalculo == 'dinheiro' || tipocalculo == 'boleto') {
                                $(".d-conta-" + chave + ", .d-ncheque-" + chave + ", .d-nsu-" + chave).prop("disabled", true);
                                $(".d-conta-" + chave).val(0).trigger("change");
                                $(".d-ncheque-" + chave + ", .d-nsu-" + chave).val("");
                            } else if (tipocalculo == 'cartao') {
                                $(".d-conta-" + chave + ", .d-ncheque-" + chave).prop("disabled", true);
                                $(".d-nsu-" + chave).prop("disabled", false);
                                $(".d-conta-" + chave).val(0).trigger("change");
                                $(".d-ncheque-" + chave).val("");
                            } else if (tipocalculo == 'cheque') {
                                $(".d-nsu-" + chave).prop("disabled", true);
                                $(".d-conta-" + chave + ", .d-ncheque-" + chave).prop("disabled", false);
                                $(".d-nsu-" + chave).val("");
                            } else if (tipocalculo == 'promissoria') {
                                $(".d-ncheque-" + chave).prop("disabled", false);
                                $(".d-conta-" + chave + ", .d-nsu-" + chave).prop("disabled", true);
                                $(".d-nsu-" + chave).val("");
                            }
                        });
                    }
                    jQuery("#myModal").modal("hide");
                    gerando = false;
                },
                error: function() {
                    jQuery("#myModal").modal("hide");
                    gerando = false;
                }
            });
        }
    }

    function alterarCombosFinanceiro() {
        var financeiros = jQuery('.combos-financeiro');
        var id = 0;
        jQuery.each(financeiros, function(i, item) {
            var itemJquery = jQuery(item);
            if (parseInt(itemJquery.attr("data-posicao")) == parseInt(posicaoAtualFinanceiro)) {
                id = itemJquery.val();
            } else if (parseInt(itemJquery.attr("data-posicao")) > parseInt(posicaoAtualFinanceiro)) {
                itemJquery.val(id).trigger('change');
            }
        });
        $("#myModalAlterarComboFinanceiro").modal('hide');
    }

    function alterarCombosConta() {
        var contas = jQuery('.combos-contas-f');
        var id = 0;
        jQuery.each(contas, function(i, item) {
            var itemJquery = jQuery(item);
            if (parseInt(itemJquery.attr("data-posicao")) == parseInt(posicaoAtualConta)) {
                id = itemJquery.val();
            } else if (parseInt(itemJquery.attr("data-posicao")) > parseInt(posicaoAtualConta) && !itemJquery.is(":disabled")) {
                itemJquery.val(id).trigger('change');
            }
        });
        $("#myModalAlterarComboConta").modal('hide');
    }

    function gerarSequenciaCheques() {
        var inputs = jQuery('.input-cheques');
        var numeroSequencia = 0;
        jQuery.each(inputs, function(i, item) {
            var itemJquery = jQuery(item);
            if (parseInt(itemJquery.attr("data-posicao")) == parseInt(posicaoAtualCheque)) {
                numeroSequencia = itemJquery.val();
            } else if (parseInt(itemJquery.attr("data-posicao")) > parseInt(posicaoAtualCheque) && !itemJquery.is(":disabled")) {
                numeroSequencia++;
                itemJquery.val(numeroSequencia);
            }
        });
        $("#myModalGerarSequenciaCheque").modal('hide');
    }

    function salvarContaBancaria() {
        var elementform = jQuery("form#form-conta-bancaria");
        var formURL = jQuery("form#form-conta-bancaria").attr("data-action");
        //plugin form.min
        elementform.ajaxSubmit({
            url: formURL,
            type: 'POST',
            success: function(data, textStatus, jqXHR) {
                //pegar o registro retornado o inserir nas combos
                if (data != null) {
                    var json = jQuery.parseJSON(data);
                    if (json.cb.id_paciente === jQuery("#RecebimentoIdPaciente").val()) {
                        var texto = json.b.descricao + ' - ' + json.cb.agencia + '/' + json.cb.conta;
                        var html = '<option value="' + json.cb.idcontabancaria + '"> ' + texto + ' </option';
                        jQuery('.combos-contas-f').append(html);
                    }
                }
                jQuery('.combos-contas-f').select2({
                    autocomplete: true,
                    width: "100%"
                });
                limparModal();
                jQuery("#myModal2").modal("hide");
            },
            erro: function() {
                jQuery("#myModal2").modal("hide");
            }
        });
    }

    function limparModal() {
        jQuery("#agencia, #conta, #titular, #documento").val("");
    }

    //Plano Sessao
    function openPlanoSessaoModal() {
        jQuery("#myModalPlanoSessao").modal("show");
    }

    function loadPlanoSessaoCadastro() {
        jQuery.ajax({
            url: $NOME_APLICACAO + "/plano_sessao/cadastrar",
            type: 'GET',
            data: {
                "layout": "ajax"
            },
            success: function(data, textStatus, jqXHR) {
                if (data !== null) {
                    jQuery("#modalBodyPlanoSessao").html(data);
                    jQuery("<script/>", {
                        type: "text/javascript",
                        src: $NOME_APLICACAO + "/js/app.cadastro.planosessao.js"
                    }).appendTo("body");

                    jQuery("#content-btn-salvar-plano-sessao").html('<button onclick="cancelarCadastroPlanoSessao()" type="button" class="btn btn-danger">Cancelar</button> <button onclick="salvarPlanoSessaoAjax()" type="button" class="btn btn-primary">Salvar</button> <button id="btn-reset-planosessao" type="reset" style="display: none;"/>');
                }
            },
            error: function() {}
        });
    }

    function salvarPlanoSessaoAjax() {
        if (jQuery("#PlanoSessaoDescricao").val() !== "" && jQuery("#PlanoSessaoQuantidadeSessoes").val() !== "" && jQuery("#PlanoSessaoQuantidadeMeses").val() !== "") {
            var dataForm = $("#PlanoSessaoCadastrarForm").serialize();
            jQuery.ajax({
                url: $NOME_APLICACAO + "/plano_sessao/cadastrar?layout=ajax",
                type: 'POST',
                data: dataForm,
                success: function(data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = jQuery.parseJSON(data);
                        jQuery("<option value='" + json.idplanosessao + "' data-tipo='" + json.tipo_quantidade_sessoes + "'>" + json.descricao + "</option>").appendTo("#RecebimentoIdPlanoSessao");
                        jQuery('#RecebimentoIdPlanoSessao').select2({
                            autocomplete: true,
                            width: "100%"
                        });

                        jQuery('#RecebimentoIdPlanoSessao').on("select2:select", function(event) {
                            onChangeTipoQuantidadePlanoSessao(event.params.data.id);
                        });
                    }
                    jQuery("#btn-reset-planosessao").trigger("click");
                    jQuery("#myModalPlanoSessao").modal("hide");
                },
                error: function() {}
            });
        }
    }

    function cancelarCadastroPlanoSessao() {
        jQuery("#btn-reset-planosessao").trigger("click");
        jQuery("#myModalPlanoSessao").modal("hide");
    }

    //Paciente
    function openPacienteModal() {
        jQuery("#myModalPaciente").modal("show");
    }

    function loadPacienteCadastro() {
        jQuery.ajax({
            url: $NOME_APLICACAO + "/paciente/cadastrar",
            type: 'GET',
            data: {
                "layout": "ajax"
            },
            success: function(data, textStatus, jqXHR) {
                if (data !== null) {
                    jQuery("#modalBodyPaciente").html(data);
                    jQuery("<script/>", {
                        type: "text/javascript",
                        src: $NOME_APLICACAO + "/js/app.cadastro.paciente.js"
                    }).appendTo("body");

                    jQuery("#PacienteDataNascimento").mask("99/99/9999");
                    jQuery("#PacienteCpf").mask("999.999.999-99");
                    jQuery("#PacienteRg").mask("99.999.999-*");

                    jQuery("#content-btn-salvar-paciente").html('<button onclick="cancelarCadastroPaciente()" type="button" class="btn btn-danger">Cancelar</button> <button onclick="salvarPacienteAjax()" type="button" class="btn btn-primary">Salvar</button> <button id="btn-reset-paciente" type="reset" style="display: none;"/>');
                    jQuery(".hidden-modal").hide();
                    jQuery(".big-modal").addClass("col-md-12").removeClass("col-md-6");
                }
            },
            error: function() {}
        });
    }

    function salvarPacienteAjax() {
        if (jQuery("#PacienteNome").val() !== "") {
            var dataForm = $("#PacienteCadastrarForm").serialize();
            jQuery.ajax({
                url: $NOME_APLICACAO + "/paciente/cadastrar?layout=ajax",
                type: 'POST',
                data: dataForm,
                success: function(data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = jQuery.parseJSON(data);
                        jQuery("<option value='" + json.idpaciente + "'>" + json.nome + " " + json.sobrenome + "</option>").appendTo("#RecebimentoIdPaciente");
                        jQuery('#RecebimentoIdPaciente').select2({
                            autocomplete: true,
                            width: "100%"
                        });
                    }
                    jQuery("#btn-reset-paciente").trigger("click");
                    jQuery("#myModalPaciente").modal("hide");
                },
                error: function() {}
            });
        }
    }

    function cancelarCadastroPaciente() {
        jQuery("#btn-reset-paciente").trigger("click");
        jQuery("#myModalPaciente").modal("hide");
    }

    function adicionarEventoDia() {
        var lastSelect = jQuery(".last-row td .select-dia").val();
        var lastInput = jQuery(".last-row td .input-hora-inicio").val();
        var lastInputTermino = jQuery(".last-row td .input-hora-termino").val();
        var selects = jQuery(".select-dia");
        var inputs = jQuery(".input-hora-inicio");
        var isValid = true;
        var mensagem = '';

        if (lastSelect == 'selecione') {
            showErrorMessage('Selecione o dia da semana.');
            return;
        }

        if (lastInput == '') {
            showErrorMessage('Informe o horário de início.');
            return;
        }

        if (lastInputTermino == '') {
            showErrorMessage('Informe o horário de término.');
            return;
        }

        if (selects.length > 1) {
            length = selects.length - 1;
            for (var i = 0; i < length; i++) {
                if (jQuery(selects[i]).val() == lastSelect && jQuery(inputs[i]).val() == lastInput) {
                    showErrorMessage('Dia e horário já selecionado anteriormente.');
                    return;
                }
            }
        }

        var lastRow = jQuery(".last-row");
        var indice = parseInt(lastRow.attr("data-indice"));
        var clone = lastRow.clone();
        indice = indice + 1;
        clone.attr("data-indice", indice);
        clone.children("td").children("select").attr("name", "data[Recebimento][eventos][" + indice + "][dia]");
        clone.children("td").children("input.input-hora-inicio").attr("name", "data[Recebimento][eventos][" + indice + "][horario_inicio]");
        clone.children("td").children("input.input-hora-termino").attr("name", "data[Recebimento][eventos][" + indice + "][horario_termino]");
        jQuery("#tabela-eventos tbody").append(clone);
        lastRow.removeClass("last-row");
        lastRow.find("td div .btn-add").hide();
        lastRow.find("td div .btn-remove").show();
        jQuery(".last-row td input").val("");
        jQuery(".input-hora-inicio").mask("99:99");
        jQuery(".input-hora-termino").mask("99:99");

        verificarSeHaEventosConfigurados();
    }

    function showErrorMessage(message) {
        $.jsPanel({
            paneltype: 'hint',
            theme: 'danger',
            position: {
                top: 70,
                right: 0
            },
            size: {
                width: 400,
                height: 'auto'
            },
            content: "<div style='padding: 20px;'>" + message + "</div>"
        });
    }

    function removerEventoDia(event) {
        jQuery(event).parent("div").parent("td").parent("tr").remove();
        verificarSeHaEventosConfigurados();
    }

    function loadAgendas() {
        jQuery.ajax({
            url: $NOME_APLICACAO + "/agenda/ajax_retornar_agenda",
            type: 'GET',
            data: {
                "id_profissional": jQuery("#RecebimentoIdProfissional").val()
            },
            success: function(data, textStatus, jqXHR) {
                jQuery("#RecebimentoIdAgenda").html(data);
            },
            error: function() {
                jQuery("#RecebimentoIdAgenda").html("");
            }
        });
    }

    function gerarEventos() {
        jQuery("#content-tabela-eventos").show();
        jQuery("#btn-gerar-eventos").hide();
        jQuery('html, body').animate({
            scrollTop: $("#content-tabela-eventos").offset().top
        }, 2000);
    }
</script>
<?php
$this->end();
