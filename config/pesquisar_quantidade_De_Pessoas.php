<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";
$InformacaoFuncionarios = new Funcionarios();


$dataPesquisa = $_POST['dataPesquisa'];
$loja = $_POST['loja'];
// Separando o dia e o mês/ano
$partesData = explode('-', $dataPesquisa);
$diaDaPesquisaComAspas = ' "' . $partesData[2] . '"'; // Dia
$mesEAnoDaPesquisa = $partesData[0] . '-' . $partesData[1]; // Ano e Mês

$quantidadePorDiaDeFuncionarios = $InformacaoFuncionarios->funcionariosDisponiveisNoDia($oracle, $diaDaPesquisaComAspas, $mesEAnoDaPesquisa, $dataPesquisa, $loja);

if (empty($quantidadePorDiaDeFuncionarios)) {
    $quantidadePorDiaDeFuncionariosImpressao = "Nenhum funcionario escalado para hoje";
} else {
    $quantidadePorDiaDeFuncionariosImpressao = count($quantidadePorDiaDeFuncionarios);
}

?>
<p><?= $quantidadePorDiaDeFuncionariosImpressao ?></p>
