<div class="">
    <div>
        <h3><?php echo $Clinica['fantasia']; ?></h3>
        <hr/>
    </div>
    <div>
        <form id="FormClinicaAlteracao" class="form-horizontal" method="post" action="<?php echo $this->Html->url(array("controller" => "clinica", "action" => "index")); ?>">
            <input type="hidden" id="ClinicaIdclinia" name="data[Clinica][idclinica]" value="<?php echo $Clinica['idclinica'] ?>" />
            <div class="form-group ">
                <label for="ClinicaFantansia" class="control-label col-md-2">Fantasia:</label>
                <div class="col-md-8">
                    <input autofocus="autofocus" id="ClinicaFantansia" name="data[Clinica][fantasia]" required value="<?php echo $Clinica['fantasia']; ?>" placeholder="Fantasia da clínica" class="form-control" />
                </div>                        
            </div>  
            <div class="form-group ">
                <label for="ClinicaCnpj" class="control-label col-md-2">CNPJ:</label>
                <div class="col-md-8">
                    <input id="ClinicaCnpj" name="data[Clinica][cnpj]" required value="<?php echo $Clinica['cnpj']; ?>" placeholder="99.999.999/9999-99" class="form-control" />
                </div>                        
            </div>
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array("controller" => "index", "action" => "index")); ?>">Ir para dashboard</a>
                <input type="submit" value="Atualizar" class="btn btn-primary"/>
            </div>
        </form>
    </div>    
</div>

<?php
echo $this->Html->script(array("jquery.maskedinput.min.js", "jquery-validate.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#ClinicaCnpj").mask("99.999.999/9999-99");
        
        jQuery('#FormClinicaAlteracao').validate({
        onKeyup: true,
        eachInvalidField: function () {
            jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
        },
        eachValidField: function () {
            jQuery(this).closest('div').removeClass('has-error').addClass('has-success');

            if (jQuery(this).val() === "__.___.___/____-__" || jQuery(this).val() === "") {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            }
        }
    });
    });
</script>
<?php
$this->end();