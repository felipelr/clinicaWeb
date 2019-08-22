<?php
echo $this->Html->css(array("style.cadastro.profissional.css", "datepicker.min.css",
    "fileinput.min.css", "select2/select2.min.css", "icheck/all.css", "jspanel/jquery-ui.min.css",
    "jspanel/jquery.jspanel.css"), null, array("block" => "css"));
echo $this->Html->script(array("jspanel/jquery-ui.min.js", "bootstrap-datepicker.min.js", "jquery.maskedinput.min.js",
    "jquery.maskMoney.min.js", "jquery-validate.min.js", "fileinput.min.js", "select2/select2.full.min.js", "icheck/icheck.min.js",
    "jspanel/mobile-detect.min.js", "jspanel/jquery.jspanel.js", "app.cadastro.profissional.js?v=1.2"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
    });
</script>
<?php
$this->end();
?>
<div class="">
    <?php
    echo $this->Form->create('Profissional', array("url" => array("controller" => "profissional", "action" => "cadastrar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastrando profissional</h3>
        <hr/>
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified barra">
                <li class="active"><a id="aba-1" data-ref="tab-1" href="#">Dados Básicos</a></li>
                <li><a id="aba-2" data-ref="tab-2" href="#">Dados Complementares</a></li>
                <li><a id="aba-3" data-ref="tab-3" href="#">Dados para Contato</a></li>
                <li><a id="aba-4" data-ref="tab-4" href="#">Comissão</a></li>
            </ul>
        </div>
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="ProfissionalNome" class="control-label col-md-4">*Nome:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="ProfissionalNome" name="data[Profissional][nome]" data-required="true" placeholder="Digite o nome" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="ProfissionalSobrenome" class="control-label col-md-4">*Sobrenome:</label>
                    <div class="col-md-8">
                        <input id="ProfissionalSobrenome" name="data[Profissional][sobrenome]" data-required="true" placeholder="Digite o sobrenome" class="form-control" />
                    </div>
                </div>
                <div class="form-group ">
                    <label for="ProfissionalDataNascimento" class="control-label col-md-4">*Data de Nascimento:</label>
                    <div class="col-md-8">
                        <div class="input-group">                                            
                            <div class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></div>
                            <input id="ProfissionalDataNascimento" name="data[Profissional][data_nascimento]" data-required="true" placeholder="dia/mes/ano" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="ProfissionalCpf" class="control-label col-md-4">*CPF:</label>
                    <div class="col-md-8">
                        <input id="ProfissionalCpf" name="data[Profissional][cpf]" placeholder="Digite o CPF" class="form-control" />
                    </div>
                </div>
                <div class="form-group ">
                    <label for="ProfissionalRg" class="control-label col-md-4">*RG:</label>
                    <div class="col-md-8">
                        <input id="ProfissionalRg" name="data[Profissional][rg]" placeholder="Digite o RG" class="form-control" />
                    </div>
                </div>
                <div class="form-group " id="div-radios-sex">
                    <label class="control-label col-md-4">*Sexo:</label>                    
                    <input name="data[Profissional][sexo]" type="radio" value="M" id="ProfissionalSexoM" checked/>
                    <label for="ProfissionalSexoM">Masculino</label>
                    <input name="data[Profissional][sexo]" type="radio" value="F" id="ProfissionalSexoF" />
                    <label for="ProfissionalSexoF">Feminino</label>
                </div>
                <div class="form-group ">
                    <label for="CaixaUsuario" class="control-label col-md-4">Usuário:</label>
                    <div class="col-md-8">
                        <select id="Usuarios" name="data[Profissional][id_usuario]" data-required="true" class="form-control" title="Usuario">
                            <?php
                            $total_ = count($Usuarios);
                            if ($total_ > 0):
                                for ($i_ = 0; $i_ < $total_; $i_++) :
                                    ?>
                                    <option value="<?php echo $Usuarios[$i_]["u"]["idusuario"]; ?>" ><?php echo $Usuarios[$i_]["u"]["nome"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>                 
            </div>
            <div class="col-md-6">
                <input id="foto" name="foto" type="file" multiple="false">
            </div>
        </div>
        <div id="tab-2" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="ProfissionalDataAdmissao" class="control-label col-md-4">Data de Admissão:</label>
                    <div class="col-md-8">
                        <div class="input-group">                                            
                            <div class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></div>
                            <input id="ProfissionalDataAdmissao" name="data[Profissional][data_admissao]" placeholder="dia/mes/ano" class="form-control"/>
                        </div>
                    </div>                
                </div>
                <div class="form-group ">
                    <label for="ProfissionalDataDemissao" class="control-label col-md-4">Data de Demissão:</label>
                    <div class="col-md-8">
                        <div class="input-group">                                            
                            <div class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></div>
                            <input id="ProfissionalDataDemissao" name="data[Profissional][data_demissao]" placeholder="dia/mes/ano" class="form-control"/>
                        </div>
                    </div>                
                </div>
                <div class="form-group">
                    <label for="ProfissionalIdCargo" class="control-label col-md-4">Cargo:</label>
                    <div class="col-md-8">
                        <select id="ProfissionalIdCargo" name="data[Profissional][id_cargo]" class="form-control" title="Cargo">
                            <?php
                            $total_cargos = count($cargos);
                            if ($total_cargos > 0):
                                for ($i_cargo = 0; $i_cargo < $total_cargos; $i_cargo++) :
                                    ?>
                                    <option value="<?php echo $cargos[$i_cargo]["c"]["idcargo"]; ?>"><?php echo $cargos[$i_cargo]["c"]["descricao"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab-3" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="EnderecoCep" class="control-label col-md-4">*CEP</label>
                    <div class="col-md-8">
                        <input id="EnderecoCep" name="data[Endereco][cep]" placeholder="Digite o cep" class="form-control" />                                                                          
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoLogradouro" class="control-label col-md-4">*Logradouro:</label>
                    <div class="col-md-8">
                        <input id="EnderecoLogradouro" name="data[Endereco][logradouro]" placeholder="Digite o nome da rua" class="form-control" />                                                                           
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoNumero" class="control-label col-md-4">*Número:</label>
                    <div class="col-md-8">
                        <input id="EnderecoNumero" name="data[Endereco][numero]" placeholder="Digite o número" class="form-control" type="number" min="0"/>                                  
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoBairro" class="control-label col-md-4">*Bairro:</label>
                    <div class="col-md-8">
                        <input id="EnderecoBairro" name="data[Endereco][bairro]" placeholder="Digite o nome do bairro" class="form-control" />                                                                           
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoCidade" class="control-label col-md-4">*Cidade:</label>
                    <div class="col-md-8">
                        <input id="EnderecoCidade" name="data[Endereco][cidade]" placeholder="Digite o nome a cidade" class="form-control" />                                                                           
                    </div>
                </div>
                <div class="form-group">
                    <label for="EnderecoUf" class="control-label col-md-4">*UF</label>
                    <div class="col-md-8">
                        <input id="EnderecoUf" name="data[Endereco][uf]" maxlength="2"  placeholder="Digite a sigla do estado" class="form-control" />                                                                           
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ProfissionalEmail" class="control-label col-md-4">*Email:</label>
                    <div class="col-md-8">
                        <input id="ProfissionalEmail" name="data[Profissional][email]" type="email"  placeholder="Digite o e-mail" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="ProfissionalTelefoneFixo" class="control-label col-md-4">Telefone fixo:</label>
                    <div class="col-md-8">
                        <input id="ProfissionalTelefoneFixo" name="data[Profissional][telefone_fixo]" placeholder="Digite o telefone fixo" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="ProfissionalTelefoneCelular" class="control-label col-md-4">Telefone celular:</label>
                    <div class="col-md-8">
                        <input id="ProfissionalTelefoneCelular" name="data[Profissional][telefone_celular]" placeholder="Digite o telefone celular" class="form-control" />
                    </div>
                </div>
            </div>
        </div>   
        <div id="tab-4" class="tab col-md-12 col-xs-12">
            <div>
                <table id="tabela-comissao" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-md-5 text-center">Categoria de Aula</th>
                            <th class="col-md-5 text-center">Percentual de Comissão(%)</th>
                            <th class="col-md-2 text-center">Adicionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="last-row" data-indice="0">
                            <td class="col-md-5 text-center">
                                <select name="data[CategoriaAula][0][idcategoriaaula]" class="form-control select-categorias" title="Categoria de Aula">
                                    <option value="0">Selecione</option>
                                    <?php
                                    $totalCategorias = count($categorias);
                                    if ($totalCategorias > 0):
                                        for ($iCategoria = 0; $iCategoria < $totalCategorias; $iCategoria++) :
                                            ?>
                                            <option value="<?php echo $categorias[$iCategoria]["c"]["idcategoriaaula"]; ?>"><?php echo $categorias[$iCategoria]["c"]["descricao"]; ?></option>
                                            <?php
                                        endfor;
                                    endif;
                                    ?>
                                </select>
                            </td>
                            <td class="col-md-5 text-center">
                                <input type="text" name="data[CategoriaAula][0][porcentagem]" class="form-control input-mask-money"/>
                            </td>
                            <td class="col-md-2 text-center">
                                <div>
                                    <button type="button" onclick="adicionarComissao()" class="btn btn-success btn-add"><i class="fa fa-plus"></i></button>
                                    <button type="button" onclick="removerComissao(this, event)" class="btn btn-danger btn-remove" style="display: none;"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "profissional", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
</div>