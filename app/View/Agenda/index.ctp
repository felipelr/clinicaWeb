<?php $this->assign('title', 'Agendas') ?>
<div>
    <div>
        <a class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "agenda", "action" => "cadastrar")); ?>">Nova Agenda</a>
        <h3>Agendas cadastradas</h3>
    </div>

    <hr/>
    <table class="display table table-hover table-responsive" id="contents-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Agenda</th>
                <th>Profissional</th>
                <th class="text-center">CPF</th>
                <th class="text-center col-xs-3 col-sm-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Agenda</th>
                <th>Profissional</th>
                <th class="text-center">CPF</th>
                <th class="text-center col-xs-3  col-sm-2">Ações</th>
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
                <h4 class="modal-title" id="myModalLabel">Exclusão de agenda</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir a agenda <span id="nome-excluir"></span> ?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "agenda", "action" => "excluir")); ?>" method="post">
                    <input type="hidden" id="idagenda" name="idagenda"/>
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
            "pageLength": 25,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo $this->Html->url(array("controller" => "agenda", "action" => "ajax")); ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "a.descricao"},
                {"data": "p.nome"},
                {"data": "p.cpf"},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(1).html(data.p.nome + " " + data.p.sobrenome);
                jQuery('td', row).eq(2).addClass("text-center");
                jQuery('td', row).eq(3).addClass("text-center").html('<div class="btn-group"><a class="btn btn-success" href="<?php echo $this->Html->url(array("controller" => "agenda", "action" => "selecionada")); ?>/' + data.a.idagenda + '"><i class="fa fa-check fa-lg"></i></a><a class="btn btn-danger" onclick="excluir(' + data.a.idagenda + ',\'' + data.a.descricao + '\')"><i class="fa fa-trash-o fa-lg"></i></a></div>');
            }
        });
    });

    function excluir(idagenda, nome) {
        $('#nome-excluir').html("<b>" + nome + "</b>");
        $('#idagenda').val(idagenda);
        $('#myModal').modal('show');
    }
</script>
<?php
$this->end();
