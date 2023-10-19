<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";

$tabela = "ESCALA_PDV_TARDE";

$matricula = $_GET['DadosMatricula1'];
$nome = $_GET['nomeSelecionado2'];
$entrada = $_GET['DadosEntrada1'];
$saida = $_GET['DadosSaida1'];
$intervalo = $_GET['DadosIntervalo1'];
$usuarioLogado = $_GET['usuarioLogado'];
$dataPesquisa = $_GET['dataPesquisa'];
$numPDV = $_GET['numPDV'];
$loja = $_GET['loja'];

$verificacaoDeDados = new Verifica();
$InsertDeDados = new Insert();
$updateDeDados = new Update();


$verifica = $verificacaoDeDados->verificaExistenciaNumPDV($oracle, $tabela, $dataPesquisa, $numPDV,$loja);

if ($retorno === "Já existem dados.") {
    $atualizaDados = $updateDeDados->updateDeFuncionariosNoPDV($oracle,$tabela, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV,$loja);
} else {
    $insereDadosFuncTarde =$InsertDeDados-> insertTabelaFuncTarde($oracle, $matricula, $nome, $entrada, $saida, $intervalo,$usuarioLogado,$dataPesquisa,$numPDV,$loja);
}




