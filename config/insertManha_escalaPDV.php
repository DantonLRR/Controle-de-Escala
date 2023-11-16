<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";

$tabela = "ESCALA_PDV_MANHA";


$matricula = $_GET['DadosMatricula'];
$nome = $_GET['nomeSelecionado'];
$entrada = $_GET['DadosEntrada'];
$saida = $_GET['DadosSaida'];
$intervalo = $_GET['DadosIntervalo'];
$usuarioLogado = $_GET['usuarioLogado'];
$dataPesquisa = $_GET['dataPesquisa'];
$numPDV = $_GET['numPDV'];
$loja = $_GET['loja'];
$verificacaoDeDados = new Verifica();
$InsertDeDados = new Insert();
$updateDeDados = new Update();
$status = 'A';

$verifica = $verificacaoDeDados->verificaExistenciaNumPDV($oracle, $tabela, $dataPesquisa, $numPDV, $loja);

if ($retorno == "Já existem dados.") {
    // echo "Já existem dados. 01";
    
     $atualizaDados = $updateDeDados->updateDeFuncionariosNoPDV($oracle,$tabela, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV,$loja);
} else if ($retorno == "Não existem dados.") {
    // echo "Não existem dados. 02 INSERT";
     $insereDadosFuncManha = $InsertDeDados->insertTabelaFuncManha($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV,$loja,$status);
}
