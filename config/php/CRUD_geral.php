<?php
$dataSelecionadaNoFiltro = $_GET['mesPesquisado'] ?? date("Y-m");

class Dias
{
    public function mesEAnoFiltro($oracle)
    {
        $lista = array();
        $query = "SELECT TO_CHAR(ADD_MONTHS(TRUNC(SYSDATE, 'YYYY'), LEVEL - 1), 'YYYY-MM') AS mes
        FROM DUAL
        CONNECT BY LEVEL <= 12";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }

    public function buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro)
    {
        $lista = array();
        $query = "SELECT
        TO_CHAR(dia, 'DD') AS dia,
        TO_CHAR(dia, 'DY', 'NLS_DATE_LANGUAGE=PORTUGUESE') AS dia_semana_abreviado
     FROM ( SELECT TRUNC(TO_DATE('$dataSelecionadaNoFiltro', 'YYYY-MM'), 'MM') 
     + LEVEL - 1 AS dia
        FROM DUAL
        CONNECT BY TRUNC(TO_DATE('$dataSelecionadaNoFiltro', 'YYYY-MM'), 'MM') 
        + LEVEL - 1 <= LAST_DAY(TO_DATE('$dataSelecionadaNoFiltro', 'YYYY-MM'))
     )";



        //  echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }

    //montagem de escala PDV
    public function escalaDiariaDePDV($oracle, $numPDV, $dataAtual, $loja)
    {

        $lista = array();
        $query = "SELECT *
         FROM Web_Montagem_Escala_Diaria_PDV a
         WHERE NUMPDV = '$numPDV'
         AND a.diaselecionado = TO_DATE('$dataAtual' , 'YYYY-MM-DD')
         and a.loja = '$loja'
         and a.status = 'A'
         ORDER BY NUMPDV ASC ";
        // echo $query;        
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
}


class Funcionarios
{

    //diaria
    public function DadosAPartirDaEscalaMensal($oracle, $dia, $lojaDaPessoaLogada, $mesSelecionado)
    {


        $lista = array();
        $query = "SELECT a.matricula,
          a.nome,
          a.loja,
          a.cargo,
          a.horaentrada,
          a.horasaida,
         trim(a.horaintervalo) as horaintervalo,
         a.$dia,
         TO_CHAR(a.mesSelecionado, 'Month- yyyy', 'NLS_DATE_LANGUAGE=PORTUGUESE') as mesSelecionadoFormatado
         FROM WEB_ESCALA_MENSAL a
         where loja = '$lojaDaPessoaLogada'
          and a.mesSelecionado = TO_DATE('$mesSelecionado','YYYY-MM')
          order by a.nome asc
          ";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        echo $query;
    }


    public function recuperaDadosDaEscalaIntermed($oracle, $matricula, $nome, $loja, $diaselecionado)
    {
        $lista = array();
        $query = "Select * from WEB_ESCALA_DIARIA_HR_INTERMED a 
        WHERE a.matricula = '$matricula'
        and trim(a.nome) = '$nome'
        and a.loja = $loja      
        and a.diaselecionado = TO_DATE('$diaselecionado', 'YYYY-MM-DD')";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // Echo $query;
    }



    //mensal

    public function informacoesOperadoresDeCaixa($TotvsOracle, $lojaDaPessoaLogada)
    {

        $lista = array();
        $query = "SELECT DISTINCT 
    
        PFUNC.CHAPA AS MATRICULA,
        PFUNC.NOME, 
        TO_CHAR(PFUNC.DATAADMISSAO, 'DD/MM/YYYY') AS DATA_ADMISSAO, 
        SUBSTR(PSECAO.DESCRICAO, 6,99) AS DEPARTAMENTO, 
        SUBSTR(PSECAO.DESCRICAO, 1,3) AS LOJA, 
        PFUNC.CODFUNCAO AS CODIGO_FUNCAO, 
        PFUNCAO.NOME AS FUNCAO, 
        ENTRADA1.BATIDA as HORAEntrada,
         SAIDA1.BATIDA as SaidaParaAlmoco,
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
            --NVL(
            TO_CHAR(TRUNC((MIN(ABATHOR.BATIDA) * 60) / 3600), 'FM9900') || ':' || 
            TO_CHAR(TRUNC(MOD(ABS(MIN(ABATHOR.BATIDA) * 60), 3600) / 60), 'FM00')--, '00:00') 
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
        
    WHERE   PFUNC.CODCOLIGADA = 1 AND PFUNC.CODSITUACAO = 'A' 
            AND SUBSTR(PSECAO.DESCRICAO,1,3) =  '$lojaDaPessoaLogada'
            AND PFUNCAO.NOME = 'OPERADOR DE CAIXA' 
    
    ORDER BY PFUNC.NOME
      ";
        $resultado = oci_parse($TotvsOracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo $query;
    }

    //pdv
    public function buscaFuncEHorarioDeTrabalhoManha($TotvsOracle, $lojaDaPessoaLogada)
    {
        $lista = array();
        $query = "SELECT DISTINCT 
    
        PFUNC.CHAPA AS MATRICULA,
        PFUNC.NOME, 
        TO_CHAR(PFUNC.DATAADMISSAO, 'DD/MM/YYYY') AS DATA_ADMISSAO, 
        SUBSTR(PSECAO.DESCRICAO, 6,99) AS DEPARTAMENTO, 
        SUBSTR(PSECAO.DESCRICAO, 1,3) AS LOJA, 
        PFUNC.CODFUNCAO AS CODIGO_FUNCAO, 
        PFUNCAO.NOME AS FUNCAO, 
        ENTRADA1.BATIDA as Entrada,
         SAIDA1.BATIDA as SaidaParaAlmoco,
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
                
            WHERE   PFUNC.CODCOLIGADA = 1 AND PFUNC.CODSITUACAO <> 'D' 
            AND SUBSTR(PSECAO.DESCRICAO,1,3) =  '$lojaDaPessoaLogada'
                    AND PFUNCAO.NOME = 'OPERADOR DE CAIXA' 
            
            and  ENTRADA1.BATIDA  BETWEEN '07:00' AND '10:00' 
            ORDER BY PFUNC.NOME";
        // echo $query;
        $resultado = oci_parse($TotvsOracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo  $lista;
    }
    public function buscaFuncEHorarioDeTrabalhoTarde($TotvsOracle,$lojaDaPessoaLogada)
    {
        $lista = array();
        $query = "SELECT DISTINCT 
    
        PFUNC.CHAPA AS MATRICULA,
        PFUNC.NOME, 
        TO_CHAR(PFUNC.DATAADMISSAO, 'DD/MM/YYYY') AS DATA_ADMISSAO, 
        SUBSTR(PSECAO.DESCRICAO, 6,99) AS DEPARTAMENTO, 
        SUBSTR(PSECAO.DESCRICAO, 1,3) AS LOJA, 
        PFUNC.CODFUNCAO AS CODIGO_FUNCAO, 
        PFUNCAO.NOME AS FUNCAO, 
        ENTRADA1.BATIDA as Entrada,
         SAIDA1.BATIDA as SaidaParaAlmoco,
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
                    --NVL(
                    TO_CHAR(TRUNC((MIN(ABATHOR.BATIDA) * 60) / 3600), 'FM9900') || ':' || 
                    TO_CHAR(TRUNC(MOD(ABS(MIN(ABATHOR.BATIDA) * 60), 3600) / 60), 'FM00')--, '00:00') 
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
                
            WHERE   PFUNC.CODCOLIGADA = 1 AND PFUNC.CODSITUACAO <> 'D' 
            AND SUBSTR(PSECAO.DESCRICAO,1,3) =  '$lojaDaPessoaLogada'
                    AND PFUNCAO.NOME = 'OPERADOR DE CAIXA' 
            
            and  ENTRADA1.BATIDA  BETWEEN '12:00' AND '14:00'  
            ORDER BY PFUNC.NOME";

        // echo "<br><br>" . $query;
        $resultado = oci_parse($TotvsOracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo  $lista;
    }


    public function filtroFuncionariosCadastradosManha($oracle, $dia, $i)
    {
        $lista = array();
        $query = "SELECT a.matricula,
        a.nome,
        a.horaentrada,
        a.horasaida,
        a.horaintervalo,
        a.datainclusao,
        a.usuinclusao,
        a.diaselecionado,
        a.numpdv
            FROM ESCALA_PDV_Manha a
            where a.numpdv = $i
            and to_char(a.diaselecionado,'YYYY-MM-DD') = '$dia'
            and  status = 'A'
            ORDER BY a.numpdv ASC
        ";
        //   echo "<br>". $query;
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }

    public function  filtroFuncionariosCadastradoTarde($oracle, $dia, $i)
    {
        $lista = array();
        $query = "SELECT a.matricula,
        a.nome,
        a.horaentrada,
        a.horasaida,
        a.horaintervalo,
        a.datainclusao,
        a.usuinclusao,
       TO_CHAR(a.DIASELECIONADO, 'YYYY-MM-DD') as DIASELECIONADO
        FROM ESCALA_PDV_TARDE a
         WHERE  TO_CHAR(a.DIASELECIONADO, 'YYYY-MM-DD') = '$dia'
         and  status = 'A'
         and a.numpdv = $i";

        // echo $query;
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }

    public function funcionariosDisponiveisNoDia($oracle, $dia, $mesSelecionado)
    {
        $lista = array();
        $query =  "SELECT *FROM WEB_ESCALA_MENSAL WHERE $dia is null  AND to_char(MESSELECIONADO , 'YYYY-MM') = '$mesSelecionado'";
        // echo $query."<br>";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // print_r( $lista);
    }

    public function FuncsJaEscaladosMANHA($oracle, $diaSelecionado)
    {
        $lista = array();
        $query =   "SELECT a.matricula,
                    a.nome,
                    a.horaentrada,
                    a.horasaida,
                    a.horaintervalo,
                    a.datainclusao,
                    a.usuinclusao,
                    a.diaselecionado,
                    a.numpdv,
                    a.status
                    FROM ESCALA_PDV_Manha a
                    where  to_char(a.diaselecionado, 'YYYY-MM-DD') = '$diaSelecionado'
                    and status = 'A'
                  
        ";
        //  echo $query."<br>";

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // print_r( $lista);
    }
    public function FuncsJaEscaladosTARDE($oracle, $diaSelecionado)
    {
        $lista = array();
        $query =   "SELECT a.matricula,
                    a.nome,
                    a.horaentrada,
                    a.horasaida,
                    a.horaintervalo,
                    a.datainclusao,
                    a.usuinclusao,
                    a.diaselecionado,
                    a.numpdv,
                    a.status
                    FROM ESCALA_PDV_TARDE a
                    where  to_char(a.diaselecionado, 'YYYY-MM-DD') = '$diaSelecionado'
                    and status = 'A'
                  
        ";
        //  echo $query."<br>";

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // print_r( $lista);
    }
}

class Verifica
{
    //mensal

    public function verificaCadastroNaEscalaMensal($oracle, $matricula, $mesPesquisado, $loja)
    {
        $lista = array();
        global  $retorno;
        $query = "SELECT * FROM WEB_ESCALA_MENSAL a
        WHERE a.matricula = '$matricula'
        AND a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM')
        AND a.loja = $loja ";



        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);

        if ($retorno) {
            if (oci_fetch($parse)) {
                $retorno = "Já existem dados.";
            } else {
                $retorno = "Não existem dados.";
            }
        } else {
            // Erro na consulta
            echo "Erro na consulta.";
        }
        while ($row = oci_fetch_assoc($parse)) {
            array_push($lista, $row);
        }
        return $lista;
        echo "select para verificaCadastroNaEscalaMensal :" . $query;
        echo "</br>" + $retorno;
    }


    public function verificaCadastroNaEscalaMensa1($oracle, $matricula, $mesPesquisado,)
    {
        $lista = array();
        global  $retorno;
        $query = "SELECT * FROM WEB_ESCALA_MENSAL a
        WHERE a.matricula = $matricula
        AND a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM') ";


        $resultado = oci_parse($oracle, $query);

        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }



    //diaria

    public function verificaAlteracaoNoHorarioDiario($oracle, $matricula, $diaselecionado, $nome, $loja)
    {
        global  $retorno;
        $query = "Select * from WEB_ESCALA_DIARIA_HR_INTERMED a 
        WHERE a.matricula = '$matricula'
        and trim(a.nome) = '$nome'
        and a.loja = $loja      
        and a.diaselecionado = TO_DATE('$diaselecionado', 'YYYY-MM-DD')
    
    ";
        $parse = oci_parse($oracle, $query);

        $retorno2 = oci_execute($parse);

        if ($retorno2) {
            if (oci_fetch($parse)) {
                $retorno = "Já existem dados.";
            } else {
                $retorno = "Não existem dados.";
            }
        } else {

            echo "Erro na consulta.";
        }

        // echo $query;
    }



    //escala pdv
    public function verificaExistenciaNumPDV($oracle, $tabela, $dataPesquisa, $numPDV, $loja)
    {
        global  $retorno;
        $query = "SELECT * FROM $tabela a
                 WHERE a.NUMPDV = '$numPDV'
                 AND a.DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD')
                 and a.loja = $loja
                 and a.status = 'A'";
        $parse = oci_parse($oracle, $query);

        $retorno2 = oci_execute($parse);

        if ($retorno2) {
            if (oci_fetch($parse)) {
                $retorno = "Já existem dados.";
            } else {
                $retorno = "Não existem dados.";
            }
        } else {

            echo "Erro na consulta.";
        }
        // echo $query;
    }
}


class Insert
{
    // mensal
    public function insertEscalaMensal($oracle, $tabela, $dia,  $matricula, $nome, $loja, $cargoFunc, $mesPesquisado, $horarioEntradaFunc, $horarioSaidaFunc,  $horarioIntervaloFunc, $opcaoSelect, $usuarioLogado)
    {

        $query = "INSERT INTO $tabela (
             matricula,
             nome,
             LOJA,
             CARGO,
             mesSelecionado,
            horaEntrada,
            horaSaida,
            horaintervalo,
             $dia,
          datainclusao, 
           usuinclusao
         ) VALUES (
         '$matricula',
          '$nome',
          $loja ,
          '$cargoFunc',
         TO_DATE('$mesPesquisado', 'YYYY-MM'),  
          '$horarioEntradaFunc',
         '$horarioSaidaFunc', 
          '$horarioIntervaloFunc ',
          '$opcaoSelect',
           SYSDATE,
           '$usuarioLogado'
        )";

        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);
        if ($retorno) {
            global $sucess;
            $sucess = 1;

            return true;
        } else {
            $sucess = 0;
            //  echo "<br>" . $query;
            return false;
        }

        echo $query;
    }

    //diaria

    public function insertNaTblIntermediariaEscalaDiaria($oracle, $matricula, $nome, $loja, $diaSelecionado, $horaEntrada, $horaSaida, $horaIntervalo, $usuInclusao)
    {
        $query = "INSERT INTO WEB_ESCALA_DIARIA_HR_INTERMED
     (matricula,
        nome,
     loja,
     diaselecionado,
      status,
     horaentrada,
      horasaida,
      horaintervalo,
     datainclusao,
     usuinclusao)
     VALUES
     (
     '$matricula',
     '$nome ',
     $loja,
     TO_DATE('$diaSelecionado', 'YYYY-MM-DD'),
     '',
     '$horaEntrada',
     '$horaSaida',
     '$horaIntervalo',
     SYSDATE,
     '$usuInclusao'
     )";

        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);
        if ($retorno) {
            global $sucess;
            $sucess = 1;

            return true;
        } else {
            $sucess = 0;
            //  echo "<br>" . $query;
            return false;
        }

        echo $query;
    }
    //escala pdv
    public function insertTabelaFuncManha($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV, $loja, $status)
    {
        $query = "INSERT INTO  ESCALA_PDV_MANHA (
        MATRICULA,
        NOME,
        HORAENTRADA,
        HORASAIDA,
        HORAINTERVALO,
        USUINCLUSAO,
        DATAINCLUSAO,
        DIASELECIONADO,
        NUMPDV,
        LOJA,
        STATUS
      )
      VALUES (
        '$matricula',
        '$nome',
        '$entrada',
        '$saida',
        '$intervalo',
        '$usuarioLogado',
        sysdate,
        TO_DATE( '$dataPesquisa','YYYY-MM-DD'),
        '$numPDV',
        $loja,
        '$status'
     )";
        $parse = oci_parse($oracle, $query);
        $retorno = oci_execute($parse);
        if ($retorno) {
            global $sucess;
            $sucess = 1;
            return true;
        } else {
            $sucess = 0;
            //  echo "<br>" . $query;
            return false;
        }
        echo "<br>" . "insert manha :" . $query;
    }

