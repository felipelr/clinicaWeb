<div class="">
    <div>
        <a type="button" class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "cadastrar")); ?>"> Cadastrar Usuário</a>
        <h3>Usuários cadastrados</h3>
    </div>
    
    <hr/>
    <table class="display table table-hover table-responsive" id="contents-table">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>E-mail</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Usuário</th>
                <th>E-mail</th>
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
                <h4 class="modal-title" id="myModalLabel">Exclusão de usuário</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir o usuário <span id="nome-excluir"></span> ?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "excluir")); ?>" method="post">
                    <input type="hidden" id="idusuario" name="idusuario"/>
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
                "url": "<?php echo $this->Html->url(array("controller" => "usuario", "action" => "ajax")); ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "u.nome"},
                {"data": "u.email"},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(0).html(data.u.nome + " " + data.u.sobrenome);
                jQuery('td', row).eq(2).addClass("text-center").html('<div class="btn-group"><a class="btn btn-success" href="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "alterar")); ?>/' + data.u.idusuario + '"><i class="fa fa-pencil fa-lg"></i></a><a class="btn btn-danger" onclick="excluir(' + data.u.idusuario + ',\'' + data.u.nome + ' ' + data.u.sobrenome + '\')"><i class="fa fa-trash-o fa-lg"></i></a></div>');
            }
        });
    });

    function excluir(idusuario, nome) {
        $('#nome-excluir').html("<b>" + nome + "</b>");
        $('#idusuario').val(idusuario);
        $('#myModal').modal('show');
    }
</script>
<?php
$this->end();
