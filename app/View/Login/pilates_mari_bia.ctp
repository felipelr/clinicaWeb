<?php
echo $this->Html->css(array("icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("icheck/icheck.min.js", "jquery-validate.min.js"), array("block" => "script"));
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Conexao</b> Viva<b>+</b></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Entre para iniciar sua sessão</p>
        <?php
        if ($this->Session->check('Message.auth')):
            echo $this->Session->flash('auth');
        endif;
        echo $this->Form->create('User', array("id" => "form_login", "url" => array('controller' => 'login', 'action' => 'pilates_mari_bia'), "method" => "post"));
        ?>
        <div class="form-group has-feedback">
            <?php echo $this->Form->input('email', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Email"), 'autofocus')); ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?php echo $this->Form->input('senha', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Senha"), 'type' => 'password')); ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <?php echo $this->Form->input('rememberMe', array('div' => false, 'type' => 'checkbox', 'label' => ' Manter Conectado')); ?>
                </div>
            </div>
            <div class="col-xs-4">
                <?php echo $this->Form->button(__("Entrar"), array('div' => false, 'class' => 'btn btn-primary btn-block btn-flat', 'type' => 'submit')); ?>                
            </div>
        </div>
        <?php
        echo $this->Form->end();
        ?>
                
        <?php echo $this->Form->button(__("Esqueci minha senha"), array('div' => false, 'class' => 'btn btn-link', 'type' => 'button', 'id' => 'esqueci')); ?>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Esqueci minha senha</h4>
            </div>
            <div class="modal-body" id="texto-modal">
                <?php echo $this->Form->create('User', array("id" => "form_esqueci_senha", "url" => array('controller' => 'login', 'action' => 'esqueci_minha_senha'), "class" => "form-horizontal", "method" => "post"));
                ?>
                <p class=""><?php echo __("Informe seu e-mail"); ?></p>
                <?php
                echo $this->Form->input('email', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Email"), 'autofocus', 'data-validate' => 'emaill', 'data-required' => 'true'));
                echo "<br/>";
                echo $this->Form->button(__("Enviar"), array('div' => false, 'class' => 'btn btn-flat btn-success', 'type' => 'submit'));
                ?>
                <?php
                echo $this->Form->end();
                ?>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<?php
$this->start('script');
?>
<style>
    .icheck>label{
        padding-left: 8px;
    }
</style>
<script type="text/javascript">
    jQuery(function () {
        jQuery('#UserRememberMe').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });

        jQuery("#esqueci").click(function () {
            jQuery("#myModal").modal('show');
        });

        jQuery("#form_login").validate({
            errorClass: "text-danger",
            validClass: "text-success",
            rules: {
                "data[User][email]": {
                    required: true, email: true
                },
                "data[User][senha]": {
                    required: true
                }
            },
            messages: {
                "data[User][email]": {
                    required: "Digite o seu e-mail",
                    email: "Digite um e-mail válido"
                },
                "data[User][senha]": {
                    required: "Digite a sua senha",
                    minLength: "A sua senha deve conter, no mínimo, 2 caracteres"
                }
            }
        });

        jQuery('#form_esqueci_senha').validate({
            onKeyup: true,
            eachInvalidField: function () {
                jQuery(this).closest('div').removeClass('has-success').addClass('has-error');
            },
            eachValidField: function () {
                jQuery(this).closest('div').removeClass('has-error').addClass('has-success');
            }
        });

        jQuery.validateExtend({
            emaill: {
                required: true,
                pattern: /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/
            }
        });
    });
</script>
<?php
$this->end();
