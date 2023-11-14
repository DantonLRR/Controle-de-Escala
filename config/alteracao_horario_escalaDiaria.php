<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";




$matricula = trim($_POST['matriculaFunc']);
$nome = trim($_POST['nomeFuncionario']);
$loja = trim($_POST['loja']);
$diaSelecionado = trim($_POST['diaDeAlteracaoDoHorario']);

$horaEntrada = trim($_POST['horarioEntradaFunc']);
$horaSaida = trim($_POST['horarioSaidaFunc']);
$horaIntervalo = trim($_POST['horarioIntervaloFunc']);
$usuInclusao = trim($_POST['usuInclusao']);




$verificacaoDeDados = new Verifica();
$InsertDeDados = new Insert();
$updateDeDados = new Update();


$verifica = $verificacaoDeDados->verificaAlteracaoNoHorarioDiario($oracle,$matricula,$diaSelecionado,$nome,$loja);

if ($retorno == "Já existem dados.") {
    echo "<br><br> update <br><br>";
    
     $atualizaDados = $updateDeDados->updateDeFuncionariosNaEscalaIntermediaria($oracle, $horaEntrada,$horaSaida,$horaIntervalo,$usuInclusao,$matricula,$nome,$loja,$diaSelecionado);
} else if ($retorno == "Não existem dados.") {
    // echo "<br><br> Não existem dados. 02 INSERT";
     $insereDadosTblIntermediaria = $InsertDeDados-> insertNaTblIntermediariaEscalaDiaria($oracle,$matricula,$nome,$loja,$diaSelecionado,$horaEntrada,$horaSaida,$horaIntervalo,$usuInclusao);
}
