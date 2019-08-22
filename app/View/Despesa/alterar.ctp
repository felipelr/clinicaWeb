<?php
echo $this->Html->css(array("select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js",
    "select2/select2.full.min.js", "icheck/icheck.min.js", "app.alteracao.despesa.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Despesa', array("url" => array("controller" => "despesa", "action" => "alterar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Alterando Despesas</h3>
        <hr/>
        <div class="row">
            <br/>
            <input type="hidden" id="DespesaIDdespesa" name="data[Despesa][iddespesa]" value="<?php echo $Despesa['iddespesa']; ?>"/>
            <div class="col-md-10">
                <div class="form-group ">
                    <label for="DespesaDescricao" class="control-label col-md-2">*Descrição:</label>
                    <div class="col-md-10">
                        <input autofocus="autofocus"  id="DespesaDescricao" name="data[Despesa][descricao]" data-required="true" placeholder="Digite a descrição" class="form-control" value="<?php echo $Despesa['descricao']; ?>"/>
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="DespesaIdFavorecido" class="control-label col-md-2">*Favorecido:</label>
                    <div class="col-md-10">
                        <select id="DespesaIdFavorecido" name="data[Despesa][id_favorecido]" data-required="true" class="form-control" title="Caixa">
                            <?php
                            $total_f = count($Favorecidos);
                            if ($total_f > 0):
                                for ($i_f = 0; $i_f < $total_f; $i_f++) :
                                    ?>
                                    <option value="<?php echo $Favorecidos[$i_f]["f"]["idfavorecido"]; ?>" <?php echo $Despesa['id_favorecido'] == $Favorecidos[$i_f]["f"]["idfavorecido"] ? 'selected' : ''; ?> ><?php echo $Favorecidos[$i_f]["f"]["nome"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>                  
                <div class="form-group ">
                    <label for="DespesaValor" class="control-label col-md-2">*Valor:</label>
                    <div class="col-md-10">
                        <input id="DespesaValor" name="data[Despesa][valor]" data-required="true" placeholder="00,00" class="form-control" value="<?php echo $Despesa['valor']; ?>"/>
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="DespesaIdCaixa" class="control-label col-md-2">*Caixa:</label>
                    <div class="col-md-10">
                        <select id="DespesaIdCaixa" name="data[Despesa][id_caixa_loja]" data-required="true" class="form-control" title="Caixa">
                            <?php
                            $total_c = count($Caixas);
                            if ($total_c > 0):
                                for ($i_c = 0; $i_c < $total_c; $i_c++) :
                                    ?>
                                    <option value="<?php echo $Caixas[$i_c]["c"]["idcaixaloja"]; ?>" <?php echo $Despesa['id_caixa_loja'] == $Caixas[$i_c]["c"]["idcaixaloja"] ? 'selected' : ''; ?> ><?php echo $Caixas[$i_c]["c"]["nome_caixa"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>       
                <div class="form-group ">
                    <label for="DespesaIdTipoFinanceiro" class="control-label col-md-2">*Tipo Financeiro:</label>
                    <div class="col-md-10">
                        <select id="DespesaIdTipoFinanceiro" name="data[Despesa][id_financeiro_tipo]" data-required="true" class="form-control" title="Tipo Financeiro">
                            <?php
                            $total_tf = count($TipoFinanceiros);
                            if ($total_tf > 0):
                                for ($i_tf = 0; $i_tf < $total_tf; $i_tf++) :
                                    ?>
                                    <option value="<?php echo $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"]; ?>" <?php echo $Despesa['id_financeiro_tipo'] == $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"] ? 'selected' : ''; ?> ><?php echo $TipoFinanceiros[$i_tf]["t"]["tipo"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="DespesaIdDespesaCusto" class="control-label col-md-2">*Centro Custo:</label>
                    <div class="col-md-10">
                        <select id="DespesaIdDespesaCusto" name="data[Despesa][id_despesa_custo]" data-required="true" class="form-control" title="Centro Custo">
                            <?php
                            $total_cc = count($CentroCustos);
                            if ($total_cc > 0):
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
                                            <option value="<?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"]; ?>" <?php echo $Despesa['id_despesa_custo'] == $CentroCustos[$i_cc]["c"]["iddespesacusto"] ? 'selected' : ''; ?> ><?php echo $CentroCustos[$i_cc]["c"]["descricao"]; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"]; ?>" <?php echo $Despesa['id_despesa_custo'] == $CentroCustos[$i_cc]["c"]["iddespesacusto"] ? 'selected' : ''; ?> ><?php echo $CentroCustos[$i_cc]["c"]["descricao"]; ?></option>
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
                    <label for="AtivoSim" class="control-label col-md-2">*Ativo:</label>
                    <div class="col-md-10" style="padding-top: 5px" id="div-radios">                   
                        <input name="data[Despesa][ativo]" type="radio" value="1" id="AtivoSim" <?php echo $Despesa['ativo'] == 1 ? 'checked' : ''; ?>/>
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[Despesa][ativo]" type="radio" value="0" id="AtivoNao" <?php echo $Despesa['ativo'] == 0 ? 'checked' : ''; ?>/>
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div> 
                <div class="form-group ">
                    <label for="DespesaObservacao" class="control-label col-md-2">Observação:</label>
                    <div class="col-md-10">
                        <input id="DespesaObservacao" name="data[Despesa][observacao]" class="form-control" value="<?php echo $Despesa['observacao']; ?>"/>
                    </div>                        
                </div>
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "despesa", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
    <br/><br/>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            
        });
    </script>
</div>