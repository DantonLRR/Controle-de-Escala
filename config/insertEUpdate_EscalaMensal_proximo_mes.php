<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";

$tabela = "WEB_ESCALA_MENSAL";


$dia= $_GET['numeroDiaDaSemana'];

$arrayDia = explode(',', $dia);
// print_r($arrayDia);
$opcaoSelect = $_GET['opcaoSelecionada'];
$nome = $_GET['funcionario'];
$mesAtual = $_GET['mesAtual'];
$mesPesquisado = $_GET['mesPesquisa'];
$usuarioLogado = $_GET['usuarioLogado'];
$matricula = $_GET['matriculaFunc'];
$loja = $_GET['loja'];

$horarioEntradaFunc = $_GET['horarioEntradaFunc'];
$horarioSaidaFunc = $_GET['horarioSaidaFunc'];
$horarioIntervaloFunc = $_GET['horarioIntervaloFunc'];
$cargoFunc = $_GET['cargoFunc'];
$inclusaoDoMesAnterior = $_GET['inclusaoDoMesAnterior'];
// echo $inclusaoDoMesAnterior;
$InsertDeDados = new Insert();

$verifica = new Verifica();

$update = new Update();

foreach ($arrayDia as $diaSelecionado) :

$verificaSeJaExistemDados = $verifica ->verificaCadastroNaEscalaMensaL2($oracle,$matricula,$mesPesquisado,$loja );

if ($retorno == '1'){
// echo $retorno." update <br>";

    $updateDeDados = $update -> updateDeFuncionariosNaEscalaMensalProximoMes($oracle,$usuarioLogado, $mesPesquisado, $nome,$diaSelecionado,$opcaoSelect,$inclusaoDoMesAnterior ,$matricula,$loja);

} else if($retorno == '0') {
    // echo $retorno." insert <br>";

    $insertDadosNaTabela = $InsertDeDados->insertEscalaMensalProximoMes($oracle, $tabela,$diaSelecionado,  $matricula,$nome,$loja,  $cargoFunc,$mesPesquisado, $horarioEntradaFunc,$horarioSaidaFunc,  $horarioIntervaloFunc, $opcaoSelect, $usuarioLogado,$inclusaoDoMesAnterior);


}

endforeach;

