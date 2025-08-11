<?php



/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */



App::uses('AppModel', 'Model');

App::uses('Despesa', 'Model');

App::uses('TipoFinanceiro', 'Model');

App::uses('CaixaMovimentacao', 'Model');

App::uses('Paciente', 'Model');



/**

 * CakePHP Financeiro

 * @author BersaN & StarK

 */

class Financeiro extends AppModel {



    public $useTable = "financeiro";

    public $primaryKey = "idfinanceiro";



    public function GerarFinanceiroDespesa($id_despesa) {

        $despesa = new Despesa();

        $despesaArray = $despesa->retornarPorId($id_despesa);

        $contador = 0;



        $valor_parcela = ($despesaArray['valor'] / $despesaArray['quantidade_parcela']);



        $valor_total_parcelas = 0;

        $data_competencia = DateTime::createFromFormat('Y-m-d H:i:s', $despesaArray['data_competencia']);

        $financeiro_tipo = new TipoFinanceiro();

        $tipo_financeiro = $financeiro_tipo->listarPorId($despesaArray['id_financeiro_tipo']);





        while ($contador < $despesaArray['quantidade_parcela']) {

            $financeiro = new Financeiro();

            $financeiro->create();

            $dados['id_despesa'] = $despesaArray['iddespesa'];

            $dados['valor'] = $valor_parcela;

            $valor_total_parcelas += $valor_parcela;

            $dados['data_vencimento'] = $data_competencia->format('Y-m-d H:i:s');

            $dados['id_financeiro_tipo'] = $despesaArray['id_financeiro_tipo'];

            $dados['total_parcela'] = $despesaArray['quantidade_parcela'];

            $dados['parcela'] = $contador + 1;

            $dados['pago'] = 0;

            $dados['convertido'] = 0;

            $dados['id_caixa_loja'] = $despesaArray['id_caixa_loja'];

            $financeiro->save($dados);

            //CRIAR INSERÇÃO NO CAIXA DE ACORDO COM O TIPO DO FINANCEIRO.



            if (($tipo_financeiro['gera_caixa'] == 1) && ((int) $tipo_financeiro['pagamento'] == 1)) {

                $this->PagarParcela($despesaArray['iddespesa'], $despesaArray['id_usuario'], $despesaArray['id_caixa_loja'], $financeiro->id, $tipo_financeiro['idfinanceirotipo'], $despesaArray['observacao']);

            } elseif (($tipo_financeiro['gera_caixa'] == 1) && ($tipo_financeiro['tipo_calculo'] != 'promissoria') ) {

                $caixa_movimentacao = new CaixaMovimentacao();

                $caixa_movimentacao->GeraSaidaCaixa($despesaArray['iddespesa'], $despesaArray['id_caixa_loja'], $despesaArray['id_usuario'], $financeiro->id, $tipo_financeiro['idfinanceirotipo'], $despesaArray['observacao'], 0);

            } elseif ((int) $tipo_financeiro['pagamento'] == 1) {

                $this->query("UPDATE financeiro SET pago = 1, data_pagamento = NOW(), id_usuario = " . $despesaArray['id_usuario'] . " WHERE idfinanceiro = " . $financeiro->id);

            }



            $contador++;

            $data_competencia->modify('+1 month');

        }

        $financeiro->CalculaDiferenca($id_despesa, 0);

    }



    public function CalculaDiferenca($id_despesa, $id_recebimento) {

        if ($id_despesa != 0) {

            return $this->query("UPDATE financeiro as f INNER JOIN ( select sum(financeiro.valor),max(financeiro.idfinanceiro) as idfinanceiro, despesa.valor, iddespesa, despesa.valor - sum(financeiro.valor) as Diferenca from financeiro inner join despesa ON(despesa.iddespesa = financeiro.id_despesa) where id_despesa = $id_despesa group by iddespesa, despesa.valor ) AS Financeiro_aux ON(Financeiro_aux.iddespesa = f.id_despesa AND Financeiro_aux.idfinanceiro = f.idfinanceiro) SET f.valor = f.valor + Financeiro_aux.Diferenca WHERE f.id_despesa = $id_despesa");

        } else {

            return $this->query("UPDATE financeiro as f INNER JOIN ( select sum(financeiro.valor),max(financeiro.idfinanceiro) as idfinanceiro, recebimento.valor, idrecebimento, recebimento.valor - sum(financeiro.valor) as Diferenca from financeiro inner join recebimento ON(recebimento.idrecebimento = financeiro.id_recebimento) where id_recebimento = $id_recebimento group by idrecebimento, recebimento.valor ) AS Financeiro_aux ON(Financeiro_aux.idrecebimento = f.id_recebimento AND Financeiro_aux.idfinanceiro = f.idfinanceiro) SET f.valor = f.valor + Financeiro_aux.Diferenca WHERE f.id_recebimento = $id_recebimento");

        }

    }



    public function PagarParcela($id_despesa, $id_usuario, $id_caixa_loja, $id_financeiro, $id_tipo_financeiro, $descricao) {

        $this->compensar_cheque_financeiro($id_financeiro, $id_usuario);

        $financeiro_tipo = new TipoFinanceiro();

        $tipo_financeiro = $financeiro_tipo->listarPorId($id_tipo_financeiro);



        $financeiro = $this->query("SELECT t.* FROM tipo_financeiro t INNER JOIN financeiro f on(t.idfinanceirotipo = f.id_financeiro_tipo) where idfinanceiro = $id_financeiro");



        //update tipo_financeiro_pagamento

        $this->query("update financeiro set id_financeiro_tipo_pagamento = $id_tipo_financeiro where idfinanceiro = $id_financeiro; ");



        if ($financeiro[0]['t']['tipo_calculo'] != 'cheque') {

            if ($tipo_financeiro['gera_caixa'] == 1) {

                $caixa_movimentacao = new CaixaMovimentacao();

                $caixa_movimentacao->GeraSaidaCaixa($id_despesa, $id_caixa_loja, $id_usuario, $id_financeiro, $id_tipo_financeiro, $descricao, 0);

            }

        }

    }



