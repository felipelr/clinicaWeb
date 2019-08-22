<div class="container">
    <div>
        <h3>Cadastrando agenda</h3>
        <hr/>
    </div>
    <div>
        <form class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "agenda", "action" => "cadastrar")); ?>">
            <div class="form-group ">
                <label for="AgendaDescricao" class="control-label col-md-2">Descricao:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="PacienteNome" name="data[Agenda][descricao]" required placeholder="Descrição da agenda" class="form-control" />
                </div>                        
            </div>
            <div class="form-group">
                <label for="AgendaIdProfissional" class="control-label col-md-2">Profissional:</label>
                <div class="col-md-8">
                    <select id="AgendaIdProfissional" name="data[Agenda][id_profissional]" class="form-control" title="Profissional">
                        <?php
                        $total_ = count($Profissionais);
                        if ($total_ > 0):
                            for ($i_ = 0; $i_ < $total_; $i_++) :
                                ?>
                                <option value="<?php echo $Profissionais[$i_]["p"]["idprofissional"]; ?>"><?php echo $Profissionais[$i_]["p"]["nome"] . " " . $Profissionais[$i_]["p"]["sobrenome"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>
                </div>
            </div>
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "agenda", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>
        </form>
    </div>    
</div>

<?php
echo $this->Html->css(array("select2/select2.min.css"), null, array("block" => "css"));
echo $this->Html->script(array("jquery-validate.min.js", "fileinput.min.js", "select2/select2.full.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $("#AgendaIdProfissional").select2({
            width: "100%"
        });
    });
</script>
<?php
$this->end();
