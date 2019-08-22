<!DOCTYPE html>
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//cluster-piwik.locaweb.com.br/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1906]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//cluster-piwik.locaweb.com.br/piwik.php?idsite=1906" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
<html>
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

        <?php
        echo $this->fetch('css');
        ?>

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
        echo $this->Html->css(array("style.admin.css?v=2.3"));
        ?>
        <!-- jQuery 2.1.4 -->
        <script src="<?php echo $this->Html->url("/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"); ?>"></script>
        <?php
        echo $this->Html->script(array("app.js?v=1.3"));
        ?>
    </head>
    <!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
    <!-- the fixed layout is not compatible with sidebar-mini -->
    <body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo $this->Html->url(array("controller" => "index", "action" => "index")); ?>" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><img src="<?php echo $this->Html->url("/img/small-logo2.png"); ?>" height="30" /></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><img src="<?php echo $this->Html->url("/img/small-logo.png"); ?>" height="45" /></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->

                            <!-- Notifications: style can be found in dropdown.less -->

                            <!-- Tasks: style can be found in dropdown.less -->

                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class=""><i class="fa fa-user"></i> &nbsp; <?php echo isset($_dados['nome']) ? $_dados['nome'] . ' ' . $_dados['sobrenome'] : ""; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header" style="height: 80px;">
                                        <p>
                                            <?php echo isset($_dados['nome']) ? $_dados['nome'] . ' ' . $_dados['sobrenome'] : ""; ?>
                                            <small><?php echo isset($_dados['tipo_usuario']) ? $_dados['tipo_usuario']['descricao'] : ""; ?></small>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "perfil")) . "/" . $_dados['idusuario']; ?>" class="btn btn-default btn-flat">
                                                <i class="fa fa-edit fa-lg"></i> Perfil
                                            </a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?php echo $this->Html->url(array("controller" => "logout", "action" => "index")); ?>" class="btn btn-default btn-flat">
                                                <i class="fa fa-power-off fa-lg"></i> Sair
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <!-- =============================================== -->

            <!-- Left side column. contains the sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <!-- search form -->
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header">MENU</li>
                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "index", "action" => "index")); ?>">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span></i>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "agenda", "action" => "index")); ?>">
                                <i class="fa fa-calendar"></i> <span>Agenda</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-users"></i>
                                <span>Cadastros</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo $this->Html->url(array("controller" => "cargo", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Cargos</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "categoria_aula", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Categoria de Aulas</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "conta_bancaria", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Conta Bancária</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "favorecido", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Favorecidos</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Pacientes</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "profissional", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Profissionais</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Usuários</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "tipo_usuario", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Tipos de Usuário</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-usd"></i>
                                <span>Finanças</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo $this->Html->url(array("controller" => "categoria_despesa", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Categorias de Despesas</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "despesa", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Despesas</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Recebimentos</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "contas_pagar", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Contas à Pagar</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "contas_receber", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Contas à Receber</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "plano_contas", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Plano de Contas</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-line-chart"></i> <span>Relatórios</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "paciente")); ?>"><i class="fa fa-circle-o"></i> Pacientes</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "despesa")); ?>"><i class="fa fa-circle-o"></i> Despesas</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "recebimento")); ?>"><i class="fa fa-circle-o"></i> Recebimentos</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "caixa")); ?>"><i class="fa fa-circle-o"></i> Caixa</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "cheque")); ?>"><i class="fa fa-circle-o"></i> Cheques</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "comissao")); ?>"><i class="fa fa-circle-o"></i> Comissão</a></li>
                            </ul>
                        </li>
                        <li class="header">CONFIGURAÇÕES</li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-cogs"></i> <span>Configurações</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo $this->Html->url(array("controller" => "caixa_loja", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Caixas</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "plano_sessao", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Plano de Sessões</a></li>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "tipo_financeiro", "action" => "index")); ?>"><i class="fa fa-circle-o"></i> Tipos de Financeiro</a></li>
                            </ul>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- =============================================== -->

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->

                <!-- Main content -->
                <section class="content">
                    <?php echo $this->Session->flash(); ?>
                    <div class="box box-primary">
                        <div class="box-header">                            
                        </div>
                        <div class="box-body">
                            <?php echo $this->fetch('content'); ?>
                        </div>
                    </div>                    
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <footer class="main-footer">
                <strong>Copyright &copy; <?php echo date("Y"); ?> Conexão Viva Mais.</strong> All rights reserved.
                <?php //echo $this->element('sql_dump');  ?>
            </footer>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Create the tabs -->
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Home tab content -->
                    <div class="tab-pane active" id="control-sidebar-home-tab">
                        <h4 class="control-sidebar-heading">Clínica </h4>
                        <p>Fantasia: <?php echo $_dados['clinica']['fantasia']; ?></p>
                        <p>CNPJ: <?php echo $_dados['clinica']['cnpj']; ?></p>

                    </div>
                    <!-- /.tab-pane -->                    
                    <div class="tab-pane" id="control-sidebar-settings-tab">
                        <h4 class="control-sidebar-heading">Clínica </h4>
                        <a class="btn btn-flat btn-success" href="<?php echo $this->Html->url(array("controller" => "clinica", "action" => "index")); ?>?id=<?php echo $_dados['clinica']['idclinica']; ?>"><i class="fa fa-edit"></i> Atualizar Dados</a>
                    </div>
                    <!-- /.tab-pane -->
                </div>
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div><!-- ./wrapper -->

        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo $this->Html->url("/AdminLTE/bootstrap/js/bootstrap.min.js"); ?>"></script>
        <!-- SlimScroll -->
        <script src="<?php echo $this->Html->url("/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"); ?>"></script>
        <!-- FastClick -->
        <script src="<?php echo $this->Html->url("/AdminLTE/plugins/fastclick/fastclick.min.js"); ?>"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo $this->Html->url("/AdminLTE/dist/js/app.min.js"); ?>"></script>
        <?php echo $this->fetch('script'); ?>
    </body>
</html>
