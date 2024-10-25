<?php $this->assign('title', 'Dashboard') ?>
<?php
echo $this->Html->css(array("style.index.index.css?v=1.9"), null, array("block" => "css"));
$this->start('script');
?>
<script src="<?php echo $this->Html->url("/AdminLTE/plugins/chartjs/Chart.min.js"); ?>"></script>
<?php echo $this->Html->script("app.index.index.js?v=1.7"); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {

    });
</script>
<?php
$this->end();
?>
<div>
    <div>
        <button type="button" id="btnMapa" class="btn bg-purple"><i class="fa fa-map-marker fa-lg"></i> Visualizar mapa de pacientes</button>
        <br/><br/>
    </div>

    <!-- Consultas Hoje e Amanha -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div id="consultas-hoje" class="info-box">
                <span class="info-box-icon bg-aqua-active"><i class="fa fa-calendar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Consultas hoje</span>
                    <span class="info-box-number"><h3><?php echo $consultasHoje; ?> paciente(s)</h3></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div id="consultas-amanha" class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Consultas amanhã</span>
                    <span class="info-box-number"><h3><?php echo $consultasAmanhan; ?> paciente(s)</h3></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div id="contratos-renovacao" class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Renovação</span>
                    <span class="info-box-number"><h3><?php echo $contratoRenovacao; ?> contrato(s)</h3></span>
                </div>
            </div>
        </div> 
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div id="aniversariantes-mes" class="info-box">
                <span class="info-box-icon bg-maroon"><i class="fa fa-birthday-cake"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Aniversariantes do mês</span>
                    <span class="info-box-number"><h3><?php echo $quantidadeAniversariantes; ?> paciente(s)</h3></span>
                </div>
            </div>
        </div>        
    </div>
    <!-- Financeiro -->
    <div class="row">
        <!-- Contas a Pagar -->        
        <?php if ($this->Acesso->validarAcesso($_dados['id_tipo_usuario'], Acesso::$ACESSO_CONTAS_PAGAR_DASHBOARD)): ?>
            <div class="col-md-6">
                <div id="financeiro" class="box box-danger">
                    <div class="box-header bg-red">
                        <h3 class="box-title"><i class="fa fa-bar-chart fa-lg"></i> &nbsp; Contas a pagar</h3>
                    </div>
                    <div class="box-body">
                        <!-- Hoje -->
                        <h3 class="pointer-hover">Hoje: <span id="total-pagar-hoje" class="text-danger">R$ 0,00</span> <i class="fa fa-caret-down"></i></h3>                    
                        <table id="table-diario" class="table table-responsive table-striped" style="display: none;">
                            <thead>
                                <tr>
                                    <th>Despesa</th>
                                    <th>Favorecido</th>
                                    <th class="text-center">Data Vencimento</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">Não há registros</td>                    
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Despesa</th>
                                    <th>Favorecido</th>
                                    <th class="text-center">Data Vencimento</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Esta semana -->
                        <hr/>
                        <h3 class="pointer-hover">Esta semana: <span id="total-pagar-semana" class="text-danger">R$ 0,00</span> <i class="fa fa-caret-down"></i></h3>
                        <table id="table-semanal" class="table table-responsive table-striped" style="display: none;">
                            <thead>
                                <tr>
                                    <th>Despesa</th>
                                    <th>Favorecido</th>
                                    <th class="text-center">Data Vencimento</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">Não há registros</td>                    
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Despesa</th>
                                    <th>Favorecido</th>
                                    <th class="text-center">Data Vencimento</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </tfoot>
                        </table>
                        <?php if ($this->Acesso->validarAcesso($_dados['id_tipo_usuario'], Acesso::$ACESSO_CONTAS_PAGAR_MES)): ?>        
                            <!-- Este mês -->
                            <hr/>
                            <h3 class="pointer-hover">Este mês: <span id="total-pagar-mes" class="text-danger">R$ 0,00 </span> <i class="fa fa-caret-down"></i></h3>
                            <table id="table-mensal" class="table table-responsive table-striped" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>Despesa</th>
                                        <th>Favorecido</th>
                                        <th class="text-center">Data Vencimento</th>
                                        <th class="text-center">Valor</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center">Não há registros</td>                    
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Despesa</th>
                                        <th>Favorecido</th>
                                        <th class="text-center">Data Vencimento</th>
                                        <th class="text-center">Valor</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>    
        <?php endif; ?>

        <!--  Contas a Receber -->
        <?php if ($this->Acesso->validarAcesso($_dados['id_tipo_usuario'], Acesso::$ACESSO_CONTAS_RECEBER_DASHBOARD)): ?>
            <div class="col-md-6">
                <div id="financeiro" class="box box-success">
                    <div class="box-header bg-green">
                        <p class="box-title"><i class="fa fa-bar-chart fa-lg"></i> &nbsp; Contas a receber</p>
                    </div>
                    <div class="box-body">
                        <!-- Hoje -->
                        <h3 class="pointer-hover">Hoje: <span id="total-receber-hoje" class="text-success">R$ 0,00</span> <i class="fa fa-caret-down"></i></h3>                    
                        <table id="table-receber-diario" class="table table-responsive table-striped" style="display: none;">
                            <thead>
                                <tr>
                                    <th>Recebimento</th>
                                    <th>Paciente</th>
                                    <th class="text-center">Data Vencimento</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">Não há registros</td>                    
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Recebimento</th>
                                    <th>Paciente</th>
                                    <th class="text-center">Data Vencimento</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Esta semana -->
                        <hr/>
                        <h3 class="pointer-hover">Esta semana: <span id="total-receber-semana" class="text-success">R$ 0,00</span> <i class="fa fa-caret-down"></i></h3>
                        <table id="table-receber-semanal" class="table table-responsive table-striped" style="display: none;">
                            <thead>
                                <tr>
                                    <th>Recebimento</th>
                                    <th>Paciente</th>
                                    <th class="text-center">Data Vencimento</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">Não há registros</td>                    
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Recebimento</th>
                                    <th>Paciente</th>
                                    <th class="text-center">Data Vencimento</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Este mês -->
                        <?php if ($this->Acesso->validarAcesso($_dados['id_tipo_usuario'], Acesso::$ACESSO_CONTAS_RECEBER_MES)): ?>  
                            <hr/>
                            <h3 class="pointer-hover">Este mês: <span id="total-receber-mes" class="text-success">R$ 0,00 </span> <i class="fa fa-caret-down"></i></h3>
                            <table id="table-receber-mensal" class="table table-responsive table-striped" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>Recebimento</th>
                                        <th>Paciente</th>
                                        <th class="text-center">Data Vencimento</th>
                                        <th class="text-center">Valor</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center">Não há registros</td>                    
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Recebimento</th>
                                        <th>Paciente</th>
                                        <th class="text-center">Data Vencimento</th>
                                        <th class="text-center">Valor</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>     
        <?php endif; ?>
    </div>
    <!-- /Financeiro -->

    <?php if ($this->Acesso->validarAcesso($_dados['id_tipo_usuario'], Acesso::$ACESSO_GRAFICOS_DASHBOARD)): ?>
        <!-- Graficos -->
        <br/>
        <div class="row">
            <div class="col-md-6">
                <!-- AREA CHART -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Resumo das atividades nas agendas</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="areaChartEventos" style="height:300px;">

                            </canvas>
                        </div>
                        <div>
                            <span class="bg-green" style="padding-left: 15px;"></span>&nbsp; Compareceu
                            <span class="bg-red" style="padding-left: 15px;"></span>&nbsp; Não Compareceu
                            <span class="bg-yellow" style="padding-left: 15px;"></span>&nbsp; Adiado
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <div class="col-md-6">
                <!-- BAR CHART -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Resumo das atividades financeiras efetivadas</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="barChartFinanceiro" style="height:300px;">

                            </canvas>
                        </div>
                        <div>
                            <span class="bg-green" style="padding-left: 15px;"></span>&nbsp; Recebimento
                            <span class="bg-red" style="padding-left: 15px;"></span>&nbsp; Despesa
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <!-- AREA CHART -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <!-- <h3 class="box-title">Resumo de contratos mensal</h3> -->
                        <h3 class="box-title">Resumo de clientes por modalidade</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="pieChartClientesModalidade" style="height:300px;">

                            </canvas>
                        </div>
                        <div id="pieChartClientesModalidadeLegend">

                        </div>
                        <!-- <div class="chart">
                            <canvas id="areaChartContratos" style="height:300px;">

                            </canvas>
                        </div>
                        <div>
                            <span class="bg-green" style="padding-left: 15px;"></span>&nbsp; Contratos Efetivados
                            <span class="bg-red" style="padding-left: 15px;"></span>&nbsp; Contratos Finalizados
                        </div> -->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <div class="col-md-6">
                <!-- AREA CHART -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Resumo das aulas contratadas</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="pieChartPlanoSessao" style="height:300px;">

                            </canvas>
                        </div>
                        <div id="pieChartPlanoSessaoLegend">

                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div><!-- /.row -->
    <?php endif; ?>
    <!-- Ultimas Consultas -->
    <br/>
    <div class="box box-primary">
        <div class="box-header">
            <p class="box-title">Últimas consultas realizadas</p>            
        </div>
        <div class="box-body well" id="body-timeline">

        </div> 
    </div>  

</div>
<!-- Modal Mapa dos pacientes -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalMapa" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-map-marker fa-lg"></i> Mapa de pacientes</h4>
            </div>
            <div class="modal-body">
                <div id="mapa-content">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalConsultas" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Consultas</h4>
            </div>
            <div class="modal-body">
                <h3 id="titulo-tabela"></h3>
                <table id="tabela-consultas" class="table table-responsive table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Profissional</th>
                            <th>Paciente</th>
                            <th>E-mail</th>
                            <th class="text-center">Horário</th>
                        </tr>
                    </thead>
                    <tbody>                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Profissional</th>
                            <th>Paciente</th>
                            <th>E-mail</th>
                            <th class="text-center">Horário</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<!-- Modal -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalAniversariantes" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Aniversariantes</h4>
            </div>
            <div class="modal-body">
                <h3 id="titulo-tabela">Aniversariantes do mês</h3>
                <table id="table-aniversariantes-mes" class="table table-responsive table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th class="text-center">Data Aniversário</th>
                        </tr>
                    </thead>
                    <tbody>                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal -->