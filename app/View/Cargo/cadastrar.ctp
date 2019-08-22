<div class="">
    <div>
        <h3>Cadastrando cargo</h3>
        <hr/>
    </div>
    <div>
        <form class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "cargo", "action" => "cadastrar")); ?>">
            <div class="form-group ">
                <label for="CargoDescricao" class="control-label col-md-2">Descricao:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="CargoDescricao" name="data[Cargo][descricao]" required placeholder="Descrição do cargo" class="form-control" />
                </div>                        
            </div>            
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "cargo", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
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