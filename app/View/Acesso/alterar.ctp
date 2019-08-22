<div class="">
    <div>
        <h3>Atualizando Acesso</h3>
        <hr/>
    </div>
    <div>
        <form class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "acesso", "action" => "cadastrar")); ?>">
            <div class="form-group ">
                <label for="acesso_descricao" class="control-label col-md-2">Descricao:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="acesso_descricao" value="<?php echo $Acesso['descricao'] ?>" name="data[Acesso][descricao]" required placeholder="Descrição do acesso" class="form-control" />
                </div>                        
            </div>   
            <div class="form-group ">
                <label for="categoria" class="control-label col-md-2">Categoria:</label>
                <div class="col-md-8">
                    <select class="form-control" id="categoria" name="data[Acesso][categoria]">
                        <option value="Cadastros" <?php echo $Acesso["categoria"] == 'Cadastros' ? 'selected' : ''; ?>>Cadastros</option>
                        <option value="Finanças"  <?php echo $Acesso["categoria"] == 'Finanças' ? 'selected' : ''; ?>>Finanças</option>
                        <option value="Relatórios"<?php echo $Acesso["categoria"] == 'Relatórios' ? 'selected' : ''; ?>>Relatórios</option>
                        <option value="Agenda"    <?php echo $Acesso["categoria"] == 'Agenda' ? 'selected' : ''; ?>>Agenda</option>
                        <option value="Configurações" <?php echo $Acesso["categoria"] == 'Configurações' ? 'selected' : ''; ?>>Configurações</option>
                        <option value="Dashboard"     <?php echo $Acesso["categoria"] == 'Dashboard' ? 'selected' : ''; ?>>Dashboard</option>
                    </select>
                </div>                        
            </div>              
            <div class="btn-group">
                <input type="hidden" name="data[Acesso][idAcesso]" value="<?php echo $Acesso['idAcesso'] ?>"/>
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "acesso", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary" />
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