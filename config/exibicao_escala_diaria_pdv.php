<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";

$tabela = "Web_Montagem_Escala_Diaria_PDV";

$matricula = $_GET['DadosMatricula'] ?? '';
$nome = $_GET['nomeSelecionado'] ?? '';
$entrada = $_GET['DadosEntrada'] ?? '';
$saida = $_GET['DadosSaida'] ?? '';
$intervalo = $_GET['DadosIntervalo'] ?? '';
$usuarioLogado = $_GET['usuarioLogado'] ?? '';
$dataPesquisa = $_GET['dataPesquisa'] ?? '';
$numPDV = $_GET['numPDV'] ?? '';
$loja = $_GET['loja'] ?? '';

$verificacaoDeDados = new Verifica();
$InsertDeDados = new Insert();
$updateDeDados = new Update();


$horasIntermediariasArray = explode(",", $_GET['horasIntermediarias']);
print_r($horasIntermediariasArray);



foreach ($horasIntermediariasArray as $periodoDeHoras) :

    $verifica = $verificacaoDeDados->verificaExistenciaNumPDV($oracle, $tabela, $dataPesquisa, $numPDV, $loja);

    if ($retorno == "Já existem dados.") {
        echo "<br>caiu no update   :" . $periodoDeHoras;
        $atualizaDados = $updateDeDados->updateMontagemEscalaPDV($oracle, $numPDV, $dataPesquisa, $usuarioLogado,  $periodoDeHoras, $nome, $loja);
    } else if ($retorno == "Não existem dados.") {
        echo "<br> caiu no insert   :" . $periodoDeHoras;
        $insereDados = $InsertDeDados->insertMontagemEscalaPDV($oracle, $periodoDeHoras, $numPDV, $dataPesquisa, $usuarioLogado, $nome, $loja);
    }
endforeach;
