<?php
echo $this->Html->css(array("select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("jquery.maskedinput.min.js", "jquery.maskMoney.min.js", "jquery-validate.min.js", "select2/select2.full.min.js",
    "icheck/icheck.min.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Caixa', array("url" => array("controller" => "caixa", "action" => "abertura"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Abertura de Caixa</h3>
        <hr/>
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6"> 
                <div class="form-group">
                    <label for="CaixaIdCaixa" class="control-label col-md-4">*Caixa:</label>
                    <div class="col-md-8">
                        <select id="CaixaIdCaixa" name="data[Caixa][id_caixa_abertura]" data-required="true" class="form-control" title="Caixa">
                            <?php
                            $total_ = count($Caixas);
                            if ($total_ > 0):
                                for ($i_ = 0; $i_ < $total_; $i_++) :
                                    ?>
                                    <option value="<?php echo $Caixas[$i_]["c"]["idcaixa"]; ?>" ><?php echo $Caixas[$i_]["c"]["descricao"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>
                    </div>                        
                </div>                
                <div class="form-group ">
                    <label for="CaixaSaldoInicial" class="control-label col-md-4">*Saldo Inicial:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="CaixaSaldoInicial" name="data[Caixa][saldo_inicial]" data-required="true" class="form-control" placeholder="Saldo inicial do caixa" />
                    </div>                        
                </div>
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "index", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Abrir Caixa" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#CaixaIdCaixa').select2({
            autocomplete: true,
            width: "100%"
        });

        $("#CaixaSaldoInicial").maskMoney({symbol: "R$", decimal: ",", thousands: "."});

        $('#div-radios').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });

        jQuery('#CaixaAberturaForm').validate({
            onKeyup: true,
            eachInvalidField: function () {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            },
            eachValidField: function () {
                jQuery(this).closest('div').removeClass('has-error').addClass('has-success');

                if (jQuery(this).val() === "__/__/____" || jQuery(this).val() === "___.___.___-__" || jQuery(this).val() === "__.___.___-_" || jQuery(this).val() === "_____-___" || jQuery(this).val() === "") {
                    jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
                }
            }
        });
    });
</script>
