<div class="">
    <div>
        <a type="button" class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "index")); ?>"> Voltar</a>
        <a type="button" class="btn btn-primary" onclick="showModalInserir()"> Adicionar Parcela</a>
        <h3>Gestão do recebimento</h3>
    </div>

    <hr/>
    <div class="row">
        <div class="col-md-1">
            <a href="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "alterar", $dadosRecebimento['r']['idrecebimento'])); ?>" type="button" class="btn btn-primary" ><i class="fa fa-lg fa-edit"></i> Editar</a>
            <br/><br/>
        </div>
        <div class="col-md-11">
            <div class="col-md-6">
                <p><b>Recebimento:</b> <?php echo $dadosRecebimento['r']['descricao']; ?></p>
                <p><b>Profissional:</b> <?php echo $dadosRecebimento['p']['nome'] . ' ' . $dadosRecebimento['p']['sobrenome']; ?></p>
            </div>
            <div class="col-md-6">
                <p><b>Categoria da Aula:</b> <?php echo $dadosRecebimento['ca']['descricao']; ?></p>
                <p><b>Centro de Custo:</b> <?php echo $dadosRecebimento['cc']['descricao']; ?></p>
            </div>
        </div>
    </div>

    <hr/>
    <table class="table table-hover table-responsive table-striped" id="contents-table">
        <thead>
            <tr>
                <th class="text-center">Parcela</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Vencimento</th>
                <th class="text-center">Recebida</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>            
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center">Parcela</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Vencimento</th>
                <th class="text-center">Recebida</th>
                <th class="text-center">Ações</th>
            </tr>
        </tfoot>
    </table>

    <hr/>
    <div class="row">
        <div class="col-md-4">
            <div id="financeiro" class="box box-primary">
                <div class="box-header bg-blue">
                    <h3 class="box-title"><i class="fa fa-bar-chart fa-lg"></i> &nbsp; Total financeiro</h3>
                </div>
                <div class="box-body">
                    <h1 id="total-geral"></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="financeiro" class="box box-danger">
                <div class="box-header bg-red">
                    <h3 class="box-title"><i class="fa fa-bar-chart fa-lg"></i> &nbsp; Total a receber</h3>
                </div>
                <div class="box-body">
                    <h1 id="total-nao-pago"></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="financeiro" class="box box-success">
                <div class="box-header bg-green">
                    <h3 class="box-title"><i class="fa fa-bar-chart fa-lg"></i> &nbsp; Total recebido</h3>
                </div>
                <div class="box-body">
                    <h1 id="total-pago"></h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Visualizar Parcela Paga -->
<div class="modal fade" id="modalVisualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalhes da parcela</h4>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-responsive table-striped" id="table-visualizacao">
                    <thead>
                        <tr>
                            <th class="text-center">Parcela</th>
                            <th class="text-center">Valor</th>
                            <th class="text-center">Vencimento</th>
                            <th class="text-center">Pagamento</th>
                            <th class="text-center">Financeiro</th>
                            <th class="text-center">Usuário</th>
                            <th class="text-center">Caixa</th>
                        </tr>
                    </thead>
                    <tbody>            
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center">Parcela</th>
                            <th class="text-center">Valor</th>
                            <th class="text-center">Vencimento</th>
                            <th class="text-center">Pagamento</th>
                            <th class="text-center">Financeiro</th>
                            <th class="text-center">Usuário</th>
                            <th class="text-center">Caixa</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Receber Parcela -->
<div class="modal fade" id="modalReceberParcela" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Confirmar recebimento</h4>
            </div>
            <div class="modal-body">
                <form id="form-receber-parcela" class="form-horizontal" method="post" data-action="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "receber_parcela")); ?>">
                    <input type="hidden" name="idrecebimento" id="idrecebimento-pagar"/>
                    <input type="hidden" name="idfinanceiro" id="idfinanceiro-pagar"/>
                    <div class="form-group">
                        <label class="col-md-3">Tipo de financeiro:</label>
                        <div class="col-md-9">
                            <select id="select-tipo-financeiro" class="form-control combos" name="tipo_financeiro">
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
                    <div class="form-group">
                        <label class="col-md-3">Caixa:</label>
                        <div class="col-md-9">
                            <select id="select-caixa" class="form-control combos" name="caixa_loja">
                                <?php
                                $total_c = count($Caixas);
                                if ($total_c > 0):
                                    for ($i_c = 0; $i_c < $total_c; $i_c++) :
                                        ?>
                                        <option value="<?php echo $Caixas[$i_c]["c"]["idcaixaloja"]; ?>" class="op-<?php echo $Caixas[$i_c]["c"]["idcaixaloja"]; ?>" ><?php echo $Caixas[$i_c]["c"]["nome_caixa"]; ?></option>
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
                <button class="btn btn-success" onclick="receberParcela()">Receber</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alterar Parcela -->
