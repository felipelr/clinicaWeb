<?php
echo $this->Html->css(array("style.cadastro.paciente.css", "datepicker.min.css", "fileinput.min.css", 
    "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("app.cadastro.paciente.js?v=2.5", "bootstrap-datepicker.min.js", 
    "jquery.maskedinput.min.js", "jquery-validate.min.js", "fileinput.min.js", "icheck/icheck.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
    });
</script>
<?php
$this->end();
?>
<div class="">
    <?php
    echo $this->Form->create('Paciente', array("url" => array("controller" => "paciente", "action" => "cadastrar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastrando paciente</h3>
        <hr/>
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified barra">
                <li class="active"><a id="aba-1" data-ref="tab-1" href="#">Dados Básicos</a></li>
                <li class=""><a id="aba-2" data-ref="tab-2" href="#">Dados Complementares</a></li>
                <li class=""><a id="aba-3" data-ref="tab-3" href="#">Dados para Contato</a></li>
            </ul>
        </div>
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6 big-modal">
                <div class="form-group ">
                    <label for="PacienteNome" class="control-label col-md-4">*Nome:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="PacienteNome" name="data[Paciente][nome]" data-required="true" placeholder="Digite o nome" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="PacienteSobrenome" class="control-label col-md-4">*Sobrenome:</label>
                    <div class="col-md-8">
                        <input id="PacienteSobrenome" name="data[Paciente][sobrenome]" data-required="true" placeholder="Digite o sobrenome" class="form-control" />
                    </div>
                </div>
                <div class="form-group ">
                    <label for="PacienteDataNascimento" class="control-label col-md-4">*Data de Nascimento:</label>
                    <div class="col-md-8">
                        <div class="input-group">                                            
                            <div class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></div>
                            <input id="PacienteDataNascimento" name="data[Paciente][data_nascimento]" data-required="true" placeholder="dia/mes/ano" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="PacienteCpf" class="control-label col-md-4">CPF:</label>
                    <div class="col-md-8">
                        <input id="PacienteCpf" name="data[Paciente][cpf]" placeholder="Digite o CPF" class="form-control" />
                    </div>
                </div>
                <div class="form-group ">
                    <label for="PacienteRg" class="control-label col-md-4">RG:</label>
                    <div class="col-md-8">
                        <input id="PacienteRg" name="data[Paciente][rg]" placeholder="Digite o RG" class="form-control" />
                    </div>
                </div>
                <div class="form-group " id="div-radios-sex">
                    <label class="control-label col-md-4">*Sexo:</label>                    
                    <input name="data[Paciente][sexo]" type="radio" value="M" id="PacienteSexoM" checked/>
                    <label for="PacienteSexoM">Masculino</label>
                    <input name="data[Paciente][sexo]" type="radio" value="F" id="PacienteSexoF" />
                    <label for="PacienteSexoF">Feminino</label>
                </div>
            </div>
            <div class="col-md-6 hidden-modal">
                <input id="foto" name="foto" type="file" multiple="false">
            </div>
        </div>
        <div id="tab-2" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6 hidden-modal">
                <div class="form-group ">
                    <label for="PacienteDataInicioAtendimento" class="control-label col-md-4">Data Início de Atendimento:</label>
                    <div class="col-md-8">
                        <div class="input-group">                                            
                            <div class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></div>
                            <input id="PacienteDataInicioAtendimento" name="data[Paciente][data_inicio_atendimento]" placeholder="dia/mes/ano" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="PacienteLocalTrabalho" class="control-label col-md-4">Local de Trabalho:</label>
                    <div class="col-md-8">
                        <input id="PacienteLocalTrabalho" name="data[Paciente][local_trabalho]" placeholder="Digite o nome local de trabalho" class="form-control" />
                    </div>                
                </div>
                <div class="form-group ">
                    <label for="PacienteNomeParceiro" class="control-label col-md-4">Nome do Parceiro:</label>
                    <div class="col-md-8">
                        <input id="PacienteNomeParceiro" name="data[Paciente][nome_parceiro]" placeholder="Digite o nome do parceiro" class="form-control" />
                    </div>
                </div>
                <div class="form-group ">
                    <label for="PacienteNomePai" class="control-label col-md-4">Nome do Pai:</label>
                    <div class="col-md-8">
                        <input id="PacienteNomePai" name="data[Paciente][nome_pai]" placeholder="Digite o nome do pai completo" class="form-control" />                    
                    </div>
                </div>
                <div class="form-group ">
                    <label for="PacienteNomeMae" class="control-label col-md-4">Nome da Mãe:</label>
                    <div class="col-md-8">
                        <input id="PacienteNomeMae" name="data[Paciente][nome_mae]" placeholder="Digite o nome da mãe completo" class="form-control" />
                    </div>                
                </div>
                <div class="form-group" id="div-radios-civil">
                    <label class="control-label col-md-4">Estado Civil:</label>
                    <input name="data[Paciente][estado_civil]" type="radio" value="Solteiro" id="PacienteEstadoCivilSolteiro" checked/>
                    <label for="PacienteEstadoCivilSolteiro">Solteiro</label>
                    <input name="data[Paciente][estado_civil]" type="radio" value="Casado" id="PacienteEstadoCivilCasado" />
                    <label for="PacienteEstadoCivilCasado">Casado</label>
                </div>
                <div class="form-group" id="div-radios">
                    <label class="control-label col-md-4">Maior de Idade:</label>
                    <input name="data[Paciente][maior_idade]" type="radio" value="1" id="PacienteMaiorIdade1" checked/>
                    <label for="PacienteMaiorIdade1">Sim</label>
                    <input name="data[Paciente][maior_idade]" type="radio" value="2" id="PacienteMaiorIdade2"/>
                    <label for="PacienteMaiorIdade2">Não</label>
                </div>
                <div id="div-nomeresp1" class="form-group">
                    <label for="PacienteNomeResponsavel1" class="control-label col-md-4">*Responsável 1:</label>
                    <div class="col-md-8">                    
                        <input id="PacienteNomeResponsavel1" name="data[Paciente][nome_responsavel_1]" placeholder="Digite o nome da responsável completo" class="form-control" />
                    </div>
                </div>
                <div id="div-nomeresp2" class="form-group">
                    <label for="PacienteNomeResponsavel2" class="control-label col-md-4">Responsável 2:</label>
                    <div class="col-md-8"> 
                        <input id="PacienteNomeResponsavel2" name="data[Paciente][nome_responsavel_2]"  placeholder="Digite o nome da responsável completo" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div id="tab-3" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6 hidden-modal">
                <div class="form-group">
                    <label for="EnderecoCep" class="control-label col-md-4">CEP</label>
                    <div class="col-md-8">
                        <input id="EnderecoCep" name="data[Endereco][cep]" placeholder="Digite o cep" class="form-control" />                                                                          
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoLogradouro" class="control-label col-md-4">Logradouro:</label>
                    <div class="col-md-8">
                        <input id="EnderecoLogradouro" name="data[Endereco][logradouro]" placeholder="Digite o nome da rua" class="form-control" />                                                                           
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoNumero" class="control-label col-md-4">Número:</label>
                    <div class="col-md-8">
                        <input id="EnderecoNumero" name="data[Endereco][numero]" placeholder="Digite o número" class="form-control" />                                  
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoBairro" class="control-label col-md-4">Bairro:</label>
                    <div class="col-md-8">
                        <input id="EnderecoBairro" name="data[Endereco][bairro]" placeholder="Digite o nome do bairro" class="form-control" />                                                                           
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoCidade" class="control-label col-md-4">Cidade:</label>
                    <div class="col-md-8">
                        <input id="EnderecoCidade" name="data[Endereco][cidade]" placeholder="Digite o nome a cidade" class="form-control" />                                                                           
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoUf" class="control-label col-md-4">UF</label>
                    <div class="col-md-8">
                        <input id="EnderecoUf" name="data[Endereco][uf]" maxlength="2"  placeholder="Digite a sigla do estado" class="form-control" />                                                                           
                    </div>
                </div>
            </div>
            <div class="col-md-6 hidden-modal">
                <div class="form-group">
                    <label for="PacienteEmail" class="control-label col-md-4">Email:</label>
                    <div class="col-md-8">
                        <input id="PacienteEmail" name="data[Paciente][email]" type="email"  placeholder="Digite o e-mail" class="form-control" />
<!--                        <input id="PacienteEmail" name="data[Paciente][email]" data-validate="emaill" data-required="true" type="email"  placeholder="Digite o e-mail" class="form-control" />-->
                    </div>
                </div>
                <div class="form-group">
                    <label for="PacienteTelefoneFixo" class="control-label col-md-4">Telefone fixo:</label>
                    <div class="col-md-8">
                        <input id="PacienteTelefoneFixo" name="data[Paciente][telefone_fixo]" placeholder="Digite o telefone fixo" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="PacienteTelefoneCelular" class="control-label col-md-4">Telefone celular:</label>
                    <div class="col-md-8">
                        <input id="PacienteTelefoneCelular" name="data[Paciente][telefone_celular]" placeholder="Digite o telefone celular" class="form-control" />
                    </div>
                </div>
            </div>
        </div>                            
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div id="content-btn-salvar-paciente">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
</div>