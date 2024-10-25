<div class="">
    <div>        
        <h3>Renovação de Contratos</h3>
    </div>

    <hr/>
    <div class="form-inline">
        <div class="form-group">
            <label class="control-label" style="width: 90px;">Vencimento</label>
        </div>
        <div class="form-group">
            <input id="data-vencimento-de" class="form-control" name="data_vencimento_de" value=""/>
        </div>
        <div class="form-group">
            <label class="control-label" style="width: 30px; margin-left: 10px;">Até</label>
        </div>
        <div class="form-group">
            <input id="data-vencimento-ate" class="form-control" name="data_vencimento_ate" value=""/>
        </div>
        <div class="form-group" style="padding-left: 10px;">
            <button class="btn btn-success" id="btn-pesquisar"><i class="fa fa-search fa-lg" ></i></button>
        </div>
    </div>

    <hr/>
    <table class="display table table-hover table-responsive" id="contents-table">
        <thead>
            <tr>
                <th>Recebimento</th>
                <th>Paciente</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Ultima Aula</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Recebimento</th>
                <th>Paciente</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Ultima Aula</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Renovar Contrato</h4>
            </div>
            <div class="modal-body">
                <form id="form-renovacao" class="form-horizontal" method="post" data-action="<?php echo $this->Html->url(array("controller" => "renovacao_contrato", "action" => "renovar")); ?>">
                    <input type="hidden" name="idrecebimento" id="idrecebimento" value=""/>
                    <div class="form-group" >
                        <label for="valor_referente" class="control-label col-md-3">Valor referente a :</label>
                        <div class="col-md-9">
                            <label style="font-weight: normal;" >
                                <input id="valor_referente" name="valor_referente" type="radio" value="PARCELA" class="radios_check validarChange" checked/> Parcela
                            </label>
                            &nbsp;&nbsp;
                            <label style="font-weight: normal;" >
                                <input id="valor_referente" name="valor_referente" type="radio" value="TOTAL" class="radios_check validarChange" /> Total do recebimento
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Valor:</label>
                        <div class="col-md-9">
                            <input id="valor" name="valor" placeholder="00,00" class="form-control" required />
                        </div>                            
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Data Competência:</label>
                        <div class="col-md-9">
                            <input id="data_competencia" name="data_competencia" placeholder="99/99/9999" class="form-control" required />
                        </div>                            
                    </div>       
                    <div class="form-group ">
                        <label class="control-label col-md-3">Quantidade de Parcelas:</label>
                        <div class="col-md-9">
                            <input id="quantidade_parcela" name="quantidade_parcela" placeholder="12" class="form-control" required />
                        </div>                         
                    </div>       
                    <div class="form-group ">
                        <label class="control-label col-md-3">Quantidade de Sessões por mês:</label>
                        <div class="col-md-9">
                            <input id="quantidade_sessoes_mes" name="quantidade_sessoes_mes" placeholder="12" class="form-control" required />
                        </div>                         
                    </div>       
                    <div class="form-group ">
                        <label for="id_financeiro_tipo" class="control-label col-md-3">Tipo Financeiro:</label>
                        <div class="col-md-9">
                            <select id="id_financeiro_tipo" name="id_financeiro_tipo" data-required="true" class="form-control combos" title="Tipo Financeiro">
                                <?php
                                $total_tf = count($TipoFinanceiros);
                                if ($total_tf > 0):
                                    for ($i_tf = 0; $i_tf < $total_tf; $i_tf++) :
                                        ?>
                                        <option value="<?php echo $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"]; ?>" class="op-<?php echo $TipoFinanceiros[$i_tf]["t"]["idfinanceirotipo"]; ?>" ><?php echo $TipoFinanceiros[$i_tf]["t"]["tipo"]; ?></option>
                                        <?php
                                    endfor;
                                endif;
                                ?>
                            </select>
                        </div>                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="renovarContrato()">Renovar Contrato</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->css(array("dataTables/jquery.dataTables_themeroller.min.css", "dataTables/dataTables.bootstrap.min.css", "datepicker.min.css", "select2/select2.min.css",
    "icheck/all.css", "fileinput.min.css", "jspanel/jquery-ui.min.css", "jspanel/jquery.jspanel.css"), null, array("block" => "css"));

