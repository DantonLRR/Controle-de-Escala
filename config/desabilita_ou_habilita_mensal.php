<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";




$mesAtual = $_POST['mesAtual'];
$mesPesquisado = $_POST['mesPesquisa'];
$usuarioLogado = $_POST['usuarioLogado'];
$loja = $_POST['loja'];
$Departamento = $_POST['Departamento'];
$status = $_POST['alteraStatusEscala'];

$InsertDeDados = new Insert();

$verifica = new Verifica();

$update = new Update();


$verificaSeJaExistemDados = $verifica->verificaSeAEscalaMensalEstaFinalizada($oracle, $mesPesquisado, $loja, $Departamento);
// print_r($verificaSeJaExistemDados);
if ($retorno === "NÃO FINALIZADA.") {
    $updateDadosNaTabela = $update->bloqueiaEscalaMensal($oracle,  $status,  $usuarioLogado, $mesPesquisado, $loja);
} else if ($retorno === "JÁ FINALIZADA.") {
    $updateDadosNaTabela2 = $update->liberaEscalaMensal($oracle, $status,  $usuarioLogado, $mesPesquisado, $loja, $Departamento);
    // print_r($updateDadosNaTabela2);

}
