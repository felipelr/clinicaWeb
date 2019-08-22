<div>
    <h3>Prontuário de <?php echo $Paciente['nome'] . ' ' . $Paciente['sobrenome']; ?></h3>
    <hr/>

    <div class="row">
        <div class="col-xs-3 col-sm-2 col-md-2 col-lg-1">
            <a class="btn btn-default btn-block" href="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "index")); ?>"><i class="fa fa-arrow-left"></i> Voltar</a>
        </div>
        <div class="col-xs-9 col-sm-10 col-md-10 col-lg-11">
            <h4 class="text-success"><b>FICHAS DO PACIENTE</b></h4>            
        </div>
    </div> 

    <hr/>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">Fisioterapia</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab1">     
            <br/>
            <div>
                <a type="button" class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "cadastrar")); ?>/<?php echo $Paciente['idpaciente']; ?>"> Nova ficha fisioterapia</a>
            </div>
            <br/>
            <table class="display table table-hover table-responsive" id="table-fisioterapia">
                <thead>
                    <tr>
                        <th>Ficha</th>
                        <th>Paciente</th>
                        <th>Profissional</th>
                        <th class="text-center col-sm-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Ficha</th>
                        <th>Paciente</th>
                        <th>Profissional</th>
                        <th class="text-center col-sm-3">Ações</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Exclusão de ficha de fisioterapia</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir a ficha <span id="descricao-excluir"></span> ?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "excluir")); ?>" method="post">
                    <input type="hidden" id="idfichafisioterapia" name="idfichafisioterapia"/>
                    <input type="hidden" id="idpaciente" name="idpaciente"/>
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

        jQuery('#table-fisioterapia').dataTable({
            "pageLength": 25,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "ajax")); ?>?idpaciente=<?php echo $Paciente['idpaciente']; ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "f.descricao"},
                {"data": "pac.nome"},
                {"data": "pro.nome"},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(1).html(data.pac.nome + " " + data.pac.sobrenome);
                jQuery('td', row).eq(2).html(data.pro.nome + " " + data.pro.sobrenome);
                jQuery('td', row).eq(3).addClass("text-center col-sm-3").html('<div class="btn-group"><a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Alterar" href="<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "alterar")); ?>/' + data.f.idfichafisioterapia + '" ><i class="fa fa-lg fa-edit"></i></a> <a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Excluir" onclick="excluir(' + data.f.idfichafisioterapia + ',' + data.f.id_paciente + ',\'' + data.f.descricao + '\')" ><i class="fa fa-lg fa-trash"></i></a> <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Acompanhamento" href="<?php echo $this->Html->url(array("controller" => "acompanhamento_fisioterapia", "action" => "registros")); ?>/' + data.f.idfichafisioterapia + '"><i class="fa fa-lg fa-list-alt"></i></a> <a class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="PDF" target="_blank" href="<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "viewpdf")); ?>/' + data.f.idfichafisioterapia + '.pdf"><i class="fa fa-lg fa-file-pdf-o"></i></a></div>');
            }
        });

        $('[data-toggle="tooltip"]').tooltip();
    });


    function excluir(idfichafisioterapia, idpaciente, descricao) {
        $('#descricao-excluir').html("<b>" + descricao + "</b>");
        $('#idfichafisioterapia').val(idfichafisioterapia);
        $('#idpaciente').val(idpaciente);
        $('#myModal').modal('show');
    }

</script>
<?php
$this->end();
