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
            font-size: 14px;
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

        p,
        span {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
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

        .page-break {
            page-break-before: always;
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

        .col-md-6 {
            position: relative;
            min-height: 1px;
            float: left !important;
            width: 50%;
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
        }

        .bg-titulo .titulo {
            color: #ffffff;
        }

        table {
            border: none;
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 50px;
        }

        table thead tr td,
        table tbody tr td {
            border: 1px solid #303030;
            min-height: 40px;
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
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $_SERVER["DOCUMENT_ROOT"] . '/sistema/app/webroot/img/logo_corpo_mente.png'; ?>" height="75" />
            </div>
            <div class="col-md-6">
                <h4>
                    CONTRATO DE PRESTAÇÃO DE SERVIÇOS
                    <br />Nº <?= $recebimento['idrecebimento'] ?> / Ano <?= date('Y') ?>
                </h4>
            </div>
        </div>
        <div style="clear: both; margin-top: 50px;">
            <p>
                Por este instrumento particular e na melhor forma de direito, de um lado,
                <?= $recebimento['paciente']['nome'] . ' ' . $recebimento['paciente']['sobrenome'] ?>,
                CPF nº <?= $recebimento['paciente']['cpf'] ?>,
                endereço <?= $endereco['logradouro'] . ', ' . $endereco['numero'] . ', ' . $endereco['bairro'] . ', ' . $endereco['cidade'] . '/' . $endereco['uf'] ?>,
                telefone <?= $recebimento['paciente']['telefone_celular'] ?>,
                email <?= $recebimento['paciente']['email'] ?>
                doravante simplesmente designado(a) <strong>CONTRATANTE</strong> e, de outro lado, a empresa 
                <strong>Conexão Corpo e Mente Centro de Reabilitação Ltda.</strong>, 
                CNPJ nº 23.690.614/0001-48, doravante designada simplesmente 
                <strong>CONTRATADO</strong>, têm, entre si, justo e contratado celebrar o presente contrato, que se regerá pelas cláusulas seguintes:
            </p>

            <div class="section-title">I. DO OBJETO E ACORDO DE SERVIÇO</div>
            <ol>
                <li><strong>Serviço contratado:</strong> (   ) PILATES, (   ) TFI, (   ) FISIOT./ REABILITAÇÃO MUSCULOESQUELÉTICA, (   ) FISIOT. UROGINECOLÓGICA, assim, cada modalidade com seu responsável técnico conforme enquadramento nos órgãos de classe.</li>
                <li><strong>Plano contratado:</strong> (   ) sessões avulsas (conforme agendamento prévio), (   ) meses ou (   ) sessões. Os agendamentos dessa modalidade estarão condicionados à disponibilidade de atendimento por parte do CONTRATADO, em horários pré-definidos e mediante agendamento prévio conforme disponibilidade e aviso por escrito. Por tratar-se de modalidade específica e pessoal, sendo intransferível, prezando pela qualidade satisfatória da prática e da produtividade e segurança ao CONTRATANTE, o CONTRATADO se reserva o direito de limitar a quantidade de alunos por cada horário disponível; assim, inicialmente acerta-se entre as partes os dias e horários descritos: <strong>_______________________________________________________________  
                _____________________________________________________</strong>, no período vigente de <strong>_____________________ a _____________________</strong>.</li> 
                <table style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>Forma de Pagamento</th>
                            <th>Valor</th>
                            <th>Data de Vencimento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parcelas as $parcela): ?>
                            <tr>
                                <td><?= h($parcela['x']['tipo']) ?></td>
                                <td><?= h(number_format($parcela['x']['valor_parcela'], 2, ',', '.')) ?></td>
                                <td><?= h(date('d/m/Y', strtotime($parcela['x']['data_vencimento']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table> 
            </ol>
            
            <div class="section-title">II. DO PREÇO E DEMAIS MATÉRIAS FINANCEIRAS</div>
            <ol>
                <li>Os planos de pagamento encontram-se divulgados na secretaria do CONTRATADO, sendo reajustados anualmente no mês de março, segundo índice de correção do IGPM ou outro que venha a substituí-lo.</li>
                <li>A quitação dos planos acontece conforme descrito em “3”, e cabe ao contratante solicitar sua via de comprovante de pagamento a fim de comprovação da quitação em espécie; em casos de cartão ou transferência via pix, vale-se da via do cliente e nas situações de nota promissória, segue item “6”.</li>
                <li>Planos assinalados como NP, o CONTRATANTE compromete-se a pagar o valor de <strong>R$________________ (____________________________________________)</strong>, mensalmente via Pix ou Espécie, sendo acordado em _____meses, no período de <strong>___________________________________________</strong>, com vencimento no dia <strong>______</strong> de cada mês.</li>
                <li>O não pagamento na data de vencimento acarretará perda do desconto na mensalidade em atraso e ainda juros moratórios diários de 0,033% (1% ao mês), sendo que eventual ingresso judicial para cobrança implicará ao CONTRATANTE a incidência de correção monetária, custas e despesas judiciais, somado aos honorários advocatícios na proporção de 20% (vinte por cento) do valor dado à causa.</li>
                <li>Eventual desistência e/ou cancelamento pelo CONTRATANTE do Plano contratado deverá ser feito por escrito, acarretando ao CONTRATANTE multa equivalente a 20% (vinte por cento) sobre o valor remanescente a pagar até o encerramento do contrato em vigor, acrescida das taxas bancárias ou de cartões de crédito. O valor do saldo remanescente será calculado segundo a tabela de preços mensais multiplicados pelo número de meses remanescentes.</li>
                <li>Faltas e ausências não serão descontadas na mensalidade, bem como feriados e recessos não cabem reposição de aulas.</li>
                <li>Durante o período de vigência do plano contratado, pode o Contratante requerer por escrito afastamento das atividades por motivo justificado de saúde (atestado médico), assim, a Contratada compromete-se a reposição das aulas em horários disponíveis e extras ao horário previamente contratado, podendo ainda haver prolongamento do período vigente do plano e conforme atestado seguinte forma: Plano 6 meses: 2 semanas; Plano 9 meses: 3 semanas; Plano 12 meses: 4 semanas.</li>
                <li>Afastamentos por motivo de férias devem ser comunicados com até 2 dias de antecedência podem ser repostas, dentro do plano vigente, em horários disponíveis e extras ao horário previamente contratado; Afastamentos por motivo de trabalho devem ser comunicados com até 3 horas de antecedência podem ser repostas dentro do mês em horários disponíveis e extras ao horário previamente contratado.</li>
                <li>Nos casos de patologia ou tratamento que impliquem em afastamento de longo prazo, haverá cancelamento do plano contratado, sem cobrança de multa, mediante acordo entre as partes.</li>
            </ol>
            <div class="section-title">III. DAS INFORMAÇÕES DO CONTRATANTE</div>
            <ol>
                <li>O CONTRANTE declara e atesta que todas as informações prestadas na anamnese, principalmente com relação a sua avaliação médica, são verídicas e que foi devidamente instruído dos riscos dos excessos na prática das atividades propostas nas aulas.</li>
                <li>Compete ao CONTRATANTE informar ao instrutor, antes do início da aula, eventual lesão, limitação óssea, articular, muscular ou qualquer intervenção médica ou cirúrgica, invasiva ou não, ocorridos após o preenchimento da anamnese.</li>
            </ol>  

            <div style="clear: both;"></div>
            <br>

            <div class="section-title">IV. DA VIGÊNCIA E DA RENOVAÇÃO</div>
            <ol>
                <li>A vigência do contrato obedecerá ao descrito nas demais informações que acompanham o presente documento, sendo que o presente instrumento particular é celebrado em caráter irrevogável e irretratável, possuindo caráter de liquidez, certeza e executoriedade, obrigando as partes e seus herdeiros ou sucessores a qualquer título.</li>
                <li>A renovação do contrato deverá ser manifestada pelo CONTRATANTE até o término do período previamente contratado, a fim de preservar os dias e horários pré-estabelecidos. No silêncio do CONTRATANTE será o pactuado considerado como extinto.</li>
                <li>Eventual tolerância para com o descumprimento de qualquer das cláusulas ou condições pactuadas neste instrumento não constituirá renúncia de direitos, novação, modificação, precedente invocável de qualquer espécie, nem retirará os efeitos da rescisão, constituindo mera tolerância da parte que não lhe deu causa.</li>
            </ol>

            <div class="section-title">V. DO ACEITE E TERMO DE CONSENTIMENTO LIVRE E ESCLARECIDO</div>
            <ol>
                <li>O CONTRATADO elege como meios de comunicação oficial com o CONTRATANTE o e-mail: <strong>[conexaocorpoemente@gmail.com]</strong> ou WhatsApp <strong>[18 99606-1729]</strong>, servindo este instrumento como canal para regular situações cotidianas e eventos/acontecimentos que possam vir a afetar a normal execução desse Contrato.</li>
                <li>O CONTRATADO reserva-se o direito de rescindir este contrato no caso de reincidência do CONTRATANTE em atitudes e comportamentos que causem danos às instalações e equipamentos da academia, bem como representem discriminação, ofensa ou falta de urbanidade com os demais clientes e/ou treinadores e funcionários da academia, ou ainda, que prejudiquem a adequada prestação dos serviços contratados aos demais clientes.</li>
                <li>Na primeira ocorrência do disposto no item 17, será apresentada ao CONTRATANTE faltoso advertência escrita.</li>
                <li>Quanto aos procedimentos de Fisioterapia Musculoesquelética ou Uroginecológica declaro ciência que a profissional responsável supriu as dúvidas e fez orientações quanto ao tratamento (conforme prontuário), e que há procedimentos que podem causar dor, irritação, desconfortos ou pequenos sangramentos, assim, ao longo das sessões são realizados reavaliações e manejos de conduta conforme necessário e estou ciente que são procedimentos de baixa periculosidade.</li>
                <li>Quanto ao uso de fotos / filmagens e depoimentos (   ) AUTORIZO  (   ) NÃO AUTORIZO, sendo sem finalidade comercial, para ser utilizada a título de divulgação das atividades desenvolvidas junto à Conexão Corpo e Mente Centro de Reabilitação Ltda. A presente autorização é concedida a título gratuito, abrangendo o uso da imagem acima mencionada em todo território nacional e no exterior, em todas as suas modalidades e, em destaque, das seguintes formas: (I) redes sociais/website; (II) cartazes; (III) e-mail marketing para clientes; (IV) folder, etc.</li>
            </ol>

            <div class="section-title">VI. DO FORO</div>
            <ol>
                <li>Fica eleito o Foro da cidade de São João da Boa Vista, Estado de São Paulo, para resolver qualquer dúvida ou litígio oriundo do presente contrato.</li>
            </ol>

            <br>
            <p style="text-align: right;">
                Assis – SP, .......... de ..................................... de <?= date('Y') ?>.
            </p>

            <br><br>
            <p style="text-align: center;">
                .........................................................................................
                <br><?= $recebimento['paciente']['nome'] . ' ' . $recebimento['paciente']['sobrenome'] ?>
            </p>
            <br><br>
            <p style="text-align: center;">
                ..........................................................................................
                <br>Conexão Corpo e Mente Centro de Reabilitação Ltda.
            </p>

            <br>
            <p>
                TESTEMUNHAS
            </p>
            <br><br>
            <div class="row">
                <div class="col-md-6">
                    <p>
                        ...............................................................
                        <br>Nome
                        <br>CPF nº
                    </p>
                </div>
                <div class="col-md-6">
                    <p>
                        ...............................................................
                        <br>Nome
                        <br>CPF nº
                    </p>
                </div>
            </div> 
            <div style="clear: both;"></div>  
        </div>
    </div>
</body>

</html>