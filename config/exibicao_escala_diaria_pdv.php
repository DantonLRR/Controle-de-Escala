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
//tarde
$matricula = $_GET['DadosMatricula1'] ?? '' ;
$nome = $_GET['nomeSelecionado2'] ?? '' ;
$entrada = $_GET['DadosEntrada1'] ?? '' ;
$saida = $_GET['DadosSaida1'] ?? '' ;
$intervalo = $_GET['DadosIntervalo1'] ?? '' ;
$usuarioLogado = $_GET['usuarioLogado'] ?? '' ;
$dataPesquisa = $_GET['dataPesquisa'] ?? '' ;

$verificacaoDeDados = new Verifica();
$InsertDeDados = new Insert();
$updateDeDados = new Update();


$horasIntermediariasArray = explode(",",$_GET['horasIntermediarias']);




foreach($horasIntermediariasArray as $periodoDeHoras):
    
$verifica = $verificacaoDeDados->verificaMontagemEscalaPDV($oracle, $numPDV, $dataPesquisa);


if ($retorno === "Já existem dados.") {
   echo"<br>update".$periodoDeHoras;
    $atualizaDados = $updateDeDados->updateMontagemEscalaPDV($oracle, $numPDV, $dataPesquisa, $usuarioLogado,  $periodoDeHoras, $nome);
} else {
    echo "<br>insert".$periodoDeHoras;
    $insereDadosFuncManha = $InsertDeDados-> insertMontagemEscalaPDV($oracle, $periodoDeHoras, $numPDV, $dataPesquisa, $usuarioLogado, $nome);
    }
endforeach;



 