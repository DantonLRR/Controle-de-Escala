<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";
include "../../base/conexao_tovs.php";
$tabela = "WEB_ESCALA_MENSAL";


$dia= '"'.$_GET['numeroDiaDaSemana'].'"';
$opcaoSelect = $_GET['opcaoSelecionada'];
$nome = $_GET['funcionario'];
$mesPesquisado = $_GET['mesAtual'];
$usuarioLogado = $_GET['usuarioLogado'];
$matricula = $_GET['matriculaFunc'];


// echo $mesPesquisado;
$InsertDeDados = new Insert();




$verifica = new Verifica();

$update = new Update();


$verificaSeJaExistemDados = $verifica ->verificaCadastroNaEscalaMensal($oracle,$matricula,$mesPesquisado );

if ($retorno === "Já existem dados.") {


    $updateDeDadps = $update -> updateDeFuncionariosNaEscalaMensal($oracle,$usuarioLogado, $mesPesquisado, $nome,$dia,$opcaoSelect, $matricula);

} else {


    $insertDadosNaTabela = $InsertDeDados->insertEscalaMensal($oracle,$tabela, $dia, $usuarioLogado, $mesPesquisado ,$nome,$opcaoSelect,$matricula);


}
