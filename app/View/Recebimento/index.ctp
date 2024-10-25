<div class="">
    <div>
        <a type="button" class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "cadastrar")); ?>"> Cadastrar Recebimento</a>
        <h3>Recebimentos cadastrados</h3>
    </div>

    <hr />
    <div>
        <h4>Filtros</h4>
        <div style="padding-left: 16px;">
            <ul class="list-inline">
                <li>
                    <p>Situação Recebimento:</p>
                </li>
                <li>
                    <div class="div-radios">
                        <label for="FiltroSituacaoAberto">
                            <input name="filtros_situacao" type="radio" value="ABERTO" id="FiltroSituacaoAberto" checked />
                            Aberto
                        </label>
                        &nbsp;&nbsp;
                        <label for="FiltroSituacaoFinalizado">
                            <input name="filtros_situacao" type="radio" value="FINALIZADO" id="FiltroSituacaoFinalizado" />
                            Finalizado
                        </label>
                        &nbsp;&nbsp;
                        <label for="FiltroSituacaoTodos">
                            <input name="filtros_situacao" type="radio" value="TODOS" id="FiltroSituacaoTodos" />
                            Todos
                        </label>
                    </div>
                </li>
            </ul>
            <p>Contratos faltando <input type="number" name="filtros_parcela_finalizar" style="width: 80px;" min="1" max="99" /> parcela(s) para finalizar</p>
        </div>
    </div>
    <hr />
    <table class="display table table-hover table-responsive" id="contents-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Recebimento</th>
                <th>Paciente</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Parcelas Recebidas</th>
                <th class="text-center">Vencimento</th>
                <th class="text-center">Sessões</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Código</th>
                <th>Recebimento</th>
                <th>Paciente</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Parcelas Recebidas</th>
                <th class="text-center">Vencimento</th>
                <th class="text-center">Sessões</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="modaExcluirRecebimento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Exclusão de recebimento</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir o recebimento <span id="nome-excluir"></span> ?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "excluir")); ?>" method="post">
                    <input type="hidden" id="idrecebimento" name="idrecebimento" />
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
                    <button type="submit" class="btn btn-primary">Sim</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php

