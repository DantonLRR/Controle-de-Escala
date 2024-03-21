<?php
include "../../base/conexao_martdb.php";
include "php/CRUD_geral.php";
include "../../base/conexao_TotvzOracle.php";
session_start();
$InformacaoFuncionarios = new Funcionarios();
$verificaSeAPessoaLogadaEEncarregada = $InformacaoFuncionarios->informacaoPessoaLogada($TotvsOracle, $_SESSION['cpf'], $_SESSION['LOJA']);
// print_r($verificaSeAPessoaLogadaEEncarregada);
foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['SETOR'];
endforeach;

$dataPesquisa = $_POST['dataPesquisa'];
$loja = $_POST['loja'];
// Separando o dia e o mês/ano
$partesData = explode('-', $dataPesquisa);
$diaDaPesquisaComAspas = ' "' . $partesData[2] . '"'; // Dia
$mesEAnoDaPesquisa = $partesData[0] . '-' . $partesData[1]; // Ano e Mês
$mesEAnoDaPesquisaFORMATADO = $partesData[1] . ' de ' . $partesData[0]; // Mês e Ano no formato MM-YYYY

$quantidadePorDiaDeFuncionarios = $InformacaoFuncionarios->funcionariosDisponiveisNoDia($oracle, $diaDaPesquisaComAspas, $mesEAnoDaPesquisa,$dadosDeQuemEstaLogadoSetor, $dataPesquisa, $loja);

if (empty($quantidadePorDiaDeFuncionarios)) {
    $quantidadePorDiaDeFuncionariosImpressao = "Escala do mes " . $mesEAnoDaPesquisaFORMATADO ." não finalizada";
} else {
    $quantidadePorDiaDeFuncionariosImpressao = count($quantidadePorDiaDeFuncionarios);
}

?>
<p><?= $quantidadePorDiaDeFuncionariosImpressao ?></p>
