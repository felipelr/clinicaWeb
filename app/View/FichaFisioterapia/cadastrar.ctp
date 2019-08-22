<div>
    <!-- Nav tabs -->
    <ul class="nav nav-pills nav-justified" role="tablist">
        <li role="presentation" class="active"><a href="#tab1" aria-controls="home" role="tab" data-toggle="tab">Paciente</a></li>
        <li role="presentation"><a href="#tab2" aria-controls="profile" role="tab" data-toggle="tab">História Clínica</a></li>
        <li role="presentation"><a href="#tab3" aria-controls="messages" role="tab" data-toggle="tab">Exame Clínico/Físico</a></li>
        <li role="presentation"><a href="#tab4" aria-controls="settings" role="tab" data-toggle="tab">Exames Complementares</a></li>
        <li role="presentation"><a href="#tab5" aria-controls="settings" role="tab" data-toggle="tab">Diagnósticos e Prognóstico</a></li>
        <li role="presentation"><a href="#tab6" aria-controls="settings" role="tab" data-toggle="tab">Plano Terapêutico</a></li>
        <li role="presentation"><a href="#tab7" aria-controls="settings" role="tab" data-toggle="tab">Evolução</a></li>
        <li role="presentation"><a href="#tab8" aria-controls="settings" role="tab" data-toggle="tab">Profissional</a></li>
    </ul>

    <!-- Tab panes -->
    <form id="form-cadastrar-ficha" class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "cadastrar")); ?>">
        <input type="hidden" name="FichaFisioterapia[id_paciente]" id="IdPacienteFicha" value="<?php echo $Paciente['idpaciente']; ?>"/>
        <input type="hidden" name="FichaFisioterapia[descricao]" id="DescricaoFicha" value="Fisioterapia"/>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="tab1">
                <div class="col-sm-10 col-sm-offset-1">
                    <h4 class="text-success"><b>Identificação do Paciente</b></h4>
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Nome:</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="FichaFisioterapia[nome_paciente]" type="text" required disabled value="<?php echo $Paciente['nome']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Sobrenome:</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="FichaFisioterapia[sobrenome_paciente]" type="text" required disabled value="<?php echo $Paciente['sobrenome']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Data de nascimento:</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="FichaFisioterapia[data_nascimento_paciente]" type="text" required disabled value="<?php echo $Paciente['data_nascimento']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group div-radios">
                        <div class="col-sm-2">
                            <label class="control-label">Sexo:</label> 
                        </div>
                        <div class="col-sm-10">
                            <input name="FichaFisioterapia[sexo_paciente]" type="radio" value="M" id="PacienteSexoM" <?php echo $Paciente['sexo'] == 'M' ? 'checked' : 'disabled'; ?>/>
                            <label class="label-radio" for="PacienteSexoM">Masculino</label>
                            <input name="FichaFisioterapia[sexo_paciente]" type="radio" value="F" id="PacienteSexoF" <?php echo $Paciente['sexo'] == 'F' ? 'checked' : 'disabled'; ?>/>
                            <label class="label-radio" for="PacienteSexoF">Feminino</label>
                        </div>                        
                    </div>
                    <div class="form-group div-radios">
                        <div class="col-sm-2">
                            <label class="control-label">Estado Civil:</label>
                        </div>
                        <div class="col-sm-10">
                            <input name="FichaFisioterapia[estado_civil_paciente]" type="radio" value="Solteiro" id="PacienteEstadoCivilSolteiro" <?php echo $Paciente['estado_civil'] == 'Solteiro' ? 'checked' : 'disabled'; ?>/>
                            <label class="label-radio" for="PacienteEstadoCivilSolteiro">Solteiro</label>
                            <input name="FichaFisioterapia[estado_civil_paciente]" type="radio" value="Casado" id="PacienteEstadoCivilCasado" <?php echo $Paciente['estado_civil'] == 'Casado' ? 'checked' : 'disabled'; ?> />
                            <label class="label-radio" for="PacienteEstadoCivilCasado">Casado</label>
                        </div>                        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Naturalidade:</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="FichaFisioterapia[naturalidade_paciente]" type="text" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Local Nascimento:</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="FichaFisioterapia[local_nascimento_paciente]" type="text" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Profissão:</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="FichaFisioterapia[profissao_paciente]" type="text" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Endereço Residencial:</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="FichaFisioterapia[endereco_residencial_paciente]" type="text" required value="<?php
                            if (isset($Endereco['logradouro']) && trim($Endereco['logradouro']) != '') {
                                echo $Endereco['logradouro'] . ', ' . $Endereco['numero'] . ', ' . $Endereco['bairro'] . ' - ' . $Endereco['cidade'] . '\\' . $Endereco['uf'];
                            }
                            ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Endereço Comercial:</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="FichaFisioterapia[endereco_comercial_paciente]" type="text" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="btn-group pull-right">
                            <a class="btn btn-danger" href="<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "prontuario")); ?>/<?php echo $Paciente['idpaciente']; ?>"><i class="fa fa-times"></i> Cancelar</a>
                            <button type="button" class="btn btn-primary" onclick="showTab(2)">Próximo <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab2">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel">
                            <h4>
                                <a class="text-success" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-controls="collapse1">
                                    <b>Queixa principal <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse1" class="panel-collapse collapse in" role="tabpanel" >
                                <textarea id="editor1" name="FichaFisioterapia[queixa_principal]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-controls="collapse2">
                                    <b>Hábitos de vida <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse2" class="panel-collapse collapse" role="tabpanel" >
                                <textarea id="editor2" name="FichaFisioterapia[habitos_vida]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-controls="collapse3">
                                    <b>História atual e pregressa da doença <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse3" class="panel-collapse collapse" role="tabpanel">
                                <textarea id="editor3" name="FichaFisioterapia[historia_atual]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-controls="collapse4">
                                    <b>Antecedentes pessoais e familiares <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse4" class="panel-collapse collapse" role="tabpanel">
                                <textarea id="editor4" name="FichaFisioterapia[antecedentes_pessoais]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-controls="collapse5">
                                    <b>Tratamantos realizados <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse5" class="panel-collapse collapse" role="tabpanel">
                                <textarea id="editor5" name="FichaFisioterapia[tratamentos_realizados]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                    </div>                        
                    <div>
                        <br/>
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default" onclick="showTab(1)"><i class="fa fa-arrow-left"></i> Anterior</button>
                            <button type="button" class="btn btn-primary" onclick="showTab(3)">Próximo <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab3">
                <div class="col-sm-10 col-sm-offset-1">
                    <h4 class="text-success"><b>Descrição do estado de saúde físico funcional</b></h4>
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="editor6" name="FichaFisioterapia[exame_clinico_fisico]" rows="10" class="form-control">                                    
                            </textarea>
                        </div>
                    </div>
                    <div>
                        <br/>
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default" onclick="showTab(2)"><i class="fa fa-arrow-left"></i> Anterior</button>
                            <button type="button" class="btn btn-primary" onclick="showTab(4)">Próximo <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab4">
                <div class="col-sm-10 col-sm-offset-1">
                    <h4 class="text-success"><b>Descrição dos exames complementares realizados</b></h4>
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="editor6" name="FichaFisioterapia[exame_complementar]" rows="10" class="form-control">                                    
                            </textarea>
                        </div>
                    </div>
                    <div>
                        <br/>
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default" onclick="showTab(3)"><i class="fa fa-arrow-left"></i> Anterior</button>
                            <button type="button" class="btn btn-primary" onclick="showTab(5)">Próximo <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab5">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
                        <div class="panel">
                            <h4>
                                <a class="text-success" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapse21" aria-controls="collapse21">
                                    <b>Diagnóstico <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse21" class="panel-collapse collapse in" role="tabpanel" >
                                <textarea id="editor7" name="FichaFisioterapia[diagnostico]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapse22" aria-controls="collapse22">
                                    <b>Prognóstico <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse22" class="panel-collapse collapse" role="tabpanel" >
                                <textarea id="editor8" name="FichaFisioterapia[prognostico]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div> 
                    </div>                                              
                    <div>
                        <br/>
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default" onclick="showTab(4)"><i class="fa fa-arrow-left"></i> Anterior</button>
                            <button type="button" class="btn btn-primary" onclick="showTab(6)">Próximo <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab6">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel-group" id="accordion3" role="tablist" aria-multiselectable="true">
                        <div class="panel">
                            <h4>
                                <a class="text-success" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse31" aria-controls="collapse31">
                                    <b>Procedimentos fisioterapêuticos propostos <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse31" class="panel-collapse collapse in" role="tabpanel" >
                                <textarea id="editor9" name="FichaFisioterapia[procedimentos_fisioterapeuticos]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div> 
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse32" aria-controls="collapse32">
                                    <b>Objetivo(s) terapêutico(s) <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse32" class="panel-collapse collapse" role="tabpanel" >
                                <textarea id="editor10" name="FichaFisioterapia[objetivos_terapeuticos]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse33" aria-controls="collapse33">
                                    <b>Quantitativo provável de atendimento <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse33" class="panel-collapse collapse" role="tabpanel" >
                                <input type="number" min="0" value="0" name="FichaFisioterapia[quantitativo_provavel]" class="form-control"/>
                            </div>
                        </div>
                    </div>                                              
                    <div>
                        <br/>
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default" onclick="showTab(5)"><i class="fa fa-arrow-left"></i> Anterior</button>
                            <button type="button" class="btn btn-primary" onclick="showTab(7)">Próximo <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab7">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel-group" id="accordion4" role="tablist" aria-multiselectable="true">
                        <div class="panel">
                            <h4>
                                <a class="text-success" role="button" data-toggle="collapse" data-parent="#accordion4" href="#collapse41" aria-controls="collapse41">
                                    <b>Evolução do estado de saúde <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse41" class="panel-collapse collapse in" role="tabpanel">
                                <textarea id="editor11" name="FichaFisioterapia[evolucao_estado_saude]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div> 
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion4" href="#collapse42" aria-controls="collapse42">
                                    <b>Tratamento realizado em cada atendimento <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse42" class="panel-collapse collapse" role="tabpanel" >
                                <textarea id="editor12" name="FichaFisioterapia[tratamento_realizado_atendimento]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                        <div class="panel">
                            <h4>
                                <a class="text-success collapsed" role="button" data-toggle="collapse" data-parent="#accordion4" href="#collapse43" aria-controls="collapse43">
                                    <b>Eventuais intercorrências <i class="fa fa-caret-down"></i></b>
                                </a>
                            </h4>
                            <hr/>
                            <div id="collapse43" class="panel-collapse collapse" role="tabpanel" >
                                <textarea id="editor13" name="FichaFisioterapia[eventuais_intercorrencias]" rows="10" class="form-control">                                    
                                </textarea>
                            </div>
                        </div>
                    </div>                                              
                    <div>
                        <br/>
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default" onclick="showTab(6)"><i class="fa fa-arrow-left"></i> Anterior</button>
                            <button type="button" class="btn btn-primary" onclick="showTab(8)">Próximo <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab8">
                <div class="col-sm-10 col-sm-offset-1">
                    <h4 class="text-success"><b>Identificação do profissional</b></h4>
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="control-label">Profissional:</label>
                        </div>
                        <div class="col-sm-10">
                            <select id="IdProfissional" name="FichaFisioterapia[id_profissional]" class="form-control" title="Profissional" required>
                                <?php
                                $total_ = count($Profissionais);
                                if ($total_ > 0):
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
                    <div>
                        <br/>
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default" onclick="showTab(7)"><i class="fa fa-arrow-left"></i> Anterior</button>
                            <button type="button" class="btn btn-success" onclick="salvar()">Salvar <i class="fa fa-save"></i></button>
                        </div>
                        <br/><br/><br/><br/>
                    </div>
                </div>
            </div>
        </div>    
    </form>
