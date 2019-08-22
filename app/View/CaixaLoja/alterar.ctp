<?php
echo $this->Html->css(array("style.cadastro.paciente.css", "jspanel/jquery-ui.min.css", "jspanel/jquery.jspanel.css",
    "datepicker.min.css", "fileinput.min.css", "select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("app.cadastro.paciente.js", "jspanel/jquery-ui.min.js", "jspanel/jquery.jspanel.js",
    "jspanel/mobile-detect.min.js", "bootstrap-datepicker.min.js", "jquery.maskedinput.min.js",
    "jquery-validate.min.js", "fileinput.min.js", "jspanel/jquery.jspanel.js", "jspanel/mobile-detect.min.js",
    "select2/select2.full.min.js", "icheck/icheck.min.js"), array("block" => "script"));
?>
<div class="">
    <?php
    echo $this->Form->create('Caixa_loja', array("url" => array("controller" => "caixa_loja", "action" => "alterar"), "class" => "form-horizontal", "method" => "post", "enctype" => "multipart/form-data"));
    ?>   
    <div>
        <h3>Cadastro de Caixa</h3>
        <hr/>
        <input type="hidden" id="CaixaIdcaixaloja" name="data[Caixa][idcaixaloja]" value="<?php echo $Caixa['idcaixaloja'] ?>" />
        <div id="tab-1" class="tab col-md-12 col-xs-12">
            <br/>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="CaixaNome" class="control-label col-md-4">*Nome do Caixa:</label>
                    <div class="col-md-8">
                        <input autofocus="autofocus" id="CaixaNome" name="data[Caixa][nome_caixa]" value="<?php echo $Caixa['nome_caixa'] ?>" data-required="true" placeholder="Digite o nome" class="form-control" />
                    </div>                        
                </div>
                <div class="form-group ">
                    <label for="CaixaUsuario" class="control-label col-md-4">*Usuário Responsável:</label>
                    <div class="col-md-8">
                        <select id="Usuarios" name="data[Caixa][id_usuario]" data-required="true" class="form-control" title="Usuario">
                            <?php
                            $total_ = count($Usuarios);
                            if ($total_ > 0):
                                for ($i_ = 0; $i_ < $total_; $i_++) :
                                    ?>
                                    <option <?php echo ($Caixa['id_usuario'] == $Usuarios[$i_]["u"]["idusuario"]) ? 'selected="selected"' : ''; ?>  value="<?php echo $Usuarios[$i_]["u"]["idusuario"]; ?>" ><?php echo $Usuarios[$i_]["u"]["nome"] . " " . $Usuarios[$i_]["u"]["sobrenome"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>
                        
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
                                    <option <?php echo ($Caixa['id_clinica'] == $Clinicas[$i_]["c"]["idclinica"]) ? 'selected="selected"' : ''; ?>  value="<?php echo $Clinicas[$i_]["c"]["idclinica"]; ?>" ><?php echo $Clinicas[$i_]["c"]["fantasia"]; ?></option>
                                    <?php
                                endfor;
                            endif;
                            ?>
                        </select>
                    </div>                        
                </div>                      
                <div class="form-group ">
                    <label for="AtivoSim" class="control-label col-md-4">*Ativo:</label>
                    <div class="col-md-8" style="padding-top: 5px" id="div-radios">                   
                        <input name="data[Caixa][ativo]" type="radio" value="1" id="AtivoSim" <?php echo ($Caixa['ativo'] == 1) ? 'checked' : ''; ?>/>
                        <label for="AtivoSim">Sim</label>
                        &nbsp;&nbsp;
                        <input name="data[Caixa][ativo]" type="radio" value="0" id="AtivoNao" <?php echo ($Caixa['ativo'] == 0) ? 'checked' : ''; ?> />
                        <label for="AtivoNao">Não</label>                    
                    </div>                        
                </div>                   
            </div>
        </div>                          
        <p style="color: #ff6707">Campos com <strong style="font-size: 15pt;">*</strong> são obrigatórios</p>
        <br/>
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "caixa_loja", "action" => "index")); ?>">Voltar</a>
                <input type="submit" value="Atualizar" class="btn btn-primary"/>
            </div>                        
        </div>
    </div>
    <?php
    $this->Form->end();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $('#Usuarios').select2({
                autocomplete: true,
                width: "100%"
            });
            $('#Clinicas').select2({
                autocomplete: true,
                width: "100%"
            });               
            $('#div-radios').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</div>