    public function receberParcela($id_recebimento, $id_usuario, $id_caixa_loja, $id_financeiro, $id_tipo_financeiro, $descricao) {

        $this->compensar_cheque_financeiro($id_financeiro, $id_usuario);



        $financeiro_tipo = new TipoFinanceiro();

        $tipo_financeiro = $financeiro_tipo->listarPorId($id_tipo_financeiro);



        $financeiro = $this->query("SELECT t.* FROM tipo_financeiro t INNER JOIN financeiro f on(t.idfinanceirotipo = f.id_financeiro_tipo) where idfinanceiro = $id_financeiro");



        //update tipo_financeiro_pagamento

        $this->query("update financeiro set data_pagamento = now(), id_financeiro_tipo_pagamento = $id_tipo_financeiro where idfinanceiro = $id_financeiro; ");



        if ($financeiro[0]['t']['tipo_calculo'] != 'cheque') {

            if ($tipo_financeiro['gera_caixa'] == 1) {

                $caixa_movimentacao = new CaixaMovimentacao();

                $recebimento = $this->query("select p.* from recebimento r inner join paciente p on(p.idpaciente = r.id_paciente) where idrecebimento = $id_recebimento");



                if ($financeiro[0]['t']['tipo_calculo'] == 'boleto' || $financeiro[0]['t']['tipo_calculo'] == 'promissoria') {

                    //Gera saida do financeiro anterior

                    if ($financeiro[0]['t']['gera_caixa'] == 1) {

                        $caixa_movimentacao->GeraSaidaRecebimento($id_recebimento, $id_usuario, $id_caixa_loja, $id_financeiro, $financeiro[0]['t']['idfinanceirotipo'], 'Valor abatido do saldo remanescente');

                    }

                }

                //Entrada no caixa com Financeiro novo

                $caixa_movimentacao->GeraEntradaRecebimento($id_recebimento, $id_usuario, $id_caixa_loja, $id_financeiro, $id_tipo_financeiro, "Recebimento de Parcela: " . $recebimento[0]["p"]["nome"] . " " . $recebimento[0]["p"]["sobrenome"]);

            }

        }

    }



    public function gerarFinanceiroRecebimento($idrecebimento, $idcaixaloja, $idusuario, $dados) {

        $size = count($dados);



        $financeiro = new Financeiro();

        for ($i = 0; $i < $size; $i++) {

            $financeiro->create();

            $dados[$i]['id_recebimento'] = $idrecebimento;

            $dados[$i]['id_caixa_loja'] = $idcaixaloja;

            $dados[$i]['id_usuario'] = $idusuario;

            $dados[$i]['total_parcela'] = $size;



            $tipoFinanceiro = new TipoFinanceiro();

            $tipoDados = $tipoFinanceiro->retornarPorId($dados[$i]['id_financeiro_tipo']);

            if ($tipoDados['tipo_calculo'] == TipoFinanceiro::$CHEQUE) {

                $dados[$i]['cheque'] = 1;

                unset($dados[$i]['ndu']);

            } else if ($tipoDados['tipo_calculo'] == TipoFinanceiro::$CARTAO) {

                $dados[$i]['cheque'] = 0;

                unset($dados[$i]['num_cheque']);

                unset($dados[$i]['id_conta_bancaria']);

            } else if ($tipoDados['tipo_calculo'] == TipoFinanceiro::$DINHEIRO || $tipoDados['tipo_calculo'] == TipoFinanceiro::$BOLETO) {

                $dados[$i]['cheque'] = 0;

                unset($dados[$i]['num_cheque']);

                unset($dados[$i]['id_conta_bancaria']);

                unset($dados[$i]['ndu']);

            }

            $financeiro->save($dados[$i]);



            $recebimento = $this->query("select p.* from recebimento r inner join paciente p on(p.idpaciente = r.id_paciente) where idrecebimento = $idrecebimento");



            if (((int) $tipoDados['recebimento'] == 1) && ($tipoDados['gera_caixa'] == 1)) {

                $this->receberParcela($idrecebimento, $idusuario, $idcaixaloja, $financeiro->id, $tipoDados['idfinanceirotipo'], "Recebimento de Parcela: " . $recebimento[0]["p"]["nome"] . " " . $recebimento[0]["p"]["sobrenome"]);

            } elseif ($tipoDados['gera_caixa'] == 1) {

                $caixa_movimentacao = new CaixaMovimentacao();

                $caixa_movimentacao->GeraEntradaRecebimento($idrecebimento, $idusuario, $idcaixaloja, $financeiro->id, $tipoDados['idfinanceirotipo'], "Recebimento de Parcela: " . $recebimento[0]["p"]["nome"] . " " . $recebimento[0]["p"]["sobrenome"]);

            } elseif ($tipoDados['recebimento'] == 1) {

                $this->query("UPDATE financeiro SET pago = 1, data_pagamento = NOW(), id_usuario = $idusuario WHERE idfinanceiro = " . $financeiro->id);

            }

        }

        $financeiro->CalculaDiferenca(0, $idrecebimento);

    }



