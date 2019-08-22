<div class="">
    <div>
        <a type="button" class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "cadastrar")); ?>"> Cadastrar Tipo de Usuário</a>
        <h3>Tipos de usuário cadastrados</h3>
    </div>
    
    <hr/>
    <table class="display table table-hover table-responsive" id="contents-table">
        <thead>
            <tr>
                <th>Tipo</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Tipo</th>
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
                <h4 class="modal-title" id="myModalLabel">Exclusão de tipo de usuário</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir o tipo de usuário <span id="nome-excluir"></span> ?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "excluir")); ?>" method="post">
                    <input type="hidden" id="idtipousuario" name="idtipousuario"/>
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
                "url": "<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "ajax")); ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "t.descricao"},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(1).addClass("text-center").html('<div class="btn-group"><a class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "acessos_usuario")); ?>/' + data.t.idtipousuario + '" data-toggle="tooltip" data-placement="bottom" title="Liberar/Bloquear eventos desta categoria" ><i class="fa fa-unlock"></i></a><a class="btn btn-success" href="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "alterar")); ?>/' + data.t.idtipousuario + '"><i class="fa fa-pencil fa-lg"></i></a><a class="btn btn-danger" onclick="excluir(' + data.t.idtipousuario + ',\'' + data.t.descricao + '\')"><i class="fa fa-trash-o fa-lg"></i></a></div>');
            }
        });
    });

    function excluir(idtipousuario, nome) {
        $('#nome-excluir').html("<b>" + nome + "</b>");
        $('#idtipousuario').val(idtipousuario);
        $('#myModal').modal('show');
    }
</script>
<?php
$this->end();