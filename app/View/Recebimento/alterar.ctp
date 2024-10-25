<div class="">
    <form id="RecebimentoAlterarForm" class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "alterar")); ?>">
        <input type="hidden" value="<?php echo $dadosRecebimento['r']['idrecebimento']; ?>" name="data[Recebimento][idrecebimento]" />
        <div>
            <h3>Alterando Recebimento #<?php echo $dadosRecebimento['r']['idrecebimento']; ?></h3>
            <hr />
            <div class="row">
                <br />
                <div class="col-md-10">
                    <ul class="nav nav-pills" role="tablist" style="margin-top: 10px; margin-bottom:20px ">
                        <li role="presentation" class="active"><a>Dados cadastrais <span class="badge"><i class="fa fa-info"></i></span></a></li>
                    </ul>
                    <div class="form-group ">
                        <label for="RecebimentoDescricao" class="control-label col-md-2">*Descrição:</label>
                        <div class="col-md-10">
                            <input value="<?php echo $dadosRecebimento['r']['descricao']; ?>" autofocus="autofocus" id="RecebimentoDescricao" name="data[Recebimento][descricao]" data-required="true" placeholder="Digite a descrição" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="RecebimentoIdProfissional" class="control-label col-md-2">*Profissional:</label>
                        <div class="col-md-10">
                            <select id="RecebimentoIdProfissional" name="data[Recebimento][id_profissional]" class="form-control combos validarChange" title="Profissional">
                                <?php
                                $total_ = count($Profissionais);
                                if ($total_ > 0) :
                                    for ($i_ = 0; $i_ < $total_; $i_++) :
                                ?>
                                        <option value="<?php echo $Profissionais[$i_]["p"]["idprofissional"]; ?>" <?php echo $Profissionais[$i_]["p"]["idprofissional"] == $dadosRecebimento["p"]["idprofissional"] ? "selected" : "" ?>><?php echo $Profissionais[$i_]["p"]["nome"] . " " . $Profissionais[$i_]["p"]["sobrenome"]; ?></option>
                                <?php
                                    endfor;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="RecebimentoIdCategoriaAula" class="control-label col-md-2">*Categoria:</label>
                        <div class="col-md-10">
                            <select name="data[Recebimento][id_categoria_aula]" class="form-control combos" title="Categoria de Aula">
                                <?php
                                $totalCategorias = count($categorias);
                                if ($totalCategorias > 0) :
                                    for ($iCategoria = 0; $iCategoria < $totalCategorias; $iCategoria++) :
                                ?>
                                        <option value="<?php echo $categorias[$iCategoria]["c"]["idcategoriaaula"]; ?>" <?php echo $categorias[$iCategoria]["c"]["idcategoriaaula"] == $dadosRecebimento["r"]["id_categoria_aula"] ? "selected" : "" ?>><?php echo $categorias[$iCategoria]["c"]["descricao"]; ?></option>
                                <?php
                                    endfor;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="DespesaIdDespesaCusto" class="control-label col-md-2">*Centro Custo:</label>
                        <div class="col-md-10">
                            <select id="RecebimentoIdDespesaCusto" name="data[Recebimento][id_centro_custo]" data-required="true" class="form-control combos" title="Centro Custo">
                                <?php
                                $total_cc = count($CentroCustos);
                                if ($total_cc > 0) :
                                    $idplano = $CentroCustos[0]["pl"]["idplanocontas"];
                                ?>
                                    <optgroup label="<?php echo $CentroCustos[0]["pl"]["descricao"]; ?>">
                                        <?php
                                        for ($i_cc = 0; $i_cc < $total_cc; $i_cc++) :
                                            if ($idplano != $CentroCustos[$i_cc]["pl"]["idplanocontas"]) {
                                                $idplano = $CentroCustos[$i_cc]["pl"]["idplanocontas"];
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo $CentroCustos[$i_cc]["pl"]["descricao"]; ?>">
                                        <option value="<?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"]; ?>" <?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"] == $dadosRecebimento["r"]["id_centro_custo"] ? "selected" : "" ?>><?php echo $CentroCustos[$i_cc]["c"]["descricao"]; ?></option>
                                    <?php
                                            } else {
                                    ?>
                                        <option value="<?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"]; ?>" <?php echo $CentroCustos[$i_cc]["c"]["iddespesacusto"] == $dadosRecebimento["r"]["id_centro_custo"] ? "selected" : "" ?>><?php echo $CentroCustos[$i_cc]["c"]["descricao"]; ?></option>
                                <?php
                                            }
                                        endfor;
                                ?>
                                    </optgroup>
                                <?php
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="RecebimentoQuantidadeSessoes" class="control-label col-md-2">*Quantidade de Sessões:</label>
                        <div class="col-md-10">
                            <input value="<?php echo $dadosRecebimento['r']['quantidade_sessoes']; ?>" autofocus="autofocus" id="RecebimentoQuantidadeSessoes" name="data[Recebimento][quantidade_sessoes]" data-required="true" placeholder="Digite a quantidade de sessões" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="col-md-12">
                <div class="btn-group">
                    <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "gestao", $dadosRecebimento['r']['idrecebimento'])); ?>">Voltar</a>
                    <input type="submit" value="Atualizar" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </form>
    <br /><br />
</div>

<?php
echo $this->Html->css(array(
    "datepicker.min.css", "select2/select2.min.css", "icheck/all.css", "fileinput.min.css", "jspanel/jquery-ui.min.css",
    "jspanel/jquery.jspanel.css"
), null, array("block" => "css"));
echo $this->Html->script(array(
    "jspanel/jquery-ui.min.js", "bootstrap-datepicker.min.js", "jquery.maskMoney.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js",
    "select2/select2.full.min.js", "icheck/icheck.min.js", "jquery.form.min.js", "fileinput.min.js",
    "jspanel/mobile-detect.min.js", "jspanel/jquery.jspanel.js"
), array("block" => "script"));
$this->start('script');
?>
<style>
    .border-red {
        border-color: red !important;
    }
</style>
<script type="text/javascript">
    var gerando = false;
    var formValido = false;
    var posicaoAtualFinanceiro = 0;
    var posicaoAtualConta = 0;
    var posicaoAtualCheque = 0;

    jQuery(document).ready(function() {
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('.combos').select2({
            autocomplete: true,
            width: "100%"
        });
        $('.radios_check').iCheck({
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
        $('#div-checks input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            increaseArea: '20%' // optional
        });

        jQuery('#RecebimentoAlterarForm').validate({
            onKeyup: true,
            onChange: true,
            eachInvalidField: function() {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            },
            eachValidField: function() {
                jQuery(this).closest('div').removeClass('has-error').addClass('has-success');
                if (jQuery(this).val() === "__/__/____") {
                    jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
                }
            }
        });

    });

    function limparModal() {
        jQuery("#agencia, #conta, #titular, #documento").val("");
    }
</script>
<?php
$this->end();
