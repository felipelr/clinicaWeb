<?php
echo $this->Html->css(array("select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("select2/select2.full.min.js", "icheck/icheck.min.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Caixa', array("url" => array("controller" => "caixa", "action" => "liberar_acesso"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>   
    <div>
        <h3>Liberação de Acesso: <span class="text-green text-bold text-uppercase"><?php echo $Caixa['descricao'] ?></span></h3>
        <hr/>
        <input type="hidden" id="CaixaIdcaixaloja" name="data[Caixa][idcaixa]" value="<?php echo $Caixa['idcaixa'] ?>" />
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-9">
                <div class="form-group ">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Liberado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_ = count($AcessosUsuarios);
                            if ($total_ > 0):
                                for ($i_ = 0; $i_ < $total_; $i_++) :
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="Usuarios[<?php echo $i_ ?>][id_usuario]" value="<?php echo $AcessosUsuarios[$i_]["u"]["idusuario"]; ?>"/>                                            
                                            <?php echo $AcessosUsuarios[$i_]["u"]["nome"] . ' ' . $AcessosUsuarios[$i_]["u"]["sobrenome"]; ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="Usuarios[<?php echo $i_ ?>][idcaixausuario]" value="<?php echo isset($AcessosUsuarios[$i_]["cu"]["idcaixausuario"]) ? $AcessosUsuarios[$i_]["cu"]["idcaixausuario"] : 0; ?>"/>
                                            <input class="input-checkbox" name="Usuarios[<?php echo $i_ ?>][liberado]" type="checkbox" value="1" <?php echo isset($AcessosUsuarios[$i_]["c"]["idcaixa"]) ? 'checked' : ''; ?> />
                                        </td>
                                    </tr>                                
                                <?php
                            endfor;
                        endif;
                        ?>
                        </tbody>
                    </table>                          
                </div>                     
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "caixa", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Confirmar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $('#Clinicas').select2({
                autocomplete: true,
                width: "100%"
            });
            $('.div-radios').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue',
                increaseArea: '20%' // optional
            });
            
            $(".input-checkbox").iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue',
                increaseArea: '20%' // optional
            });

            jQuery('#CaixaLiberarAcessoForm').validate({
                onKeyup: true,
                eachInvalidField: function () {
                    jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
                },
                eachValidField: function () {
                    jQuery(this).closest('div').removeClass('has-error').addClass('has-success');

                    if (jQuery(this).val() === "__/__/____" || jQuery(this).val() === "___.___.___-__" || jQuery(this).val() === "__.___.___-_" || jQuery(this).val() === "_____-___" || jQuery(this).val() === "") {
                        jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
                    }
                }
            });
        });
    </script>
</div>
