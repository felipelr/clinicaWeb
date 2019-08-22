<div class="">
    <div>        
        <h3>Relatório de Caixa</h3>
        <hr/>
        <br/>
    </div>
    <div class="well">
        <form id="form-consultar" class="form-inline" data-action="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "ajax_relatorio_caixa")); ?>" method="post">
            <div class="row-divisao">
                <div class="form-group">
                    <label class="control-label" style="width: 90px;">Data de Movimentação</label>
                </div>
                <div class="form-group">
                    <input id="data-movimentacao-de" class="form-control" name="data_movimentacao_de" value="<?php echo $data_movimentacaoDE; ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label" style="width: 40px;">Até</label>
                </div>
                <div class="form-group">
                    <input id="data-movimentacao-ate" class="form-control" name="data_movimentacao_ate" value="<?php echo $data_movimentacaoATE; ?>"/>
                </div>
                <div class="form-group" id="div-radios-cadastro">
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro1" value="1" <?php echo (int) $radioDataCadastro_ == 1 ? "checked" : "" ?> > Hoje
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro2" value="2" > Amanhã
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro3" value="3" > Esta semana
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro4" value="4" > Este mês
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro5" value="5" > Este ano
                    </label>
                </div>
            </div>
            <div class="row-divisao">
                <div class="form-group">
                    <label class="control-label" style="width: 90px;">Tipo Finaceiro</label>
                </div>
                <div class="form-group" style="min-width: 300px; margin-right: 15px;">
                    <select id="tipos-financeiros" class="form-control" name="tipo_financeiro">
                        <option value="0">Todos</option>
                        <?php
                        $total_tf = count($TipoFinanceirosRelatorio);
                        if ($total_tf > 0):
                            for ($i_tf = 0; $i_tf < $total_tf; $i_tf++) :
                                ?>
                        <option value="<?php echo $TipoFinanceirosRelatorio[$i_tf]["t"]["idfinanceirotipo"]; ?>" <?php echo $TipoFinanceirosRelatorio[$i_tf]["t"]["idfinanceirotipo"] == $tipoFinanceiro_ ? "selected" : ""; ?> ><?php echo $TipoFinanceirosRelatorio[$i_tf]["t"]["tipo"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"  style="width: 70px;">Clinica</label>        
                </div>
                <div class="form-group" style="min-width: 300px;" >
                    <select id="clinica" class="form-control"  name="clinica">
<!--                        <option value="0">Todos</option>-->
                        <?php
                        $total_cli = count($Clinicas);
                        if ($total_cli > 0):
                            for ($i_cli = 0; $i_cli < $total_cli; $i_cli++) :
                                ?>
                        <option value="<?php echo $Clinicas[$i_cli]["c"]["idclinica"]; ?>" <?php echo $Clinicas[$i_cli]["c"]["idclinica"] == $clinica_ ? "selected" : ""; ?> ><?php echo $Clinicas[$i_cli]["c"]["fantasia"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>                   
                </div>                
            </div>
            <div class="row-divisao">
                <div class="form-group">
                    <label class="control-label" style="width: 90px;">Caixa: </label>
                </div>
                <div class="form-group" style="min-width: 300px;">
                    <select id="DespesaIdCaixa" name="Caixa" data-required="true" class="form-control combos" title="Caixa">
                            <?php
                            $total_c = count($Caixas);
                            if ($total_c > 0):
                                for ($i_c = 0; $i_c < $total_c; $i_c++) :
                                    ?>
                        <option value="<?php echo $Caixas[$i_c]["c"]["idcaixaloja"]; ?>" ><?php echo $Caixas[$i_c]["c"]["nome_caixa"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                    </select>                 
                </div> 
            </div>         
            <div class="row-divisao">
                <br/>
                <button id="btn-consultar" type="button" onclick="consultar()" class="btn btn-primary center-block"><i class="fa fa-search fa-lg"></i> Consultar</button>
            </div>
        </form>
    </div>

    <hr/>
    <table id="tabela-despesas" class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th><button  type="button" class="btn btn-success center-block" onclick="movimentacao();"><i class="fa fa-calculator fa-lg"></i> Movimentações</button></th>
                <th style="display: none"><button  type="button" class="btn btn-warning center-block" onclick="fechar_caixa();"><i class="fa fa-lock fa-lg"></i> Fechar Caixa</button></th>
                <th colspan="4"></th>
            </tr>
            <tr>
                <th>Data de Pagamento</th>
                <th>Tipo de Movimentação</th>
                <th>Descrição</th>
                <th class="text-center">Parcela</th>
                <th class="text-center">Usuário</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Saldo</th>
            </tr>
        </thead>        
        <tbody id="relatorio_corpo">
        <td colspan='6' class='text-center'>Não há registros</td>
        </tbody>
    </table>
</div>

<!-- Modal Sangria no Caixa -->
<div class="modal fade" id="modalMovto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Movimentações no Caixa</h4>
            </div>
            <div class="modal-body">
                <form id="form-movimentacoes" class="form-horizontal" method="post" data-action="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "inserir_movimentacao")); ?>">
                    <div class="form-group">
                        <label class="col-md-3" style=" margin-top: 10%">Posição do Caixa Atual:</label>
                        <div class="col-md-9" id="tabela_posicao">
                            <table class="table table-striped" id="table-movimentacoes">

                            </table>
                        </div>                            
                    </div>                    
                    <input type="hidden" name="idcaixaloja" id="idcaixa-movto"/>
                    <div class="form-group">
                        <label class="col-md-3">Tipo de financeiro:</label>
                        <div class="col-md-9">
                            <select id="tipos-financeiros-movto" class="form-control" name="tipo_financeiro">
                                    <?php
                                    $total_tf = count($TipoFinanceiros);
                                    if ($total_tf > 0):
                                        for ($i_tf = 0; $i_tf < $total_tf; $i_tf++) :
                                            ?>
                                <option value="<?php echo $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"]; ?>" <?php echo $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"] == $tipoFinanceiro_ ? "selected" : ""; ?> ><?php echo $TipoFinanceiros[$i_tf]["t"]["tipo"]; ?></option>
                                            <?php
                                        endfor;
                                    endif;
                                    ?>
                            </select>
                        </div>                            
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Caixa:</label>
                        <div class="col-md-9">
                            <select id="IdCaixaLoja" name="Caixa" data-required="true" class="form-control combos" title="Caixa">
                                    <?php
                                    $total_c = count($Caixas);
                                    if ($total_c > 0):
                                        for ($i_c = 0; $i_c < $total_c; $i_c++) :
                                            ?>
                                <option class="caixas" value="<?php echo $Caixas[$i_c]["c"]["idcaixaloja"]; ?>" ><?php echo $Caixas[$i_c]["c"]["nome_caixa"]; ?></option>
                                            <?php
                                        endfor;
                                    endif;
                                    ?>
                            </select>  
                        </div>                            
                    </div>
                    <div class="form-group ">       
                        <label class="control-label col-md-3" >Valor:</label>                
                        <div class="col-md-9" style="padding-top: 5px">
                            <input name="valor_movto" data-required="true"   id="MovimentacaoValor" class="form-control" />                    
                        </div>                
                    </div>    
                    <div class="form-group ">
                        <label for="Entrada" class="control-label col-md-3">*Movimentação:</label>
                        <div class="col-md-9" style="padding-top: 5px" id="div-radios">                   
                            <input name="rbtMovimentacao" class="radios_check" type="radio" value="1" id="Entrada" checked />
                            <label for="Entrada">Entrada (Suprimento)</label>
                            &nbsp;&nbsp;
                            <input name="rbtMovimentacao"  class="radios_check" type="radio" value="0" id="Saida" />
                            <label for="Saida">Saída (Sangria)</label>
                            <input name="rbtMovimentacao"  class="radios_check" type="radio" value="2" id="transferencia" />
                            <label for="transferencia">Transferência</label>           
                        </div>                        
                    </div>
                    <div class="form-group" id="caixa_destino" style="display: none">
                        <label class="control-label col-md-3">Caixa Destino:</label>
                        <div class="col-md-9">
                            <select id="caixa_destino" name="caixa_destino" data-required="true" class="form-control combos" title="Caixa">
                                    <?php
                                    $total_c = count($Caixas);
                                    if ($total_c > 0):
                                        for ($i_c = 0; $i_c < $total_c; $i_c++) :
                                            ?>
                                <option class="caixas" value="<?php echo $Caixas[$i_c]["c"]["idcaixaloja"]; ?>" ><?php echo $Caixas[$i_c]["c"]["nome_caixa"]; ?></option>
                                            <?php
                                        endfor;
                                    endif;
                                    ?>
                            </select>  
                        </div>                            
                    </div>                    
                    <div class="form-group ">       
                        <label class="control-label col-md-3" >Observação:</label>                
                        <div class="col-md-9" style="padding-top: 5px">
                            <textarea class="form-control" data-required="true" name="observacao" id="observacao" value=""></textarea>                 
                        </div>                
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" id="btn-confirmar" onclick="confirmar_movimentacao()">Confirmar</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->css(array("datepicker.min.css", "icheck/all.css", "select2/select2.min.css", "style.relatorio.despesa.css?v=1.0"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js","jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery.maskedinput.min.js", "icheck/icheck.min.js", "select2/select2.full.min.js", "jquery.form.min.js"), array("block" => "script"));
$this->start('script');
?>