</div>
<!-- Modal -->
<div class="modal fade" id="myModalSalvar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Salvar ficha de fisioterapia</h4>
            </div>
            <div class="modal-body">
                <p>Informe uma descrição para a ficha</p>
                <input id="DescricaoModal" class="form-control naovalidar" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" onclick="concluir()">Concluir</button>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->css(array("datepicker.min.css", "icheck/all.css", "select2/select2.min.css",
    "style.ficha.paciente.css?v=1.2"), null, array("block" => "css"));
$this->start('css');
?>
<link rel="stylesheet" href="<?php echo $this->Html->url("/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"); ?>">
<?php
$this->end();

echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js",
    "icheck/icheck.min.js", "select2/select2.full.min.js"), array("block" => "script"));
$this->start('script');
?>
<script src="<?php echo $this->Html->url("/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"); ?>"></script>
<script type="text/javascript">
                    $(document).ready(function () {
                        $('.div-radios input').iCheck({
                            checkboxClass: 'icheckbox_minimal-blue',
                            radioClass: 'iradio_minimal-blue',
                            increaseArea: '20%' // optional
                        });

                        $("textarea").wysihtml5();

                        $("#IdProfissional").select2({
                            width: "100%"
                        });


                    });

                    function showTab(id) {
                        $('a[href="#tab' + id + '"]').tab('show');
                    }

                    function salvar() {
                        $('input[type="text"]').removeClass('border-red');
                        var arrayValidar = $('input[type="text"]');
                        var isFormValid = true;
                        for (var i = 0; i < arrayValidar.length; i++) {
                            debugger;
                            if ($(arrayValidar[i]).val() == "" && !$(arrayValidar[i]).hasClass(".naovalidar")) {
                                isFormValid = false;
                                $('a[href="#tab1"]').tab('show');
                                $(arrayValidar[i]).addClass('border-red');
                                break;
                            }
                        }
                        if (isFormValid) {
                            $("#myModalSalvar").modal("show");
                        }
                    }

                    function concluir() {
                        if ($("#DescricaoModal").val() != "") {
                            $("#DescricaoFicha").val($("#DescricaoModal").val());
                            $("input").prop("disabled", false);
                            $("#form-cadastrar-ficha").submit();
                        }
                    }
</script>
<?php
$this->end();
?>