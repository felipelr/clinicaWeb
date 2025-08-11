<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
            .table thead tr th, .table tbody tr td, .table tfoot tr th {
                border: 1px solid #cecece;
                padding: 10px;
                text-align: left;
            }
            .table{          
                border-collapse: collapse;
                border-spacing: 0 !important;    
                width: 100%;
            }
            .tituloRecebimento{
                font-weight: bolder;
                background-color: #d9edf7;
            }
            .rodapeRecebimento{
                font-weight: bolder;
                background-color: #eeeeee;
            }
            .rodapeRelatorio{
                font-weight: bolder;
                background-color: #337ab7;
                color: #ffffff;
            }
            .row-nopadding{
                padding: 0px !important;
                margin: 0px !important;
            }
            .pago{
                font-weight: bolder;
                color: #3c763d;
            }
            .nao-pago{
                font-weight: bolder;
                color: #d9534f;
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
                <h2 class="titulo">Recebimentos do Paciente</h2>
            </div>            
            <hr>
            <div>
                <h2 class="text-success">Identificação do Paciente</h2>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Nome:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $Paciente['nome']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Sobrenome:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $Paciente['sobrenome']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Data de nascimento:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $Paciente['data_nascimento']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Sexo:</label> 
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $Paciente['sexo'] == 'M' ? 'Masculino' : 'Feminino'; ?></span>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Estado Civil:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $Paciente['estado_civil'] == 'Solteiro' ? 'Solteiro' : 'Casado'; ?></span>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">Data Início de Atendimento:</label>
                        </div>
                        <div class="col-sm-10">
                            <span><?php echo $Paciente['data_inicio_atendimento']; ?></span>
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
                            <label class="control-label">Endereço:</label>
                        </div>
                        <div class="col-sm-10">
                            <span>
                                <?php
                                if (isset($Endereco['logradouro']) && trim($Endereco['logradouro']) != '') {
                                    echo $Endereco['logradouro'] . ', ' . $Endereco['numero'] . ', ' . $Endereco['bairro'] . ' - ' . $Endereco['cidade'] . '\\' . $Endereco['uf'];
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="clear: left;">
                <br>
            </div>

            <hr>

            <div>
                <h2 class="text-success">Recebimentos</h2>
                <table class="table">
                    <tbody>
                        <?php
                        $total = count($Recebimentos);
                        $valorGeralRecebimentos = 0;
                        $valorGeralRecebido = 0;
                        if ($total > 0):
                            for ($i = 0; $i < $total; $i++) :
                                $totalRecebido = 0;
                                ?>
                                <tr class="tituloRecebimento">
                                    <td>Recebimento: <?php echo $Recebimentos[$i]["r"]["descricao"]; ?></td>
                                </tr>   
                                <tr>
                                    <td class="row-nopadding">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Parcela</th>
                                                    <th>Vencimento</th>
                                                    <th>Valor R$</th>
                                                    <th>Pago</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $idrecebimento_ = $Recebimentos[$i]["r"]["idrecebimento"];
                                                for ($i = $i; $i < $total; $i++) :
                                                    if ($idrecebimento_ == $Recebimentos[$i]["r"]["idrecebimento"]) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $Recebimentos[$i]["f"]["parcela"] . ' / ' . $Recebimentos[$i]["f"]["total_parcela"] ?></td>                                                            
                                                            <td>
                                                                <?php
                                                                $dataVencimento = isset($Recebimentos[$i]["f"]["data_vencimento"]) ? date('d/m/Y', strtotime($Recebimentos[$i]["f"]["data_vencimento"])) : "";
                                                                echo $dataVencimento;
                                                                ?>
                                                            </td>
                                                            <td><?php echo number_format($Recebimentos[$i]["f"]["valor"], 2, ",", ".") ?></td>
                                                            <td class="<?php echo $Recebimentos[$i]["f"]["pago"] == 1 ? 'pago' : 'nao-pago' ?>"><?php echo $Recebimentos[$i]["f"]["pago"] == 1 ? 'SIM' : 'NÃO' ?></td>
                                                        </tr>
                                                        <?php
                                                        if ($Recebimentos[$i]["f"]["pago"] == 1) {
                                                            $totalRecebido += $Recebimentos[$i]["f"]["valor"];
                                                        }
                                                    } else {
                                                        break;
                                                    }
                                                endfor;
                                                $i--;
                                                ?> 
                                            </tbody>
                                        </table>                                         
                                    </td>
                                </tr>  
                                <tr class="rodapeRecebimento">
                                    <td>Valor Total: R$ <?php echo number_format($Recebimentos[$i]["r"]["valor"], 2, ",", "."); ?> | Valor  Recebido: R$ <?php echo number_format($totalRecebido, 2, ",", "."); ?></td>
                                </tr>                                   
                                <?php
                                $valorGeralRecebimentos += $Recebimentos[$i]["r"]["valor"];
                                $valorGeralRecebido += $totalRecebido;
                            endfor;
                        endif;
                        if ($total == 0):
                            ?>
                            <tr>
                                <td colspan="1">Não há registros</td>                                    
                            </tr> 
                            <?php
                        endif;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="rodapeRelatorio">
                            <th>Valor Geral Recebimentos: R$ <?php echo number_format($valorGeralRecebimentos, 2, ",", "."); ?> | Valor Geral Recebido: R$ <?php echo number_format($valorGeralRecebido, 2, ",", "."); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </body>
</html>
