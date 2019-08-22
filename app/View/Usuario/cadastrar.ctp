<div class="">
    <div>
        <h3>Cadastrando usuário</h3>
        <hr/>
    </div>
    <div>
        <form id="UsuarioForm" class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "cadastrar")); ?>">
            <div class="form-group ">
                <label for="UsuarioNome" class="control-label col-md-2">Nome:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="UsuarioNome" name="data[User][nome]" data-required="true" placeholder="Nome do usuário" class="form-control" />
                </div>                        
            </div>
            <div class="form-group ">
                <label for="UsuarioSobrenome" class="control-label col-md-2">Sobrenome:</label>
                <div class="col-md-8">
                    <input id="UsuarioSobrenome" name="data[User][sobrenome]" data-required="true" placeholder="Sobrenome do usuário" class="form-control" />
                </div>                        
            </div>
            <div class="form-group ">
                <label for="UsuarioEmail" class="control-label col-md-2">Email:</label>
                <div class="col-md-8">
                    <input id="UsuarioEmail" name="data[User][email]" type="email" data-validate="emaill" data-required="true" placeholder="xxx@xxx.com" class="form-control" />
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
                                <option value="<?php echo $Tipos[$i_]["t"]["idtipousuario"]; ?>"><?php echo $Tipos[$i_]["t"]["descricao"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Clinica</label>  
                <div class="col-md-8">
                    <select id="clinica" class="form-control" name="data[User][id_clinica]">
                        <?php
                        $total_cli = count($Clinicas);
                        if ($total_cli > 0):
                            for ($i_cli = 0; $i_cli < $total_cli; $i_cli++) :
                                ?>
                                <option value="<?php echo $Clinicas[$i_cli]["c"]["idclinica"]; ?>" ><?php echo $Clinicas[$i_cli]["c"]["fantasia"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>   
                </div>
            </div>
            <hr/>
            <div class="form-group ">
                <label for="UsuarioSenha" class="control-label col-md-2">Senha:</label>
                <div class="col-md-8">
                    <input id="UsuarioSenha" name="data[User][senha]" type="password" data-required="true" class="form-control" />
                </div>                        
            </div>
            <div class="form-group ">
                <label for="UsuarioConfirmarSenha" class="control-label col-md-2">Confirme a senha:</label>
                <div class="col-md-8">
                    <input id="UsuarioConfirmarSenha" name="data[User][confirmar_senha]" type="password" data-required="true" class="form-control" />
                </div>                        
            </div>
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>
        </form>
    </div>    
</div>

<?php
echo $this->Html->css(array("select2/select2.min.css"), null, array("block" => "css"));
echo $this->Html->script(array("jquery.maskedinput.min.js", "jquery-validate.min.js", "select2/select2.full.min.js", "app.cadastro.usuario.js?v=1.1"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
    });
</script>
<?php
$this->end();
