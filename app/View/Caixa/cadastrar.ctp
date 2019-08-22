<?php
echo $this->Html->css(array("select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("select2/select2.full.min.js", "icheck/icheck.min.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Caixa', array("url" => array("controller" => "caixa", "action" => "cadastrar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>    
    <div>
        <h3>Cadastro de Caixa</h3>
        <hr/>
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="CaixaDescricao" class="control-label col-md-4">*Descrição:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus"  id="CaixaDescricao" name="data[Caixa][descricao]" data-required="true" placeholder="Digite a descrição do caixa" class="form-control" />
                    </div>                        
                </div> 
                <div class="form-group ">
                    <label for="CaixaClinica" class="control-label col-md-4">*Clínica:</label>
                    <div class="col-md-8">
                        <select id="Clinicas" name="data[Caixa][id_clinica]" data-required="true" class="form-control" title="Clinica">
                            <?php
                            $total_ = count($Clinicas);
                            if ($total_ > 0):
                                for ($i_ = 0; $i_ < $total_; $i_++) :
                                    ?>
                                    <option value="<?php echo $Clinicas[$i_]["c"]["idclinica"]; ?>" ><?php echo $Clinicas[$i_]["c"]["fantasia"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>
                    </div>                        
                </div>                  
                <div class="form-group ">
                    <label class="control-label col-md-4">*Tipo:</label>
                    <div class="col-md-8 div-radios" style="padding-top: 5px">    
                        <input name="data[Caixa][tipo]" type="radio" value="COFRE" id="CaixaTipoCofre" />
                        <label for="CaixaTipoCofre">Cofre</label>   
                        &nbsp;&nbsp;              
                        <input name="data[Caixa][tipo]" type="radio" value="CONTA" id="CaixaTipoConta" />
                        <label for="CaixaTipoConta">Conta Bancária</label>
                        &nbsp;&nbsp;
                        <input name="data[Caixa][tipo]" type="radio" value="DIARIO" id="CaixaTipoDiario" checked />
                        <label for="CaixaTipoDiario">Diário</label>                     
                    </div>                        
                </div>                  
                <div class="form-group ">
                    <label class="control-label col-md-4">*Ativo:</label>
                    <div class="col-md-8 div-radios" style="padding-top: 5px" >                   
                        <input name="data[Caixa][ativo]" type="radio" value="1" id="AtivoSim" checked />
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[Caixa][ativo]" type="radio" value="0" id="AtivoNao" />
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div>                    
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "caixa", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Salvar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $('#Clinicas').select2({
                autocomplete: true,
                width: "100%"
            });
            $('.div-radios').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue',
                increaseArea: '20%' // optional
            });

            jQuery('#CaixaCadastrarForm').validate({
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
</div>