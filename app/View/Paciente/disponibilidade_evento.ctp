<div class="">
    <div id="content-alert"></div>
    <h3>Disponibilidade de eventos</h3>
    <hr/>
    &nbsp; &nbsp;
    <button id="btn-atualizar" class="btn btn-primary" type="button" onclick="editar()">Alterar <i class="fa fa-edit"></i></button>
    <button id="btn-salvar" class="btn btn-success" type="button" onclick="salvar()" style="display: none;">Salvar <i class="fa fa-save"></i></button>
    <br/>
    <form id="form-disponibilidade" method="post" action="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "alterar_disponibilidade_ajax", $idpaciente)); ?>">
        <table class="table table-striped table-responsive table-hover">
            <thead>
                <tr>
                    <th>Recebimento</th>
                    <th class="text-center">Eventos Disponíveis</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $size = count($Recebimentos);
                if ($size > 0):
                    for ($i = 0; $i < $size; $i++):
                        ?>
                        <tr>
                            <td>
                                <?php echo $Recebimentos[$i]['r']['descricao']; ?>
                            </td>
                            <td class="text-center">
                                <span class="span-total"><?php echo $Recebimentos[$i]['ed']['total']; ?></span>
                                <input type="number" name="<?php echo "eventos[$i][total]" ?>" class="input-total" value="<?php echo $Recebimentos[$i]['ed']['total']; ?>" style="display: none;" min="0"/>
                                <input type="hidden" name="<?php echo "eventos[$i][ideventodisponibilidade]" ?>" value="<?php echo $Recebimentos[$i]['ed']['ideventodisponibilidade']; ?>"/>
                            </td>
                        </tr>
                        <?php
                    endfor;
                endif;
                if ($size == 0):
                    ?>
                    <tr>
                        <td class="text-center" colspan="2">Não há registros</td>
                    </tr>
                    <?php
                endif;
                ?>

            </tbody>
        </table>
    </form>

</div>

<!-- MODAL SALVANDO -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalSalvando" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Aguarde</h4>
            </div>
            <div class="modal-body">
                <p>Salvando ...</p>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- /MODAL -->
<?php
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {

    });

    function editar() {
        $('.span-total, #btn-atualizar').hide();
        $('.input-total, #btn-salvar').show();
    }
    function salvar() {
        $("#myModalSalvando").modal("show");
        $("#form-disponibilidade").submit();
    }
</script>
<?php
$this->end();
