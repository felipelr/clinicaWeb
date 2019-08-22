<div class="">
    <div>
        <h3>Cadastrando conta bancárias</h3>
        <hr/>
    </div>
    <div>
        <form id="ContaBancariaCadastrarForm" class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "conta_bancaria", "action" => "cadastrar")); ?>">
            <div class="form-group ">
                <label for="agencia" class="control-label col-md-2">Agência:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" type="number" id="agencia" name="data[ContaBancaria][agencia]" required placeholder="Informe a agência" class="form-control" />
                </div>                        
            </div>
            <div class="form-group ">
                <label for="conta" class="control-label col-md-2">Conta:</label>
                <div class="col-md-8">
                    <input id="conta" type="number" name="data[ContaBancaria][conta]" required placeholder="Informe a conta" class="form-control" />
                </div>                        
            </div>
            <div class="form-group ">
                <label for="titular" class="control-label col-md-2">Titular:</label>
                <div class="col-md-8">
                    <input id="titular" name="data[ContaBancaria][titular]" required placeholder="Informe o nome do titular" class="form-control" />
                </div>                        
            </div>
            <div class="form-group ">
                <label class="control-label col-md-2">Tipo Doc.:</label>                    
                <div class="col-md-8" id="div-radios">
                    <input name="tipodocumento" type="radio" value="CPF" id="tipocpf" checked/>
                    <label for="tipocpf">CPF</label>
                    <input name="tipodocumento" type="radio" value="CNPJ" id="tipocnpj" />
                    <label for="tipocnpj">CNPJ</label>
                </div>

            </div>
            <div class="form-group ">
                <label id="lb-documento" for="documento" class="control-label col-md-2">CPF/CNPJ:</label>
                <div class="col-md-8">
                    <input id="documento" name="data[ContaBancaria][documento]" required placeholder="Informe o documento" class="form-control" />
                </div>                        
            </div>
            <div class="form-group ">
                <label for="id_banco" class="control-label col-md-2">Banco:</label>
                <div class="col-md-8">
                    <select id="id_banco" name="data[ContaBancaria][id_banco]" data-required="true" class="form-control" title="Banco">
                        <?php
                        $total_banco = count($Bancos);
                        if ($total_banco > 0):
                            for ($i_banco = 0; $i_banco < $total_banco; $i_banco++) :
                                ?>
                                <option value="<?php echo $Bancos[$i_banco]["b"]["idbanco"]; ?>" ><?php echo $Bancos[$i_banco]["b"]["codigo"] . ' - ' . $Bancos[$i_banco]["b"]["descricao"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>      
                </div>
            </div>
            <div class="form-group ">
                <label for="id_paciente" class="control-label col-md-2">Paciente:</label>
                <div class="col-md-8">
                    <select id="id_paciente" name="data[ContaBancaria][id_paciente]" class="form-control" title="Paciente">
                        <option value="-1" selected >Nenhum</option>
                        <?php
                        $total_ = count($Pacientes);
                        if ($total_ > 0):
                            for ($i_ = 0; $i_ < $total_; $i_++) :
                                ?>
                                <option value="<?php echo $Pacientes[$i_]["p"]["idpaciente"]; ?>"><?php echo $Pacientes[$i_]["p"]["nome"] . ' ' . $Pacientes[$i_]["p"]["sobrenome"]; ?></option>
                                <?php
                            endfor;
                        endif;
                        ?>
                    </select>
                </div>
            </div>

            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "conta_bancaria", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>
        </form>
    </div>    
</div>

<?php
echo $this->Html->css(array("icheck/all.css", "select2/select2.min.css"), null, array("block" => "css"));
echo $this->Html->script(array("icheck/icheck.min.js", "jquery.maskedinput.min.js", "jquery-validate.min.js", "select2/select2.full.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#div-radios').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });

        jQuery("#documento").mask("999.999.999-99");

        $('#tipocpf').on('ifChecked', function (event) {
            jQuery("#documento").mask("999.999.999-99");
        });

        $('#tipocnpj').on('ifChecked', function (event) {
            jQuery("#documento").mask("99.999.999/9999-99");
        });

        $('#id_banco').select2({
            autocomplete: true,
            width: "100%"
        });
        $('#id_paciente').select2({
            autocomplete: true,
            width: "100%"
        });

        jQuery('#ContaBancariaCadastrarForm').validate({
            onKeyup: true,
            eachInvalidField: function () {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            },
            eachValidField: function () {
                jQuery(this).closest('div').removeClass('has-error').addClass('has-success');

                if (jQuery(this).val() === "___.___.___-__" || jQuery(this).val() === "") {
                    jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
                }
            }
        });
    });
</script>
<?php
$this->end();
