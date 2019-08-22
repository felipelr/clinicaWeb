<?php
echo $this->Html->css(array("icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("icheck/icheck.min.js", "app.tipo.financeiro.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Tipo', array("url" => array("controller" => "tipo_financeiro", "action" => "alterar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastro de Centro de Custos</h3>
        <hr/>
        <input type="hidden" id="TipoIdTipoFinanceiro" name="data[Tipo][idfinanceirotipo]" value="<?php echo $Tipo['idfinanceirotipo'] ?>" />
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-12">
                <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                    <li role="presentation" class="active"><a>Dados Gerais <span class="badge"><i class="fa fa-info"></i></span></a></li>
                </ul>                 
                <div class="form-group ">
                    <label for="Tipo" class="control-label col-md-2">*Descrição:</label>
                    <div class="col-md-10">
                        <input autofocus="autofocus" id="Tipo" name="data[Tipo][tipo]" data-required="true" placeholder="ex: Visa Crédito, Dinheiro" class="form-control"  value="<?php echo $Tipo['tipo'] ?>" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="GeraSim" class="control-label col-md-2">*Gera Caixa:  <span data-toggle="tooltip" data-placement="bottom" title="Este recurso fará com que o sistema gere um registro com o valor da despesa/receita no caixa logado"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px">                   
                        <input name="data[Tipo][gera_caixa]" type="radio" value="1" id="GeraSim"  <?php echo ($Tipo['gera_caixa'] == 1) ? 'checked' : ''; ?> />
                        <label for="GeraSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[Tipo][gera_caixa]" type="radio" value="0" id="GeraNao" <?php echo ($Tipo['gera_caixa'] == 0) ? 'checked' : ''; ?> />
                        <label for="GeraNao">Não</label>                    
                    </div>                        
                </div>    
                <div class="form-group ">
                    <label for="AtivoSim" class="control-label col-md-2">*Tipo:</label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px"> 
                        <label>
                            <input name="data[Tipo_Financeiro][tipo_calculo]" type="radio"  value="dinheiro" <?php echo ($Tipo['tipo_calculo'] == 'dinheiro') ? 'checked' : ''; ?>/> Dinheiro
                        </label>
                        <label>
                            <input name="data[Tipo_Financeiro][tipo_calculo]" type="radio"  value="cartao" <?php echo ($Tipo['tipo_calculo'] == 'cartao') ? 'checked' : ''; ?>/> Cartão
                        </label>  
                        <label>
                            <input name="data[Tipo_Financeiro][tipo_calculo]" type="radio"  value="cheque" <?php echo ($Tipo['tipo_calculo'] == 'cheque') ? 'checked' : ''; ?>/> Cheque
                        </label>
                        <label>
                            <input name="data[Tipo_Financeiro][tipo_calculo]" type="radio"  value="boleto" <?php echo ($Tipo['tipo_calculo'] == 'boleto') ? 'checked' : ''; ?>/> Boleto
                        </label>
                        <label>
                            <input name="data[Tipo_Financeiro][tipo_calculo]" type="radio"  value="promissoria" <?php echo ($Tipo['tipo_calculo'] == 'promissoria') ? 'checked' : ''; ?>/> Promissória
                        </label>
                    </div>                        
                </div>
                <?php
                if($Tipo['tipo_calculo'] == 'cartao'):
                ?>
                <div class="form-group">
                    <label for="Tipo" class="control-label col-md-2">*Carência:</label>
                    <div class="col-md-10">
                        <input autofocus="autofocus" id="carencia" name="data[Tipo][carencia]" data-required="true" placeholder="ex: 10 dias após lançamento" class="form-control" value="<?php echo $Tipo['carencia'] ?>" />
                    </div>                        
                </div>
                <?php
                endif;
                ?>                   
                <div class="form-group ">
                    <label for="AtivoSim" class="control-label col-md-2">*Ativo:</label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px">                   
                        <input name="data[Tipo][ativo]" type="radio" value="1" id="AtivoSim" <?php echo ($Tipo['ativo'] == 1) ? 'checked' : ''; ?>/>
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[Tipo][ativo]" type="radio" value="0" id="AtivoNao" <?php echo ($Tipo['ativo'] == 0) ? 'checked' : ''; ?> />
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div>
                <br/>
                <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                    <li role="presentation" class="active"><a>Despesas <span class="badge"><i class="fa fa-info"></i></span></a></li>
                </ul>                   
                <div class="form-group ">
                    <label for="PagaSim" class="control-label col-md-2">*Pagamento:  <span data-toggle="tooltip" data-placement="bottom" title="Este recurso fará com que o sistema gere um registro com o valor da despesa/receita no caixa logado"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px">                   
                        <input name="data[Tipo][pagamento]" type="radio" value="1" id="PagaSim"  <?php echo ($Tipo['pagamento'] == 1) ? 'checked' : ''; ?> />
                        <label for="PagaSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[Tipo][pagamento]" type="radio" value="0" id="PagaNao" <?php echo ($Tipo['pagamento'] == 0) ? 'checked' : ''; ?> />
                        <label for="PagaNao">Não</label>                    
                    </div>                        
                </div> 
                <br/>
                <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                    <li role="presentation" class="active"><a>Recebimentos <span class="badge"><i class="fa fa-info"></i></span></a></li>
                </ul>
                <div class="form-group ">
                    <label for="ReceberSim" class="control-label col-md-2">*Receb. de Clientes:  <span data-toggle="tooltip" data-placement="bottom" title="Define que este tipo de financeiro é utilizada para receber diretamente o valor do lançamento"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px">                   
                        <input name="data[Tipo][recebimento]" type="radio" value="1" id="ReceberSim" <?php echo ($Tipo['recebimento'] == 1) ? 'checked' : ''; ?>  />
                        <label for="ReceberSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[Tipo][recebimento]"  type="radio" value="0" id="ReceberNao" <?php echo ($Tipo['recebimento'] == 0) ? 'checked' : ''; ?> />
                        <label for="ReceberNao">Não</label>                    
                    </div>                        
                </div>                    
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "tipo_financeiro", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {

    });

</script>
