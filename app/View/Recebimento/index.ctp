<div class="">
    <div>
        <a type="button" class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "cadastrar")); ?>"> Cadastrar Recebimento</a>
        <h3>Recebimentos cadastrados</h3>
    </div>

    <hr/>
    <table class="display table table-hover table-responsive" id="contents-table">
        <thead>
            <tr>
                <th>Recebimento</th>
                <th>Paciente</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Parcelas Recebidas</th>
                <th class="text-center">Vencimento</th>
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
                <th class="text-center">Parcelas Recebidas</th>
                <th class="text-center">Vencimento</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <input type="hidden" id="idrecebimento" name="idrecebimento"/>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
                    <button type="submit" class="btn btn-primary">Sim</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->css(array("dataTables/jquery.dataTables_themeroller.min.css", "dataTables/dataTables.bootstrap.min.css"), null, array("block" => "css"));
echo $this->Html->script(array("dataTables/jquery.dataTables.min.js", "dataTables/dataTables.bootstrap.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#contents-table').dataTable({
            pageLength: 25,
            "processing": true,
            "serverSide": true,
            "oSearch": {"sSearch": "<?php echo $textSearch; ?>"},
            "ajax": {
                "url": "<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "ajax")); ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "r.descricao"},
                {"data": "p.nome"},
                {"data": "r.valor"},
                {"data": "r.parcelas_pagas", "bSortable": false},
                {"data": "r.data_vencimento", "bSortable": false},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(1).html(data.p.nome + " " + data.p.sobrenome);
                jQuery('td', row).eq(2).addClass("text-center");
                jQuery('td', row).eq(3).addClass("text-center");
                jQuery('td', row).eq(4).addClass("text-center");
                jQuery('td', row).eq(5).addClass("text-center").html('<div class="btn-group"><button class="btn btn-success" onclick="gotoGestao(' + data.r.idrecebimento + ')"><i class="fa fa-edit fa-lg"></i></button> <a class="btn btn-danger" onclick="excluir(' + data.r.idrecebimento + ',\'' + data.r.descricao + '\')"><i class="fa fa-trash-o fa-lg"></i></a> <a class="btn btn-primary" href="' + $NOME_APLICACAO + '/recebimento/viewpdf/' + data.r.idrecebimento + '.pdf" id="relatorio-link" target="_blank" data-original-title="PDF"><i class="fa fa-lg fa-file-pdf-o"></i></a> </div>');
            }
        });
    });

    function gotoGestao(idrecebimento) {
        var textSearch = jQuery("#contents-table_filter label input[type='search']").val();
        window.location.href = $NOME_APLICACAO + "/recebimento/gestao/" + idrecebimento + "?search=" + textSearch;
    }

    function excluir(idrecebimento, nome) {
        $('#nome-excluir').html("<b>" + nome + "</b>");
        $('#idrecebimento').val(idrecebimento);
        $('#myModal').modal('show');
    }
</script>
<?php
$this->end();
