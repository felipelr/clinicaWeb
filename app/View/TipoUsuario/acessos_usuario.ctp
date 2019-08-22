<div class="">
    <div>
        <h3>Tipos de usuário cadastrados</h3>
    </div>
    <br>
    
    <form class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "acessos_usuario")); ?>?idtipousuario=<?php echo $idTipoUsuario; ?>">
        <div id="conteudo">
            <?php
            $total_acessos = count($Acessos);
            $categoria = "";
            if ($total_acessos > 0) {
                if ($categoria = "") {
                    echo '<ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px; border-top-color: #337ab7; ">
                            <li role="presentation" class="active"><a>' . $Acessos[0]['a']['categoria'] . '<span class="badge"><i class="fa fa-info"></i></span></a></li>
                          </ul><br> ';
                    $categoria = $Acessos['a']['categoria'];
                }
                for ($i_acesso = 0; $i_acesso < $total_acessos; $i_acesso++) {

                    if ($categoria != $Acessos[$i_acesso]['a']['categoria']) {
                        echo '<div style=" width: 100%;float: left;margin-top: 30px;">
                                <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px; border-top-color: #337ab7; ">
                                  <li role="presentation" class="active"><a>' . $Acessos[$i_acesso]['a']['categoria'] . '<span class="badge"><i class="fa fa-info"></i></span></a></li>
                                </ul>
                              </div>';
                        $categoria = $Acessos[$i_acesso]['a']['categoria'];
                    }
                    $isCheck = $Acessos[$i_acesso][0]['id_tipo_usuario'] != 0 ? "checked" : "";
                    echo '<div class="col-xs-6 col-md-3">
                            <div>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="' . $Acessos[$i_acesso]['a']['idAcesso'] . '" ' . $isCheck . ' class="check" name="Acesso[' . $i_acesso . ']" value="' . $Acessos[$i_acesso]['a']['idAcesso'] . '" /> ' . $Acessos[$i_acesso]['a']['descricao'] . '
                                </label>                                
                            </div>
                         </div>
                         ';
                    $categoria = $Acessos[$i_acesso]['a']['categoria'];
                }
            }
            ?>
            <div class="col-md-12" style="margin-top: 50px">
                <div class="btn-group">
                    <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "index")); ?>">Voltar</a>
                    <input type="submit" value="Salvar" class="btn btn-primary"/>
                </div>                        
            </div>
        </div> 
    </form>
</div>
<!-- Modal -->

<?php
echo $this->Html->css(array("dataTables/jquery.dataTables_themeroller.min.css", "icheck/all.css", "dataTables/dataTables.bootstrap.min.css"), null, array("block" => "css"));
echo $this->Html->script(array("dataTables/jquery.dataTables.min.js", "icheck/icheck.min.js", "dataTables/dataTables.bootstrap.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.check').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
    });

</script>
<?php
$this->end();
