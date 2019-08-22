<!DOCTYPE html>
<html lang="pt" style="background-image: url('<?php echo $this->Html->url("/img/bg-html.jpg"); ?>'); background-size: 100% 100%; height: 100%;">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->fetch('title'); ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?php echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon')); ?>
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo $this->Html->url("/AdminLTE/bootstrap/css/bootstrap.min.css"); ?>">
        <!-- Font Awesome -->    
        <?php echo $this->Html->css(array("font-awesome/css/font-awesome.min.css")); ?>
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $this->Html->url("/AdminLTE/dist/css/AdminLTE.min.css"); ?>">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo $this->Html->url("/AdminLTE/dist/css/skins/_all-skins.min.css"); ?>">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php
        echo $this->fetch('css');
        ?>
        <!-- jQuery 2.1.4 -->
        <script src="<?php echo $this->Html->url("/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"); ?>"></script>

    </head>
    <body class="hold-transition login-page" style="background-color: rgba(100,100,100,0.3); height: 100%;">
        <div class="container" style="height: 100%; padding-top: 10px;">

            <?php echo $this->Session->flash(); ?>

            <?php echo $this->fetch('content'); ?>

        </div>

        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo $this->Html->url("/AdminLTE/bootstrap/js/bootstrap.min.js"); ?>"></script>

        <?php echo $this->fetch('script'); ?>
    </body>
</html>