echo $this->Html->script(array("jspanel/jquery-ui.min.js", "jquery.ui.touch-punch.min.js", "dataTables/jquery.dataTables.min.js", "dataTables/dataTables.bootstrap.min.js",
    "bootstrap-datepicker.min.js", "jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js", "select2/select2.full.min.js", "icheck/icheck.min.js",
    "jquery.form.min.js", "fileinput.min.js", "jspanel/mobile-detect.min.js", "jspanel/jquery.jspanel.js", "moment-with-locales.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">

    jQuery(document).ready(function () {
        var table = jQuery('#contents-table').dataTable({
            pageLength: 25,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo $this->Html->url(array("controller" => "renovacao_contrato", "action" => "ajax_renovacao")); ?>",
                "type": "POST",
                "data": function (d) {
                    d.vencimento_de = $('#data-vencimento-de').val(),
                    d.vencimento_ate = $('#data-vencimento-ate').val()
                }
            },
            "columns": [
                {"data": "r.descricao", "bSortable": false},
                {"data": "p.nome", "bSortable": false},
                {"data": "r.valor", "bSortable": false},
                {"data": "r.data_vencimento", "bSortable": false},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(1).html(data.p.nome + " " + data.p.sobrenome);
                jQuery('td', row).eq(2).addClass("text-center");
                jQuery('td', row).eq(3).addClass("text-center");
                jQuery('td', row).eq(4).addClass("text-center").html('<div class="btn-group"> <button class="btn btn-primary" onclick="gotoGestao(' + data.r.idrecebimento + ')"><i class="fa fa-edit fa-lg"></i></button> <a class="btn btn-success" onclick="renovar(' + data.r.idrecebimento + ')" data-toggle="tooltip" data-placement="top" title="Renovar Contrato" target="_blank"><i class="fa fa-repeat fa-lg"></i></a> </div>');
            }
        });
        
        $("#btn-pesquisar").click(function (){            
            table.fnDraw();
        });

        $('[data-toggle="tooltip"]').tooltip();

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

        jQuery("#data-vencimento-de").mask("99/99/9999");
        jQuery("#data-vencimento-ate").mask("99/99/9999");

        jQuery('#form-renovacao').validate({
            onKeyup: true,
            onChange: true,
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

        jQuery('.combos').select2({
            autocomplete: true,
            width: "100%"
        });
        $("#valor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});

    });

    function gotoGestao(idrecebimento) {
        var textSearch = jQuery("#contents-table_filter label input[type='search']").val();
        window.location.href = $NOME_APLICACAO + "/recebimento/gestao/" + idrecebimento + "?search=" + textSearch;
    }

    function renovar(idrecebimento) {
        if (idrecebimento !== null && idrecebimento !== undefined) {
            jQuery('#idrecebimento').val(idrecebimento);
            jQuery.ajax({
                url: $NOME_APLICACAO + "/renovacao_contrato/ajax_recebimento_detalhes?idrecebimento=" + idrecebimento,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = $.parseJSON(data);
                        jQuery("#valor").val(json.recebimento.valor);
                        jQuery("#data_competencia").val(json.recebimento.data_competencia_nova);
                        jQuery("#quantidade_parcela").val(json.recebimento.quantidade_parcela);
                        jQuery("#quantidade_sessoes_mes").val(json.recebimento.quantidade_sessoes / json.recebimento.quantidade_parcela);
                        jQuery("#id_financeiro_tipo").select2('val', json.financeiro.id_financeiro_tipo);
                        jQuery('#myModal').modal('show');
                    }
                },
                error: function () {

                }
            });
        }
    }

    function renovarContrato() {
        if (jQuery("#valor").val() !== "" && jQuery("#data_competencia").val() !== "" && jQuery("#data_competencia").val() !== "__/__/____"
                && jQuery("#quantidade_parcela").val() && jQuery("#quantidade_sessoes_mes").val()) {
            var dadosForm = jQuery("#form-renovacao").serialize();
            var urlForm = jQuery("#form-renovacao").attr("data-action");
            jQuery.ajax({
                url: urlForm,
                type: 'POST',
                data: dadosForm,
                success: function (data, textStatus, jqXHR) {
                    jQuery("#myModal").modal("hide");
                    jQuery.jsPanel({
                        paneltype: 'hint',
                        theme: 'success',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Contrato renovado com sucesso.</div>"
                    });
                    location.reload();
                },
                error: function () {
                    jQuery("#myModal").modal("hide");
                    jQuery.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Falha ao renovar contrato.</div>"
                    });
                }
            });
        }
    }
</script>
<?php
$this->end();