    public function insertTabelaFuncTarde($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV, $loja, $status)
    {
        $query = "INSERT INTO  ESCALA_PDV_TARDE (
        MATRICULA,
        NOME,
        HORAENTRADA,
        HORASAIDA,
        HORAINTERVALO,
        USUINCLUSAO,
        DATAINCLUSAO,
        DIASELECIONADO,
        NUMPDV,
        LOJA,
        STATUS
     )
      VALUES (
        '$matricula',
        '$nome',
        '$entrada',
        '$saida',
        '$intervalo',
        '$usuarioLogado',
        sysdate,
        TO_DATE( '$dataPesquisa','YYYY-MM-DD'),
        '$numPDV',
        $loja,
        '$status'        
     )";
        // echo $query;
        $parse = oci_parse($oracle, $query);
        $retorno = oci_execute($parse);
        if ($retorno) {
            global $sucess;
            $sucess = 1;
            return true;
        } else {
            $sucess = 0;
            //  echo "<br>" . $query;
            return false;
        }
        echo "<br>" . " insert tarde : " . $query;
    }

    //montagem de escala PDV
    public function insertMontagemEscalaPDV($oracle, $periodoDeHoras,  $numPDV, $dataPesquisa, $usuarioLogado, $nome, $loja, $status)
    {
        global  $retorno;
        $query = "INSERT INTO Web_Montagem_Escala_Diaria_PDV (
            NUMPDV,
            DIASELECIONADO,
            DATAINCLUSAO,
            USUINCLUSAO,
            $periodoDeHoras,
            LOJA,
            STATUS
            ) 
            VALUES (
            '$numPDV',
            TO_DATE( '$dataPesquisa','YYYY-MM-DD'),
            sysdate,
            '$usuarioLogado',
            '$nome',
            '$loja',
            '$status'
        )";

        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);
        if ($retorno) {
            global $sucess;
            $sucess = 1;

            return true;
        } else {
            $sucess = 0;

            return false;
        }
        echo "<br>" . "insertMontagemEscalaPDV: " . $query;
    }
}



