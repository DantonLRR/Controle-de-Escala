<?php
include "../../base/conexao_martdb.php";
include "../../base/conexao_TotvzOracle.php";
include "../config/php/CRUD_geral.php";
$verificacaoDeDados = new Verifica();
$MatriculaDaPessoaSelecionada = $_GET['MatriculaDaPessoaSelecionada'];
$loja = trim($_GET['loja']);
$dataAtual = empty($_GET['dataAtual']) ? date("Y-m-d") : $_GET['dataAtual'];
$nomeFunc = trim($_GET['nomeSelecionado']);


$verifica = $verificacaoDeDados->verificaAlteracaoNoHorarioDiario($oracle, $MatriculaDaPessoaSelecionada, $dataAtual, $nomeFunc, $loja);

if ($retorno == "Já existem dados.") {
    $sql = "SELECT  a.nome,
            a.loja,
            a.diaselecionado,
            a.matricula as MATRICULA,
            a.horaentrada as HORAENTRADA,
            a.horasaida as HORASAIDA,
            a.horaintervalo as INICIOINTERVALO
            from webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED a 
                    WHERE a.matricula = '$MatriculaDaPessoaSelecionada'
                    and a.loja = $loja      
                    and a.diaselecionado = TO_DATE('$dataAtual', 'YYYY-MM-DD')";
    //  echo $sql;
    $parse = ociparse($oracle, $sql);
    oci_execute($parse);
} else if ($retorno == "Não existem dados.") {
    $sql = " SELECT a.nome,
                a.messelecionado,
                a.matricula AS MATRICULA,
                a.horaentrada AS HORAENTRADA,
                a.horasaida AS HORASAIDA,
                a.horaintervalo AS INICIOINTERVALO
            FROM webmartminas.web_escala_mensal a
            WHERE a.matricula = '$MatriculaDaPessoaSelecionada'
            AND a.loja = $loja
            AND a.messelecionado = (SELECT MAX(messelecionado) 
                                    FROM webmartminas.web_escala_mensal 
                                    WHERE matricula = '$MatriculaDaPessoaSelecionada'
                                    AND loja = $loja) ";
    //  echo $sql;
    $parse = ociparse($oracle, $sql);
    oci_execute($parse);
};


while (($row = oci_fetch_assoc($parse)) != false) {
    $array_valor = array(
        'MATRICULA' => $row['MATRICULA'],
        'HORAENTRADA' => $row['HORAENTRADA'],
        'HORASAIDA' => $row['HORASAIDA'],
        'SAIDAPARAALMOCO' => $row['INICIOINTERVALO']
    );

    echo json_encode($array_valor);
}
