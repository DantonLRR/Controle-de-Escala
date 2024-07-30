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
    $sql = "SELECT a.matricula as MATRICULA,
            a.nome,
            a.loja,
            a.diaselecionado,
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
    $sql = "SELECT DISTINCT 
    
            PFUNC.CHAPA AS MATRICULA,
            PFUNC.NOME, 
            TO_CHAR(PFUNC.DATAADMISSAO, 'DD/MM/YYYY') AS DATA_ADMISSAO, 
            SUBSTR(PSECAO.DESCRICAO, 6,99) AS DEPARTAMENTO, 
            SUBSTR(PSECAO.DESCRICAO, 1,3) AS LOJA, 
            PFUNC.CODFUNCAO AS CODIGO_FUNCAO, 
            PFUNCAO.NOME AS FUNCAO, 
            ENTRADA1.BATIDA as HORAEntrada,
            SAIDA1.BATIDA as INICIOINTERVALO,
            ENTRADA2.BATIDA as VoltaDoAlmoco,
            SAIDA2.BATIDA as HoraSaida,
            ENTRADA1.BATIDA || ' - ' || SAIDA1.BATIDA || ' - ' || ENTRADA2.BATIDA || ' - ' || SAIDA2.BATIDA AS HORARIO
                
            FROM PFUNC 

            INNER JOIN PFUNCAO 
            ON (PFUNC.CODCOLIGADA = PFUNCAO.CODCOLIGADA 
            AND PFUNC.CODFUNCAO = PFUNCAO.CODIGO) 

            INNER JOIN PSECAO 
            ON (PFUNC.CODCOLIGADA = PSECAO.CODCOLIGADA 
            AND PFUNC.CODSECAO = PSECAO.CODIGO)

            LEFT JOIN (
            SELECT DISTINCT 

                AHORARIO.CODCOLIGADA, AHORARIO.CODIGO,

                TO_CHAR(TRUNC((MIN(ABATHOR.BATIDA) * 60) / 3600), 'FM9900') || ':' || 
                TO_CHAR(TRUNC(MOD(ABS(MIN(ABATHOR.BATIDA) * 60), 3600) / 60), 'FM00')
                AS BATIDA
            FROM ABATHOR 
                INNER JOIN AHORARIO ON 
                    ABATHOR.CODCOLIGADA = AHORARIO.CODCOLIGADA 
                    AND ABATHOR.CODHORARIO = AHORARIO.CODIGO 
                WHERE   ABATHOR.TIPO = 0 AND ABATHOR.INDICE = 1 AND ABATHOR.NATUREZA = 0
                GROUP BY AHORARIO.CODCOLIGADA, AHORARIO.CODIGO
                ) ENTRADA1 ON
            ENTRADA1.CODCOLIGADA = PFUNC.CODCOLIGADA AND ENTRADA1.CODIGO = PFUNC.CODHORARIO

            LEFT JOIN (
            SELECT DISTINCT 
                AHORARIO.CODCOLIGADA, AHORARIO.CODIGO,
                NVL(TO_CHAR(TRUNC((MIN(ABATHOR.BATIDA) * 60) / 3600), 'FM9900') || ':' || 
                TO_CHAR(TRUNC(MOD(ABS(MIN(ABATHOR.BATIDA) * 60), 3600) / 60), 'FM00'), '00:00') AS BATIDA
            FROM ABATHOR 
                INNER JOIN AHORARIO ON 
                    ABATHOR.CODCOLIGADA = AHORARIO.CODCOLIGADA 
                    AND ABATHOR.CODHORARIO = AHORARIO.CODIGO 
                WHERE   ABATHOR.TIPO = 0 AND ABATHOR.INDICE = 1 AND ABATHOR.NATUREZA = 1
                GROUP BY AHORARIO.CODCOLIGADA, AHORARIO.CODIGO
                ) SAIDA1 ON
            SAIDA1.CODCOLIGADA = PFUNC.CODCOLIGADA AND SAIDA1.CODIGO = PFUNC.CODHORARIO

            LEFT JOIN (
            SELECT DISTINCT 
                AHORARIO.CODCOLIGADA, AHORARIO.CODIGO,
                NVL(TO_CHAR(TRUNC((MAX(ABATHOR.BATIDA) * 60) / 3600), 'FM9900') || ':' || 
                TO_CHAR(TRUNC(MOD(ABS(MAX(ABATHOR.BATIDA) * 60), 3600) / 60), 'FM00'), '00:00') AS BATIDA
            FROM ABATHOR 
                INNER JOIN AHORARIO ON 
                    ABATHOR.CODCOLIGADA = AHORARIO.CODCOLIGADA 
                    AND ABATHOR.CODHORARIO = AHORARIO.CODIGO 
                WHERE   ABATHOR.TIPO = 0 AND ABATHOR.INDICE = 1 AND ABATHOR.NATUREZA = 0
                GROUP BY AHORARIO.CODCOLIGADA, AHORARIO.CODIGO
                ) ENTRADA2 ON
            ENTRADA2.CODCOLIGADA = PFUNC.CODCOLIGADA AND ENTRADA2.CODIGO = PFUNC.CODHORARIO

            LEFT JOIN (
            SELECT DISTINCT 
                AHORARIO.CODCOLIGADA, AHORARIO.CODIGO,
                NVL(TO_CHAR(TRUNC((MAX(ABATHOR.BATIDA) * 60) / 3600), 'FM9900') || ':' || 
                TO_CHAR(TRUNC(MOD(ABS(MAX(ABATHOR.BATIDA) * 60), 3600) / 60), 'FM00'), '00:00') AS BATIDA
            FROM ABATHOR 
                INNER JOIN AHORARIO ON 
                    ABATHOR.CODCOLIGADA = AHORARIO.CODCOLIGADA 
                    AND ABATHOR.CODHORARIO = AHORARIO.CODIGO 
                WHERE   ABATHOR.TIPO = 0 AND ABATHOR.INDICE = 1 AND ABATHOR.NATUREZA = 1
                GROUP BY AHORARIO.CODCOLIGADA, AHORARIO.CODIGO
                ) SAIDA2 ON
            SAIDA2.CODCOLIGADA = PFUNC.CODCOLIGADA AND SAIDA2.CODIGO = PFUNC.CODHORARIO

            WHERE   PFUNC.CODCOLIGADA = 1 
            AND PFUNC.CODSITUACAO  <> 'D' 
                AND SUBSTR(PSECAO.DESCRICAO,1,3) LIKE $loja
               
            and  PFUNC.CHAPA = '$MatriculaDaPessoaSelecionada'

            ORDER BY PFUNC.NOME
             -- AND PFUNCAO.NOME = 'OPERADOR DE CAIXA'
        ";
//  echo $sql;
    $parse = ociparse($TotvsOracle, $sql);
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
