<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="#"><b>Conexao</b> Viva<b>+</b></a>
    </div>
    <!-- User name -->
    <div class="lockscreen-name"><?php echo isset($_dados['nome']) ? $_dados['nome'] . ' ' . $_dados['sobrenome'] : ""; ?></div>

    <!-- START LOCK SCREEN ITEM -->
    <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
            <div style="border-radius: 50%;width: 70px;height: 70px;">
                <i class="fa fa-user fa-3x" style="margin-left: 18px; margin-top: 15px;"></i>
            </div>            
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials">
            <div class="input-group">
                <label class="form-control">
                    <a class="btn btn-link" href="<?php echo $this->Html->url(array("controller" => "index", "action" => "index")); ?>"> &nbsp; Ir para Dashboard &nbsp; <i class="fa fa-arrow-right text-muted"></i></a>
                </label>
            </div>
        </form><!-- /.lockscreen credentials -->

    </div><!-- /.lockscreen-item -->
    <div class="help-block text-center">
        <h4 class="text-danger"><i class="fa fa-warning"></i> Você não possui permissão para acessar este recurso.</h4>
    </div>
</div><!-- /.center -->
<br/>