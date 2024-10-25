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
                doravante simplesmente designado(a) CONTRATANTE e, de outro lado, a empresa Conexão Corpo e Mente Centro de Reabilitação Ltda.,
                CNPJ nº 23.690.614/0001-48, CREFITO 3: 9051/SP, localizada na Rua Monteiro Lobato, nº 550 – Vila Rodrigues, Assis/SP;
                Telefone 18 99606-1729; email: conexaocorpoemente@gmail.com, e responsável técnica Bruna Mastroldi dos Santos,
                CREFITO 3: 157.227F, doravante designada simplesmente CONTRATADO, têm, entre si, justo e contratado celebrar o presente contrato,
                que se regerá pelas cláusulas seguintes:
            </p>

            <p>
                <strong>I + DO OBJETO</strong><br>
                <strong>1/</strong> O CONTRATADO prestará ao CONTRATANTE os serviços consistentes em:
                ( ) FISIOTERAPIA, ( ) PILATES ( ) TFI, no plano de _____ ( ) meses ou ( ) sessões, com a utilização de equipamentos próprios e adequados para a prática da modalidade contratada, bem como análise e diagnóstico cinesiofuncional, orientação e acompanhamento na realização do tratamento/treinamento pretendido bem como na execução de mecanoterapia / cinesioterapia/ reabilitação/treinamento físico.
            </p>


            <p>
                <strong>II + DAS INFORMAÇÕES DO CONTRATANTE</strong><br>
                <strong>2/</strong> O CONTRANTE declara e atesta que todas as informações prestadas na anamnese, principalmente com relação a sua avaliação médica, são verídicas e que foi devidamente instruído dos riscos dos excessos na prática das atividades propostas nas aulas.
                <strong>3/</strong> Compete ao CONTRATANTE informar ao instrutor, antes do início da aula, eventual lesão, limitação óssea, articular, muscular ou qualquer intervenção médica ou cirúrgica, invasiva ou não, ocorridos após o preenchimento da anamnese.
            </p>

            <p>
                <strong>III + DOS HORÁRIOS DOS ATENDIMENTOS</strong><br>
                <strong>4/</strong> Os agendamentos dessa modalidade estarão condicionados à disponibilidade de atendimento por parte do CONTRATADO, em horários pré-definidos e mediante agendamento prévio conforme disponibilidade. Por tratar-se de modalidade específica, prezando pela qualidade satisfatória da prática e da produtividade e segurança ao CONTRATANTE, o CONTRATADO se reserva o direito de limitar a quantidade de alunos por cada horário disponível. Os agendamentos fixos e renovação dessa contratação, encontra-se anexo.
            </p>
            <p>
                <strong>5/</strong> A As sessões agendadas devem ser desmarcadas, via WhatsApp, com no mínimo 2 (duas) horas de antecedência. Cancelamentos de agendamentos, quando motivados, serão repostos em sua totalidade; quando desmotivados, serão repostos apenas à razão de 25% (vinte e cinco por cento) do número de agendamentos cancelados. A reposição de aulas no horário regularmente adotado pelo aluno não é garantida, devendo ser efetuado mediante disponibilidade de vaga, previamente agendado e dentro do prazo de vigência desse, salvo exceção motivada.
            </p>

            <p>
                <strong>IV + DO PREÇO E DEMAIS MATÉRIAS FINANCEIRAS</strong>
                <br><strong>6/</strong> Os planos de pagamento adotam os seguintes intervalos de tempo mensal: UM, TRÊS, SEIS, NOVE E DOZE MESES. A forma de pagamento pode ocorrer por meio de quitação em dinheiro, cheque e cartão de crédito a própria contratada.
                <br><strong>7/</strong> Os valores dos planos de pagamento encontram-se divulgados na secretaria do CONTRATADO, sendo reajustados anualmente no mês de março, segundo índice correção correspondente ao IGPM-FGV ou outro que venha a substituí-lo.
                <br><strong>8/</strong> O não pagamento na data de vencimento acarretará multa de 2% (dois por cento), acrescida de juros moratórios diários de 0,033% (1% ao mês), sendo que eventual ingresso judicial para cobrança implicará ao CONTRATANTE a incidência de correção monetária, custas e despesas judiciais, somado aos honorários advocatícios na proporção de 20% (vinte por cento) do valor dado à causa.
                <br><strong>9/</strong> Eventual desistência e/ou cancelamento pelo CONTRATANTE do Plano contratado deverá ser feito por escrito, acarretando ao CONTRATANTE multa equivalente a 20% (vinte por cento) sobre o valor remanescente a pagar até o encerramento do contrato em vigor, acrescida das taxas bancárias ou de cartões de crédito. O valor do saldo remanescente será calculado segundo a tabela de preços mensais multiplicados pelo número de meses remanescentes.
                <br><strong>10/</strong> Durante o período de vigência do plano contratado, pode o CONTRATANTE requerer por escrito afastamento das atividades a seu livre critério. Durante o afastamento as parcelas do plano contratado serão regularmente pagas, sendo o intervalo acrescido à data de encerramento do plano em vigor. Os períodos de afastamento concedidos assim serão regulados: Plano 6 meses: 2 semanas; Plano 9 meses: 3 semanas; Plano 12 meses: 4 semanas.
                <br><strong>11/</strong> O CONTRATANTE assume o dever de comunicar suas ausências com ao menos 2 dias úteis de antecedência para gozar o período de afastamento previsto no item 10, sempre em múltiplos semanais (semanas completas). A simples ausência às aulas implica em continuidade no decurso do prazo contratado inicialmente.
                <br><strong>12/</strong> Não estão compreendidos nos períodos previstos no item anterior os afastamentos de ordem médica devidamente atestados por profissional habilitado e comunicados por escrito ao CONTRATADO em até 5 (cinco) dias após o afastamento do CONTRATANTE das aulas. Nos casos em que a patologia apresentada implicar em afastamento de longo prazo haverá cancelamento do plano contratado, sem cobrança de multa, mediante acordo entre as partes.

            </p>

            <div class="page-break">
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

                <div style="clear: both;"></div>
                <br>

                <p>
                    <strong>V + DA VIGÊNCIA E DA RENOVAÇÃO</strong>
                    <br><strong>13/</strong> A vigência do contrato obedecerá ao descrito nas demais informações que acompanham o presente documento, sendo que o presente instrumento particular é celebrado em caráter irrevogável e irretratável, possuindo caráter de liquidez, certeza e executoriedade, obrigando as partes e seus herdeiros ou sucessores a qualquer título.
                    <br><strong>14/</strong> A renovação do contrato se dará por meio de aposição de ciência no Anexo I do presente instrumento (Termo de Renovação), devendo ser manifestada pelo CONTRATANTE até o término do período previamente contratado. No silêncio do CONTRATANTE será o pactuado considerado como extinto.
                    <br><strong>15/</strong> Eventual tolerância para com o descumprimento de qualquer das cláusulas ou condições pactuadas neste instrumento não constituirá renúncia de direitos, novação, modificação, precedente invocável de qualquer espécie, nem retirará os efeitos da rescisão, constituindo mera tolerância da parte que não lhe deu causa.

                </p>

                <p>
                    <strong>V + DO ACEITE</strong><br>
                    <strong>16/</strong> O CONTRATADO elege como meios de comunicação oficial com o CONTRATANTE o e-mail: [conexaocorpoemente@gmail.com] ou WhattsApp [18 99606-1729], servindo este instrumento como canal para regular situações cotidianas e eventos/acontecimentos que possam vir a afetar a normal execução desse Contrato. As comunicações entre as partes mencionada nos itens 9, 10, 11 e 12 serão efetuadas por escrito pelo endereço de email: [conexaocorpoemente@gmail.com] ou whattsApp [18 99606-1729].
                    <strong>17/</strong> O CONTRATADO reserva-se o direito de rescindir este contrato no caso de reincidência do CONTRATANTE em atitudes e comportamentos que causem danos às instalações e equipamentos da academia, bem como representem discriminação, ofensa ou falta de urbanidade com os demais clientes e/ou treinadores e funcionários da academia, ou ainda, que prejudiquem a adequada prestação dos serviços contratados aos demais clientes.
                    <strong>18/</strong> Na primeira ocorrência do disposto no item 17, será apresentada ao CONTRATANTE faltoso advertência escrita.
                    <strong>19/</strong> O CONTRATANTE declara que previamente leu e entendeu todas as cláusulas e condições presentes a este instrumento quanto às hipóteses de desistência e/ou cancelamento do plano contratado e rescisão.
                    <strong>20/</strong> As partes elegem o foro da Comarca de Assis/SP para dirimir as questões e litígios dele decorrentes.
                    <strong>21/</strong> O presente ajuste obriga as partes contratantes, bem como seus herdeiros e sucessores a qualquer título e, como sinal de concordância com os termos deste instrumento firmam o mesmo em 2 (duas) vias de igual teor.

                </p>

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
            </div>



            <div style="clear: both;"></div>

            <div class="page-break">
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

                <div style="clear: both;"></div>

                <br>

                <p style="text-align: center;">
                    <strong>ANEXO I – TERMO DE RENOVAÇÃO/COMPLEMENTAÇÃO DE MODALIDADES / AULAS</strong>
                </p>
                <br>

                <div style="border: 1px solid #303030; padding: 4px; width: 100%;">
                    <span>
                        Cliente: <?= $recebimento['paciente']['nome'] . ' ' . $recebimento['paciente']['sobrenome'] ?>
                    </span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td>Nº Contrato</td>
                            <td>Modalidade</td>
                            <td>Plano Contratado</td>
                            <td>Vigência do Plano</td>
                            <td>Agendamentos</td>
                            <td>Ciência</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < 30; $i++) {
                            echo '
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="page-break">
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

                <div style="clear: both;"></div>

                <br>

                <p style="text-align: center;">
                    <strong>ANEXO II – TERMO DE AUTORIZAÇÃO DE USO DE IMAGEM</strong>
                </p>
                <br>
                <p>
                    Eu, <?= $recebimento['paciente']['nome'] . ' ' . $recebimento['paciente']['sobrenome'] ?>,
                    portador da Cédula de Identidade nº <?= $recebimento['paciente']['rg'] ?>,
                    inscrito no CPF sob nº <?= $recebimento['paciente']['cpf'] ?>,
                    residente à <?= $endereco['logradouro'] . ', ' . $endereco['numero'] . ', ' . $endereco['bairro'] . ', ' . $endereco['cidade'] . '/' . $endereco['uf'] ?>,
                    ( ) AUTORIZO ( ) NÃO AUTORIZO, o uso de minha imagem (ou do menor
                    ______________________________________________________________________________________
                    sob minha responsabilidade) em fotos ou filme, sem finalidade comercial,
                    para ser utilizada a título de divulgação das atividades desenvolvidas junto à Conexão Corpo e Mente Centro de Reabilitação Ltda. A presente autorização é concedida a título gratuito,
                    abrangendo o uso da imagem acima mencionada em todo território nacional e no exterior, em todas as suas modalidades e, em destaque, das seguintes formas: (I) redes sociais/website; (II) cartazes; (III) divulgação em geral.
                    Por esta ser a expressão da minha vontade declaro que autorizo o uso acima descrito sem que nada haja a ser reclamado a título de direitos conexos à minha imagem ou a qualquer outro.
                </p>

                <br>
                <p style="text-align: right;">
                    Assis – SP, .......... de ..................................... de <?= date('Y') ?>.
                </p>

                <br><br><br><br>
                <p style="text-align: center;">
                    ...............................................................
                    <br>[ASSINATURA DO ALUNO]
                </p>
            </div>
        </div>
    </div>
</body>

</html>