class Update
{
    //mensal
    public function updateDeFuncionariosNaEscalaMensal($oracle, $usuarioLogado, $mesPesquisado, $nome, $dia, $opcaoSelect, $matricula, $loja)
    {
        $query = "UPDATE WEB_ESCALA_MENSAL a SET
            datainclusao = SYSDATE,
            usuinclusao = '$usuarioLogado',
            mesSelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM'),
            nome = '$nome',
            $dia = '$opcaoSelect',
            LOJA = '$loja' 
         WHERE a.matricula = '$matricula'
          and messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM')
          and loja = $loja";
        echo $query;
        $parse = oci_parse($oracle, $query);

        oci_execute($parse);
    }


    //diaria
    public function updateDeFuncionariosNaEscalaIntermediaria($oracle, $horaEntrada, $horaSaida, $horaIntervalo, $usuInclusao, $matricula, $nome, $loja, $diaSelecionado)
    {
        $query = "UPDATE WEB_ESCALA_DIARIA_HR_INTERMED
        SET 
          horaentrada = '$horaEntrada',
          horasaida = '$horaSaida',
          horaintervalo = '$horaIntervalo',
          usuinclusao = '$usuInclusao'
        WHERE matricula = '$matricula'
          and trim(nome) = '$nome'
          and loja = $loja
          and diaselecionado = TO_DATE('$diaSelecionado', 'YYYY-MM-DD')
          ";

        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);
        if ($retorno) {
            global $sucess;
            $sucess = 1;
            return true;
        } else {
            $sucess = 0;
            // echo "<br>" . $query;
            return false;
        }

