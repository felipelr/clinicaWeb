<?php
echo $this->Html->css(array("datepicker.min.css", "select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js",
    "select2/select2.full.min.js", "icheck/icheck.min.js", "app.cadastro.despesa.js?v=1.2"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Despesa', array("url" => array("controller" => "despesa", "action" => "simplificado"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastro de Despesas Simplificado</h3>
        <hr/>
        <div class="row">
            <br/>         
            <div class="col-md-10">
                <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                    <li role="presentation" class="active"><a>Dados cadastrais <span class="badge"><i class="fa fa-info"></i></span></a></li>
                </ul>   
                <div class="form-group ">
                    <label for="DespesaDescricao" class="control-label col-md-2">*Descrição:</label>
                    <div class="col-md-10">
                        <input autofocus="autofocus" id="DespesaDescricao" name="data[Despesa][descricao]" data-required="true" placeholder="Digite a descrição" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="DespesaIdCategoriaDespesa" class="control-label col-md-2">*Categoria de Despesa:</label>
                    <div class="col-md-10">
                        <div class="input-group">
                            <select id="DespesaIdCategoriaDespesa" name="data[Despesa][id_categoria_despesa]" data-required="true" class="form-control combos" title="Categoria de Despesa">
                                <?php
                                $total_f = count($ArrayCategoriaDespesa);
                                if ($total_f > 0):
                                    for ($i_f = 0; $i_f < $total_f; $i_f++) :
                                        ?>
                                        <option value="<?php echo $ArrayCategoriaDespesa[$i_f]["cd"]["idcategoriadespesa"]; ?>" ><?php echo $ArrayCategoriaDespesa[$i_f]["cd"]["descricao"]; ?></option>
                                        <?php
                                    endfor;
                                endif;
                                ?>
                            </select>
                            <div class="input-group-btn">
                                <button type="button" onclick="openCategoriaDespesaModal()" class="btn btn-success"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div> 
                </div>                
                <div class="form-group ">
                    <label for="DespesaValor" class="control-label col-md-2">*Valor:</label>
                    <div class="col-md-10">
                        <input id="DespesaValor" name="data[Despesa][valor]" data-required="true" placeholder="00,00" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="DespesaDataCompetencia" class="control-label col-md-2">*Data competência:</label>
                    <div class="col-md-10">
                        <input id="DespesaDataCompetencia" name="data[Despesa][data_competencia]" placeholder="__/__/____" data-required="true" class="form-control" />
                    </div>                        
                </div>                
                <div class="form-group ">
                    <label for="AtivoSim" class="control-label col-md-2">*Ativo:</label>
                    <div class="col-md-10" style="padding-top: 5px" id="div-radios">                   
                        <input name="data[Despesa][ativo]" class="radios_check" type="radio" value="1" id="AtivoSim" checked />
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[Despesa][ativo]"  class="radios_check" type="radio" value="0" id="AtivoNao" />
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div>
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "despesa", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
    <br/><br/>
</div>
<!-- Modal -->
<div class="modal fade" id="myModalCategoriaDespesa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Categoria de Despesa</h4>
            </div>
            <div class="modal-body">
                <div id="modalBodyCategoriaDespesa" style="padding-left: 15px; padding-right: 15px;">

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        
        $('.combos').select2({
            autocomplete: true,
            width: "100%"
        });
        $('.radios_check').iCheck({
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
        $('#div-checks').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            increaseArea: '20%' // optional
        });
        $("#DespesaValor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
        $("#DespesaDataCompetencia").mask("99/99/9999");

        var checkout = $("#DespesaDataCompetencia").datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function () {
            checkout.hide();
        }).data('datepicker');

        jQuery('#DespesaCadastrarForm').validate({
            onKeyup: true,
            eachInvalidField: function () {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            },
            eachValidField: function () {
                jQuery(this).closest('div').removeClass('has-error').addClass('has-success');

                if (jQuery(this).val() === "__/__/____") {
                    jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
                }
            }
        });

        $('#RepetirSim, #RepetirNao').iCheck({
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
        $('#RepetirSim, #RepetirNao').on('ifChecked', function (event) {
            $('.div_prazo').toggle($(".lancamento").eq(0).is(':checked'));
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        loadCategoriaDespesaCadastro();
    });

    //Categoria de Despesa
    function loadCategoriaDespesaCadastro() {
        jQuery.ajax({
            url: $NOME_APLICACAO + "/categoria_despesa/cadastrar",
            type: 'GET',
            data: {"layout": "ajax"},
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    jQuery("#modalBodyCategoriaDespesa").html(data);
                    jQuery("<script/>", {
                        type: "text/javascript",
                        src: $NOME_APLICACAO + "/js/app.cadastro.categoria_despesa.js"
                    }).appendTo("body");
                    jQuery("#content-btn-salvar-categoria").html('<button onclick="cancelarCadastroCategoriaDespesa()" type="button" class="btn btn-danger">Cancelar</button> <button onclick="salvarCategoriaDespesaAjax()" type="button" class="btn btn-primary">Salvar</button> <button id="btn-reset-categoria-despesa" type="reset" style="display: none;"/>');
                }
            },
            error: function () {
            }
        });
    }

    function openCategoriaDespesaModal() {
        jQuery("#myModalCategoriaDespesa").modal("show");
    }

    function salvarCategoriaDespesaAjax() {
        if (jQuery("#CategoriaDespesaDescricao").val() !== "") {
            var dataForm = $("#CategoriaDespesaCadastrarForm").serialize();
            jQuery.ajax({
                url: $NOME_APLICACAO + "/categoria_despesa/cadastrar?layout=ajax",
                type: 'POST',
                data: dataForm,
                success: function (data, textStatus, jqXHR) {
                    if (data !== null) {
                        var json = jQuery.parseJSON(data);
                        jQuery("<option value='" + json.idcategoriadespesa + "'>" + json.descricao + "</option>").appendTo("#DespesaIdCategoriaDespesa");
                        jQuery('#DespesaIdCategoriaDespesa').select2({
                            autocomplete: true,
                            width: "100%"
                        });
                    }
                    jQuery("#btn-reset-categoria-despesa").trigger("click");
                    jQuery("#myModalCategoriaDespesa").modal("hide");
                },
                error: function () {
                }
            });
        }
    }

    function cancelarCadastroCategoriaDespesa() {
        jQuery("#btn-reset-categoria-despesa").trigger("click");
        jQuery("#myModalCategoriaDespesa").modal("hide");
    }    
</script>
<?php
$this->end();