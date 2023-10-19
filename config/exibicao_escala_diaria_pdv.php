<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";


//manha
$matricula = $_GET['DadosMatricula']?? '' ;
$nome = $_GET['nomeSelecionado'] ?? '' ;
$entrada = $_GET['DadosEntrada'] ?? '' ;
$saida = $_GET['DadosSaida'] ?? '' ;
$intervalo = $_GET['DadosIntervalo'] ?? '' ;
$usuarioLogado = $_GET['usuarioLogado'] ?? '' ;
$dataPesquisa = $_GET['dataPesquisa'] ?? '' ;
$numPDV = $_GET['numPDV'] ?? '' ;
$loja = $_GET['loja'];

$verificacaoDeDados = new Verifica();
$InsertDeDados = new Insert();
$updateDeDados = new Update();


$horasIntermediariasArray = explode(",",$_GET['horasIntermediarias']);




foreach($horasIntermediariasArray as $periodoDeHoras):
    
$verifica = $verificacaoDeDados->verificaMontagemEscalaPDV($oracle, $numPDV, $dataPesquisa,$loja);


if ($retorno === "Já existem dados.") {
//    echo"<br>update".$periodoDeHoras;
    $atualizaDados = $updateDeDados->updateMontagemEscalaPDV($oracle, $numPDV, $dataPesquisa, $usuarioLogado,  $periodoDeHoras, $nome,$loja);
} else {
    // echo "<br>insert".$periodoDeHoras;
    $insereDados = $InsertDeDados-> insertMontagemEscalaPDV($oracle, $periodoDeHoras, $numPDV, $dataPesquisa, $usuarioLogado, $nome,$loja);
    }
endforeach;



 