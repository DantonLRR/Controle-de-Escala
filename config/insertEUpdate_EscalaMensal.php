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
$programaFerias = isset($_GET['programaFerias']) ? $_GET['programaFerias'] : '';

$InsertDeDados = new Insert();

$verifica = new Verifica();

$update = new Update();
$verificaSeJaaescalaEstaFinalizada = $verifica->verificaSeAEscalaMensalEstaFinalizadaParaInsercaoDeDados($oracle,  $mesPesquisado, $loja,  $departamentoFunc);
// print_r($verificaSeJaaescalaEstaFinalizada);
if ($retorno === "NÃO FINALIZADA.") {
    $statusDaTabela = "NÃO FINALIZADA.";
} else if ($retorno === "JÁ FINALIZADA.") {
    $statusDaTabela = "JÁ FINALIZADA.";
}
echo $statusDaTabela ;
if ($statusDaTabela === "NÃO FINALIZADA.") {
    foreach ($arrayDia as $diaSelecionado) :

        $verificaSeJaExistemDados = $verifica->verificaCadastroNaEscalaMensaL2($oracle, $matricula, $mesPesquisado, $loja, $departamentoFunc);

        if ($retorno == '1') {
            // echo $retorno . " update <br>";

            if ($remocaoDeFeriasProgramadas == 'sim' || $programaFerias == 'sim') {
                // echo "caiu no if <br> ";
                $updateDeDados = $update->updateDeFuncionariosNaEscalaMensalFerias($oracle, $usuarioLogado, $mesPesquisado, $nome, $diaSelecionado, $opcaoSelect, $matricula, $loja, $DATAINICIOFERIASPROGRAMADAS, $DATAFIMFERIASPROGRAMADAS,$departamentoFunc);
            } else {
                // echo "caiu no else <br> ";
                $updateDeDados = $update->updateDeFuncionariosNaEscalaMensal($oracle, $usuarioLogado, $mesPesquisado, $nome, $diaSelecionado, $opcaoSelect, $matricula, $loja,$departamentoFunc);
            }
        } else if ($retorno == '0') {
            // echo $retorno . " insert <br>";
            $insertDadosNaTabela = $InsertDeDados->insertEscalaMensal($oracle, $tabela, $diaSelecionado,  $matricula, $nome, $loja,  $cargoFunc, $mesPesquisado, $horarioEntradaFunc, $horarioSaidaFunc,  $horarioIntervaloFunc, $opcaoSelect, $usuarioLogado, $departamentoFunc, $DATAINICIOFERIASPROGRAMADAS, $DATAFIMFERIASPROGRAMADAS);
        }

    endforeach;
}else{
    echo json_encode(false);
}
