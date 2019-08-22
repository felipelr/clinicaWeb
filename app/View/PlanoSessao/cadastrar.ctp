<?php
echo $this->Html->css(array("datepicker.min.css", "select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js",
    "select2/select2.full.min.js", "icheck/icheck.min.js", "app.cadastro.planosessao.js?v=1.4"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('PlanoSessao', array("url" => array("controller" => "plano_sessao", "action" => "cadastrar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastro de Plano de Sessão</h3>
        <hr/>
        <div class="row">
            <br/>
            <div class="col-md-10">
                <div class="form-group ">
                    <label for="PlanoSessaoDescricao" class="control-label col-md-2">*Descrição:</label>
                    <div class="col-md-10">
                        <input autofocus="autofocus" id="PlanoSessaoDescricao" name="data[PlanoSessao][descricao]" data-required="true" placeholder="Digite a descrição" class="form-control" />
                    </div>                        
                </div>             
                <div class="form-group ">
                    <label for="PlanoSessaoValor" class="control-label col-md-2">*Valor:</label>
                    <div class="col-md-10">
                        <input id="PlanoSessaoValor" name="data[PlanoSessao][valor]" data-required="true" placeholder="00,00" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label class="control-label col-md-2">*Valor referente a:</label>
                    <div class="col-md-10 div-radios" style="padding-top: 5px">
                        <label for="PlanoSessaoValorReferenteTotal">                   
                            <input name="data[PlanoSessao][valor_referente]" type="radio" value="TOTAL" id="PlanoSessaoValorReferenteTotal" checked />
                            Total do plano
                        </label>
                        &nbsp;&nbsp;
                        <label for="PlanoSessaoValorReferenteParcela">                   
                            <input name="data[PlanoSessao][valor_referente]" type="radio" value="PARCELA" id="PlanoSessaoValorReferenteParcela"  />
                            Parcela
                        </label>                   
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="PlanoSessaoQuantidadeSessoes" class="control-label col-md-2">*Quantidade de sessões:</label>
                    <div class="col-md-10">
                        <input id="PlanoSessaoQuantidadeSessoes" name="data[PlanoSessao][quantidade_sessoes]" data-required="true" placeholder="0" type="number" class="form-control" max="99" min="1"/>
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="PlanoSessaoQuantidadeMeses" class="control-label col-md-2">*Quantidade de meses:</label>
                    <div class="col-md-10">
                        <input id="PlanoSessaoQuantidadeMeses" name="data[PlanoSessao][quantidade_meses]" data-required="true" placeholder="0" type="number" class="form-control" max="99" min="1"/>
                    </div>                        
                </div>      
                <div class="form-group" style="display: none;">
                    <label for="PlanoSessaoIdTipoFinanceiro" class="control-label col-md-2">*Tipo Financeiro:</label>
                    <div class="col-md-10">
                        <select id="PlanoSessaoIdTipoFinanceiro" name="data[PlanoSessao][id_financeiro_tipo]" data-required="true" class="form-control" title="Tipo Financeiro">
                            <?php
                            $total_tf = count($TipoFinanceiros);
                            if ($total_tf > 0):
                                for ($i_tf = 0; $i_tf < $total_tf; $i_tf++) :
                                    ?>
                                    <option value="<?php echo $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"]; ?>" ><?php echo $TipoFinanceiros[$i_tf]["t"]["tipo"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="AtivoSim" class="control-label col-md-2">*Ativo:</label>
                    <div class="col-md-10 div-radios" style="padding-top: 5px">                   
                        <input name="data[PlanoSessao][ativo]" type="radio" value="1" id="AtivoSim" checked />
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[PlanoSessao][ativo]" type="radio" value="0" id="AtivoNao" />
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div>
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div id="content-btn-salvar-plano-sessao">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "plano_sessao", "action" => "index")); ?>">Voltar</a>
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
            jQuery('.combos').select2({
                autocomplete: true,
                width: "100%"
            });
        });
    </script>
</div>