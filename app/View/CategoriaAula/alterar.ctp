<div class="">
    <div>
        <h3>Alterando Categoria de Aula</h3>
        <hr/>
    </div>
    <div>
        <form class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "categoria_aula", "action" => "alterar")); ?>">
            <input type="hidden" id="CategoriaAulaIdcategoriaaula" name="data[CategoriaAula][idcategoriaaula]" value="<?php echo $CategoriaAula['idcategoriaaula'] ?>" />
            <div class="form-group ">
                <label for="CategoriaAulaDescricao" class="control-label col-md-2">Descricao:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="CategoriaAulaDescricao" name="data[CategoriaAula][descricao]" required value="<?php echo $CategoriaAula['descricao'] ?>" placeholder="Descrição da categoria de aula" class="form-control" />
                </div>                        
            </div>            
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "categoria_aula", "action" => "index")); ?>">Voltar</a>
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