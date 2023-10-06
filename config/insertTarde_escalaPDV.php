<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";


$matricula = $_GET['DadosMatricula1'];
$nome = $_GET['nomeSelecionado2'];
$entrada = $_GET['DadosEntrada1'];
$saida = $_GET['DadosSaida1'];
$intervalo = $_GET['DadosIntervalo1'];
$usuarioLogado = $_GET['usuarioLogado'];
$dataPesquisa = $_GET['dataPesquisa'];

$InsertDeDados = new Insert();

$insereDadosFuncTarde =$InsertDeDados-> insertTabelaFuncTarde($oracle, $matricula, $nome, $entrada, $saida, $intervalo,$usuarioLogado,$dataPesquisa);


