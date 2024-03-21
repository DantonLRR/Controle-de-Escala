<?php
include "../../base/conexao_TotvzOracle.php";


$MatriculausuarioPesquisado = $_POST['funcionarioFeriasMatricula'];
$lojaDaPessoaLogada = $_POST['loja'];
$setorDaPessoaLogada = $_POST['Departamento'];

$lista = array();
$query = "SELECT DISTINCT 

            PFUNC.CHAPA AS MATRICULA,
            PFUNC.NOME, 
            TO_CHAR(PFUNC.DATAADMISSAO, 'DD/MM/YYYY') AS DATA_ADMISSAO, 
            SUBSTR(PSECAO.DESCRICAO, 7,99) AS DEPARTAMENTO, 
            SUBSTR(PSECAO.DESCRICAO, 1,3) AS LOJA, 
            PFUNC.CODFUNCAO AS CODIGO_FUNCAO, 
            PFUNCAO.NOME AS FUNCAO,
            trim(REPLACE(REPLACE(REPLACE(pfuncao.nome,
                                         'ENCARREGADO DE',
                                         ''),
                                 'TRAINEE',
                                 ''),
                         'ENCARREGADO',
                         '')) AS DEPARTAMENTO2, 
            ENTRADA1.BATIDA as HORAEntrada,
            SAIDA1.BATIDA as SaidaParaAlmoco,
            ENTRADA2.BATIDA as VoltaDoAlmoco,
            SAIDA2.BATIDA as HoraSaida,
            PFUNC.CODSITUACAO as situacao,
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
                
            WHERE   PFUNC.CODCOLIGADA = 1 AND PFUNC.CODSITUACAO <>'D' 
                    AND SUBSTR(PSECAO.DESCRICAO,1,3) =  '$lojaDaPessoaLogada'
                    and  SUBSTR(PSECAO.DESCRICAO, 6, 99) like '%$setorDaPessoaLogada%'
            and PFUNC.CHAPA = $MatriculausuarioPesquisado
            ORDER BY PFUNC.NOME
    ";
// echo $query;

$parse = oci_parse($TotvsOracle, $query);
$retorno = oci_execute($parse);

$row = oci_fetch_assoc($parse);
if ($row !== false) {
    $array_valor = array(
        'FUNCAO' => $row['FUNCAO'],
        'HORAENTRADA' => $row['HORAENTRADA'],
        'HORASAIDA' => $row['HORASAIDA'],
        'HORAINTERVALO' =>$row['SAIDAPARAALMOCO']
    );
    echo json_encode($array_valor, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(false);
}
