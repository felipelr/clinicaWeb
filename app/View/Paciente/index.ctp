<div class="">
    <div>
        <a type="button" class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "cadastrar")); ?>"> Cadastrar Paciente</a>
        <h3>Pacientes cadastrados</h3>
    </div>
    
    <hr/>
    <table class="display table table-hover table-responsive" id="contents-table">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>E-mail</th>
                <th class="text-center">CPF</th>
                <th class="text-center">Tel. Fixo</th>
                <th class="text-center">Tel. Celular</th>
                <th class="text-center col-sm-3 col-md-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Paciente</th>
                <th>E-mail</th>
                <th class="text-center">CPF</th>
                <th class="text-center">Tel. Fixo</th>
                <th class="text-center">Tel. Celular</th>
                <th class="text-center col-sm-3">Ações</th>
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
                <h4 class="modal-title" id="myModalLabel">Exclusão de paciente</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir o paciente <span id="nome-excluir"></span> ?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "excluir")); ?>" method="post">
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
        
        jQuery('#contents-table').dataTable({
            pageLength: 25,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo $this->Html->url(array("controller" => "paciente", "action" => "ajax")); ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "p.nome"},
                {"data": "p.email"},
                {"data": "p.cpf"},
                {"data": "p.telefone_fixo"},
                {"data": "p.telefone_celular"},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(0).html(data.p.nome + " " + data.p.sobrenome);
                jQuery('td', row).eq(2).addClass("text-center");
                jQuery('td', row).eq(3).addClass("text-center");
                jQuery('td', row).eq(4).addClass("text-center");
                jQuery('td', row).eq(5).addClass("text-center").html('<div class="btn-group">' + 
                        ' <a class="btn btn-success" href="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "alterar")); ?>/' + data.p.idpaciente + '"><i class="fa fa-pencil fa-lg"></i></a> ' + 
                        ' <a class="btn btn-danger" onclick="excluir(' + data.p.idpaciente + ',\'' + data.p.nome + ' ' + data.p.sobrenome + '\')"><i class="fa fa-trash-o fa-lg"></i></a> ' + 
                        ' <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Prontuário" href="<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "prontuario")); ?>/' + data.p.idpaciente + '"><i class="fa fa-folder-open fa-lg"></i></a> ' + 
                        ' <a class="btn bg-navy" data-toggle="tooltip" data-placement="top" title="Disponibilidade de Eventos" href="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "disponibilidade_evento")); ?>/' + data.p.idpaciente + '"><i class="fa fa-calendar-check-o fa-lg"></i></a> ' + 
                        ' <a type="button" class="btn btn-warning" href="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "paciente_recebimentos_viewpdf")); ?>/' + data.p.idpaciente + '.pdf" data-toggle="tooltip" data-placement="top" title="Recebimentos do Paciente" target="_blank"><i class="fa fa-lg fa-file-pdf-o"></i></a>'+
                        ' </div>');
            }
        });
        
        $('[data-toggle="tooltip"]').tooltip();
    });

    function excluir(idpaciente, nome) {
        $('#nome-excluir').html("<b>" + nome + "</b>");
        $('#idpaciente').val(idpaciente);
        $('#myModal').modal('show');
    }
</script>
<?php
$this->end();
