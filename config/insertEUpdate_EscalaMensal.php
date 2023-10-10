<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";

$tabela = "WEB_ESCALA_MENSAL";


$dia= '"'.$_GET['numeroDiaDaSemana'].'"';
$opcaoSelect = $_GET['opcaoSelecionada'];
$nome = $_GET['funcionario'];
$mesPesquisado = $_GET['mesAtual'];
$usuarioLogado = $_GET['usuarioLogado'];
// echo $mesPesquisado;
$InsertDeDados = new Insert();
$insertDadosNaTabela = $InsertDeDados->insertEscalaMensal($oracle,$tabela, $dia, $usuarioLogado, $mesPesquisado ,$nome,$opcaoSelect);





// $verificacaoDeDados = new Verifica();

// $updateDeDados = new Update();
// $verifica = $verificacaoDeDados->verificaExistenciaNumPDV($oracle, $tabela, $dataPesquisa, $numPDV);

// if ($retorno === "Já existem dados.") {
//     $atualizaDados = $updateDeDados->updateDeFuncionariosNoPDV($oracle,$tabela, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV);
// } else {
//     $insereDadosFuncManha = $InsertDeDados->insertTabelaFuncManha($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV);
// }
