<div class="">
    <div>
        <a type="button" class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "caixa", "action" => "cadastrar")); ?>"> Cadastrar Caixa</a>
        <h3>Caixas cadastrados</h3>
    </div>

    <hr/>
    <table class="display table table-hover table-responsive" id="contents-table">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Clínica</th>
                <th>Tipo</th>
                <th class="text-center col-sm-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Descrição</th>
                <th>Clínica</th>
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
                <h4 class="modal-title" id="myModalLabel">Exclusão do Caixa</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir o Caixa Selecionado <span id="nome-excluir"></span> ?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "caixa", "action" => "excluir")); ?>" method="post">
                    <input type="hidden" id="idcaixa" name="idcaixa"/>
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
                "url": "<?php echo $this->Html->url(array("controller" => "caixa", "action" => "ajax")); ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "c.descricao"},
                {"data": "cli.fantasia"},
                {"data": "c.tipo"},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(2).html(data.c.tipo == "DIARIO" ? "Diário" : data.c.tipo == "COFRE" ? "Cofre" : "Conta Bancária");
                jQuery('td', row).eq(3).addClass("text-center").html('<div class="btn-group"> <a class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "caixa", "action" => "liberar_acesso")); ?>/' + data.c.idcaixa + '" data-toggle="tooltip" data-placement="bottom" title="Liberar usuários para acessar o caixa" ><i class="fa fa-unlock"></i></a> <a class="btn btn-success" href="<?php echo $this->Html->url(array("controller" => "caixa", "action" => "alterar")); ?>/' + data.c.idcaixa + '"><i class="fa fa-pencil fa-lg"></i></a> <a class="btn btn-danger" onclick="excluir(' + data.c.idcaixa + ',\'' + data.c.descricao + '\')"><i class="fa fa-trash-o fa-lg"></i></a></div>');
            }
        });
    });

    function excluir(idcaixa, nome) {
        $('#nome-excluir').html("<b>" + nome + "</b>");
        $('#idcaixa').val(idcaixa);
        $('#myModal').modal('show');
    }
</script>
<?php
$this->end();
