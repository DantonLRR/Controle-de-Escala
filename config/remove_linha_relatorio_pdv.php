<?php
include "../../base/conexao_martdb.php";
include "php/CRUD_geral.php";

$tabela = "Web_Montagem_Escala_Diaria_PDV";

$tabelaPdvManha = "ESCALA_PDV_MANHA";
$tabelaPdvtarde = "ESCALA_PDV_TARDE";
$tabela_relatorio = "Web_Montagem_Escala_Diaria_PDV";
$verificacaoDeDados = new Verifica();
$updateDeDados = new Update();

$dataPesquisa = $_GET['dataPesquisa'];
$numPDV = $_GET['numPDV'];
$loja = $_GET['loja'];
$opcaoDeExclusao = $_GET['opcaoDeExclusao'];
// echo $opcaoDeExclusao;
if ($opcaoDeExclusao === "ExcluirManha") {
    //manha
    $verifica = $verificacaoDeDados->verificaExistenciaNumPDV($oracle, $tabelaPdvManha, $dataPesquisa, $numPDV, $loja);
    if ($retorno == "Já existem dados.") {
        $RemocaoDeLinhaDoRelatorio2 = $updateDeDados->updateRemocaoEscalaPDV($oracle, $tabelaPdvManha, $numPDV, $dataPesquisa, $loja);
    }
} else if ($opcaoDeExclusao == "ExcluirTarde") {
    //tarde
    $verifica = $verificacaoDeDados->verificaExistenciaNumPDV($oracle, $tabelaPdvtarde, $dataPesquisa, $numPDV, $loja);
    if ($retorno == "Já existem dados.") {
        $RemocaoDeLinhaDoRelatorio3 = $updateDeDados->updateRemocaoEscalaPDV($oracle, $tabelaPdvtarde, $numPDV, $dataPesquisa, $loja);
    }
}
