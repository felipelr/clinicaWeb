<?php
echo $this->Html->css(array("datepicker.min.css", "select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js",
    "select2/select2.full.min.js", "icheck/icheck.min.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('CategoriaDespesa', array("url" => array("controller" => "categoria_despesa", "action" => "alterar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Alterando Categoria de Despesa</h3>
        <hr/>
        <div class="row">
            <input type="hidden" id="CategoriaDespesaIDdespesa" name="data[CategoriaDespesa][idcategoriadespesa]" value="<?php echo $CategoriaDespesa['idcategoriadespesa']; ?>"/>
            <div class="col-md-10">
                <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                    <li role="presentation" class="active"><a>Dados cadastrais <span class="badge"><i class="fa fa-info"></i></span></a></li>
                </ul> 
                <div class="form-group ">
                    <label for="CategoriaDespesaDescricao" class="control-label col-md-2">*Descrição:</label>
                    <div class="col-md-10">
                        <input autofocus="autofocus"  id="CategoriaDespesaDescricao" name="data[CategoriaDespesa][descricao]" data-required="true" placeholder="Digite a descrição" class="form-control" value="<?php echo $CategoriaDespesa['descricao']; ?>"/>
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="CategoriaDespesaIdFavorecido" class="control-label col-md-2">*Favorecido:</label>
                    <div class="col-md-10">
                        <select id="CategoriaDespesaIdFavorecido" name="data[CategoriaDespesa][id_favorecido]" data-required="true" class="form-control" title="Caixa">
                            <?php
                            $total_f = count($Favorecidos);
                            if ($total_f > 0):
                                for ($i_f = 0; $i_f < $total_f; $i_f++) :
                                    ?>
                                    <option value="<?php echo $Favorecidos[$i_f]["f"]["idfavorecido"]; ?>" <?php echo $CategoriaDespesa['id_favorecido'] == $Favorecidos[$i_f]["f"]["idfavorecido"] ? 'selected' : ''; ?> ><?php echo $Favorecidos[$i_f]["f"]["nome"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>
                <div class="form-group" >
                    <label for="CategoriaDespesaValorReferente" class="control-label col-md-2">*Valor referente a :</label>
                    <div class="col-md-10">
                        <label style="font-weight: normal;" >
                            <input id="CategoriaDespesaValorReferente" name="data[CategoriaDespesa][valor_referente]" type="radio" value="TOTAL" class="radios_check" <?php echo $CategoriaDespesa["valor_referente"] == "TOTAL" ? "checked" : ""; ?>/> Total da despesa
                        </label>
                        &nbsp;&nbsp;
                        <label style="font-weight: normal;" >
                            <input id="CategoriaDespesaValorReferente" name="data[CategoriaDespesa][valor_referente]" type="radio" value="PARCELA" class="radios_check" <?php echo $CategoriaDespesa["valor_referente"] == "PARCELA" ? "checked" : ""; ?>/> Parcela
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="CategoriaDespesaRepetir" class="control-label col-md-2">*Repetir lançamento ?</label>
                    <div class="col-md-10" style="padding-top: 5px">
                        <label for="RepetirSim">
                            <input name="data[CategoriaDespesa][repetir]" class="lancamento" type="radio" value="1" id="RepetirSim"  <?php echo $CategoriaDespesa["repetir"] == 1 ? "checked" : ""; ?>/>
                            Sim
                        </label>
                        &nbsp;&nbsp;                        
                        <label for="RepetirNao">
                            <input name="data[CategoriaDespesa][repetir]" class="lancamento" type="radio" value="0" id="RepetirNao" <?php echo $CategoriaDespesa["repetir"] != 1 ? "checked" : ""; ?>/>
                            Não
                        </label>                    
                    </div>    
                </div>
                <div class="form-group div_prazo" <?php echo $CategoriaDespesa["repetir"] != 1 ? 'style="display: none;"' : ""; ?> >
                    <label for="CategoriaDespesaRepetir" class="control-label col-md-2">*Tipo de Intervalo</label>
                    <div class="col-md-10" style="padding-top: 5px">
                        <input name="data[CategoriaDespesa][tipo_intervalo]" class="radios_check" type="radio" value="DIA" id="dias" <?php echo $CategoriaDespesa["tipo_intervalo"] == "DIA" ? "checked" : ""; ?>/>
                        <label for="dias">Dia(s)</label>
                        &nbsp;&nbsp;
                        <input name="data[CategoriaDespesa][tipo_intervalo]" class="radios_check" type="radio" value="MES" id="meses" <?php echo $CategoriaDespesa["tipo_intervalo"] == "MES" ? "checked" : ""; ?> />
                        <label for="meses">Mês(es)</label>
                        &nbsp;&nbsp;                        
                        <input name="data[CategoriaDespesa][tipo_intervalo]" class="radios_check" type="radio" value="ANO" id="anos" <?php echo $CategoriaDespesa["tipo_intervalo"] == "ANO" ? "checked" : ""; ?>/>
                        <label for="anos">Ano(s)</label>                    
                    </div>    
                </div>                
                <div class="form-group div_prazo" <?php echo $CategoriaDespesa["repetir"] != 1 ? 'style="display: none;"' : ""; ?>>
                    <label for="CategoriaDespesaPrazo" class="control-label col-md-2">*Prazo:<span data-toggle="tooltip" data-placement="bottom" title="De quanto em quanto tempo a despesa irá se repetir"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10">
                        <input id="CategoriaDespesaPrazo" name="data[CategoriaDespesa][prazo_repetir]" placeholder="0" type="number" class="form-control" max="99" min="1" value="<?php echo $CategoriaDespesa['prazo_repetir']; ?>"/>
                    </div>                     
                </div>
                <div class="form-group div_prazo" <?php echo $CategoriaDespesa["repetir"] != 1 ? 'style="display: none;"' : ""; ?>>
                    <label for="CategoriaDespesaRepeticao" class="control-label col-md-2">*Repetição:<span data-toggle="tooltip" data-placement="bottom" title="Quantidade de vezes a repetir a despesa"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10">
                        <input id="CategoriaDespesaRepeticao" name="data[CategoriaDespesa][quantidade_repetir]"  placeholder="0" type="number" class="form-control" max="99" min="1" value="<?php echo $CategoriaDespesa['quantidade_repetir']; ?>"/>
                    </div>                      
                </div>                
                <ul class="nav nav-pills" role="tablist" style="margin-top: 30px; margin-bottom:20px ">
                    <li role="presentation" class="active"><a>Financeiro <span class="badge"><i class="fa fa-calculator"></i></span></a></li>
                </ul>
                <div class="form-group ">
                    <label for="CategoriaDespesaIdCaixa" class="control-label col-md-2">*Caixa:</label>
                    <div class="col-md-10">
                        <select id="CategoriaDespesaIdCaixa" name="data[CategoriaDespesa][id_caixa_loja]" data-required="true" class="form-control" title="Caixa">
                            <?php
                            $total_c = count($Caixas);
                            if ($total_c > 0):
                                for ($i_c = 0; $i_c < $total_c; $i_c++) :
                                    ?>
                                    <option value="<?php echo $Caixas[$i_c]["c"]["idcaixaloja"]; ?>" <?php echo $CategoriaDespesa['id_caixa_loja'] == $Caixas[$i_c]["c"]["idcaixaloja"] ? 'selected' : ''; ?> ><?php echo $Caixas[$i_c]["c"]["nome_caixa"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>       
                <div class="form-group ">
                    <label for="CategoriaDespesaIdTipoFinanceiro" class="control-label col-md-2">*Tipo Financeiro:</label>
                    <div class="col-md-10">
                        <select id="CategoriaDespesaIdTipoFinanceiro" name="data[CategoriaDespesa][id_financeiro_tipo]" data-required="true" class="form-control" title="Tipo Financeiro">
                            <?php
                            $total_tf = count($TipoFinanceiros);
                            if ($total_tf > 0):
                                for ($i_tf = 0; $i_tf < $total_tf; $i_tf++) :
                                    ?>
                                    <option value="<?php echo $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"]; ?>" <?php echo $CategoriaDespesa['id_financeiro_tipo'] == $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"] ? 'selected' : ''; ?> ><?php echo $TipoFinanceiros[$i_tf]["t"]["tipo"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>

                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="CategoriaDespesaPrazo" class="control-label col-md-2">*Parcelas:<span data-toggle="tooltip" data-placement="bottom" title="Quantidade de parcelas a gerar a partir da primeira competência"><i class="fa fa-question-circle" ></i></span></label>
                    <div class="col-md-10">
                        <input id="CategoriaDespesaPrazo" name="data[CategoriaDespesa][quantidade_parcela]" data-required="true" placeholder="0" value="<?php echo $CategoriaDespesa['idcategoriadespesa']; ?>" type="number" class="form-control" max="99" min="1"/>
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="CategoriaDespesaIdDespesaCusto" class="control-label col-md-2">*Centro Custo:</label>
                    <div class="col-md-10">
                        <select id="CategoriaDespesaIdDespesaCusto" name="data[CategoriaDespesa][id_despesa_custo]" data-required="true" class="form-control" title="Centro Custo">
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
                                            <option value="<?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"]; ?>" <?php echo $CategoriaDespesa['id_despesa_custo'] == $CentroCustos[$i_cc]["c"]["iddespesacusto"] ? 'selected' : ''; ?> ><?php echo $CentroCustos[$i_cc]["c"]["descricao"]; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"]; ?>" <?php echo $CategoriaDespesa['id_despesa_custo'] == $CentroCustos[$i_cc]["c"]["iddespesacusto"] ? 'selected' : ''; ?> ><?php echo $CentroCustos[$i_cc]["c"]["descricao"]; ?></option>
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
                        <input name="data[CategoriaDespesa][ativo]" class="radios_check" type="radio" value="1" id="AtivoSim" <?php echo $CategoriaDespesa['ativo'] == 1 ? 'checked' : ''; ?>/>
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[CategoriaDespesa][ativo]" class="radios_check" type="radio" value="0" id="AtivoNao" <?php echo $CategoriaDespesa['ativo'] == 0 ? 'checked' : ''; ?>/>
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div> 
                <div class="form-group ">
                    <label for="CategoriaDespesaObservacao" class="control-label col-md-2">Observação:</label>
                    <div class="col-md-10">
                        <input id="CategoriaDespesaObservacao" name="data[CategoriaDespesa][observacao]" class="form-control" value="<?php echo $CategoriaDespesa['observacao']; ?>"/>
                    </div>                        
                </div>
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "categoria_despesa", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Atualizar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
    <br/><br/>
</div>
<!-- Modal -->
<div class="modal fade" id="myModalFavorecido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Favorecido</h4>
            </div>
            <div class="modal-body">
                <div id="modalBodyFavorecido" style="padding-left: 15px; padding-right: 15px;">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModalTipoFinanceiro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Tipo de Financeiro</h4>
            </div>
            <div class="modal-body">
                <div id="modalBodyTipoFinanceiro" style="padding-left: 15px; padding-right: 15px;">

                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {

        $('.combos').select2({
            autocomplete: true,
            width: "100%"
        });
        $('.radios_check').iCheck({
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
        $('#div-checks').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            increaseArea: '20%' // optional
        });

        jQuery('#CategoriaDespesaAlterarForm').validate({
            onKeyup: true,
            eachInvalidField: function () {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            },
            eachValidField: function () {
                jQuery(this).closest('div').removeClass('has-error').addClass('has-success');

                if (jQuery(this).val() === "__/__/____") {
                    jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
                }
            }
        });

        $('#RepetirSim, #RepetirNao').iCheck({
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
        $('#RepetirSim, #RepetirNao').on('ifChecked', function (event) {
            $('.div_prazo').toggle($(".lancamento").eq(0).is(':checked'));
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        loadFavorecidoCadastro();
        loadTipoFinanceiroCadastro();
    });

    //Favorecido
    function loadFavorecidoCadastro() {
        jQuery.ajax({
            url: $NOME_APLICACAO + "/favorecido/cadastrar",
            type: 'GET',
            data: {"layout": "ajax"},
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    jQuery("#modalBodyFavorecido").html(data);
                    jQuery("<script/>", {
                        type: "text/javascript",
                        src: $NOME_APLICACAO + "/js/app.cadastro.favorecido.js"
                    }).appendTo("body");

                    jQuery("#content-btn-salvar").html('<button onclick="cancelarCadastroFavorecido()" type="button" class="btn btn-danger">Cancelar</button> <button onclick="salvarFavorecidoAjax()" type="button" class="btn btn-primary">Salvar</button> <button id="btn-reset-favorecido" type="reset" style="display: none;"/>');
                }
            },
            error: function () {
            }
        });
    }

    function openFavorecidoModal() {
        jQuery("#myModalFavorecido").modal("show");
    }

    function salvarFavorecidoAjax() {
        if (jQuery("#FavorecidoNome").val() !== "") {
            var dataForm = $("#FavorecidoCadastrarForm").serialize();
            jQuery.ajax({
                url: $NOME_APLICACAO + "/favorecido/cadastrar?layout=ajax",
                type: 'POST',
                data: dataForm,
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = jQuery.parseJSON(data);
                        jQuery("<option value='" + json.idfavorecido + "'>" + json.nome + "</option>").appendTo("#CategoriaDespesaIdFavorecido");
                        jQuery('#CategoriaDespesaIdFavorecido').select2({
                            autocomplete: true,
                            width: "100%"
                        });
                    }
                    jQuery("#btn-reset-favorecido").trigger("click");
                    jQuery("#myModalFavorecido").modal("hide");
                },
                error: function () {
                }
            });
        }
    }

    function cancelarCadastroFavorecido() {
        jQuery("#btn-reset-favorecido").trigger("click");
        jQuery("#myModalFavorecido").modal("hide");
    }

    //TipoFinanceiro
    function loadTipoFinanceiroCadastro() {
        jQuery.ajax({
            url: $NOME_APLICACAO + "/tipo_financeiro/cadastrar",
            type: 'GET',
            data: {"layout": "ajax"},
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    jQuery("#modalBodyTipoFinanceiro").html(data);
                    jQuery("<script/>", {
                        type: "text/javascript",
                        src: $NOME_APLICACAO + "/js/app.tipo.financeiro.js"
                    }).appendTo("body");

                    jQuery("#content-btn-salvar-tipo-financeiro").html('<button onclick="cancelarCadastroTipoFinanceiro()" type="button" class="btn btn-danger">Cancelar</button> <button onclick="salvarTipoFinanceiroAjax()" type="button" class="btn btn-primary">Salvar</button> <button id="btn-reset-tipo-financeiro" type="reset" style="display: none;"/>');
                }
            },
            error: function () {
            }
        });
    }

    function openTipoFinanceiroModal() {
        jQuery("#myModalTipoFinanceiro").modal("show");
    }

    function salvarTipoFinanceiroAjax() {
        if (jQuery("#TipoFinanceiroDescricao").val() !== "") {
            var dataForm = $("#TipoFinanceiroCadastrarForm").serialize();
            jQuery.ajax({
                url: $NOME_APLICACAO + "/tipo_financeiro/cadastrar?layout=ajax",
                type: 'POST',
                data: dataForm,
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = jQuery.parseJSON(data);
                        jQuery("<option value='" + json.idfinanceirotipo + "'>" + json.tipo + "</option>").appendTo("#CategoriaDespesaIdTipoFinanceiro");
                        jQuery('#CategoriaDespesaIdTipoFinanceiro').select2({
                            autocomplete: true,
                            width: "100%"
                        });
                    }
                    jQuery("#btn-reset-tipo-financeiro").trigger("click");
                    jQuery("#myModalTipoFinanceiro").modal("hide");
                },
                error: function () {
                }
            });
        }
    }

    function cancelarCadastroTipoFinanceiro() {
        jQuery("#btn-reset-tipo-financeiro").trigger("click");
        jQuery("#myModalTipoFinanceiro").modal("hide");
    }
</script>
<?php
$this->end();
