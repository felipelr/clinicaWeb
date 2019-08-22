<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>
            <?php echo $this->fetch('title'); ?>
        </title>
        <style>
            html {
                font-family: sans-serif;
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }
            body {
                margin: 0;
                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                font-size: 14px;
            }
            h1 {
                font-size: 36px;
            }
            h2{                
                font-size: 30px;
            }
            h4{
                font-size: 18px;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: Arial, Helvetica, sans-serif;
                font-weight: 500;
                line-height: 1.1;
                color: inherit;
            }
            hr {
                height: 0;
                -webkit-box-sizing: content-box;
                -moz-box-sizing: content-box;
                box-sizing: content-box;
                margin-top: 20px;
                margin-bottom: 20px;
                border: 0;
                border-top: 1px solid #eee;
            }
            .text-success{
                color: #3c763d;
            }
            .text-primary {
                color: #337ab7;
            }
            .control-label{
                font-weight: bolder;
            }
            .col-sm-2, .col-sm-10{
                position: relative;
                min-height: 1px;
                padding-right: 15px;
                padding-left: 15px;
                float: left !important;
            }
            .col-sm-10 {
                width: 75%;
            }
            .col-sm-2 {
                width: 25%;
            }
            .row{
                clear: left;
                padding-top: 15px;
                padding-bottom: 15px;
            }
            .container {
                padding-right: 15px;
                padding-left: 15px;
            }
            .bg-titulo{
                padding-left: 15px;
                background-color: #3c8dbc;
            }
            .bg-titulo .titulo{
                color: #ffffff;
            }
            .table thead tr th, .table tbody tr td {
                border: 1px solid #cecece;
                padding: 10px;
                text-align: left;
            }
            .table{          
                border-collapse: collapse;
                border-spacing: 0 !important;    
                width: 100%;
            }
        </style>
    </head>
    <body>        
        <script type="text/php">
            if ( isset($pdf) ) {
            $font = Font_Metrics::get_font("helvetica", "bold");
            $pdf->page_text(550, 750, "{PAGE_NUM} de {PAGE_COUNT}", $font, 10, array(0,0,0));
            }
        </script>
        <div>
            <div class="bg-titulo">
                <h2 class="titulo">Ficha de Fisioterapia</h2>
            </div>
            <!-- <h2><?php //echo $FichaFisioterapia['descricao'];        ?></h2> -->
            <hr/>
            <div>
                <h2 class="text-success">Identificação do Paciente</h2>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Nome:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['nome_paciente']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Sobrenome:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['sobrenome_paciente']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Data de nascimento:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['data_nascimento_paciente']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Sexo:</label> 
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['sexo_paciente'] == 'M' ? 'Masculino' : 'Feminino'; ?></span>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Estado Civil:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['estado_civil_paciente'] == 'Solteiro' ? 'Solteiro' : 'Casado'; ?></span>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Naturalidade:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['naturalidade_paciente']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Local Nascimento:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['local_nascimento_paciente']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Tel. Fixo:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $Paciente['telefone_fixo']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Tel. Celular:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $Paciente['telefone_celular']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Profissão:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['profissao_paciente']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Endereço Residencial:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['endereco_residencial_paciente']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Endereço Comercial:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $FichaFisioterapia['endereco_comercial_paciente']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="clear: left;">
                <br/>
            </div>

            <hr/>
            <div style="page-break-before: always;"></div>
            <div>
                <h2 class="text-success">História Clínica</h2>
                <div class="container">
                    <div>
                        <h4><b>Queixa principal</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['queixa_principal']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>Hábitos de vida</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['habitos_vida']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>História atual e pregressa da doença</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['historia_atual']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>Antecedentes pessoais e familiares</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['antecedentes_pessoais']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>Tratamantos realizados</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['tratamentos_realizados']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>

            <div>
                <h2 class="text-success">Exame Clínico/Físico</h2>
                <div class="container">
                    <div>
                        <h4><b>Descrição do estado de saúde físico funcional</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['exame_clinico_fisico']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>

            <div>
                <h2 class="text-success">Exames Complementares</h2>
                <div class="container">
                    <div>
                        <h4><b>Descrição dos exames complementares realizados</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['exame_complementar']; ?>
                        </div>
                    </div>
                </div>                
            </div>

            <hr/>

            <div>
                <h2 class="text-success">Diagnóstico e Prognóstico</h2>
                <div class="container">
                    <div>
                        <h4><b>Diagnóstico</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['diagnostico']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>Prognóstico</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['prognostico']; ?>
                        </div>
                    </div>
                </div>                
            </div>

            <hr/>

            <div>
                <h2 class="text-success">Plano Terapêutico</h2>
                <div class="container">
                    <div>
                        <h4><b>Procedimentos fisioterapêuticos propostos</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['procedimentos_fisioterapeuticos']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>Objetivo(s) terapêutico(s)</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['objetivos_terapeuticos']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>Quantitativo provável de atendimento</b></h4>
                        <div>
                            <span><?php echo $FichaFisioterapia['quantitativo_provavel']; ?></span>
                        </div>
                    </div>
                </div>                
            </div>

            <hr/>

            <div>
                <h2 class="text-success">Evolução</h2>
                <div class="container">
                    <div>
                        <h4><b>Evolução do estado de saúde</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['evolucao_estado_saude']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>Tratamento realizado em cada atendimento</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['tratamento_realizado_atendimento']; ?>
                        </div>
                    </div>
                    <div>
                        <h4><b>Eventuais intercorrências</b></h4>
                        <div>
                            <?php echo $FichaFisioterapia['eventuais_intercorrencias']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>

            <div>
                <h2 class="text-success">Acompanhamentos</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Data do registro</th>
                            <th>Descrição do acompanhamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = count($Acompanhamentos);
                        if ($total > 0):
                            for ($i = 0; $i < $total; $i++) :
                                ?>
                                <tr>
                                    <td><?php echo $Acompanhamentos[$i]["evento"]["descricao"]; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($Acompanhamentos[$i]["acompanhamento_fisioterapia"]["modified"])); ?></td>
                                    <td><?php echo $Acompanhamentos[$i]["acompanhamento_fisioterapia"]["descricao"]; ?></td>
                                </tr>                                
                                <?php
                            endfor;
                        endif;
                        if ($total == 0):
                            ?>
                                <tr>
                                    <td colspan="3">Não há registros</td>                                    
                                </tr> 
                            <?php
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>

            <hr/>

            <div>
                <h2 class="text-success">Identificação do profissional</h2>
                <div class="container">
                    <div>
                        <br/>____________________________________________________<br/>
                        <?php echo $Profissional['nome'] . ' ' . $Profissional['sobrenome']; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
