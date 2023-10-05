<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_escalaMensal.php";


$matricula = $_GET['DadosMatricula1'];
$nome = $_GET['nomeSelecionado2'];
$entrada = $_GET['DadosEntrada1'];
$saida = $_GET['DadosSaida1'];
$intervalo = $_GET['DadosIntervalo1'];
$usuarioLogado = $_GET['usuarioLogado'];


$InsertDeDados = new Insert();

$insereDadosFuncTarde =$InsertDeDados-> insertTabelaFuncTarde($oracle, $matricula, $nome, $entrada, $saida, $intervalo,$usuarioLogado);


