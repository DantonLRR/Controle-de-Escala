<?php
include "../../base/conexao_martdb.php";
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
$status = 'A';
$verificacaoDeDados = new Verifica();
$InsertDeDados = new Insert();
$updateDeDados = new Update();


$verifica = $verificacaoDeDados->verificaExistenciaNumPDV($oracle, $tabela, $dataPesquisa, $numPDV,$loja);

if ($retorno == "Já existem dados.") {
    // echo "Já existem dados. 01 Update";

    $atualizaDados = $updateDeDados->updateDeFuncionariosNoPDV($oracle,$tabela, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV,$loja);
} else if($retorno == "Não existem dados."){
    // echo "Não existem dados. 02 INSERT";

    $insereDadosFuncTarde =$InsertDeDados-> insertTabelaFuncTarde($oracle, $matricula, $nome, $entrada, $saida, $intervalo,$usuarioLogado,$dataPesquisa,$numPDV,$loja,$status);
    
}




