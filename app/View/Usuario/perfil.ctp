<div class="">
    <h2><?php echo $User['nome']." ".$User['sobrenome'] ?></h2>
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header bg-green">
                    <h3 class="box-title">DADOS CADASTRAIS</h3>
                </div>
                <div class="box-body">
                    <form id="CadastroForm" class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "perfil")); ?>">
                        <input id="UsuarioIdUsario" type="hidden" name="data[User][idusuario]" value="<?php echo $User['idusuario'] ?>"/>
                        <div class="form-group ">
                            <label for="UsuarioNome" class="control-label col-md-4">Nome:</label>
                            <div class="col-md-8">
                                <input id="UsuarioNome" name="data[User][nome]" data-required="true" placeholder="Nome do usuário" class="form-control" value="<?php echo $User['nome'] ?>"/>
                            </div>                        
                        </div>
                        <div class="form-group ">
                            <label for="UsuarioSobrenome" class="control-label col-md-4">Sobrenome:</label>
                            <div class="col-md-8">
                                <input id="UsuarioSobrenome" name="data[User][sobrenome]" data-required="true" placeholder="Sobrenome do usuário" class="form-control" value="<?php echo $User['sobrenome'] ?>"/>
                            </div>                        
                        </div>
                        <div class="form-group ">
                            <label for="UsuarioEmail" class="control-label col-md-4">Email:</label>
                            <div class="col-md-8">
                                <input id="UsuarioEmail" name="data[User][email]" type="email" data-validate="emaill" data-required="true" placeholder="xxx@xxx.com" class="form-control" value="<?php echo $User['email'] ?>"/>
                            </div>                        
                        </div>
                        <div class="form-group hidden">
                            <label for="UsuarioIdTipoUsuario" class="control-label col-md-4">Tipo usuário:</label>
                            <div class="col-md-8">
                                <select id="UsuarioIdTipoUsuario" name="data[User][id_tipo_usuario]" class="form-control" title="TipoUsuario">
                                    <?php
                                    $total_ = count($Tipos);
                                    if ($total_ > 0):
                                        for ($i_ = 0; $i_ < $total_; $i_++) :
                                            ?>
                                            <option value="<?php echo $Tipos[$i_]["t"]["idtipousuario"]; ?>"  <?php echo ((int) $Tipos[$i_]["t"]["idtipousuario"] == (int) $User['id_tipo_usuario']) ? "selected" : ""; ?>><?php echo $Tipos[$i_]["t"]["descricao"]; ?></option>
                                            <?php
                                        endfor;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <input type="submit" value="Altualizar" class="btn btn-primary"/>
                        </div> 
                    </form>                        
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header bg-red">
                    <h3 class="box-title">ALTERAR SENHA</h3>
                </div>
                <div class="box-body">
                    <form id="AlterarSenhaForm" class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "alterar_senha")); ?>">
                        <input id="UsuarioIdUsario" type="hidden" name="data[User][idusuario]" value="<?php echo $User['idusuario'] ?>"/>
                        <div class="form-group ">
                            <label for="UsuarioSenha" class="control-label col-md-4">Senha atual:</label>
                            <div class="col-md-8">
                                <input id="UsuarioSenha" name="data[User][senha]" type="password" data-required="true" class="form-control" />
                            </div>                        
                        </div>
                        <div class="form-group ">
                            <label for="UsuarioNovaSenha" class="control-label col-md-4">Nova senha:</label>
                            <div class="col-md-8">
                                <input id="UsuarioNovaSenha" name="data[User][nova_senha]" type="password" data-required="true" class="form-control" />
                            </div>                        
                        </div>
                        <div class="form-group">
                            <label for="UsuarioConfirmarNovaSenha" class="control-label col-md-4">Confirme a nova senha:</label>
                            <div class="col-md-8">
                                <input id="UsuarioConfirmarNovaSenha" name="data[User][confirmar_nova_senha]" type="password" data-required="true" class="form-control" />
                            </div>                        
                        </div>
                        <div class="">
                            <input type="submit" value="Alterar senha" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->css(array("select2/select2.min.css"), null, array("block" => "css"));
echo $this->Html->script(array("jquery.maskedinput.min.js", "jquery-validate.min.js", "select2/select2.full.min.js", "app.perfil.usuario.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {

    });
</script>
<?php
$this->end();
