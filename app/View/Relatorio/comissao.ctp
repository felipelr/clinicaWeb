<div class="">
    <div>        
        <h3>Relatório de Comissão</h3>
        <br/>
    </div>
    <div class="well">
        <form id="form-consultar" class="form-inline" data-action="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "ajax_relatorio_comissao")); ?>" method="post">
            <div class="row-divisao">
                <div class="form-group">
                    <label class="control-label" style="width: 90px;">Data</label>
                </div>
                <div class="form-group">
                    <input id="data-relatorio" class="form-control" name="data-relatorio" value="<?php echo $dataRelatorio ?>" />
                </div>
            </div>
            <div class="row-divisao">
                <div class="form-group">
                    <label for="AgendaIdProfissional" class="control-label" style="width: 90px;">Profissional:</label>
                </div>
                <div class="form-group" style="min-width: 300px; margin-right: 15px;">
                    <select id="IdProfissional" name="profissional" class="form-control combos validarChange" title="Profissional">
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
            <div class="row-divisao">
                <br/>
                <button id="btn-consultar" type="button" onclick="consultar()" class="btn btn-primary center-block"><i class="fa fa-search fa-lg"></i> Consultar</button>
            </div>       
        </form>
    </div>
    <hr/>
    <div>
        <table id="tabela-recebimento" class="table table-responsive table-bordered">
            <thead>
                <tr>
                    <th class='text-left'>Recebimento</th>
                    <th class='text-left'>Evento </th>
                    <th class='text-left'>Categoria</th>
                    <th class='text-center'>Atendimentos</th>
                    <th class='text-center'>Aten. Efetivados</th>
                    <th class='text-center'>Porcentagem </th>
                    <th class='text-right'>Valor Sessão</th>
                    <th class="text-right">Comissao</th>
                    <?php if ($this->Acesso->validarAcesso($_dados['id_tipo_usuario'], Acesso::$ACESSO_RELATORIO_VALOR_TOTAL_COMISSAO)): ?>
                        <th class='text-right'>Total Geral</th>
                    <?php endif; ?>
                </tr>
            </thead>        
            <tbody id="relatorio_corpo">
            <td colspan='9' class='text-center'>Não há registros</td>
            </tbody>
        </table>        
    </div>
</div>
<?php
echo $this->Html->css(array("datepicker.min.css", "icheck/all.css", "select2/select2.min.css", "style.relatorio.despesa.css?v=1.0"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskedinput.min.js", "icheck/icheck.min.js", "select2/select2.full.min.js", "jquery.form.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#data-relatorio").datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months"
        });

        $('.combos').select2({
            autocomplete: true,
            width: "100%"
        });
        carregando = false;
    });

    function consultar() {
        if (!carregando) {
            carregando = true;
            jQuery("#btn-consultar").html('<i class="fa fa-refresh fa-lg"></i> Aguarde carregando...');
            var data = jQuery("#form-consultar").serialize();
            var formURL = jQuery("#form-consultar").attr("data-action");

            jQuery.ajax({
                url: formURL,
                type: 'POST',
                data: data,
                success: function(data, textStatus, jqXHR) {
                    jQuery("#tabela-recebimento tbody").html("");
                    jQuery("#tabela-recebimento tbody").html(data);
                    jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                },
                error: function() {
                    jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                }
            });
            $('html, body').animate({
                scrollTop: $("#btn-consultar").offset().top
            }, 2000);

        }

    }

</script>

<?php
$this->end();
