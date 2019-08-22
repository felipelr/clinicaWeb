<div class="">
    <div>
        <a type="button" class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "plano_sessao", "action" => "cadastrar")); ?>"> Cadastrar Plano de Sessão</a>
        <h3>Planos de sessão cadastrados</h3>
    </div>
    
    <hr/>
    <table class="display table table-hover table-responsive" id="contents-table">
        <thead>
            <tr>
                <th>Plano de sessão</th>
                <th>Valor</th>
                <th class="text-center col-sm-2">Sessões</th>
                <th class="text-center col-sm-2">Meses</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Plano de sessão</th>
                <th>Valor</th>
                <th class="text-center col-sm-2">Sessões</th>
                <th class="text-center col-sm-2">Meses</th>
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
                <h4 class="modal-title" id="myModalLabel">Exclusão de plano de sessão</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir o plano de sessão <span id="nome-excluir"></span> ?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "plano_sessao", "action" => "excluir")); ?>" method="post">
                    <input type="hidden" id="idplanosessao" name="idplanosessao"/>
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
            "ajax": {
                "url": "<?php echo $this->Html->url(array("controller" => "plano_sessao", "action" => "ajax")); ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "p.descricao"},
                {"data": "p.valor"},
                {"data": "p.quantidade_sessoes"},
                {"data": "p.quantidade_meses"},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(2).addClass("text-center");
                jQuery('td', row).eq(3).addClass("text-center");
                jQuery('td', row).eq(4).addClass("text-center").html('<div class="btn-group"><a class="btn btn-success" href="<?php echo $this->Html->url(array("controller" => "plano_sessao", "action" => "alterar")); ?>/' + data.p.idplanosessao + '"><i class="fa fa-pencil fa-lg"></i></a><a class="btn btn-danger" onclick="excluir(' + data.p.idplanosessao + ',\'' + data.p.descricao + '\')"><i class="fa fa-trash-o fa-lg"></i></a></div>');
            }
        });
    });

    function excluir(idpaciente, nome) {
        $('#nome-excluir').html("<b>" + nome + "</b>");
        $('#idplanosessao').val(idpaciente);
        $('#myModal').modal('show');
    }
</script>
<?php
$this->end();