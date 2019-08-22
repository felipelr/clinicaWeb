<?php
echo $this->Html->css(array("select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("jquery.maskedinput.min.js", "jquery-validate.min.js", "select2/select2.full.min.js", "jquery.maskMoney.min.js", "icheck/icheck.min.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Centro_custos', array("url" => array("controller" => "centro_custos", "action" => "alterar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastro de Centro de Custos</h3>
        <hr/>
        <input type="hidden" id="CentroIddespesacusto" name="data[Centro][iddespesacusto]" value="<?php echo $Centro['iddespesacusto'] ?>" />
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="CentroDescricao" class="control-label col-md-4">*Descrição:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="CentroDescricao" name="data[Centro][descricao]" data-required="true" placeholder="Digite a descrição Ex: 'Despesas Gerais'" class="form-control" value="<?php echo $Centro['descricao'] ?>" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="CentroValor" class="control-label col-md-4">Valor Planejado:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="CentroValor" name="data[Centro][valor_planejado]" data-required="true" class="form-control" placeholder="Valor padrão para despesas" value="<?php echo $Centro['valor_planejado'] ?>" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="Planos" class="control-label col-md-4">*Plano de Contas:</label>
                    <div class="col-md-8">
                        <select id="Planos" name="data[Centro][id_plano_contas]" data-required="true" class="form-control" title="Plano de Contas">
                            <?php
                            $total_ = count($Planos);
                            if ($total_ > 0):
                                for ($i_ = 0; $i_ < $total_; $i_++) :
                                    ?>
                                    <option <?php echo ($Centro['id_plano_contas'] == $Planos[$i_]["p"]["idplanocontas"]) ? 'selected="selected"' : ''; ?> value="<?php echo $Planos[$i_]["p"]["idplanocontas"]; ?>" ><?php echo $Planos[$i_]["p"]["descricao"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>  
                <div class="form-group ">
                    <label for="CentroObs" class="control-label col-md-4">Observação:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="CentroObs" name="data[Centro][observacao]" data-required="true" placeholder="Observações" class="form-control" value="<?php echo $Centro['observacao'] ?>" />
                    </div>                        
                </div>    
                <div class="form-group " id="div-radios">
                    <label class="control-label col-md-4">*Ativo:</label>                    
                    <input name="data[Centro][ativo]" type="radio" value="1" id="AtivoSim"  <?php echo ($Centro['ativo'] == 1) ? 'checked' : ''; ?>/>
                    <label for="AtivoSim">Sim</label>
                    &nbsp;&nbsp;
                    <input name="data[Centro][ativo]" type="radio" value="0" id="AtivoNao" <?php echo ($Centro['ativo'] == 0) ? 'checked' : ''; ?> />
                    <label for="AtivoNao">Não</label>
                </div>                
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="col-md-12">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "centro_custos", "action" => "index", $Centro['id_plano_contas'])); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#Planos').select2({
            autocomplete: true,
            width: "100%"
        });
        $("#CentroValor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
        $('#div-radios input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
    });

</script>
