<div class="">
    <div>
        <h3>Alterando tipo de usuário</h3>
        <hr/>
    </div>
    <div>
        <form class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "alterar")); ?>">
            <input type="hidden" id="TipoUsuarioIdcargo" name="data[TipoUsuario][idtipousuario]" value="<?php echo $TipoUsuario['idtipousuario'] ?>" />
            <div class="form-group ">
                <label for="TipoUsuarioDescricao" class="control-label col-md-2">Descricao:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="TipoUsuarioDescricao" name="data[TipoUsuario][descricao]" required value="<?php echo $TipoUsuario['descricao'] ?>" placeholder="Descrição do tipo" class="form-control" />
                </div>                        
            </div>            
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Atualizar" class="btn btn-primary"/>
            </div>
        </form>
    </div>    
</div>

<?php
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
    });
</script>
<?php
$this->end();