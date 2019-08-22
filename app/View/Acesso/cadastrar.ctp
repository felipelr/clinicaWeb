<div class="">
    <div>
        <h3>Cadastrando Acessos</h3>
        <hr/>
    </div>
    <div>
        <form class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "acesso", "action" => "cadastrar")); ?>">
            <div class="form-group ">
                <label for="acesso_descricao" class="control-label col-md-2">Descricao:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="acesso_descricao" name="data[Acesso][descricao]" required placeholder="Descrição do Acesso" class="form-control" />
                </div>                        
            </div>   
            <div class="form-group ">
                <label for="categoria" class="control-label col-md-2">Categoria:</label>
                <div class="col-md-8">
                    <select class="form-control" id="categoria" name="data[Acesso][categoria]">
                        <option value="Cadastros">Cadastros</option>
                        <option value="Finanças">Finanças</option>
                        <option value="Relatórios">Relatórios</option>
                        <option value="Agenda">Agenda</option>
                        <option value="Configurações">Configurações</option>
                        <option value="Dashboard">Dashboard</option>
                    </select>
                </div>                        
            </div>              
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "acesso", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>
        </form>
    </div>    
</div>

<?php
echo $this->Html->css(array("select2/select2.min.css"), null, array("block" => "css"));
echo $this->Html->script(array("select2/select2.full.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#categoria').select2({
            autocomplete: true,
            width: "100%"
        });
    });
</script>
<?php
$this->end();