echo $this->Html->css(array(
    "dataTables/jquery.dataTables_themeroller.min.css",
    "dataTables/dataTables.bootstrap.min.css",
    "icheck/all.css",
    "jspanel/jquery-ui.min.css",
    "jspanel/jquery.jspanel.css",
), null, array("block" => "css"));
echo $this->Html->script(array(
    "dataTables/jquery.dataTables.min.js",
    "dataTables/dataTables.bootstrap.min.js",
    "icheck/icheck.min.js",
    "jspanel/jquery-ui.min.js",
    "jspanel/mobile-detect.min.js",
    "jspanel/jquery.jspanel.js",
), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.div-radios').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });

        const recebimentoDados = <?= isset($recebimentoDados) ? json_encode($recebimentoDados) : 0 ?>;

        const tableRecebimentos = jQuery('#contents-table').dataTable({
            pageLength: 25,
            "processing": true,
            "serverSide": true,
            "order": [
                [5, "asc"] //ordenacao default por vencimento
            ],
            "oSearch": {
                "sSearch": "<?php echo $textSearch; ?>"
            },
            "ajax": {
                "url": "<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "ajax")); ?>",
                "type": "POST",
                "data": function(_data) {
                    _data.situacao = $('input[name=filtros_situacao]:checked').val();
                    _data.parcelas_finalizar = $('input[name=filtros_parcela_finalizar]').val();
                }
            },
            "columns": [{
                    "data": "r.idrecebimento"
                },
                {
                    "data": "r.descricao"
                },
                {
                    "data": "p.nome"
                },
                {
                    "data": "r.valor"
                },
                {
                    "data": "r.parcelas_pagas",
                    "bSortable": false
                },
                {
                    "data": "x.data_vencimento",
                },
                {
                    "data": "r.quantidade_sessoes"
                },
                {
                    "mData": null,
                    "bSortable": false
                }
            ],
            "createdRow": function(row, data, index) {
                jQuery('td', row).eq(2).html(data.p.nome + " " + data.p.sobrenome);
                jQuery('td', row).eq(3).addClass("text-center");
                jQuery('td', row).eq(4).addClass("text-center");
                jQuery('td', row).eq(5).addClass("text-center");
                jQuery('td', row).eq(6).addClass("text-center");
                jQuery('td', row).eq(7).addClass("text-center").html('<div class="btn-group"><button class="btn btn-success" onclick="gotoGestao(' + data.r.idrecebimento + ')"><i class="fa fa-edit fa-lg"></i></button> <a class="btn btn-danger" onclick="excluir(' + data.r.idrecebimento + ',\'' + data.r.descricao + '\')"><i class="fa fa-trash-o fa-lg"></i></a> <a class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Relatório Paciente/Eventos" href="' + $NOME_APLICACAO + '/recebimento/viewpdf/' + data.r.idrecebimento + '.pdf" id="relatorio-link" target="_blank"><i class="fa fa-lg fa-file-pdf-o"></i></a>  <a class="btn btn-warning" data-toggle="tooltip" data-placement="bottom" title="Contrato" href="' + $NOME_APLICACAO + '/recebimento/viewpdf_contrato/' + data.r.idrecebimento + '.pdf" id="relatorio-link" target="_blank" ><i class="fa fa-lg fa-file-pdf-o"></i></a> </div>');
            }
        });

        $('input[name=filtros_situacao]').on('ifChecked', function(event) {
            tableRecebimentos.api().ajax.reload();
        });

        $('input[name=filtros_parcela_finalizar]').on('change', function(event) {
            tableRecebimentos.api().ajax.reload();
        });

        if (recebimentoDados) {
            $.jsPanel({
                paneltype: 'modal',
                theme: 'success',
                title: "Sucesso",
                position: {
                    top: 50,
                    left: "center"
                },
                show: 'magictime slideInUp',
                size: {
                    width: 'auto',
                    height: 'auto'
                },
                content: "<div style='padding: 16px'>" +
                    "<h3 class='text-success' style='margin-top: 0px'><strong>Novo contrato gerado</strong></h3>" +
                    "<hr/>" +
                    "<h4><strong>Contrato:</strong> &nbsp;&nbsp; " + recebimentoDados.idrecebimento + "</h4>" +
                    "<h4><strong>Descrição:</strong> &nbsp;&nbsp; " + recebimentoDados.descricao + "</h4>" +
                    "<h4><strong>Paciente:</strong> &nbsp;&nbsp; " + recebimentoDados.paciente.nome + ' ' + recebimentoDados.paciente.sobrenome + "</h4>" +
                    "</div>"
            });
            /* $.jsPanel({
                paneltype: 'hint',
                position: 'right-top -5 5 DOWN',
                theme: 'green filledlight',
                border: '2px solid',
                contentSize: '400 88',
                show: 'animated slideInUp',
                content: "<div>" +
                    "<h4>Contrato: " + recebimentoDados.idrecebimento + "</h4>" +
                    "<h4>Descrição: " + recebimentoDados.descricao + "</h4>" +
                    "<h4>Paciente: " + recebimentoDados.paciente.nome + ' ' + recebimentoDados.paciente.sobrenome + "</h4>" +
                    "</div>"
            }); */
        }
    });

    function gotoGestao(idrecebimento) {
        var textSearch = jQuery("#contents-table_filter label input[type='search']").val();
        window.location.href = $NOME_APLICACAO + "/recebimento/gestao/" + idrecebimento + "?search=" + textSearch;
    }

    function excluir(idrecebimento, nome) {
        $('#nome-excluir').html("<b>" + nome + "</b>");
        $('#idrecebimento').val(idrecebimento);
        $('#modaExcluirRecebimento').modal('show');
    }
</script>
<?php
$this->end();