<div class="modal fade" id="modalAlterarParcela" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Alterar parcela</h4>
            </div>
            <div class="modal-body">
                <form id="form-alterar-parcela" class="form-horizontal" method="post" data-action="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "alterar_parcela")); ?>">
                    <input type="hidden" name="idrecebimento" id="idrecebimento-alterar"/>
                    <input type="hidden" name="idfinanceiro" id="idfinanceiro-alterar"/>
                    <div class="form-group">
                        <label class="col-md-3">Valor:</label>
                        <div class="col-md-9">
                            <input id="valor-alterar" name="valor" placeholder="00,00" class="form-control" required />
                        </div>                            
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">Vencimento:</label>
                        <div class="col-md-9">
                            <input id="vencimento-alterar" name="data_vencimento" placeholder="99/99/9999" class="form-control" required />
                        </div>                            
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">Motivo:</label>
                        <div class="col-md-9">
                            <textarea id="motivo-alterar" rows="4" name="motivo" maxlength="255" wrap="hard" class="form-control" required></textarea>
                        </div>                            
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="alterarParcela()">Alterar</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Excluir Parcela -->
<div class="modal fade" id="modalExcluirParcela" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Excluir parcela</h4>
            </div>
            <div class="modal-body">
                <form id="form-excluir-parcela" class="form-horizontal" method="post" data-action="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "excluir_parcela")); ?>">
                    <input type="hidden" name="idrecebimento" id="idrecebimento-excluir"/>
                    <input type="hidden" name="idfinanceiro" id="idfinanceiro-excluir"/>
                    <div class="form-group">
                        <label class="col-md-3">Motivo:</label>
                        <div class="col-md-9">
                            <textarea id="motivo-excluir" rows="4" name="motivo" maxlength="255" wrap="hard" class="form-control" required></textarea>
                        </div>                            
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="excluirParcela()">Excluir</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Inserir Parcela -->
<div class="modal fade" id="modalInserirParcela" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Inserir parcela</h4>
            </div>
            <div class="modal-body">
                <form id="form-inserir-parcela" class="form-horizontal" method="post" data-action="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "inserir_parcela")); ?>">
                    <input type="hidden" name="idrecebimento" id="idrecebimento-inserir" value="<?php echo $idrecebimento; ?>"/>
                    <div class="form-group">
                        <label class="col-md-3">Valor:</label>
                        <div class="col-md-9">
                            <input id="valor-inserir" name="valor" placeholder="00,00" class="form-control" required />
                        </div>                            
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">Vencimento:</label>
                        <div class="col-md-9">
                            <input id="vencimento-inserir" name="data_vencimento" placeholder="99/99/9999" class="form-control" required />
                        </div>                            
                    </div>       
                    <div class="form-group ">
                        <label for="IdTipoFinanceiroInserir" class="col-md-3">Tipo Financeiro:</label>
                        <div class="col-md-9">
                            <select id="IdTipoFinanceiroInserir" name="id_financeiro_tipo" data-required="true" class="form-control combos" title="Tipo Financeiro">
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
                    <div class="form-group">
                        <label class="col-md-3">Eventos Disponíveis:</label>
                        <div class="col-md-9">
                            <input class="form-control" type="number" name="total_disponibilidade" min="0" value="<?php echo $dadosDisponibilidade['total']; ?>" />
                            <input id="IdEventoDisponibilidadeInserir" type="hidden" name="ideventodisponibilidade" value="<?php echo $dadosDisponibilidade['ideventodisponibilidade']; ?>"/>
                        </div>                            
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="inserirParcela()">Inserir</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->css(array("datepicker.min.css", "select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js",
    "select2/select2.full.min.js", "icheck/icheck.min.js", "jquery.form.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.combos').select2({
            autocomplete: true,
            width: "100%"
        });
        $("#valor-alterar").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
        $("#vencimento-alterar").mask("99/99/9999");

        $("#valor-inserir").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
        $("#vencimento-inserir").mask("99/99/9999");

        carregarDados();
    });

    function carregarDados() {
        jQuery.ajax({
            url: $NOME_APLICACAO + "/recebimento/ajax_gestao?idrecebimento=<?php echo $idrecebimento; ?>",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    var html = "";
                    $.each(json.dados, function (index, value) {
                        var pago = parseInt(value.financeiro.pago) === 0 ? 'NÃO' : 'SIM';
                        var botoes = parseInt(value.financeiro.pago) === 0 ? '<div class="btn-group"> <button class="btn btn-success" title="Receber parcela" onclick="showModalReceber(' + value.financeiro.idfinanceiro + ')"><i class="fa fa-usd"></i></button> <button class="btn btn-warning" title="Alterar valor da parcela" onclick="showModalAlterar(' + value.financeiro.idfinanceiro + ')"><i class="fa fa-pencil"></i></button> <button class="btn btn-danger" title="Excluir parcela" onclick="showModalExcluir(' + value.financeiro.idfinanceiro + ')"><i class="fa fa-trash"></i></button> </div>' : '<div class="btn-group"> <button class="btn btn-primary" title="Visualizar detalhes" onclick="vizualizar(' + value.financeiro.idfinanceiro + ')"><i class="fa fa-search"></i></button> </div>';
                        html += '<tr><td class="text-center">' + value.financeiro.parcela + '/' + value.financeiro.total_parcela + '</td> <td class="text-center">' + value.financeiro.valor + '</td> <td class="text-center">' + value.financeiro.data_vencimento + '</td> <td class="text-center">' + pago + '</td> <td class="text-center"> ' + botoes + ' </td></tr>';
                    });
                    jQuery('#contents-table tbody').html(html);
                }
            },
            error: function () {

            }
        });

        jQuery.ajax({
            url: $NOME_APLICACAO + "/recebimento/ajax_gestao_totais?idrecebimento=<?php echo $idrecebimento; ?>",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    jQuery('#total-geral').html(json.dados.total_geral);
                    jQuery('#total-nao-pago').html(json.dados.total_nao_pago);
                    jQuery('#total-pago').html(json.dados.total_pago);
                }
            },
            error: function () {

            }
        });
    }

    function showModalReceber(idfinanceiro) {
        if (idfinanceiro !== null && idfinanceiro !== undefined) {
            jQuery.ajax({
                url: $NOME_APLICACAO + "/recebimento/ajax_parcela_detalhes?idfinanceiro=" + idfinanceiro,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = $.parseJSON(data);
                        jQuery("#idfinanceiro-pagar").val(json.financeiro.idfinanceiro);
                        jQuery("#idrecebimento-pagar").val(json.financeiro.id_recebimento);
                        jQuery("#select-caixa").select2('val', json.financeiro.id_caixa_loja); //set the value
                        jQuery("#select-tipo-financeiro").select2('val', json.financeiro.id_financeiro_tipo); //set the value
                        jQuery("#modalReceberParcela").modal("show");
                    }
                },
                error: function () {

                }
            });
        }
    }

    function showModalAlterar(idfinanceiro) {
        if (idfinanceiro !== null && idfinanceiro !== undefined) {
            jQuery.ajax({
                url: $NOME_APLICACAO + "/recebimento/ajax_parcela_detalhes?idfinanceiro=" + idfinanceiro,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = $.parseJSON(data);
                        jQuery("#idfinanceiro-alterar").val(json.financeiro.idfinanceiro);
                        jQuery("#idrecebimento-alterar").val(json.financeiro.id_recebimento);
                        jQuery("#valor-alterar").val(json.financeiro.valor);
                        jQuery("#vencimento-alterar").val(json.financeiro.data_vencimento);
                        jQuery("#motivo-alterar").val("");
                        jQuery("#modalAlterarParcela").modal("show");
                    }
                },
                error: function () {

                }
            });
        }
    }

    function showModalExcluir(idfinanceiro) {
        if (idfinanceiro !== null && idfinanceiro !== undefined) {
            jQuery.ajax({
                url: $NOME_APLICACAO + "/recebimento/ajax_parcela_detalhes?idfinanceiro=" + idfinanceiro,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = $.parseJSON(data);
                        jQuery("#idfinanceiro-excluir").val(json.financeiro.idfinanceiro);
                        jQuery("#idrecebimento-excluir").val(json.financeiro.id_recebimento);
                        jQuery("#motivo-excluir").val("");
                        jQuery("#modalExcluirParcela").modal("show");
                    }
                },
                error: function () {

                }
            });
        }
    }

    function receberParcela() {
        var dadosForm = jQuery("#form-receber-parcela").serialize();
        var urlForm = jQuery("#form-receber-parcela").attr("data-action");
        jQuery.ajax({
            url: urlForm,
            type: 'POST',
            data: dadosForm,
            success: function (data, textStatus, jqXHR) {
                carregarDados();
                jQuery("#modalReceberParcela").modal("hide");
            },
            error: function () {
                carregarDados();
                jQuery("#modalReceberParcela").modal("hide");
            }
        });
    }

    function alterarParcela() {
        if (jQuery("#valor-alterar").val() !== "" && jQuery("#motivo-alterar").val() !== "") {
            var dadosForm = jQuery("#form-alterar-parcela").serialize();
            var urlForm = jQuery("#form-alterar-parcela").attr("data-action");
            jQuery.ajax({
                url: urlForm,
                type: 'POST',
                data: dadosForm,
                success: function (data, textStatus, jqXHR) {
                    carregarDados();
                    jQuery("#modalAlterarParcela").modal("hide");
                },
                error: function () {
                    carregarDados();
                    jQuery("#modalAlterarParcela").modal("hide");
                }
            });
        }
    }

    function excluirParcela() {
        if (jQuery("#motivo-excluir").val() !== "") {
            var dadosForm = jQuery("#form-excluir-parcela").serialize();
            var urlForm = jQuery("#form-excluir-parcela").attr("data-action");
            jQuery.ajax({
                url: urlForm,
                type: 'POST',
                data: dadosForm,
                success: function (data, textStatus, jqXHR) {
                    carregarDados();
                    jQuery("#modalExcluirParcela").modal("hide");
                },
                error: function () {
                    carregarDados();
                    jQuery("#modalExcluirParcela").modal("hide");
                }
            });
        }
    }

    function vizualizar(idfinanceiro) {
        if (idfinanceiro !== null && idfinanceiro !== undefined) {
            jQuery.ajax({
                url: $NOME_APLICACAO + "/recebimento/ajax_parcela_detalhes?idfinanceiro=" + idfinanceiro,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = $.parseJSON(data);
                        var html = '<tr> <td class="text-center"> ' + json.financeiro.parcela + '/' + json.financeiro.total_parcela + ' </td> <td class="text-center"> ' + json.financeiro.valor + ' </td> <td class="text-center"> ' + json.financeiro.data_vencimento + ' </td> <td class="text-center"> ' + json.financeiro.data_pagamento + ' </td>  <td class="text-center"> ' + json.tipo_financeiro.tipo + ' </td> <td class="text-center"> ' + json.usuario.nome + ' ' + json.usuario.sobrenome + ' </td> <td class="text-center"> ' + json.caixa_loja.nome_caixa + ' </td> </tr>';
                        jQuery("#table-visualizacao tbody").html(html);
                        jQuery("#modalVisualizar").modal("show");
                    }
                },
                error: function () {

                }
            });
        }
    }

    function showModalInserir() {
        jQuery("#modalInserirParcela").modal("show");
    }

    function inserirParcela() {
        if (jQuery("#valor-inserir").val() !== "" && jQuery("#vencimento-inserir").val() !== "") {
            var dadosForm = jQuery("#form-inserir-parcela").serialize();
            var urlForm = jQuery("#form-inserir-parcela").attr("data-action");
            jQuery.ajax({
                url: urlForm,
                type: 'POST',
                data: dadosForm,
                success: function (data, textStatus, jqXHR) {
                    jQuery("#valor-inserir").val("");
                    jQuery("#vencimento-inserir").val("");
                    carregarDados();
                    jQuery("#modalInserirParcela").modal("hide");
                    jQuery("#IdEventoDisponibilidadeInserir").val(data.total);
                },
                error: function () {
                    jQuery("#valor-inserir").val("");
                    jQuery("#vencimento-inserir").val("");
                    carregarDados();
                    jQuery("#modalInserirParcela").modal("hide");
                }
            });
        }
    }
</script>
<?php
$this->end();
