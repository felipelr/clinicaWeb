<div class="">
    <div>        
        <h3>Relatório de recebimentos</h3>
        <br/>
    </div>
    <div class="well">
        <form id="form-consultar" class="form-inline" data-action="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "ajax_relatorio_recebimento")); ?>" method="post">
            <div class="row-divisao">
                <div class="form-group">
                    <label class="control-label" style="width: 90px;">Cadastro</label>
                </div>
                <div class="form-group">
                    <input id="data-cadastro-de" class="form-control" name="data_cadastro_de" value="<?php echo $dataCadastroDE; ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label" style="width: 40px;">Até</label>
                </div>
                <div class="form-group">
                    <input id="data-cadastro-ate" class="form-control" name="data_cadastro_ate" value="<?php echo $dataCadastroATE; ?>"/>
                </div>
                <div class="form-group" id="div-radios-cadastro">
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro1" value="1" <?php echo (int) $radioDataCadastro_ == 1 ? "checked" : "" ?> > Hoje
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro2" value="2" <?php echo (int) $radioDataCadastro_ == 2 ? "checked" : "" ?> > Amanhã
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro3" value="3" <?php echo (int) $radioDataCadastro_ == 3 ? "checked" : "" ?> > Esta semana
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro4" value="4" <?php echo (int) $radioDataCadastro_ == 4 ? "checked" : "" ?> > Este mês
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_cadastro" id="radio-data-cadastro5" value="5" <?php echo (int) $radioDataCadastro_ == 5 ? "checked" : "" ?> > Este ano
                    </label>
                </div>
                <div class="form-group">
                    &nbsp;<button class="btn btn-danger btn-flat" type="button" onclick="limparDataCadastro()">Limpar</button>
                </div>
            </div>
            <div class="row-divisao">
                <div class="form-group">
                    <label class="control-label" style="width: 90px;">Vencimento</label>
                </div>
                <div class="form-group">
                    <input id="data-vencimento-de" class="form-control" name="data_vencimento_de" value="<?php echo $dataVencimentoDE; ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label" style="width: 40px;">Até</label>
                </div>
                <div class="form-group">
                    <input id="data-vencimento-ate" class="form-control" name="data_vencimento_ate" value="<?php echo $dataVencimentoATE; ?>"/>
                </div>
                <div class="form-group" id="div-radios-vencimento">
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_vencimento" id="radio-data-vencimento1" value="1" <?php echo (int) $radioDataVencimento_ == 1 ? "checked" : "" ?> > Hoje
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_vencimento" id="radio-data-vencimento2" value="2" <?php echo (int) $radioDataVencimento_ == 2 ? "checked" : "" ?> > Amanhã
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_vencimento" id="radio-data-vencimento3" value="3" <?php echo (int) $radioDataVencimento_ == 3 ? "checked" : "" ?> > Esta semana
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_vencimento" id="radio-data-vencimento4" value="4" <?php echo (int) $radioDataVencimento_ == 4 ? "checked" : "" ?> > Este mês
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_vencimento" id="radio-data-vencimento5" value="5" <?php echo (int) $radioDataVencimento_ == 5 ? "checked" : "" ?> > Este ano
                    </label>
                </div>
                <div class="form-group">
                    &nbsp;<button class="btn btn-danger btn-flat" type="button" onclick="limparDataVencimento()">Limpar</button>
                </div>
            </div>
            <div class="row-divisao">
                <div class="form-group">
                    <label class="control-label" style="width: 90px;">Pagamento</label>
                </div>
                <div class="form-group">
                    <input id="data-pagamento-de" class="form-control" name="data_pagamento_de" value="<?php echo $dataPagamentoDE; ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label" style="width: 40px;">Até</label>
                </div>
                <div class="form-group">
                    <input id="data-pagamento-ate" class="form-control" name="data_pagamento_ate" value="<?php echo $dataPagamentoATE; ?>"/>
                </div>
                <div class="form-group" id="div-radios-pagamento">
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_pagamento" id="radio-data-pagamento1" value="1" <?php echo (int) $radioDataVencimento_ == 1 ? "checked" : "" ?> > Hoje
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_pagamento" id="radio-data-pagamento2" value="2" <?php echo (int) $radioDataVencimento_ == 2 ? "checked" : "" ?> > Amanhã
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_pagamento" id="radio-data-pagamento3" value="3" <?php echo (int) $radioDataVencimento_ == 3 ? "checked" : "" ?> > Esta semana
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_pagamento" id="radio-data-pagamento4" value="4" <?php echo (int) $radioDataVencimento_ == 4 ? "checked" : "" ?> > Este mês
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radio_data_pagamento" id="radio-data-pagamento5" value="5" <?php echo (int) $radioDataVencimento_ == 5 ? "checked" : "" ?> > Este ano
                    </label>
                </div>
                <div class="form-group">
                    &nbsp;<button class="btn btn-danger btn-flat" type="button" onclick="limparDataPagamento()">Limpar</button>
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
                <div class="form-group">
                    <label class="control-label" style="width: 120px;">Plano de Contas</label>
                </div>
                <div class="form-group" style="min-width: 300px;">
                    <select id="planos-contas" class="form-control" name="plano_contas">
                        <option value="0">Todos</option>
                        <?php
                        $total_ = count($Planos);
                        if ($total_ > 0):
                            for ($i_ = 0; $i_ < $total_; $i_++) :
                                ?>
                                <option value="<?php echo $Planos[$i_]["p"]["idplanocontas"]; ?>" <?php echo $Planos[$i_]["p"]["idplanocontas"] == $planoContas_ ? "selected" : ""; ?> ><?php echo $Planos[$i_]["p"]["descricao"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>                    
                </div>                
            </div>            
            <div class="row-divisao">
                <div class="form-group">
                    <label class="control-label" style="width: 90px;">Paciente</label>
                </div>
                <div class="form-group" style="min-width: 300px; margin-right: 15px;">                    
                    <select id="pacientes" class="form-control" name="paciente">
                        <option value="0">Todos</option>
                        <?php
                        $total_f = count($Pacientes);
                        if ($total_f > 0):
                            for ($i_f = 0; $i_f < $total_f; $i_f++) :
                                ?>
                                <option value="<?php echo $Pacientes[$i_f]["p"]["idpaciente"]; ?>" <?php echo $Pacientes[$i_f]["p"]["idpaciente"] == $paciente_ ? "selected" : ""; ?> ><?php echo $Pacientes[$i_f]["p"]["nome"] . " " . $Pacientes[$i_f]["p"]["sobrenome"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>                   
                </div>
                <div class="form-group">
                    <label class="control-label" style="width: 120px;">Clinica</label>        
                </div>
                <div class="form-group" style="width: 300px;">
                    <select id="clinica" class="form-control" name="clinica">
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
                    <label class="control-label" style="width: 160px;">Nome do recebimento:</label>                
                </div>
                <div class="form-group" style="margin-right: 15px;">
                    <input name="nome_recebimento" class="form-control" value="<?php echo $nome_recebimento; ?>"/>                 
                </div>                
                <div class="form-group">
                    <label class="control-label" style="width: 100px;">Cheque?</label>                
                </div>
                <div class="form-group">
                    <select id="cheque" class="form-control" name="cheque">
                        <option value="0" <?php echo $cheque_ == 0 ? "selected" : ""; ?> >Não</option>
                        <option value="1" <?php echo $cheque_ == 1 ? "selected" : ""; ?> >Sim</option>
                    </select>                   
                </div>
                <div class="form-group">
                    <label class="control-label" style="width: 15%; margin-left: 15px">Pago?</label>                
                </div>
                <div class="form-group">
                    <select id="pago" class="form-control" name="pago">
                        <option value="0" <?php echo $pago_ == 0 ? "selected" : ""; ?> >Não</option>
                        <option value="1" <?php echo $pago_ == 1 ? "selected" : ""; ?> >Sim</option>
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
    <div>
    <table id="tabela-recebimento" class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th>Recebimento</th>
                <th>Centro de custo</th>
                <th>Financeiro</th>
                <th>Paciente</th>
                <th class="text-center">Data Vencimento</th>
                <th class="text-center">Data Pagamento</th>
                <th class="text-center">Valor total</th>
                <th class="text-center">Valor Recebido</th>
                <th class="text-center">Valor restante</th>
            </tr>
        </thead>        
        <tbody id="relatorio_corpo">
            <tr>
                <td colspan='9' class='text-center'>Não há registros</td>
            </tr>        
        </tbody>
    </table> 
    </div>
</div>

<?php
echo $this->Html->css(array("datepicker.min.css", "icheck/all.css", "select2/select2.min.css", "style.relatorio.recebimento.css"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskedinput.min.js", "icheck/icheck.min.js", "select2/select2.full.min.js", "jquery.form.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    var carregando = false;

    jQuery(document).ready(function () {
        var checkinCadastro = $('#data-cadastro-de').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            if (ev.date.valueOf() > checkoutCadastro.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkoutCadastro.setValue(newDate);
            }
            checkinCadastro.hide();
            $('#data-cadastro-ate')[0].focus();
        }).data('datepicker');

        var checkoutCadastro = $('#data-cadastro-ate').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            checkoutCadastro.hide();
        }).data('datepicker');

        var checkinVencimento = $('#data-vencimento-de').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            if (ev.date.valueOf() > checkoutVencimento.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkoutVencimento.setValue(newDate);
            }
            checkinVencimento.hide();
            $('#data-vencimento-ate')[0].focus();
        }).data('datepicker');

        var checkoutVencimento = $('#data-vencimento-ate').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            checkoutVencimento.hide();
        }).data('datepicker');
        
        var checkinPagamento = $('#data-pagamento-de').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > checkoutPagamento.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkoutPagamento.setValue(newDate);
            }
            checkinPagamento.hide();
            $('#data-pagamento-ate')[0].focus();
        }).data('datepicker');

        var checkoutPagamento = $('#data-pagamento-ate').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function(ev) {
            checkoutPagamento.hide();
        }).data('datepicker');

        jQuery("#data-cadastro-de").mask("99/99/9999");
        jQuery("#data-cadastro-ate").mask("99/99/9999");
        jQuery("#data-vencimento-de").mask("99/99/9999");
        jQuery("#data-vencimento-ate").mask("99/99/9999");
        jQuery("#data-pagamento-de").mask("99/99/9999");
        jQuery("#data-pagamento-ate").mask("99/99/9999");

        $('#div-radios-cadastro input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        $('#div-radios-vencimento input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        $('#div-radios-pagamento input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $('#tipos-financeiros, #planos-contas, #pacientes, #cheque, #clinica').select2({
            autocomplete: true,
            width: "100%"
        });

        //Cadastro
        $('#radio-data-cadastro1').on('ifChecked', function (event) {
            var now = new Date();
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(now);
        });
        $('#radio-data-cadastro2').on('ifChecked', function (event) {
            var now = new Date();
            now.setDate(now.getDate() + 1);
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(now);
        });
        $('#radio-data-cadastro3').on('ifChecked', function (event) {
            var dateLastSunday = "<?php echo date("w") == 0 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("last Sunday"))); ?>";
            var dateNesxtSaturday = "<?php echo date("w") == 6 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("next Saturday"))); ?>";
            var now = new Date(dateLastSunday);
            var dateFim = new Date(dateNesxtSaturday);
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(dateFim);
        });
        $('#radio-data-cadastro4').on('ifChecked', function (event) {
            var fistDay = "<?php echo date("Y-m-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-m-t H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(dateFim);
        });
        $('#radio-data-cadastro5').on('ifChecked', function (event) {
            var fistDay = "<?php echo date("Y-01-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-12-31 H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinCadastro.setValue(now);
            checkoutCadastro.setValue(dateFim);
        });

        //Vencimento
        $('#radio-data-vencimento1').on('ifChecked', function (event) {
            var now = new Date();
            checkinVencimento.setValue(now);
            checkoutVencimento.setValue(now);
        });
        $('#radio-data-vencimento2').on('ifChecked', function (event) {
            var now = new Date();
            now.setDate(now.getDate() + 1);
            checkinVencimento.setValue(now);
            checkoutVencimento.setValue(now);
        });
        $('#radio-data-vencimento3').on('ifChecked', function (event) {
            var dateLastSunday = "<?php echo date("w") == 0 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("last Sunday"))); ?>";
            var dateNesxtSaturday = "<?php echo date("w") == 6 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("next Saturday"))); ?>";
            var now = new Date(dateLastSunday);
            var dateFim = new Date(dateNesxtSaturday);
            checkinVencimento.setValue(now);
            checkoutVencimento.setValue(dateFim);
        });
        $('#radio-data-vencimento4').on('ifChecked', function (event) {
            var fistDay = "<?php echo date("Y-m-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-m-t H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinVencimento.setValue(now);
            checkoutVencimento.setValue(dateFim);
        });
        $('#radio-data-vencimento5').on('ifChecked', function (event) {
            var fistDay = "<?php echo date("Y-01-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-12-31 H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinVencimento.setValue(now);
            checkoutVencimento.setValue(dateFim);
        });
        
        //Pagamento
        $('#radio-data-pagamento1').on('ifChecked', function(event) {
            var now = new Date();
            checkinPagamento.setValue(now);
            checkoutPagamento.setValue(now);
        });
        $('#radio-data-pagamento2').on('ifChecked', function(event) {
            var now = new Date();
            now.setDate(now.getDate() + 1);
            checkinPagamento.setValue(now);
            checkoutPagamento.setValue(now);
        });
        $('#radio-data-pagamento3').on('ifChecked', function(event) {
            var dateLastSunday = "<?php echo date("w") == 0 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("last Sunday"))); ?>";
            var dateNesxtSaturday = "<?php echo date("w") == 6 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("next Saturday"))); ?>";
            var now = new Date(dateLastSunday);
            var dateFim = new Date(dateNesxtSaturday);
            checkinPagamento.setValue(now);
            checkoutPagamento.setValue(dateFim);
        });
        $('#radio-data-pagamento4').on('ifChecked', function(event) {
            var fistDay = "<?php echo date("Y-m-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-m-t H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinPagamento.setValue(now);
            checkoutPagamento.setValue(dateFim);
        });
        $('#radio-data-pagamento5').on('ifChecked', function(event) {
            var fistDay = "<?php echo date("Y-01-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-12-31 H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinPagamento.setValue(now);
            checkoutPagamento.setValue(dateFim);
        });

        jQuery('.combos').select2({
            autocomplete: true,
            width: "100%"
        });

        consultar();
    });



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
                success: function (data, textStatus, jqXHR) {
                    jQuery("#tabela-recebimento tbody").html("");
                    jQuery("#tabela-recebimento tbody").html(data);
                    jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                },
                error: function () {
                    jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                }
            });
            $('html, body').animate({
                scrollTop: $("#tabela-recebimento").offset().top
            }, 2000);
            
        }

    }
    
    function limparDataCadastro(){
        $("#data-cadastro-de").val("");
        $("#data-cadastro-ate").val("");
    }
    function limparDataVencimento(){
        $("#data-vencimento-de").val("");
        $("#data-vencimento-ate").val("");
    }
    function limparDataPagamento(){
        $("#data-pagamento-de").val("");
        $("#data-pagamento-ate").val("");
    }

</script>
<?php
$this->end();
