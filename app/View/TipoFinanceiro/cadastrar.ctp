<?php
echo $this->Html->css(array("icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("icheck/icheck.min.js", "app.tipo.financeiro.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('TipoFinanceiro', array("url" => array("controller" => "tipo_financeiro", "action" => "cadastrar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastro de Tipos de Financeiro</h3>
        <hr/>
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>  
            <div class="col-md-12">
                <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                    <li role="presentation" class="active"><a>Dados Gerais <span class="badge"><i class="fa fa-info"></i></span></a></li>
                </ul> 
                <div class="form-group ">
                    <label for="Tipo" class="control-label col-md-2">*Descrição:</label>
                    <div class="col-md-10">
                        <input id="TipoFinanceiroDescricao" autofocus="autofocus" id="Tipo" name="data[TipoFinanceiro][tipo]" data-required="true" placeholder="ex: Visa Crédito, Dinheiro" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="GeraSim" class="control-label col-md-2">*Gera Caixa:  <span data-toggle="tooltip" data-placement="bottom" title="Este recurso fará com que o sistema gere um registro com o valor da despesa/receita no caixa logado"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px">                   
                        <input name="data[TipoFinanceiro][gera_caixa]" type="radio" value="1" id="GeraSim" />
                        <label for="GeraSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[TipoFinanceiro][gera_caixa]"  type="radio" value="0" id="GeraNao" checked />
                        <label for="GeraNao">Não</label>                    
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="AtivoSim" class="control-label col-md-2">*Tipo:</label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px"> 
                        <label>
                            <input name="data[TipoFinanceiro][tipo_calculo]" type="radio" id="dinheiro" class="tipo_calculo" value="dinheiro" checked/> Dinheiro
                        </label>
                        <label>
                            <input name="data[TipoFinanceiro][tipo_calculo]" type="radio" class="tipo_calculo" value="cartao" /> Cartão
                        </label>  
                        <label>
                            <input name="data[TipoFinanceiro][tipo_calculo]" type="radio" class="tipo_calculo" value="cheque" /> Cheque
                        </label>
                        <label>
                            <input name="data[TipoFinanceiro][tipo_calculo]" type="radio" class="tipo_calculo" value="boleto" /> Boleto
                        </label>
                        <label>
                            <input name="data[TipoFinanceiro][tipo_calculo]" type="radio" class="tipo_calculo" value="promissoria" /> Promissória
                        </label>
                    </div>                        
                </div>
                <div class="form-group carencia_div">
                    <label for="GeraSim" class="control-label col-md-2">*Carência?  <span data-toggle="tooltip" data-placement="bottom" title="Adicionar um prazo após lançamento"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px">                   
                        <input class="carencia_radio" type="radio" name="teste" value="1" id="SimCarencia" />
                        <label for="SimCarencia">Sim</label>
                        &nbsp;&nbsp;
                        <input class="carencia_radio" type="radio" name="teste"  value="0" id="NaoCarencia" checked />
                        <label for="NaoCarencia">Não</label>                    
                    </div>                        
                </div>                
                <div class="form-group " id="div_carencia">
                    <label for="Tipo" class="control-label col-md-2">*Dias:</label>
                    <div class="col-md-10">
                        <input autofocus="autofocus" id="carencia" name="data[TipoFinanceiro][carencia]" data-required="true" placeholder="ex: 10 dias após lançamento" class="form-control" value="0" />
                    </div>                        
                </div>                
                <div class="form-group ">
                    <label for="AtivoSim" class="control-label col-md-2">*Ativo:</label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px">                   
                        <input name="data[TipoFinanceiro][ativo]" type="radio"  value="1" id="AtivoSim" checked/>
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[TipoFinanceiro][ativo]" type="radio" value="0" id="AtivoNao" />
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div>                  
                <br/>
                <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                    <li role="presentation" class="active"><a>Despesas <span class="badge"><i class="fa fa-info"></i></span></a></li>
                </ul>   
                <div class="form-group ">
                    <label for="PagarSim" class="control-label col-md-2">*Pag. de Despesa:  <span data-toggle="tooltip" data-placement="bottom" title="Define que este tipo de financeiro é utilizada para pagamento imediato de uma despesa"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10 radios_check" style="padding-top: 5px">                   
                        <input name="data[TipoFinanceiro][pagamento]" type="radio" value="1" id="PagaSim" />
                        <label for="PagaSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[TipoFinanceiro][pagamento]"  type="radio" value="0" id="PagaNao" checked />
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
                        <input name="data[TipoFinanceiro][recebimento]" type="radio" value="1" id="ReceberSim" />
                        <label for="ReceberSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[TipoFinanceiro][recebimento]"  type="radio" value="0" id="ReceberNao" checked />
                        <label for="ReceberNao">Não</label>                    
                    </div>                        
                </div>                
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div id="content-btn-salvar-tipo-financeiro">
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
        $('.carencia_radio').on('ifChecked', function(event) {
                $('#div_carencia').toggle($('.carencia_radio').eq(0).is(':checked'));
        });
        $('.tipo_calculo').on('ifChecked', function(event) {
            if ($(this).val() === "cartao") {
                $('.carencia_div').show();
            } else {
                $('.carencia_div').hide();
            }

        });
        $('.tipo_calculo').iCheck('check');
    });

</script>
