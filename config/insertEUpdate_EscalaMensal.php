<?php
include "../../base/conexao_martdb.php";
include "php/CRUD_geral.php";

$tabela = "WEB_ESCALA_MENSAL";


$dia = trim($_GET['numeroDiaDaSemana']);

$arrayDia = explode(',', $dia);
// print_r($arrayDia);
$opcaoSelect = trim($_GET['opcaoSelecionada']);
$nome = trim($_GET['funcionario']);
$mesAtual = trim($_GET['mesAtual']);
$mesPesquisado = trim($_GET['mesPesquisa']);
$usuarioLogado = trim($_GET['usuarioLogado']);
$matricula = trim($_GET['matriculaFunc']);
$loja = trim($_GET['loja']);
$departamentoFunc = trim($_GET['departamentoFunc']);
$horarioEntradaFunc = trim($_GET['horarioEntradaFunc']);
$horarioSaidaFunc = trim($_GET['horarioSaidaFunc']);
$horarioIntervaloFunc = trim($_GET['horarioIntervaloFunc']);
$cargoFunc = trim($_GET['cargoFunc']);
$DATAINICIOFERIASPROGRAMADAS = isset($_GET['dataInicialFerias']) ? $_GET['dataInicialFerias'] : '';
$DATAFIMFERIASPROGRAMADAS = isset($_GET['dataFinalFerias']) ? $_GET['dataFinalFerias'] : '';
$remocaoDeFeriasProgramadas = isset($_GET['remocaoDeFeriasProgramadas']) ? $_GET['remocaoDeFeriasProgramadas'] : '';
$InsertDeDados = new Insert();

$verifica = new Verifica();

$update = new Update();

foreach ($arrayDia as $diaSelecionado) :

    $verificaSeJaExistemDados = $verifica->verificaCadastroNaEscalaMensaL2($oracle, $matricula, $mesPesquisado, $loja, $departamentoFunc);

    if ($retorno == '1') {
        echo $retorno . " update <br>";

        if ($remocaoDeFeriasProgramadas == 'sim'){
            $updateDeDados = $update->updateDeFuncionariosNaEscalaMensalFerias($oracle, $usuarioLogado, $mesPesquisado, $nome, $diaSelecionado, $opcaoSelect, $matricula, $loja, $DATAINICIOFERIASPROGRAMADAS, $DATAFIMFERIASPROGRAMADAS);
        }else{
            $updateDeDados = $update->updateDeFuncionariosNaEscalaMensal($oracle, $usuarioLogado, $mesPesquisado, $nome, $diaSelecionado, $opcaoSelect, $matricula, $loja);
        }        
    } else if ($retorno == '0') {
        echo $retorno . " insert <br>";
        $insertDadosNaTabela = $InsertDeDados->insertEscalaMensal($oracle, $tabela, $diaSelecionado,  $matricula, $nome, $loja,  $cargoFunc, $mesPesquisado, $horarioEntradaFunc, $horarioSaidaFunc,  $horarioIntervaloFunc, $opcaoSelect, $usuarioLogado, $departamentoFunc, $DATAINICIOFERIASPROGRAMADAS, $DATAFIMFERIASPROGRAMADAS);
    }

endforeach;