    public function relatorioContasPagar($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $tipoFinanceiro_, $planoContas_, $favorecido_, $contas_pagas_, $clinica_, $nome_despesa_) {

        $query = "";

        if ((int) $tipoFinanceiro_ != 0) {

            $query = $query . " and financeiro.id_financeiro_tipo = $tipoFinanceiro_ ";

        }

        if ((int) $planoContas_ != 0) {

            $query = $query . " and plano_contas.idplanocontas = $planoContas_ ";

        }

        if ((int) $favorecido_ != 0) {

            $query = $query . " and favorecido.idfavorecido =  $favorecido_ ";

        }

        if ((int) $contas_pagas_ == 0) {

            $query = $query . " and financeiro.pago =  0 ";

        }

        if ((int) $clinica_ != 0) {

            $query = $query . " and clinica.idclinica =  $clinica_ ";

        }



        $dados = $this->query("select despesa.iddespesa, despesa.descricao, plano_contas.descricao,

                centro_custos.descricao, financeiro.idfinanceiro, financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago,

                favorecido.nome, clinica.fantasia, (select min(fin.data_vencimento) from financeiro fin where fin.id_despesa = despesa.iddespesa) as min_data_vencimento

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                inner join centro_custos on(despesa.id_despesa_custo = centro_custos.iddespesacusto)

                inner join plano_contas on(centro_custos.id_plano_contas = plano_contas.idplanocontas)

                inner join favorecido on (despesa.id_favorecido = favorecido.idfavorecido) 

                inner join clinica on (despesa.id_clinica = clinica.idclinica)

                where despesa.descricao like '%$nome_despesa_%' and CAST(financeiro.created as DATE) between CAST('$dataCadastroDE' as DATE) and CAST('$dataCadastroATE' as DATE)

                and CAST(financeiro.data_vencimento as DATE) between CAST('$dataVencimentoDE' as DATE) and CAST('$dataVencimentoATE' as DATE) 

                and despesa.ativo = 1 $query ORDER BY despesa.iddespesa, financeiro.data_vencimento ");



        return $dados;

    }



    public function relatorioContasReceber($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $tipoFinanceiro_, $planoContas_, $paciente_, $cheque_, $clinica_, $nome_recebimento_) {

        $query = "";

        if ((int) $tipoFinanceiro_ != 0) {

            $query = $query . " and financeiro.id_financeiro_tipo = $tipoFinanceiro_ ";

        }

        if ((int) $planoContas_ != 0) {

            $query = $query . " and plano_contas.idplanocontas = $planoContas_ ";

        }

        if ((int) $paciente_ != 0) {

            $query = $query . " and paciente.idpaciente =  $paciente_ ";

        }

        if ((int) $cheque_ == 0) {

            $query = $query . " and financeiro.cheque =  0 ";

        }

        if ((int) $clinica_ != 0) {

            $query = $query . " and clinica.idclinica =  $clinica_ ";

        }



        $dados = $this->query("select recebimento.idrecebimento, recebimento.descricao, plano_contas.descricao,

                centro_custos.descricao, financeiro.idfinanceiro, financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago,

                paciente.nome, paciente.sobrenome, clinica.fantasia, (select min(fin.data_vencimento) from financeiro fin where fin.id_recebimento = recebimento.idrecebimento) as min_data_vencimento

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                inner join centro_custos on(recebimento.id_centro_custo = centro_custos.iddespesacusto)

                inner join plano_contas on(centro_custos.id_plano_contas = plano_contas.idplanocontas)

                inner join paciente on (recebimento.id_paciente = paciente.idpaciente) 

                inner join clinica on (recebimento.id_clinica = clinica.idclinica)

                where recebimento.descricao like '%$nome_recebimento_%' and CAST(financeiro.created as DATE) between CAST('$dataCadastroDE' as DATE) and CAST('$dataCadastroATE' as DATE)

                and CAST(financeiro.data_vencimento as DATE) between CAST('$dataVencimentoDE' as DATE) and CAST('$dataVencimentoATE' as DATE) 

                and recebimento.ativo = 1 $query");



        return $dados;

    }



    //despesa

    public function relatorioDiario($idClinica) {

        $dados = $this->query("select despesa.iddespesa, despesa.descricao, plano_contas.descricao,

                centro_custos.descricao, financeiro.idfinanceiro, financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago,

                favorecido.nome, clinica.fantasia

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                inner join centro_custos on(despesa.id_despesa_custo = centro_custos.iddespesacusto)

                inner join plano_contas on(centro_custos.id_plano_contas = plano_contas.idplanocontas)

                inner join favorecido on (despesa.id_favorecido = favorecido.idfavorecido) 

                inner join clinica on (despesa.id_clinica = clinica.idclinica)

                where CAST(financeiro.data_vencimento as DATE) = CAST(NOW() as DATE) 

                and financeiro.pago = 0

                and despesa.ativo = 1

                and despesa.id_clinica = {$idClinica}

                group by despesa.iddespesa; ");

        return $dados;

    }



    //despesa

    public function relatorioSemanal($idClinica) {

        $dataInicio = date("w") == 0 ? date('Y-m-d') : date('Y-m-d', (strtotime("last Sunday")));

        $dataFim = date("w") == 6 ? date('Y-m-d') : date('Y-m-d', (strtotime("next Saturday")));

        $dados = $this->query("select despesa.iddespesa, despesa.descricao, plano_contas.descricao,

                centro_custos.descricao, financeiro.idfinanceiro,financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago,

                favorecido.nome, clinica.fantasia

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                inner join centro_custos on(despesa.id_despesa_custo = centro_custos.iddespesacusto)

                inner join plano_contas on(centro_custos.id_plano_contas = plano_contas.idplanocontas)

                inner join favorecido on (despesa.id_favorecido = favorecido.idfavorecido) 

                inner join clinica on (despesa.id_clinica = clinica.idclinica)

                where CAST(financeiro.data_vencimento as DATE) between CAST('$dataInicio' as DATE) and CAST('$dataFim' as DATE) 

                and financeiro.pago = 0

                and despesa.ativo = 1

                and despesa.id_clinica = {$idClinica}

                group by despesa.iddespesa; ");

        return $dados;

    }



    //despesa

    public function relatorioMensal($idClinica) {

        $dataInicio = date("Y-m-01");

        $dataFim = date("Y-m-t");

        $dados = $this->query("select despesa.iddespesa, despesa.descricao, plano_contas.descricao,

                centro_custos.descricao, financeiro.idfinanceiro,financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago,

                favorecido.nome, clinica.fantasia

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                inner join centro_custos on(despesa.id_despesa_custo = centro_custos.iddespesacusto)

                inner join plano_contas on(centro_custos.id_plano_contas = plano_contas.idplanocontas)

                inner join favorecido on (despesa.id_favorecido = favorecido.idfavorecido) 

                inner join clinica on (despesa.id_clinica = clinica.idclinica)

                where CAST(financeiro.data_vencimento as DATE) between CAST('$dataInicio' as DATE) and CAST('$dataFim' as DATE) 

                and financeiro.pago = 0

                and despesa.ativo = 1

                and despesa.id_clinica = {$idClinica}

                group by despesa.iddespesa; ");

        return $dados;

    }



    //recebimento

    public function relatorioDiarioRecebimento($idClinica) {

        $dados = $this->query("select recebimento.idrecebimento, recebimento.descricao, plano_contas.descricao,

                centro_custos.descricao, financeiro.idfinanceiro,financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago,

                paciente.nome, paciente.sobrenome, clinica.fantasia

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                inner join centro_custos on(recebimento.id_centro_custo = centro_custos.iddespesacusto)

                inner join plano_contas on(centro_custos.id_plano_contas = plano_contas.idplanocontas)

                inner join paciente on (recebimento.id_paciente = paciente.idpaciente) 

                inner join clinica on (recebimento.id_clinica = clinica.idclinica)

                where CAST(financeiro.data_vencimento as DATE) = CAST(NOW() as DATE) 

                and financeiro.pago = 0

                and recebimento.ativo = 1

                and recebimento.id_clinica = {$idClinica}

                group by recebimento.idrecebimento; ");

        return $dados;

    }



    //recebimento

    public function relatorioSemanalRecebimento($idClinica) {

        $dataInicio = date("w") == 0 ? date('Y-m-d') : date('Y-m-d', (strtotime("last Sunday")));

        $dataFim = date("w") == 6 ? date('Y-m-d') : date('Y-m-d', (strtotime("next Saturday")));

        $dados = $this->query("select recebimento.idrecebimento, recebimento.descricao, plano_contas.descricao,

                centro_custos.descricao, financeiro.idfinanceiro,financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago,

                paciente.nome, paciente.sobrenome, clinica.fantasia

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                inner join centro_custos on(recebimento.id_centro_custo = centro_custos.iddespesacusto)

                inner join plano_contas on(centro_custos.id_plano_contas = plano_contas.idplanocontas)

                inner join paciente on (recebimento.id_paciente = paciente.idpaciente) 

                inner join clinica on (recebimento.id_clinica = clinica.idclinica)

                where CAST(financeiro.data_vencimento as DATE) between CAST('$dataInicio' as DATE) and CAST('$dataFim' as DATE) 

                and financeiro.pago = 0

                and recebimento.ativo = 1

                and recebimento.id_clinica = {$idClinica}

                group by recebimento.idrecebimento; ");

        return $dados;

    }



    //recebimento

    public function relatorioMensalRecebimento($idClinica) {

        $dataInicio = date("Y-m-01");

        $dataFim = date("Y-m-t");

        $dados = $this->query("select recebimento.idrecebimento, recebimento.descricao, plano_contas.descricao,

                centro_custos.descricao, financeiro.idfinanceiro,financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago,

                paciente.nome, paciente.sobrenome, clinica.fantasia

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                inner join centro_custos on(recebimento.id_centro_custo = centro_custos.iddespesacusto)

                inner join plano_contas on(centro_custos.id_plano_contas = plano_contas.idplanocontas)

                inner join paciente on (recebimento.id_paciente = paciente.idpaciente) 

                inner join clinica on (recebimento.id_clinica = clinica.idclinica)

                where CAST(financeiro.data_vencimento as DATE) between CAST('$dataInicio' as DATE) and CAST('$dataFim' as DATE) 

                and financeiro.pago = 0

                and recebimento.ativo = 1

                and recebimento.id_clinica = {$idClinica}

                group by recebimento.idrecebimento; ");

        return $dados;

    }



    //lista por despesa

    public function listarGestaoJQuery($iddespesa) {

        return $this->query("select despesa.iddespesa, financeiro.idfinanceiro, financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                where despesa.iddespesa = $iddespesa and despesa.ativo = 1 order by financeiro.data_vencimento, financeiro.parcela asc");

    }



    //lista por recebimento

    public function listarGestaoRecebimentoJQuery($idrecebimento) {

        return $this->query("select recebimento.idrecebimento, financeiro.idfinanceiro, financeiro.parcela,

                financeiro.total_parcela, financeiro.valor, financeiro.data_vencimento, financeiro.pago

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                where recebimento.idrecebimento = $idrecebimento and recebimento.ativo = 1 order by financeiro.data_vencimento, financeiro.parcela asc");

    }



    public function totalFinanceiroPorDespesa($iddespesa) {

        return $this->query("select sum(financeiro.valor) as total

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                where despesa.iddespesa = $iddespesa and despesa.ativo = 1 ");

    }



    public function totalFinanceiroPagoPorDespesa($iddespesa) {

        return $this->query("select sum(financeiro.valor) as total

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                where despesa.iddespesa = $iddespesa and financeiro.pago = 1 and despesa.ativo = 1");

    }



    public function totalFinanceiroNaoPagoPorDespesa($iddespesa) {

        return $this->query("select sum(financeiro.valor) as total

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                where despesa.iddespesa = $iddespesa and financeiro.pago = 0 and despesa.ativo = 1");

    }



    public function totalFinanceiroPorRecebimento($idrecebimento) {

        return $this->query("select sum(financeiro.valor) as total

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                where recebimento.idrecebimento = $idrecebimento and recebimento.ativo = 1 ");

    }



    public function totalFinanceiroPagoPorRecebimento($idrecebimento) {

        return $this->query("select sum(financeiro.valor) as total

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                where recebimento.idrecebimento = $idrecebimento and financeiro.pago = 1 and recebimento.ativo = 1");

    }



    public function totalFinanceiroNaoPagoPorRecebimento($idrecebimento) {

        return $this->query("select sum(financeiro.valor) as total

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                where recebimento.idrecebimento = $idrecebimento and financeiro.pago = 0 and recebimento.ativo = 1");

    }



    //detalhes para despesa

    public function retornarDetalhes($idfinanceiro) {

        return $this->query("select financeiro.*, usuario.nome, usuario.sobrenome,

                caixa_loja.nome_caixa, tipo_financeiro.tipo

                from financeiro

                inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

                inner join tipo_financeiro on(tipo_financeiro.idfinanceirotipo = IFNULL(financeiro.id_financeiro_tipo_pagamento,financeiro.id_financeiro_tipo))

                left join usuario on(financeiro.id_usuario = usuario.idusuario)

                inner join caixa_loja on(financeiro.id_caixa_loja = caixa_loja.idcaixaloja)

                where financeiro.idfinanceiro = $idfinanceiro and despesa.ativo = 1");

    }



    //detalhes para recebimento

    public function retornarDetalhesRecebimento($idfinanceiro) {

        return $this->query("select financeiro.*, usuario.nome, usuario.sobrenome,

                caixa_loja.nome_caixa, tipo_financeiro.tipo

                from financeiro

                inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

                inner join tipo_financeiro on(tipo_financeiro.idfinanceirotipo = IFNULL(financeiro.id_financeiro_tipo_pagamento,financeiro.id_financeiro_tipo))

                left join usuario on(financeiro.id_usuario = usuario.idusuario)

                inner join caixa_loja on(financeiro.id_caixa_loja = caixa_loja.idcaixaloja)

                where financeiro.idfinanceiro = $idfinanceiro and recebimento.ativo = 1");

    }



    //alterar parcela por despesa

    public function alterarParcela($iddespesa, $idfinanceiro, $valor, $vencimento, $motivo, $iduser) {

        $dataSource = $this->getDataSource();

        $dataSource->begin();

        try {

            $this->query("insert into despesa_log (id_despesa, id_usuario, motivo, valor_anterior, valor_atual, created) values ($iddespesa, $iduser, '$motivo', (select valor from financeiro where idfinanceiro = $idfinanceiro), $valor, NOW());

                    update financeiro set valor = $valor, data_vencimento = CAST('$vencimento' as DATE) where idfinanceiro = $idfinanceiro; 

                    update despesa set valor = (select sum(valor) from financeiro where id_despesa = $iddespesa) where iddespesa = $iddespesa;");

            $dataSource->commit();

            return true;

        } catch (Exception $e) {

            $dataSource->rollback();

            return false;

        }

    }



    public function alterarParcelaRecebimento($idrecebimento, $idfinanceiro, $valor, $vencimento, $motivo, $iduser) {

        $dataSource = $this->getDataSource();

        $dataSource->begin();

        try {

            $this->query("insert into recebimento_log (id_recebimento, id_usuario, motivo, valor_anterior, valor_atual, created) values ($idrecebimento, $iduser, '$motivo', (select valor from financeiro where idfinanceiro = $idfinanceiro), $valor, NOW());

                    update financeiro set valor = $valor, data_vencimento = CAST('$vencimento' as DATE) where idfinanceiro = $idfinanceiro; 

                    update recebimento set valor = (select sum(valor) from financeiro where id_recebimento = $idrecebimento) where idrecebimento = $idrecebimento;");

            $dataSource->commit();

            return true;

        } catch (Exception $e) {

            $dataSource->rollback();

            return false;

        }

    }



    //excluir parcela por despesa

    public function excluirParcela($iddespesa, $idfinanceiro, $motivo, $iduser) {

        $dataSource = $this->getDataSource();

        $dataSource->begin();

        try {

            $this->query("insert into despesa_log (id_despesa, id_usuario, motivo, valor_anterior, valor_atual, created) values ($iddespesa, $iduser, '$motivo', (select valor from financeiro where idfinanceiro = $idfinanceiro), (select valor from financeiro where idfinanceiro = $idfinanceiro), NOW());

                    delete from financeiro where idfinanceiro = $idfinanceiro; 

                    update despesa set valor = (select sum(valor) from financeiro where id_despesa = $iddespesa),

                    quantidade_parcela = (select count(*) from financeiro where id_despesa = $iddespesa) where iddespesa = $iddespesa;

                    update financeiro set total_parcela = (select quantidade_parcela from despesa where iddespesa = $iddespesa) where id_despesa = $iddespesa; ");

            $dataSource->commit();

            return true;

        } catch (Exception $e) {

            $dataSource->rollback();

            return false;

        }

    }



    //excluir parcela por recebimento

    public function excluirParcelaRecebimento($idrecebimento, $idfinanceiro, $motivo, $iduser) {

        $dataSource = $this->getDataSource();

        $dataSource->begin();

        try {

            $this->query("insert into recebimento_log (id_recebimento, id_usuario, motivo, valor_anterior, valor_atual, created) values ($idrecebimento, $iduser, '$motivo', (select valor from financeiro where idfinanceiro = $idfinanceiro), (select valor from financeiro where idfinanceiro = $idfinanceiro), NOW());

                    delete from financeiro where idfinanceiro = $idfinanceiro; 

                    update recebimento set valor = (select sum(valor) from financeiro where id_recebimento = $idrecebimento),

                    quantidade_parcela = (select count(*) from financeiro where id_recebimento = $idrecebimento) where idrecebimento = $idrecebimento; 

                    update financeiro set total_parcela = (select quantidade_parcela from recebimento where idrecebimento = $idrecebimento) where id_recebimento = $idrecebimento; ");

            $dataSource->commit();

            return true;

        } catch (Exception $e) {

            $dataSource->rollback();

            return false;

        }

    }



    public function relatorio_cheques($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $planoContas_, $paciente_, $clinica_, $num_cheque_, $tipoFinanceiro_, $compensado_) {

        $query = "";

        if ((int) $planoContas_ != 0) {

            $query = $query . " and pc.idplanocontas = $planoContas_ ";

        }

        if ((int) $paciente_ != 0) {

            $query = $query . " and p.idpaciente =  $paciente_ ";

        }

        if ((int) $compensado_ != 1) {

            $query = $query . " and f.pago =  $compensado_ ";

        }

        if ((int) $clinica_ != 0) {

            $query = $query . " and c.idclinica =  $clinica_ ";

        }

        if ((int) $tipoFinanceiro_ != 0) {

            $query = $query . " and f.id_financeiro_tipo = $tipoFinanceiro_ ";

        }

        if ($num_cheque_ != "") {

            $query = $query . " and f.num_cheque =  '$num_cheque_' ";

        }



        $sql = "

        SELECT  c.fantasia, DATE_FORMAT(f.data_vencimento, '%m/%Y') as Data_ordem, (CASE WHEN f.pago = 1 THEN 'Compensado' ELSE 'Pendente' END) as Status, 

                IFNULL(fa.nome,CONCAT(p.nome,' ',p.sobrenome)) as Pessoa, IFNULL(d.iddespesa,0) as id_despesa,IFNULL(r.idrecebimento,0) as idrecebimento, 

                f.num_cheque,f.idfinanceiro, t.tipo, IFNULL(d.descricao,IFNULL(r.descricao,'')) as Descricao,

                CASE WHEN IFNULL(d.iddespesa,0) <> 0 THEN  (f.valor * -1) ELSE f.valor END AS valor,c.idclinica, pc.descricao,

                f.data_vencimento, IFNULL(f.data_pagamento,'') as Data_compensacao, (CASE WHEN f.pago = 1 THEN u.nome ELSE '' END) as Usuario,cx.nome_caixa

        FROM financeiro f 

        LEFT JOIN recebimento r ON(f.id_recebimento = r.idrecebimento) 

        LEFT JOIN despesa d ON(d.iddespesa = f.id_despesa)

        INNER JOIN tipo_financeiro t ON(t.idfinanceirotipo = f.id_financeiro_tipo)

        LEFT JOIN paciente p ON(r.id_paciente = p.idpaciente)

        LEFT JOIN favorecido fa ON(fa.idfavorecido = d.id_favorecido)

        LEFT JOIN usuario u ON(u.idusuario = f.id_usuario)

        INNER JOIN clinica c ON(c.idclinica = COALESCE(d.id_clinica,r.id_clinica,0))

        INNER JOIN centro_custos cc ON(cc.iddespesacusto = COALESCE(d.id_despesa_custo,r.id_centro_custo,0))

        INNER JOIN caixa_loja cx ON(cx.idcaixaloja = f.id_caixa_loja)

        INNER JOIN plano_contas pc ON(pc.idplanocontas = cc.id_plano_contas)

        WHERE t.tipo_calculo = 'cheque' AND CAST(f.created as DATE) BETWEEN CAST('$dataCadastroDE' AS DATE) and CAST('$dataCadastroATE' AS DATE)

            AND CAST(f.data_vencimento AS DATE) BETWEEN CAST('$dataVencimentoDE' AS DATE) AND CAST('$dataVencimentoATE' AS DATE)

            AND f.ativo = 1 $query 

        ORDER BY c.idclinica, f.data_vencimento ASC , Status";



        return $this->query("$sql");

    }



    public function compensar_cheque_financeiro($id_financeiro, $id_usuario) {

        $dataSource = $this->getDataSource();

        $dataSource->begin();

        try {

            //Marca como Compensado

            $this->query("UPDATE financeiro SET pago = 1, data_pagamento = NOW(), id_usuario = $id_usuario WHERE idfinanceiro = " . $id_financeiro);



            $financeiro = $this->query("SELECT t.* FROM tipo_financeiro t INNER JOIN financeiro f on(t.idfinanceirotipo = f.id_financeiro_tipo) where idfinanceiro = $id_financeiro");



            if ($financeiro[0]['t']['tipo_calculo'] == 'cheque') {

                //Retira o Cheque do caixa

                $this->query("INSERT INTO caixa_movimentacao

                        (      id_caixa_loja,  id_clinica,     id_usuario,  data_pagamento, created,    id_tipo_financeiro,   descricao,  valor, parcela,total_parcelas)

                        SELECT f.id_caixa_loja, c.idclinica,    $id_usuario ,     now(),      now() , f.id_financeiro_tipo , CONCAT('Compensação(Saída) do cheque:',' ', IFNULL(f.num_cheque,d.descricao)), 

                               CASE WHEN IFNULL(f.id_despesa,0) <> 0 THEN f.valor ELSE (f.valor * -1) END AS Valor,f.parcela, f.total_parcela

                        FROM financeiro f

                        LEFT JOIN recebimento r ON(f.id_recebimento = r.idrecebimento) 

                        LEFT JOIN despesa d ON(d.iddespesa = f.id_despesa)

                        INNER JOIN tipo_financeiro t ON(t.idfinanceirotipo = f.id_financeiro_tipo)

                        INNER JOIN clinica c ON(c.idclinica = COALESCE(d.id_clinica,r.id_clinica,0))

                        WHERE t.tipo_calculo = 'cheque' And idfinanceiro = $id_financeiro");



                //Da uma entrada em dinheiro no caixa

                $this->query("INSERT INTO caixa_movimentacao

                        (      id_caixa_loja,  id_clinica,     id_usuario,  data_pagamento, created,    id_tipo_financeiro,   descricao,  valor, parcela,total_parcelas)

                        SELECT f.id_caixa_loja, c.idclinica,   $id_usuario,  now(),      now() , (select t.idfinanceirotipo from tipo_financeiro t where UPPER(t.tipo) = 'DINHEIRO'), CONCAT('Compensação(Entrada) do cheque:',' ', IFNULL(f.num_cheque,'Despesa')),

                               CASE WHEN IFNULL(f.id_despesa,0) <> 0 THEN  (f.valor * -1) ELSE f.valor  END AS Valor,f.parcela, f.total_parcela

                        FROM financeiro f

                        LEFT JOIN recebimento r ON(f.id_recebimento = r.idrecebimento) 

                        LEFT JOIN despesa d ON(d.iddespesa = f.id_despesa)

                        INNER JOIN tipo_financeiro t ON(t.idfinanceirotipo = f.id_financeiro_tipo)

                        INNER JOIN clinica c ON(c.idclinica = COALESCE(d.id_clinica,r.id_clinica,0))

                        WHERE t.tipo_calculo = 'cheque' And idfinanceiro = $id_financeiro");

            }

            $dataSource->commit();

            return true;

        } catch (Exception $e) {

            $dataSource->rollback();

            return false;

        }

    }



    public function inserir_recebimento_profissional($idrecebimento, $idprofissional, $idclinica, $iduser) {

        $this->query("INSERT INTO profissional_recebimento VALUES($idrecebimento,$idprofissional,$idclinica,  NOW(), $iduser )");

    }



    public function retornarValoresRecebimentosEfetivadosAnual($idClinica) {

        $array = array();

        $dados = $this->query("select sum(case when month(financeiro.data_pagamento) = 1 then financeiro.valor else 0 end) as janeiro,

            sum(case when month(financeiro.data_pagamento) = 2 then financeiro.valor else 0 end) as fevereiro,

            sum(case when month(financeiro.data_pagamento) = 3 then financeiro.valor else 0 end) as marco,

            sum(case when month(financeiro.data_pagamento) = 4 then financeiro.valor else 0 end) as abril,

            sum(case when month(financeiro.data_pagamento) = 5 then financeiro.valor else 0 end) as maio,

            sum(case when month(financeiro.data_pagamento) = 6 then financeiro.valor else 0 end) as junho,

            sum(case when month(financeiro.data_pagamento) = 7 then financeiro.valor else 0 end) as julho,

            sum(case when month(financeiro.data_pagamento) = 8 then financeiro.valor else 0 end) as agosto,

            sum(case when month(financeiro.data_pagamento) = 9 then financeiro.valor else 0 end) as setembro,

            sum(case when month(financeiro.data_pagamento) = 10 then financeiro.valor else 0 end) as outubro,

            sum(case when month(financeiro.data_pagamento) = 11 then financeiro.valor else 0 end) as novembro,

            sum(case when month(financeiro.data_pagamento) = 12 then financeiro.valor else 0 end) as dezembro

            from financeiro

            inner join recebimento on(financeiro.id_recebimento = recebimento.idrecebimento)

            where year(financeiro.data_pagamento) = year(now()) 

            and financeiro.id_recebimento is not null 

            and recebimento.ativo = 1 

            and recebimento.id_clinica = {$idClinica}

            and financeiro.pago = 1");



        if (isset($dados[0][0]['janeiro'])) {

            $array[] = (double) $dados[0][0]['janeiro'];

            $array[] = (double) $dados[0][0]['fevereiro'];

            $array[] = (double) $dados[0][0]['marco'];

            $array[] = (double) $dados[0][0]['abril'];

            $array[] = (double) $dados[0][0]['maio'];

            $array[] = (double) $dados[0][0]['junho'];

            $array[] = (double) $dados[0][0]['julho'];

            $array[] = (double) $dados[0][0]['agosto'];

            $array[] = (double) $dados[0][0]['setembro'];

            $array[] = (double) $dados[0][0]['outubro'];

            $array[] = (double) $dados[0][0]['novembro'];

            $array[] = (double) $dados[0][0]['dezembro'];

        } else {

            for ($i = 0; $i < 12; $i++) {

                $array[] = 0;

            }

        }

        return $array;

    }



    public function retornarValoresDespesasEfetivadasAnual($idClinica) {

        $array = array();

        $dados = $this->query("select sum(case when month(financeiro.data_pagamento) = 1 then financeiro.valor else 0 end) as janeiro,

            sum(case when month(financeiro.data_pagamento) = 2 then financeiro.valor else 0 end) as fevereiro,

            sum(case when month(financeiro.data_pagamento) = 3 then financeiro.valor else 0 end) as marco,

            sum(case when month(financeiro.data_pagamento) = 4 then financeiro.valor else 0 end) as abril,

            sum(case when month(financeiro.data_pagamento) = 5 then financeiro.valor else 0 end) as maio,

            sum(case when month(financeiro.data_pagamento) = 6 then financeiro.valor else 0 end) as junho,

            sum(case when month(financeiro.data_pagamento) = 7 then financeiro.valor else 0 end) as julho,

            sum(case when month(financeiro.data_pagamento) = 8 then financeiro.valor else 0 end) as agosto,

            sum(case when month(financeiro.data_pagamento) = 9 then financeiro.valor else 0 end) as setembro,

            sum(case when month(financeiro.data_pagamento) = 10 then financeiro.valor else 0 end) as outubro,

            sum(case when month(financeiro.data_pagamento) = 11 then financeiro.valor else 0 end) as novembro,

            sum(case when month(financeiro.data_pagamento) = 12 then financeiro.valor else 0 end) as dezembro

            from financeiro

            inner join despesa on(financeiro.id_despesa = despesa.iddespesa)

            where year(financeiro.data_pagamento) = year(now()) 

            and financeiro.id_despesa is not null 

            and despesa.ativo = 1

            and despesa.id_clinica = {$idClinica}

            and financeiro.pago = 1");



        if (isset($dados[0][0]['janeiro'])) {

            $array[] = (double) $dados[0][0]['janeiro'];

            $array[] = (double) $dados[0][0]['fevereiro'];

            $array[] = (double) $dados[0][0]['marco'];

            $array[] = (double) $dados[0][0]['abril'];

            $array[] = (double) $dados[0][0]['maio'];

            $array[] = (double) $dados[0][0]['junho'];

            $array[] = (double) $dados[0][0]['julho'];

            $array[] = (double) $dados[0][0]['agosto'];

            $array[] = (double) $dados[0][0]['setembro'];

            $array[] = (double) $dados[0][0]['outubro'];

            $array[] = (double) $dados[0][0]['novembro'];

            $array[] = (double) $dados[0][0]['dezembro'];

        } else {

            for ($i = 0; $i < 12; $i++) {

                $array[] = 0;

            }

        }

        return $array;

    }



    public function retornarValoresContratosEfetivadosAnual($idClinica) {

        $array = array();

        $dados = $this->query("

        SELECT 

            SUM(case when month(f.data_vencimento) = 1 then 1 else 0 end) as janeiro,

            SUM(case when month(f.data_vencimento) = 2 then 1 else 0 end) as fevereiro,

            SUM(case when month(f.data_vencimento) = 3 then 1 else 0 end) as marco,

            SUM(case when month(f.data_vencimento) = 4 then 1 else 0 end) as abril,

            SUM(case when month(f.data_vencimento) = 5 then 1 else 0 end) as maio,

            SUM(case when month(f.data_vencimento) = 6 then 1 else 0 end) as junho,

            SUM(case when month(f.data_vencimento) = 7 then 1 else 0 end) as julho,

            SUM(case when month(f.data_vencimento) = 8 then 1 else 0 end) as agosto,

            SUM(case when month(f.data_vencimento) = 9 then 1 else 0 end) as setembro,

            SUM(case when month(f.data_vencimento) = 10 then 1 else 0 end) as outubro,

            SUM(case when month(f.data_vencimento) = 11 then 1 else 0 end) as novembro,

            SUM(case when month(f.data_vencimento) = 12 then 1 else 0 end) as dezembro

        FROM financeiro f

        INNER JOIN recebimento r ON(f.id_recebimento = r.idrecebimento)

        INNER JOIN paciente p ON(r.id_paciente = p.idpaciente)

        WHERE year(f.data_vencimento) = year(now()) 

        and r.id_clinica = {$idClinica}

        and f.parcela = 1");



        if (isset($dados[0][0]['janeiro'])) {

            $array[] = (double) $dados[0][0]['janeiro'];

            $array[] = (double) $dados[0][0]['fevereiro'];

            $array[] = (double) $dados[0][0]['marco'];

            $array[] = (double) $dados[0][0]['abril'];

            $array[] = (double) $dados[0][0]['maio'];

            $array[] = (double) $dados[0][0]['junho'];

            $array[] = (double) $dados[0][0]['julho'];

            $array[] = (double) $dados[0][0]['agosto'];

            $array[] = (double) $dados[0][0]['setembro'];

            $array[] = (double) $dados[0][0]['outubro'];

            $array[] = (double) $dados[0][0]['novembro'];

            $array[] = (double) $dados[0][0]['dezembro'];

        } else {

            for ($i = 0; $i < 12; $i++) {

                $array[] = 0;

            }

        }

        return $array;

    }



    public function retornarValoresContratosCanceladosAnual($idClinica) {

        $array = array();

        $dados = $this->query("

        SELECT 

            SUM(case when month(f.data_vencimento) = 1 then 1 else 0 end) as janeiro,

            SUM(case when month(f.data_vencimento) = 2 then 1 else 0 end) as fevereiro,

            SUM(case when month(f.data_vencimento) = 3 then 1 else 0 end) as marco,

            SUM(case when month(f.data_vencimento) = 4 then 1 else 0 end) as abril,

            SUM(case when month(f.data_vencimento) = 5 then 1 else 0 end) as maio,

            SUM(case when month(f.data_vencimento) = 6 then 1 else 0 end) as junho,

            SUM(case when month(f.data_vencimento) = 7 then 1 else 0 end) as julho,

            SUM(case when month(f.data_vencimento) = 8 then 1 else 0 end) as agosto,

            SUM(case when month(f.data_vencimento) = 9 then 1 else 0 end) as setembro,

            SUM(case when month(f.data_vencimento) = 10 then 1 else 0 end) as outubro,

            SUM(case when month(f.data_vencimento) = 11 then 1 else 0 end) as novembro,

            SUM(case when month(f.data_vencimento) = 12 then 1 else 0 end) as dezembro

        FROM financeiro f

        INNER JOIN recebimento r ON(f.id_recebimento = r.idrecebimento)

        INNER JOIN paciente p ON(r.id_paciente = p.idpaciente)

        WHERE year(f.data_vencimento) = year(now()) 

        and r.id_clinica = {$idClinica}

        and f.parcela = f.total_parcela;");        



        if (isset($dados[0][0]['janeiro'])) {

            $array[] = (double) $dados[0][0]['janeiro'];

            $array[] = (double) $dados[0][0]['fevereiro'];

            $array[] = (double) $dados[0][0]['marco'];

            $array[] = (double) $dados[0][0]['abril'];

            $array[] = (double) $dados[0][0]['maio'];

            $array[] = (double) $dados[0][0]['junho'];

            $array[] = (double) $dados[0][0]['julho'];

            $array[] = (double) $dados[0][0]['agosto'];

            $array[] = (double) $dados[0][0]['setembro'];

            $array[] = (double) $dados[0][0]['outubro'];

            $array[] = (double) $dados[0][0]['novembro'];

            $array[] = (double) $dados[0][0]['dezembro'];

        } else {

            for ($i = 0; $i < 12; $i++) {

                $array[] = 0;

            }

        }

        return $array;

    }



    public function relatorio_comissao($dataRelatorio, $id_profissional) {

        $sql = "

            SELECT X.nome as Profissional, X.Cargo as Cargo, X.paciente as Paciente,X.idprofissional, X.idpaciente, X.idrecebimento,
                                     X.descricao, X.descricao_evento,X.Porcentagem,X.Categoria_aula, COUNT(X.idevento) as Total_eventos, SUM(X.Compareceu) as Efetivados, X.Valor_sessao,
                                     (SUM(X.Compareceu) * X.Valor_sessao) * (X.Porcentagem/100) as Comissao, (SUM(X.Compareceu) * X.Valor_sessao) as Total_geral		 
            FROM (
                                    SELECT CONCAT(profissional.nome, ' ', profissional.sobrenome) as nome,evento.descricao as descricao_evento, cargo.descricao as Cargo,categoria_aula.descricao as Categoria_aula, CONCAT(paciente.nome, ' ', paciente.sobrenome) AS paciente,
                                                             recebimento.descricao, evento.idevento, evento.id_evento_status, profissional.idprofissional, paciente.idpaciente, recebimento.idrecebimento,
                                                             CASE WHEN evento.id_evento_status = 5 OR evento.id_evento_status = 2 THEN 1 ELSE 0 END AS Compareceu,
                                                             CASE WHEN IFNULL(plano_sessao.valor_sessao,0) = 0 THEN recebimento.valor / recebimento.quantidade_sessoes ELSE plano_sessao.valor_sessao END as Valor_sessao, 
                                                             profissional_categoria_aula.porcentagem as Porcentagem
                                    FROM evento
                                    INNER JOIN agenda ON(evento.id_agenda = agenda.idagenda)
                                    INNER JOIN profissional ON(profissional.idprofissional = agenda.id_profissional)
                                    INNER JOIN paciente ON(paciente.idpaciente = evento.id_paciente)
                                    INNER JOIN recebimento ON(evento.id_recebimento = recebimento.idrecebimento)
                                    LEFT JOIN plano_sessao ON(recebimento.id_plano_sessao = plano_sessao.idplanosessao) 
                                    INNER JOIN profissional_categoria_aula ON(profissional.idprofissional = profissional_categoria_aula.id_profissional AND recebimento.id_categoria_aula = profissional_categoria_aula.id_categoria_aula)
                                    INNER JOIN categoria_aula ON(recebimento.id_categoria_aula = categoria_aula.idcategoriaaula)
                                    INNER JOIN cargo ON(cargo.idcargo = profissional.id_cargo)
                                    INNER JOIN evento_disponibilidade ON(evento_disponibilidade.id_paciente = paciente.idpaciente and evento_disponibilidade.id_recebimento = recebimento.idrecebimento)
                                    INNER JOIN clinica ON(clinica.idclinica = recebimento.id_clinica)
                                    WHERE profissional.idprofissional = $id_profissional and DATE_FORMAT(evento.data_inicio, '%m-%Y') = '$dataRelatorio' and agenda.ativo = 1 and evento.gerou_reposicao = 0
                                    GROUP BY profissional.idprofissional, paciente.idpaciente, recebimento.idrecebimento, evento.idevento
            ) AS X
            GROUP BY X.idprofissional, X.idpaciente, X.idrecebimento"; 
            
            return $this->query("$sql");


        /* $sql = "SELECT X.nome as Profissional, X.Cargo as Cargo, X.paciente as Paciente,X.idprofissional, X.idpaciente, X.idrecebimento,

        X.descricao, X.descricao_evento,X.Porcentagem,X.Categoria_aula, COUNT(X.idevento) as Total_eventos, SUM(X.Compareceu) as Efetivados, 
        
        (X.ValorMensalidade / COUNT(X.idevento)) AS Valor_sessao,
        
        (SUM(X.Compareceu) * (X.ValorMensalidade / COUNT(X.idevento))) * (X.Porcentagem/100) as Comissao, (SUM(X.Compareceu) * (X.ValorMensalidade / COUNT(X.idevento))) as Total_geral		 
        
        FROM (
        
              SELECT CONCAT(profissional.nome, ' ', profissional.sobrenome) as nome,evento.descricao as descricao_evento, cargo.descricao as Cargo,categoria_aula.descricao as Categoria_aula, CONCAT(paciente.nome, ' ', paciente.sobrenome) AS paciente,
        
                    recebimento.descricao, evento.idevento, evento.id_evento_status, profissional.idprofissional, paciente.idpaciente, recebimento.idrecebimento,
                    
                    CASE WHEN evento.id_evento_status = 5 OR evento.id_evento_status = 2 THEN 1 ELSE 0 END AS Compareceu,
                    
                    CASE WHEN IFNULL(plano_sessao.valor_sessao,0) = 0 
                        
                        THEN recebimento.valor / recebimento.quantidade_sessoes
                        
                        ELSE plano_sessao.valor_sessao END as Valor_sessao, 
                    
                    profissional_categoria_aula.porcentagem as Porcentagem,
                    
                    recebimento.valor / recebimento.quantidade_parcela AS ValorMensalidade,
                    
                    recebimento.quantidade_sessoes / recebimento.quantidade_parcela AS QuantidadeSessoesMensal
        
              FROM evento
        
              INNER JOIN agenda ON(evento.id_agenda = agenda.idagenda)
        
              INNER JOIN profissional ON(profissional.idprofissional = agenda.id_profissional)
        
              INNER JOIN paciente ON(paciente.idpaciente = evento.id_paciente)
        
              INNER JOIN recebimento ON(evento.id_recebimento = recebimento.idrecebimento)
        
              LEFT JOIN plano_sessao ON(recebimento.id_plano_sessao = plano_sessao.idplanosessao)
        
              INNER JOIN profissional_recebimento ON(profissional_recebimento.id_profissional = profissional.idprofissional and profissional_recebimento.id_recebimento = recebimento.idrecebimento)
        
              INNER JOIN profissional_categoria_aula ON(profissional.idprofissional = profissional_categoria_aula.id_profissional AND recebimento.id_categoria_aula = profissional_categoria_aula.id_categoria_aula)
        
              INNER JOIN categoria_aula ON(recebimento.id_categoria_aula = categoria_aula.idcategoriaaula)
        
              INNER JOIN cargo ON(cargo.idcargo = profissional.id_cargo)
        
              INNER JOIN evento_disponibilidade ON(evento_disponibilidade.id_paciente = paciente.idpaciente and evento_disponibilidade.id_recebimento = recebimento.idrecebimento)
        
              INNER JOIN clinica ON(clinica.idclinica = recebimento.id_clinica)
        
              WHERE profissional.idprofissional = $id_profissional and DATE_FORMAT(evento.data_inicio, '%m-%Y') = '$dataRelatorio' and agenda.ativo = 1
        
              GROUP BY profissional.idprofissional, paciente.idpaciente, recebimento.idrecebimento, evento.idevento
        
        ) AS X
        
        GROUP BY X.idprofissional, X.idpaciente";

        return $this->query("$sql"); */

    }



    public function retornarClientesPorModalidade($idClinica) {

        $array = [];
        $dados = $this->query("
            SELECT 
                COUNT(X.idPaciente) AS QtdePacientes, 
                MAX(c.descricao) AS Categoria 
            FROM (
                SELECT max(concat(p.nome, ' ',  p.sobrenome)) AS Paciente, p.idPaciente, r.id_categoria_aula, max(r.valor) AS valor
                FROM evento AS e 
                INNER JOIN paciente AS p ON(e.id_paciente = p.idpaciente)
                INNER JOIN recebimento AS r ON(r.idrecebimento = e.id_recebimento)
                WHERE
                r.id_clinica = {$idClinica}
                AND p.ativo = 1 AND cast(e.data_inicio AS DATE) >= cast(NOW()AS DATE)
                GROUP BY p.idpaciente, r.id_categoria_aula
            ) AS X 
            INNER JOIN categoria_aula AS c ON(c.idcategoriaaula = X.id_categoria_aula)
            GROUP BY c.idcategoriaaula
        ");


        if(!isset($dados)) {
            return [];
        }

        $count = count($dados);

        for($i = 0; $i < $count; $i++) {
            $array[] = $dados[$i][0];
        }

        return $array; 

    }

}