        echo $query;
    }


    //pdv
    public function updateDeFuncionariosNoPDV($oracle, $tabela, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV, $loja,)
    {
        $query = "UPDATE $tabela SET
        MATRICULA = '$matricula',
        NOME = '$nome',
        HORAENTRADA = '$entrada',
        HORASAIDA = '$saida',
        HORAINTERVALO = '$intervalo',
        USUINCLUSAO = '$usuarioLogado',
        DATAINCLUSAO = sysdate,
        DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD'),
        NUMPDV = '$numPDV',
        LOJA = '$loja'
      WHERE NUMPDV = '$numPDV'
      AND loja = $loja
      and DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD')
      and STATUS = 'A'
      ";
        $parse = oci_parse($oracle, $query);

        oci_execute($parse);
        echo "<br>" . "update : " . $query;
    }


    //montagem de escala PDV


    public function updateMontagemEscalaPDV($oracle, $numPDV, $dataPesquisa, $usuarioLogado,  $periodoDeHoras, $nome, $loja)
    {
        global  $retorno;
        $query = "UPDATE Web_Montagem_Escala_Diaria_PDV SET  
            
             DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD'),
            DATAINCLUSAO = sysdate,
            USUINCLUSAO = '$usuarioLogado',
            $periodoDeHoras =  '$nome'

            WHERE NUMPDV = '$numPDV'
            and loja = '$loja'
            and status = 'A'
            ";



        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);
        echo $retorno;
        if ($retorno) {
            global $sucess;
            $sucess = 1;

            return true;
        } else {
            $sucess = 0;
            //  echo "<br>" . $query;
            return false;
        }

        // echo $query;
    }

    public function updateRemocaoEscalaPDV($oracle, $tabela, $numPDV, $dataPesquisa, $loja)
    {
        global  $retorno;
        $query = " UPDATE $tabela
         SET status = 'R' 
         WHERE NUMPDV = '$numPDV'
         AND diaselecionado = TO_DATE('$dataPesquisa', 'YYYY-MM-DD')
         AND loja = '$loja'
         AND status = 'A'";

        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);

        if ($retorno) {
            global $sucess;
            $sucess = 1;

            return true;
        } else {
            $sucess = 0;
            //  echo "<br>" . $query;
            return false;
        }
        // echo $retorno;
        // echo $query;
    }
}
