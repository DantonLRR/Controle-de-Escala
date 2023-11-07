<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";

$tabela = "Web_Montagem_Escala_Diaria_PDV";

$verificacaoDeDados = new Verifica();
$updateDeDados = new Update();

$dataPesquisa = $_GET['dataPesquisa'];
$numPDV = $_GET['numPDV'];
$loja = $_GET['loja'];


$verifica = $verificacaoDeDados->verificaExistenciaNumPDV($oracle, $tabela, $dataPesquisa, $numPDV, $loja);
echo $verifica;
echo $retorno;

if ($retorno == "Já existem dados.") {
    $RemocaoDeLinhaDoRelatorio = $updateDeDados -> updateRemocaoEscalaPDV($oracle, $numPDV, $dataPesquisa, $loja);
    echo $RemocaoDeLinhaDoRelatorio;
}
