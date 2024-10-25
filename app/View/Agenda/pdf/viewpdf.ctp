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
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
        }

        h1 {
            font-size: 36px;
        }

        h2 {
            font-size: 30px;
        }

        h4 {
            font-size: 18px;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
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

        .text-success {
            color: #3c763d;
        }

        .text-primary {
            color: #337ab7;
        }

        .text-danger {
            color: #a94442;
        }

        .text-warning {
            color: #8a6d3b;
        }

        .text-roxo {
            color: #9e00a6 !important;
        }

        .text-center {
            text-align: center !important;
        }

        .control-label {
            font-weight: bolder;
        }

        .col-sm-2,
        .col-sm-10 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
            float: left !important;
        }

        .col-sm-10 {
            width: 83.33333333%;
        }

        .col-sm-2 {
            width: 16.66666667%;
        }

        .row {
            clear: left;
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .container {
            padding-right: 15px;
            padding-left: 15px;
        }

        .bg-titulo {
            padding-left: 15px;
            background-color: #3c8dbc;
            border-radius: 4px;
        }

        .bg-titulo .titulo {
            color: #ffffff;
        }

        .bg-recebimentos {
            padding: 8px;
            background-color: #eeeeee;
            border-radius: 4px;
        }

        .table thead tr th,
        .table tbody tr td,
        .table tfoot tr th {
            border: 1px solid #cecece;
            padding: 10px;
        }

        .table {
            border-collapse: collapse;
            border-spacing: 0 !important;
            width: 100%;
            font-size: 10pt;
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
            <h2 class="titulo text-center">Relatório de Paciente</h2>
        </div>

        <div>
            <?php
            if ($recebimentos != '') {
                echo " <h4>Recebimentos filtrados: {$recebimentos}</h4>";
            }
            if (isset($dataRecebimentos) && count($dataRecebimentos) > 0) {
                echo "<div class='bg-recebimentos'>";
                foreach ($dataRecebimentos as $row) {
                    echo "<p>Recebimento #" . $row['recebimento']['idrecebimento'] . " - Sessões: " . $row['recebimento']['quantidade_sessoes'] . "</p>";
                }
                echo "</div>";
            }
            ?>
        </div>

        <?php $size = count($Eventos); ?>
        <h2><?php echo $size > 0 ? $Eventos[0]['p']['nome'] . " " . $Eventos[0]['p']['sobrenome'] : ""; ?></h2>
        <hr />
        <h4 class="text-primary">Eventos do paciente</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>Profissional</th>
                    <th>Evento</th>
                    <th class="text-center">Status</th>
                    <th>Observação</th>
                    <th class="text-center">Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($size > 0) :
                    for ($i = 0; $i < $size; $i++) :
                ?>
                        <tr>
                            <td><?php echo $Eventos[$i]['pro']['nome'] . " " . $Eventos[$i]['pro']['sobrenome']; ?></td>
                            <td><?php echo $Eventos[$i]['e']['descricao']; ?></td>
                            <td class="text-center"><?php echo strtoupper($Eventos[$i]['es']['descricao']); ?></td>
                            <td><?php echo $Eventos[$i]['e']['observacao']; ?></td>
                            <td class="text-center">
                                <?php
                                echo date("d/m/Y H:i", strtotime($Eventos[$i]['e']['data_inicio'])) . "<br/> até <br/>";
                                echo date("d/m/Y H:i", strtotime($Eventos[$i]['e']['data_fim']));
                                ?>
                            </td>
                        </tr>
                    <?php
                    endfor;
                endif;
                if ($size == 0) :
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Não há registros</td>
                    </tr>
                <?php
                endif;
                ?>

            </tbody>
            <tfoot>
                <tr>
                    <th>Profissional</th>
                    <th>Evento</th>
                    <th class="text-center">Status</th>
                    <th>Observação</th>
                    <th class="text-center">Data</th>
                </tr>
            </tfoot>
        </table>
        <hr />
        <div>

        </div>
    </div>
</body>

</html>