<div>
    <h3>Ficha <?php echo $FichaFisioterapia['descricao']; ?></h3>
    <h4>Acompanhamentos de <?php echo $FichaFisioterapia['nome_paciente'] . ' ' . $FichaFisioterapia['sobrenome_paciente']; ?></h4>
    <hr/>

    <div class="row">
        <div class="col-xs-3 col-sm-2 col-md-2 col-lg-1">
            <a class="btn btn-default btn-block" href="<?php echo $this->Html->url(array("controller" => "ficha_fisioterapia", "action" => "prontuario")); ?>/<?php echo $FichaFisioterapia['id_paciente']; ?>"><i class="fa fa-arrow-left"></i> Voltar</a>
        </div>
        <div class="col-xs-9 col-sm-10 col-md-10 col-lg-11">
            <h4 class="text-success"><b>REGISTROS</b></h4>
        </div>
    </div> 
    <hr/>
    <div>
        <br/>
        <table class="display table table-hover table-responsive" id="table-fisioterapia">
            <thead>
                <tr>
                    <th class="text-center col-sm-2">Data do registro</th>
                    <th class="col-sm-8">Descrição</th>
                    <th class="text-center col-sm-2">Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center col-sm-2">Data do registro</th>
                    <th class="col-sm-8">Descrição</th>
                    <th class="text-center col-sm-2">Ações</th>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
<!-- Modal -->
<div class="modal fade" id="myModalExcluir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Exclusão de ficha de fisioterapia</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir este registro?</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $this->Html->url(array("controller" => "acompanhamento_fisioterapia", "action" => "excluir")); ?>" method="post">
                    <input type="hidden" id="idacompanhamentofisioterapia" name="idacompanhamentofisioterapia"/>
                    <input type="hidden" id="idfichafisioterapia" name="idfichafisioterapia"/>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
                    <button type="submit" class="btn btn-primary">Sim</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalhes -->
<div class="modal fade" id="myModalDetalhes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalhes do registro</h4>
            </div>
            <div class="modal-body">
                <div class="bg-success" style="padding: 10px;">
                    <h3>Evento relacionado</h3>
                    <p><b>Titulo:</b> <span id="evento-titulo"></span></p>
                    <p><b>Data do atendimento:</b> <span id="evento-data"></span></p>
                    <p><b>Observação:</b> <span id="evento-observacao"></span></p>
                </div>
                <div class="" style="padding: 10px;">
                    <h3>Detalhes do registro</h3>
                    <p><b>Data do registro:</b> <span id="registro-data"></span></p>
                    <p><b>Descrição <i class="fa fa-lg fa-caret-down"></i></b></p>
                    <div id="registro-descricao" style="padding-left: 16px; border: 2px solid #eeeeee;"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->css(array("dataTables/jquery.dataTables_themeroller.min.css", "dataTables/dataTables.bootstrap.min.css",
    "jspanel/jquery-ui.min.css", "jspanel/jquery.jspanel.css"), null, array("block" => "css"));
echo $this->Html->script(array("dataTables/jquery.dataTables.min.js", "dataTables/dataTables.bootstrap.min.js", "jspanel/jquery-ui.min.js",
    "jspanel/mobile-detect.min.js", "jspanel/jquery.jspanel.js", "moment-with-locales.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {

        jQuery('#table-fisioterapia').dataTable({
            "pageLength": 25,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo $this->Html->url(array("controller" => "acompanhamento_fisioterapia", "action" => "ajax")); ?>?idfichafisioterapia=<?php echo $FichaFisioterapia['idfichafisioterapia']; ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "a.created"},
                {"data": "a.descricao"},
                {"mData": null, "bSortable": false}
            ],
            "createdRow": function (row, data, index) {
                jQuery('td', row).eq(0).addClass("text-center col-sm-2");
                jQuery('td', row).eq(1).addClass("col-sm-8");
                jQuery('td', row).eq(2).addClass("text-center col-sm-2").html('<div class="form-group"> <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Detalhes" onclick="showDetalhes(' + data.a.idacompanhamentofisioterapia + ')"><i class="fa fa-search-plus fa-lg"></i></button> <button type="button" class="btn btn-danger" onclick="excluir(' + data.a.idacompanhamentofisioterapia + ',' + data.a.id_ficha_fisioterapia + ')"><i class="fa fa-trash fa-lg"></i></button> </div>');
            }
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    function showDetalhes(id) {
        jQuery.ajax({
            url: $NOME_APLICACAO + "/acompanhamento_fisioterapia/retornar_detalhes?idacompanhamentofisioterapia=" + id,
            type: 'GET',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    if (json.status) {
                        $("#evento-titulo").html(json.dados.evento.titulo);
                        $("#evento-data").html(json.dados.evento.data_inicio);
                        $("#evento-observacao").html(json.dados.evento.observacao);
                        $("#registro-data").html(json.dados.acompanhamento_fisioterapia.created);
                        $("#registro-descricao").html(json.dados.acompanhamento_fisioterapia.descricao);
                        $("#myModalDetalhes").modal("show");
                    } else {
                        $.jsPanel({
                            paneltype: 'hint',
                            theme: 'danger',
                            position: {top: 70, right: 0},
                            size: {width: 400, height: 'auto'},
                            content: "<div style='padding: 20px;'>Problemas retornar detalhes.</div>"
                        });
                    }
                }
            },
            error: function () {
                $.jsPanel({
                    paneltype: 'hint',
                    theme: 'danger',
                    position: {top: 70, right: 0},
                    size: {width: 400, height: 'auto'},
                    content: "<div style='padding: 20px;'>Problemas retornar detalhes.</div>"
                });
            }
        });
    }


    function excluir(idacompanhamentofisioterapia, idfichafisioterapia) {
        $('#idacompanhamentofisioterapia').val(idacompanhamentofisioterapia);
        $("#idfichafisioterapia").val(idfichafisioterapia);
        $('#myModalExcluir').modal('show');
    }

</script>
<?php
$this->end();