<script type="text/javascript">
    var carregando = false;

    jQuery(document).ready(function() {

        var checkinCadastro = $('#data-movimentacao-de').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > checkoutCadastro.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkoutCadastro.setValue(newDate);
            }
            checkinCadastro.hide();
            $('#data-movimentacao-ate')[0].focus();
        }).data('datepicker');

        var checkoutCadastro = $('#data-movimentacao-ate').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function(ev) {
            checkoutCadastro.hide();
        }).data('datepicker');

        jQuery("#data-movimentacao-de").mask("99/99/9999");
        jQuery("#data-movimentacao-ate").mask("99/99/9999");

        $('#div-radios-cadastro input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        jQuery('#div-radios').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $('#div-radios-vencimento input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $('#tipos-financeiros, #clinica, #tipos-financeiros-movto').select2({
            autocomplete: true,
            width: "100%"
        });

        //transferencia
        $('.radios_check').on('ifChecked', function(event) {
            if ($(this).val() == '2') {
                $('#caixa_destino').css("display", "");
            } else
                $('#caixa_destino').css("display", "none");
        });

        //Cadastro
        $('#radio-data-cadastro1').on('ifChecked', function(event) {
            var now = new Date();
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(now);
        });
        $('#radio-data-cadastro2').on('ifChecked', function(event) {
            var now = new Date();
            now.setDate(now.getDate() + 1);
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(now);
        });
        $('#radio-data-cadastro3').on('ifChecked', function(event) {
            var dateLastSunday = "<?php echo date("w") == 0 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("last Sunday"))); ?>";
            var dateNesxtSaturday = "<?php echo date("w") == 6 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("next Saturday"))); ?>";
            var now = new Date(dateLastSunday);
            var dateFim = new Date(dateNesxtSaturday);
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(dateFim);
        });
        $('#radio-data-cadastro4').on('ifChecked', function(event) {
            var fistDay = "<?php echo date("Y-m-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-m-t H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(dateFim);
        });
        $('#radio-data-cadastro5').on('ifChecked', function(event) {
            var fistDay = "<?php echo date("Y-01-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-12-31 H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(dateFim);
        });

        //Vencimento
        jQuery('.combos').select2({
            autocomplete: true,
            width: "100%"
        });

        consultar();
    });

    function movimentacao() {
        debugger;
        jQuery('#IdCaixaLoja option').each(function(){
           if(jQuery('#DespesaIdCaixa').val() == jQuery(this).val()){
               jQuery(this).attr('selected','selected');
           } 
        });
        jQuery('#IdCaixaLoja').trigger('change');
        $("#MovimentacaoValor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});

        jQuery.ajax({
            url: $NOME_APLICACAO + "/relatorio/tabela_movimentacoes/" + jQuery('#DespesaIdCaixa').val(),
            type: 'POST',
            success: function(data, textStatus, jqXHR) {
                jQuery("#table-movimentacoes").html("");
                jQuery("#table-movimentacoes").html(data);
          
            }
        });
        jQuery('#modalMovto').modal('show');
        
    }

    function fechar_caixa() {
    debugger;
        jQuery("#btn-consultar").html('<i class="fa fa-refresh fa-lg"></i> Aguarde carregando...');
        var data = jQuery("#form-consultar").serialize();
        jQuery("#form-consultar").attr("data-action", "<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "fechar_caixa")); ?>")
        var formURL = jQuery("#form-consultar").attr("data-action");
        
        jQuery.ajax({
            url: formURL,
            type: 'POST',
            data: data,
            success: function(data, textStatus, jqXHR) {
                jQuery("#tabela-despesas").html("");
                jQuery("#tabela-despesas").html(data);
                jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                carregando = false;
            },
            error: function() {
                jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                carregando = false;
            }
        });
    }

    function confirmar_movimentacao() {
        if (jQuery('#MovimentacaoValor').val() == '' || jQuery('#observacao').val() == '') {
            alert('Campo Valor e Observação são obrigatórios');
            return false;
        } else {
            if (!carregando) {
                carregando = true;
                jQuery("#btn-confirmar").html('<i class="fa fa-refresh fa-lg"></i> Aguarde Concluindo...');
                var data = jQuery("#form-movimentacoes").serialize();
                var formURL = jQuery("#form-movimentacoes").attr("data-action");

                jQuery.ajax({
                    url: formURL,
                    type: 'POST',
                    data: data,
                    success: function(data, textStatus, jqXHR) {
                        jQuery("#btn-confirmar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                        carregando = false;
                    },
                    error: function() {
                        jQuery("#btn-confirmar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                        carregando = false;
                    }
                });
            }
            jQuery('#modalMovto').modal('hide');

        }
    }

    function consultar() {
        if (!carregando) {
            carregando = true;
            jQuery("#btn-consultar").html('<i class="fa fa-refresh fa-lg"></i> Aguarde carregando...');
            var data = jQuery("#form-consultar").serialize();
            var formURL = jQuery("#form-consultar").attr("data-action");

            jQuery.ajax({
                url: formURL,
                type: 'POST',
                data: data,
                success: function(data, textStatus, jqXHR) {
                    jQuery("#tabela-despesas tbody").html("");
                    jQuery("#tabela-despesas tbody").html(data);
                    jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                },
                error: function() {
                    jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                }
            });

            $('html, body').animate({
                scrollTop: $("#relatorio_corpo").offset().top
            }, 2000);
        }

    }
</script>
<?php
$this->end();
