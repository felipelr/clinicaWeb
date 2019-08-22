<?php
echo $this->Html->css(array("icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("icheck/icheck.min.js"), array("block" => "script"));
?>
<?php
if ($this->Session->check('Message.auth')):
    echo $this->Session->flash('auth');
endif;
echo $this->Form->create('User', array("id" => "form_login", "url" => array('controller' => 'login', 'action' => 'login_2'), "class" => "form-signin", "method" => "post"));
?>
<h1 class="form-signin-heading bg-primary"><?php echo __("Login"); ?></h1>
<div class="input-group">
    <div class="input-group-addon"><i class="fa fa-user fa-lg"></i></div>
    <?php echo $this->Form->input('email', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Email"), 'autofocus')); ?>
</div>
<div class="input-group">
    <div class="input-group-addon"><i class="fa fa-lock fa-lg"></i></div>
    <?php echo $this->Form->input('senha', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Senha"), 'type' => 'password')); ?>
</div>
<?php
echo $this->Form->input('rememberMe', array('div' => false, 'type' => 'checkbox', 'label' => 'Manter Conectado'));
echo $this->Form->button(__("Esqueci minha senha"), array('div' => false, 'class' => 'btn btn-lg btn-link pull-right', 'type' => 'button', 'id' => 'esqueci'));
echo $this->Form->button(__("Entrar"), array('div' => false, 'class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit'));
echo $this->Form->end();
?>

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
                echo $this->Form->input('email', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Email"), 'autofocus', 'data-validate' => 'emaill','data-required' => 'true'));
                echo "<br/>";
                echo $this->Form->button(__("Enviar"), array('div' => false, 'class' => 'btn btn-lg btn-success', 'type' => 'submit'));
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