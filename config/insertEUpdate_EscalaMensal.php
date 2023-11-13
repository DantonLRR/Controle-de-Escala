<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";

$tabela = "WEB_ESCALA_MENSAL";


$dia= '"'.$_GET['numeroDiaDaSemana'].'"';
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

$InsertDeDados = new Insert();

$verifica = new Verifica();

$update = new Update();


$verificaSeJaExistemDados = $verifica ->verificaCadastroNaEscalaMensal($oracle,$matricula,$mesPesquisado,$loja );

if ($retorno === "Já existem dados.") {


    $updateDeDados = $update -> updateDeFuncionariosNaEscalaMensal($oracle,$usuarioLogado, $mesPesquisado, $nome,$dia,$opcaoSelect, $matricula,$loja );

} else if($retorno === "Não existem dados.") {


    $insertDadosNaTabela = $InsertDeDados->insertEscalaMensal($oracle, $tabela,$dia,  $matricula,$nome,$loja,  $cargoFunc,$mesPesquisado, $horarioEntradaFunc,$horarioSaidaFunc,  $horarioIntervaloFunc, $opcaoSelect, $usuarioLogado);


}
