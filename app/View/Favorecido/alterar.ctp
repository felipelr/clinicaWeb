<?php

echo $this->Html->css(array("datepicker.min.css", "select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js",
    "select2/select2.full.min.js", "icheck/icheck.min.js", "app.cadastro.favorecido.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Favorecido', array("url" => array("controller" => "favorecido", "action" => "cadastrar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastro de Favorecidos</h3>
        <hr/>
        <div class="row">
            <br/>
            <input type="hidden" id="FavorecidoIdFavorecido" name="data[Favorecido][idfavorecido]" value="<?php echo $Favorecido['idfavorecido']; ?>"/>
            <div class="col-md-10">
                <div class="form-group ">
                    <label for="FavorecidoNome" class="control-label col-md-2">*Nome:</label>
                    <div class="col-md-10">
                        <input autofocus="autofocus" id="FavorecidoNome" name="data[Favorecido][nome]" data-required="true" placeholder="Digite o nome" class="form-control" value="<?php echo $Favorecido['nome']; ?>"/>
                    </div>                        
                </div>                
                <div class="form-group ">
                    <label for=ResumidoSim" class="control-label col-md-2">*Resumido:</label>
                    <div class="col-md-10" style="padding-top: 5px" id="div-radios">                   
                        <input name="resumido" class="radios_check div-radios" type="radio" value="1" id="AtivoSim" checked />
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="resumido"  class="radios_check div-radios" type="radio" value="0" id="AtivoNao" />
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div> 
                <div id="resumido">
                    <div class="form-group ">
                        <label for="FavorecidoContato" class="control-label col-md-2">Contato:</label>
                        <div class="col-md-10">
                            <input id="FavorecidoContato" name="data[Favorecido][contato]" placeholder="Digite o contato" class="form-control" value="<?php echo $Favorecido['contato']; ?>"/>
                        </div>                        
                    </div>
                    <div class="form-group ">
                        <label for="FavorecidoTelefone" class="control-label col-md-2">Telefone:</label>
                        <div class="col-md-10">
                            <input id="FavorecidoTelefone" name="data[Favorecido][telefone]"  placeholder="(99) 9999-9999" class="form-control" value="<?php echo $Favorecido['telefone']; ?>"/>
                        </div>                        
                    </div>
                    <div class="form-group">
                        <label for="FavorecidoEmail" class="control-label col-md-2">Email:</label>
                        <div class="col-md-10">
                            <input id="FavorecidoEmail" name="data[Favorecido][email]" data-validate="emaill"  type="email"  placeholder="Digite o e-mail" class="form-control" value="<?php echo $Favorecido['email']; ?>"/>
                        </div>
                    </div>

                    <input id="EnderecoIdEnredeco" name="data[Endereco][idendereco]" type="hidden" value="<?php echo $Endereco['idendereco']; ?>"/>
                    <div class="form-group">
                        <label for="EnderecoCep" class="control-label col-md-2">CEP</label>
                        <div class="col-md-10">
                            <input id="EnderecoCep" name="data[Endereco][cep]" placeholder="Digite o cep" class="form-control" value="<?php echo $Endereco['cep']; ?>"/>                                                                          
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="EnderecoLogradouro" class="control-label col-md-2">Logradouro:</label>
                        <div class="col-md-10">
                            <input id="EnderecoLogradouro" name="data[Endereco][logradouro]" placeholder="Digite o nome da rua" class="form-control" value="<?php echo $Endereco['logradouro']; ?>"/>                                                                           
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="EnderecoNumero" class="control-label col-md-2">Número:</label>
                        <div class="col-md-10">
                            <input id="EnderecoNumero" name="data[Endereco][numero]" data-validate="numeroo"  placeholder="Digite o número" class="form-control" value="<?php echo $Endereco['numero']; ?>"/>                                  
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="EnderecoBairro" class="control-label col-md-2">Bairro:</label>
                        <div class="col-md-10">
                            <input id="EnderecoBairro" name="data[Endereco][bairro]"  placeholder="Digite o nome do bairro" class="form-control" value="<?php echo $Endereco['bairro']; ?>"/>                                                                           
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="EnderecoCidade" class="control-label col-md-2">Cidade:</label>
                        <div class="col-md-10">
                            <input id="EnderecoCidade" name="data[Endereco][cidade]" placeholder="Digite o nome a cidade" class="form-control" value="<?php echo $Endereco['cidade']; ?>"/>                                                                           
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="EnderecoUf" class="control-label col-md-2">UF</label>
                        <div class="col-md-10">
                            <input id="EnderecoUf" name="data[Endereco][uf]" maxlength="2"  placeholder="Digite a sigla do estado" class="form-control" value="<?php echo $Endereco['uf']; ?>"/>                                                                           
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="FavorecidoObservacao" class="control-label col-md-2">Observação:</label>
                        <div class="col-md-10">
                            <input id="FavorecidoObservacao" name="data[Favorecido][observacao]" class="form-control" value="<?php echo $Favorecido['observacao']; ?>"/>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "favorecido", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
    <br/><br/>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $('.div-radios').on('ifChecked', function(event) {
                $('#resumido').toggle($(".div-radios").eq(1).is(':checked'));
            });
            $('#resumido').toggle();
        });
    </script>
</div>