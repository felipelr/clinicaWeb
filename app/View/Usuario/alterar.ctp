<div class="">
    <div>
        <h3>Alterando usuário</h3>
        <hr/>
    </div>
    <div>
        <form id="UsuarioForm" class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "alterar")); ?>">
            <input id="UsuarioIdUsario" type="hidden" name="data[User][idusuario]" value="<?php echo $User['idusuario'] ?>"/>
            <div class="form-group ">
                <label for="UsuarioNome" class="control-label col-md-2">Nome:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="UsuarioNome" name="data[User][nome]" data-required="true" placeholder="Nome do usuário" class="form-control" value="<?php echo $User['nome'] ?>"/>
                </div>                        
            </div>
            <div class="form-group ">
                <label for="UsuarioSobrenome" class="control-label col-md-2">Sobrenome:</label>
                <div class="col-md-8">
                    <input id="UsuarioSobrenome" name="data[User][sobrenome]" data-required="true" placeholder="Sobrenome do usuário" class="form-control" value="<?php echo $User['sobrenome'] ?>"/>
                </div>                        
            </div>
            <div class="form-group ">
                <label for="UsuarioEmail" class="control-label col-md-2">Email:</label>
                <div class="col-md-8">
                    <input id="UsuarioEmail" name="data[User][email]" type="email" data-validate="emaill" data-required="true" placeholder="xxx@xxx.com" class="form-control" value="<?php echo $User['email'] ?>"/>
                </div>                        
            </div>
            <div class="form-group">
                <label for="UsuarioIdTipoUsuario" class="control-label col-md-2">Tipo usuário:</label>
                <div class="col-md-8">
                    <select id="UsuarioIdTipoUsuario" name="data[User][id_tipo_usuario]" class="form-control" title="TipoUsuario">
                        <?php
                        $total_ = count($Tipos);
                        if ($total_ > 0):
                            for ($i_ = 0; $i_ < $total_; $i_++) :
                                ?>
                                <option value="<?php echo $Tipos[$i_]["t"]["idtipousuario"]; ?>"  <?php echo ((int) $Tipos[$i_]["t"]["idtipousuario"] == (int) $User['id_tipo_usuario']) ? "selected" : ""; ?> ><?php echo $Tipos[$i_]["t"]["descricao"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Clínica</label>
                <div class="col-md-8">
                    <select id="clinica" class="form-control" name="data[User][id_clinica]">
                        <?php
                        $total_cli = count($Clinicas);
                        if ($total_cli > 0):
                            for ($i_cli = 0; $i_cli < $total_cli; $i_cli++) :
                                ?>
                                <option value="<?php echo $Clinicas[$i_cli]["c"]["idclinica"]; ?>" <?php echo $Clinicas[$i_cli]["c"]["idclinica"] == (int) $User['id_clinica'] ? "selected" : ""; ?> ><?php echo $Clinicas[$i_cli]["c"]["fantasia"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>   
                </div>
            </div>
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Atualizar" class="btn btn-primary"/>
            </div>
        </form>
    </div>    
</div>

<?php
echo $this->Html->css(array("select2/select2.min.css"), null, array("block" => "css"));
echo $this->Html->script(array("jquery.maskedinput.min.js", "jquery-validate.min.js", "select2/select2.full.min.js", "app.alteracao.usuario.js?v=1.1"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
    });
</script>
<?php
$this->end();
