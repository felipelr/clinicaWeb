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
    echo $this->Form->create('Plano_contas', array("url" => array("controller" => "plano_contas", "action" => "alterar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>   
    <div>
        <h3>Alterar Plano de Contas</h3>
        <hr/>
        <input type="hidden" id="CaixaIdcaixaloja" name="data[Plano][idplanocontas]" value="<?php echo $Plano['idplanocontas'] ?>" />
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="PlanoDescricao" class="control-label col-md-4">*Nome do Plano:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="PlanoDescricao" name="data[Plano][descricao]" value="<?php echo $Plano['descricao'] ?>" data-required="true" placeholder="Digite a descrição" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="PlanoObs" class="control-label col-md-4">Observação</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="PlanoObs" name="data[Plano][observacao]" value="<?php echo $Plano['observacao'] ?>" data-required="true" placeholder="Digite a observação do Plano" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label class="control-label col-md-4">*Ativo:</label>                    
                    <input name="data[Plano][ativo]" type="radio" value="1" id="AtivoSim"  <?php echo ($Plano['ativo'] == 1) ? 'checked' : ''; ?>/>
                    <label for="AtivoSim">Sim</label>
                    &nbsp;&nbsp;
                    <input name="data[Plano][ativo]" type="radio" value="0" id="AtivoNao" <?php echo ($Plano['ativo'] == 0) ? 'checked' : ''; ?> />
                    <label for="AtivoNao">Não</label>
                </div>      
                <div class="form-group ">
                    <label class="control-label col-md-4">*Tipo:</label>                    
                    <input name="data[Plano][tipo]" type="radio" value="D" id="TipoD" <?php echo ($Plano['tipo'] == 'D') ? 'checked' : ''; ?>/>
                    <label for="TipoD">Despesa</label>
                    &nbsp;&nbsp;
                    <input name="data[Plano][tipo]" type="radio" value="R" id="TipoR" <?php echo ($Plano['tipo'] == 'R') ? 'checked' : ''; ?>/>
                    <label for="TipoR">Receita</label>
                    &nbsp;&nbsp;
                    <input name="data[Plano][tipo]" type="radio" value="A" id="TipoA" <?php echo ($Plano['tipo'] == 'A') ? 'checked' : ''; ?>/>
                    <label for="TipoA">Ativo</label>
                    &nbsp;&nbsp;
                    <input name="data[Plano][tipo]" type="radio" value="P" id="TipoP" <?php echo ($Plano['tipo'] == 'P') ? 'checked' : ''; ?>/>
                    <label for="TipoP">Passivo</label>
                </div>
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="col-md-12">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "plano_contas", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Atualizar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
</div>