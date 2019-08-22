<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $this->fetch('title'); ?>
        </title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600|Oxygen:700' rel='stylesheet' type='text/css'>
        <?php
        //echo $this->Html->meta('icon');
        echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));

        echo $this->fetch('meta');
        echo $this->Html->css(array("bootstrap-lumen.min.css?v=1.1", "style.css?v=1.7", "font-awesome/css/font-awesome.min.css"));
        echo $this->fetch('css');
        echo $this->Html->script(array("jquery-2.1.1.min.js", "bootstrap.min.js", "app.js?v=1.2"));
        ?>
    </head>
    <body>
        <div>
            <nav id="cabecalho" class="navbar navbar-default bg-primary navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed"
                                data-toggle="collapse" data-target="#navbar_collapse">
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo $this->Html->url(array("controller" => "index", "action" => "index")); ?>"><img src="<?php echo $this->Html->url("/img/small-logo.png"); ?>" height="50" style="margin-top: -20px;"/></a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbar_collapse">
                        <ul class="nav navbar-nav">
                            <!--<li>                                
                                    <a href="<?php echo $this->Html->url(array("controller" => "index", "action" => "index")); ?>"><i class="fa fa-dashboard fa-lg"></i> &nbsp; Dashboard</a>
                                </li>-->
                            <li>                                
                                <a href="<?php echo $this->Html->url(array("controller" => "agenda", "action" => "index")); ?>"><i class="fa fa-calendar fa-lg"></i> &nbsp; Agendas</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-users fa-lg"></i> &nbsp; Cadastros &nbsp; <b class="caret"></b></a>
                                <ul id="dropdown-menu" class="dropdown-menu">
                                    <li>
                                        <a id="link-cargo" href="<?php echo $this->Html->url(array("controller" => "cargo", "action" => "index")); ?>">
                                            Cargos
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-conta-bancaria" href="<?php echo $this->Html->url(array("controller" => "conta_bancaria", "action" => "index")); ?>">
                                            Conta Bancária
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-favorecido" href="<?php echo $this->Html->url(array("controller" => "favorecido", "action" => "index")); ?>">
                                            Favorecidos
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-paciente" href="<?php echo $this->Html->url(array("controller" => "paciente", "action" => "index")); ?>">
                                            Pacientes
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-profissional" href="<?php echo $this->Html->url(array("controller" => "profissional", "action" => "index")); ?>">
                                            Profissionais
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-usuario" href="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "index")); ?>">
                                            Usuários
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bar-chart fa-lg"></i> &nbsp; Finanças &nbsp; <b class="caret"></b></a>
                                <ul id="dropdown-menu" class="dropdown-menu">
                                    <li>
                                        <a id="link-despesa" href="<?php echo $this->Html->url(array("controller" => "despesa", "action" => "index")); ?>">
                                            Despesas
                                        </a>
                                    </li> 
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-despesa" href="<?php echo $this->Html->url(array("controller" => "recebimento", "action" => "index")); ?>">
                                            Recebimentos
                                        </a>
                                    </li>   
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-contas-pagar" href="<?php echo $this->Html->url(array("controller" => "contas_pagar", "action" => "index")); ?>">
                                            Contas à Pagar
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-contas-pagar" href="<?php echo $this->Html->url(array("controller" => "contas_receber", "action" => "index")); ?>">
                                            Contas à Receber
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-plano-conta" href="<?php echo $this->Html->url(array("controller" => "plano_contas", "action" => "index")); ?>">
                                            Plano de Contas
                                        </a>
                                    </li> 
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-cogs fa-lg"></i> &nbsp; Configurações &nbsp; <b class="caret"></b></a>
                                <ul id="dropdown-menu" class="dropdown-menu">
                                    <li>
                                        <a id="link-caixa" href="<?php echo $this->Html->url(array("controller" => "caixa_loja", "action" => "index")); ?>">
                                            Caixas
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-plano-sessao" href="<?php echo $this->Html->url(array("controller" => "plano_sessao", "action" => "index")); ?>">
                                            Plano de Sessões
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a id="link-tipo-financeiro" href="<?php echo $this->Html->url(array("controller" => "tipo_financeiro", "action" => "index")); ?>">
                                            Tipos de Financeiro
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-line-chart fa-lg"></i> &nbsp; Relatórios &nbsp; <b class="caret"></b></a>
                                <ul id="dropdown-menu" class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "paciente")); ?>"> &nbsp; Pacientes</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "despesa")); ?>"> &nbsp; Despesas</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "recebimento")); ?>"> &nbsp; Recebimentos</a>
                                    </li>     
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "caixa")); ?>"> &nbsp; Caixa</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "cheque")); ?>"> &nbsp; Cheques</a>
                                    </li>  
                                </ul>
                            </li>
                        </ul>

                        <!-- NAVBAR RIGHT !-->
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-user fa-lg"></i> &nbsp; <?php echo isset($_dados['nome']) ? $_dados['nome'] . ' ' . $_dados['sobrenome'] : ""; ?> <b class="caret"></b></a>
                                <ul id="dropdown-menu" class="dropdown-menu">
                                    <li><a href="<?php echo $this->Html->url(array("controller" => "usuario", "action" => "perfil")) . "/" . $_dados['idusuario']; ?>"><i class="fa fa-edit fa-lg"></i> &nbsp; Alterar perfil</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo $this->Html->url(array("controller" => "logout", "action" => "index")); ?>">
                                            <i class="fa fa-power-off fa-lg"></i> &nbsp; Sair
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </div>
                <!-- /.container-fluid --> 
            </nav>            
        </div>

        <div class="wrapper container">
<?php echo $this->Session->flash(); ?>

            <?php echo $this->fetch('content'); ?>
        </div>


        <div id="footer">
        </div>
<?php echo $this->fetch('script'); ?>
        <?php //echo $this->element('sql_dump');  ?>
    </body>
</html>
