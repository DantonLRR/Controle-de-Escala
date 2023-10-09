<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";


$matricula = $_GET['DadosMatricula'];
$nome = $_GET['nomeSelecionado'];
$entrada = $_GET['DadosEntrada'];
$saida = $_GET['DadosSaida'];
$intervalo = $_GET['DadosIntervalo'];
$usuarioLogado = $_GET['usuarioLogado'];
$dataPesquisa = $_GET['dataPesquisa'];
$numPDV = $_GET['numPDV'];
$InsertDeDados = new Insert();

$insereDadosFuncManha =$InsertDeDados-> insertTabelaFuncManha($oracle, $matricula, $nome, $entrada, $saida, $intervalo,$usuarioLogado,$dataPesquisa,$numPDV );


