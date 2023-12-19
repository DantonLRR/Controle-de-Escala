<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";
$loja = $_POST['loja'];
$mesPesquisado = $_POST['mesPesquisa'];
$usuarioLogado = $_POST['usuarioLogado'];

$InsertDeDados = new log_escala_mensal();


    $insertDadosNaTabela = $InsertDeDados->log_liberacao_escala_mensal($oracle, $loja, $mesPesquisado, $usuarioLogado);

