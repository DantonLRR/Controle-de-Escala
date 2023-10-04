<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_escalaMensal.php";


$matricula = $_GET['DadosMatricula'];
$nome = $_GET['nomeSelecionado'];
$entrada = $_GET['DadosEntrada'];
$saida = $_GET['DadosSaida'];
$intervalo = $_GET['DadosIntervalo'];



$InsertDeDados = new Insert();

$insereDadosFuncManha =$InsertDeDados-> insertAceitarTipoAcordo($oracle, $matricula, $nome, $entrada, $saida, $intervalo);


