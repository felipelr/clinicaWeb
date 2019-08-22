<?php
echo $this->Html->css(array("icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("icheck/icheck.min.js", "jquery-validate.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('input[type="radio"]').iCheck({
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
<?php
$this->end();
?>
<div class="">
    <?php
    echo $this->Form->create('Plano_contas', array("url" => array("controller" => "plano_contas", "action" => "cadastrar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastro de Plano de Contas</h3>
        <hr/>
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="CaixaNome" class="control-label col-md-4">*Descrição</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="CaixaNome" name="data[Plano][descricao]" data-required="true" placeholder="Digite a descrição Ex: 'Despesas Gerais'" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="CaixaNome" class="control-label col-md-4">Observação</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="CaixaNome" name="data[Plano][observacao]" data-required="true" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label class="control-label col-md-4">*Ativo:</label>                    
                    <input name="data[Plano][ativo]" type="radio" value="1" id="AtivoSim" checked/>
                    <label for="AtivoSim">Sim</label>
                    &nbsp;&nbsp;
                    <input name="data[Plano][ativo]" type="radio" value="0" id="AtivoNao" />
                    <label for="AtivoNao">Não</label>
                </div>
                <div class="form-group ">
                    <label class="control-label col-md-4">*Tipo:</label>                    
                    <input name="data[Plano][tipo]" type="radio" value="D" id="TipoD" checked/>
                    <label for="TipoD">Despesa</label>
                    &nbsp;&nbsp;
                    <input name="data[Plano][tipo]" type="radio" value="R" id="TipoR" />
                    <label for="TipoR">Receita</label>
                    &nbsp;&nbsp;
                    <input name="data[Plano][tipo]" type="radio" value="A" id="TipoA" />
                    <label for="TipoA">Ativo</label>
                    &nbsp;&nbsp;
                    <input name="data[Plano][tipo]" type="radio" value="P" id="TipoP" />
                    <label for="TipoP">Passivo</label>
                </div>
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="col-md-12">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "plano_contas", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
</div>