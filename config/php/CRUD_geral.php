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



        //  echo  "buscandoMesEDiaDaSemana <br>".$query;

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
         FROM webmartminas.Web_Montagem_Escala_Diaria_PDV a
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
class lojas
{

    public function recuperacaoDasLojas($oracle)
    {
        $lista = array();
        $query = "SELECT NROEMPRESA
        FROM max_empresa
        WHERE STATUS = 'A'
            AND NROEMPRESA not in (8,9,7,203)
        ORDER BY NROEMPRESA ASC";
        echo $query;
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function recuperacaoDosSetoresDaLoja($oracle)
    {
        $lista = array();
        $query = "SELECT DISTINCT(a.departamento) from webmartminas.web_escala_mensal a
         ORDER BY A.DEPARTAMENTO ASC
         ";
        echo $query;
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
    //gerenciamento GPR
    public function gerencGPRselectNaEscalaDiaria($oracle, $dataInicial, $dataFinal, $loja)
    {
        $lista = array();
        $query = "SELECT A.MATRICULA,
        A.NOME,
        A.LOJA,
       TO_CHAR(a.DIASELECIONADO, 'DD/MM/YYYY', 'NLS_DATE_LANGUAGE=PORTUGUESE') as DIASELECIONADOFormatado,
        A.STATUS,
        A.HORAENTRADA,
        A.HORASAIDA,
        A.HORAINTERVALO,
        A.DATAINCLUSAO,
        A.USUINCLUSAO           
        from webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED a 
        where a.diaselecionado BETWEEN TO_DATE('$dataInicial', 'YYYY-MM-DD') and TO_DATE('$dataFinal', 'YYYY-MM-DD')
        and a.loja = '$loja' 
        ";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo $query
    }
    //diaria
    public function DadosAPartirDaEscalaMensal($oracle, $dia, $lojaDaPessoaLogada, $mesSelecionado, $departamento)
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
         TO_CHAR(a.mesSelecionado, 'Month- yyyy', 'NLS_DATE_LANGUAGE=PORTUGUESE') as mesSelecionadoFormatado,
         a.status
         FROM webmartminas.WEB_ESCALA_MENSAL a
         where loja = '$lojaDaPessoaLogada'
          and a.mesSelecionado = TO_DATE('$mesSelecionado','YYYY-MM')
          and a.status = 'F'
          and a.departamento like '%$departamento%'
          order by a.nome asc
          ";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo $query;
    }


    public function recuperaDadosDaEscalaIntermed($oracle, $matricula, $nome, $loja, $diaselecionado)
    {
        $lista = array();
        $query = "SELECT * from webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED a 
        WHERE a.matricula = '$matricula'
        and trim(a.nome) = '$nome'
        and a.loja = $loja   
         --and a.diaselecionado = TO_DATE('$diaselecionado', 'YYYY-MM-DD')   
        AND a.dataInclusao = (SELECT MAX(dataInclusao)
                                from webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED a 
                                WHERE a.matricula = '$matricula'
                                and trim(a.nome) = '$nome'
                                and a.loja = $loja 
                            )";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // Echo $query;
    }



    //mensal
    public function informacaoPessoaLogada($TotvsOracle, $cpf, $lojaDaPessoaLogada)
    {
        $lista = array();
        $query = "SELECT 
        F.CHAPA,
        F.NOME,
        F.CODPESSOA,
        SUBSTR(PF.NOME, 0, 11) AS FUNCAO,
        SUBSTR(PF.NOME, 0, 8) AS FUNCAOLIDER,
        SUBSTR(S.DESCRICAO, 1, 3) AS LOJA,
        P.CPF,
        SUBSTR(S.DESCRICAO,7) AS SETOR,
        TO_CHAR(F.DATAADMISSAO, 'YYYY-MM-DD') AS DATA_ADMISSAO,
        trim(REPLACE(REPLACE(REPLACE(PF.NOME,
                                             'ENCARREGADO DE',
                                             ''),
                                     'TRAINEE',
                                     ''),
                             'ENCARREGADO',
                             '')) AS DEPARTAMENTO2,
        CASE 
            WHEN DTAVISOPREVIOTRAB IS NULL THEN TO_CHAR(DTAVISOPREVIO, 'YYYY-MM-DD') 
            ELSE TO_CHAR(DTAVISOPREVIOTRAB, 'YYYY-MM-DD') 
            END AS DATA_AVISO_PREVIO
            FROM 
                PFUNC F 
            JOIN 
                PSECAO S ON F.CODCOLIGADA = S.CODCOLIGADA AND F.CODSECAO = S.CODIGO
            JOIN 
                PPESSOA P ON P.CODIGO = F.CODPESSOA
            JOIN 
                PFUNCAO PF ON F.CODCOLIGADA = PF.CODCOLIGADA AND F.CODFUNCAO = PF.CODIGO 
            JOIN 
                (SELECT MAX(DATAADMISSAO) AS DATAADMISSAO, CODPESSOA, CODCOLIGADA FROM PFUNC GROUP BY CODCOLIGADA, CODPESSOA) DTRECENTE 
            ON 
                F.CODCOLIGADA = DTRECENTE.CODCOLIGADA AND F.CODPESSOA = DTRECENTE.CODPESSOA AND F.DATAADMISSAO = DTRECENTE.DATAADMISSAO
            WHERE 
                F.CODCOLIGADA = 1 
                and f.CODCOLIGADA = 1 AND f.CODSITUACAO = 'A'
                and P.CPF = $cpf
            ORDER BY   F.NOME
            ";
        $resultado = oci_parse($TotvsOracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo $query;
    }
    public function informacoesOperadoresDeCaixa($TotvsOracle, $lojaDaPessoaLogada, $setorDaPessoaLogada)
    {

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
                    
                WHERE   PFUNC.CODCOLIGADA = 1  
                AND PFUNC.CODSITUACAO IN ('A','F') 
                        AND SUBSTR(PSECAO.DESCRICAO,1,3) =  '$lojaDaPessoaLogada'
                        and  SUBSTR(PSECAO.DESCRICAO, 6, 99) like '%$setorDaPessoaLogada%'
                        and  SUBSTR(PFUNCAO.NOME, 1, 99) NOT LIKE '%APRENDIZ%'
                        and  SUBSTR(PFUNCAO.NOME, 1, 99) NOT LIKE '%ENCARREGADO%'
                ORDER BY PFUNC.NOME ";
        $resultado = oci_parse($TotvsOracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo $query;
    }

    //agendamento de ferias escala mensal
    public function recuperaFuncionariosQueTiveramFeriasAgendadas($oracle, $loja, $DEPARTAMENTO)
    {
        $lista = array();
        $query = " SELECT DISTINCT(nome), 
        a.cargo, 
        a.matricula,
        A.HORAENTRADA, A.HORASAIDA, A.HORAINTERVALO,
        TO_CHAR(TO_DATE(a.datainicioferiasprogramadas, 'DD-MON-RR'), 'YYYY-MM-DD') AS datainicioferiasprogramadas,
       TO_CHAR(TO_DATE(a.datafimferiasprogramadas, 'DD-MON-RR'), 'YYYY-MM-DD') AS datafimferiasprogramadas
        from web_escala_mensal a 
        where a.loja= '$loja'
        and a.departamento= '$DEPARTAMENTO'
        and a.datainicioferiasprogramadas || a.datafimferiasprogramadas is not null
        order by a.nome asc
        ";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // Echo $query;
    }



    //pdv

    public function buscaFuncEHorarioDeTrabalhoManha($oracle, $lojaDaPessoaLogada, $diaDeHojeComAspas, $mesSelecionadoDaEscalaMensal, $departamento, $diaMesEAnoAtual)
    {
        $lista = array();
        $query = "SELECT DISTINCT a.matricula,
        a.nome,
        a.loja,
        a.messelecionado,
        a.horaentrada,
        a.horasaida,
        a.horaintervalo
        FROM webmartminas.WEB_ESCALA_MENSAL a
        WHERE a.loja = $lojaDaPessoaLogada
        AND a.$diaDeHojeComAspas IS NULL
        AND a.messelecionado = TO_DATE('$mesSelecionadoDaEscalaMensal', 'YYYY-MM')
        AND a.horaentrada BETWEEN '05:00' AND '11:59'
        and a.departamento like '%$departamento%'
        AND a.cargo BETWEEN 'OPERADOR DE CAIXA' AND 'OPERADOR DE LOJA'
        and a.matricula not in
        (SELECT b.matricula
        FROM webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED b
        WHERE b.diaselecionado = TO_DATE('$diaMesEAnoAtual', 'YYYY-MM-DD')
        AND b.loja = $lojaDaPessoaLogada)
        UNION
        SELECT b.matricula,
        b.nome,
        b.loja,
        b.diaselecionado as diaSelecionado,
        b.horaentrada,
        b.horasaida,
        b.horaintervalo
        FROM webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED b
        WHERE b.diaselecionado = TO_DATE('$diaMesEAnoAtual', 'YYYY-MM-DD')
        AND b.loja = $lojaDaPessoaLogada 
        AND b.horaentrada BETWEEN '05:00' AND '11:59'
        order by nome asc
        ";
        // echo $query;
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo  $lista;
    }
    public function buscaFuncEHorarioDeTrabalhoTarde($oracle, $lojaDaPessoaLogada, $diaDeHojeComAspas, $mesSelecionadoDaEscalaMensal, $departamento, $diaMesEAnoAtual)
    {
        $lista = array();
        $query = "SELECT DISTINCT a.matricula,
        a.nome,
        a.loja,
        a.messelecionado,
        a.horaentrada,
        a.horasaida,
        a.horaintervalo
        FROM webmartminas.WEB_ESCALA_MENSAL a
        WHERE a.loja = $lojaDaPessoaLogada
        AND a.$diaDeHojeComAspas IS NULL
        AND a.messelecionado = TO_DATE('$mesSelecionadoDaEscalaMensal', 'YYYY-MM')
        AND a.horaentrada BETWEEN '12:00' AND '14:00'
        and a.departamento like '%$departamento%'
        AND a.cargo BETWEEN 'OPERADOR DE CAIXA' AND 'OPERADOR DE LOJA'
        and a.matricula not in
        (SELECT b.matricula
        FROM webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED b
        WHERE b.diaselecionado = TO_DATE('$diaMesEAnoAtual', 'YYYY-MM-DD')
        AND b.loja = $lojaDaPessoaLogada)
        UNION
        SELECT b.matricula,
        b.nome,
        b.loja,
        b.diaselecionado as diaSelecionado,
        b.horaentrada,
        b.horasaida,
        b.horaintervalo
        FROM webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED b
        WHERE b.diaselecionado = TO_DATE('$diaMesEAnoAtual', 'YYYY-MM-DD')
        AND b.loja = $lojaDaPessoaLogada 
        AND b.horaentrada BETWEEN '12:00' AND '14:00'
        order by nome asc
        ";
        // echo $query;
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo  $lista;
    }


    public function filtroFuncionariosCadastradosManha($oracle, $dia, $i, $lojaDaPessoaLogada)
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
            FROM webmartminas.ESCALA_PDV_Manha a
            where a.numpdv = $i
            and to_char(a.diaselecionado,'YYYY-MM-DD') = '$dia'
            and  status = 'A'
            and a.loja = $lojaDaPessoaLogada
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

    public function  filtroFuncionariosCadastradoTarde($oracle, $dia, $i, $lojaDaPessoaLogada)
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
            FROM webmartminas.ESCALA_PDV_TARDE a
            where a.numpdv = $i
            and to_char(a.diaselecionado,'YYYY-MM-DD') = '$dia'
            and  status = 'A'
            and a.loja = $lojaDaPessoaLogada
            ORDER BY a.numpdv ASC
            
         ";

        // echo $query;
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }

    public function funcionariosDisponiveisNoDia($oracle, $diaComAspas, $mesSelecionado, $departamento, $dataPesquisada, $loja)
    {
        $lista = array();
        $query =  "SELECT DISTINCT a.matricula,
        a.nome,
        a.loja,
        a.messelecionado,
        a.horaentrada,
        a.horasaida,
        a.horaintervalo
        FROM webmartminas.WEB_ESCALA_MENSAL a
        WHERE a.loja = $loja
        AND a.$diaComAspas IS NULL
        AND a.messelecionado = TO_DATE('$mesSelecionado', 'YYYY-MM')
        and a.status = 'F'
        and a.departamento like '%$departamento%'
        AND a.cargo BETWEEN 'OPERADOR DE CAIXA' AND 'OPERADOR DE LOJA'
        and a.matricula not in
        (SELECT b.matricula
        FROM webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED b
        WHERE b.diaselecionado = TO_DATE('$dataPesquisada', 'YYYY-MM-DD')
        AND b.loja = $loja)
        UNION
        SELECT b.matricula,
        b.nome,
        b.loja,
        b.diaselecionado as diaSelecionado,
        b.horaentrada,
        b.horasaida,
        b.horaintervalo
        FROM webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED b
        WHERE b.diaselecionado = TO_DATE('$dataPesquisada', 'YYYY-MM-DD')
        AND b.loja = $loja


        ";
        // echo $query."<br>";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // print_r($lista);
    }

    public function FuncsJaEscaladosMANHA($oracle, $diaSelecionado, $lojaDaPessoaLogada)
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
                    FROM webmartminas.ESCALA_PDV_Manha a
                    where  to_char(a.diaselecionado, 'YYYY-MM-DD') = '$diaSelecionado'
                    and status = 'A'
                    and loja ='$lojaDaPessoaLogada'
                  
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
    public function FuncsJaEscaladosTARDE($oracle, $diaSelecionado, $lojaDaPessoaLogada)
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
                    FROM webmartminas.ESCALA_PDV_TARDE a
                    where  to_char(a.diaselecionado, 'YYYY-MM-DD') = '$diaSelecionado'
                    and status = 'A'
                    and loja ='$lojaDaPessoaLogada'
                  
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
        $query = "SELECT * FROM webmartminas.WEB_ESCALA_MENSAL a
        WHERE a.matricula = '$matricula'
        AND a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM')
        AND a.loja = $loja ";

        echo "select para verificaCadastroNaEscalaMensal  <br><br> " . $query;


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
        echo "</br>" + $retorno;
    }

    public function verificaCadastroNaEscalaMensal2($oracle, $matricula, $mesPesquisado, $loja, $departamentoFunc)
    {
        $lista = array();
        global  $retorno;
        $query = "SELECT * FROM webmartminas.WEB_ESCALA_MENSAL a
        WHERE a.matricula = '$matricula'
        AND a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM')
        AND a.loja = $loja
        AND a.departamento = '$departamentoFunc' 
        ";




        $parse = oci_parse($oracle, $query);

        oci_execute($parse);
        oci_fetch_assoc($parse);

        if (oci_num_rows($parse) >= 1) {

            $retorno = 1;
        }
        if (oci_num_rows($parse) < 1) {

            $retorno = 0;
        }

        // echo "</br>".$retorno;
    }

    public function verificaCadastroNaEscalaMensa1($oracle, $matricula, $mesPesquisado,)
    {
        $lista = array();
        global  $retorno;
        $query = "SELECT * FROM webmartminas.WEB_ESCALA_MENSAL a
        WHERE a.matricula = $matricula
        AND a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM') ";


        $resultado = oci_parse($oracle, $query);

        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }

    public function verificaSeOMesSelecionadoTemAlgumFuncionarioEscalado($oracle, $mesPesquisado, $loja, $departamento)
    {
        $lista = array();
        global  $retorno1;
        $query = "SELECT * FROM webmartminas.WEB_ESCALA_MENSAL a
        WHERE a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM')
        and a.departamento like '%$departamento%'
        and a.loja = '$loja'
        ";
        // echo "<br> verificaSeOMesSelecionadoTemAlgumFuncionarioEscalado".$query;

        $parse = oci_parse($oracle, $query);

        $retorno1 = oci_execute($parse);

        if ($retorno1) {
            if (oci_fetch($parse)) {
                $retorno1 = "JÁ EXISTE CADASTO.";
            } else {
                $retorno1 = "NÃO EXISTE CADASTRO.";
            }
        } else {
            // Erro na consulta
            echo "Erro na consulta.";
        }
        while ($row = oci_fetch_assoc($parse)) {
            array_push($lista, $row);
        }
        return $lista;
        echo "</br>" + $retorno1;
    }

    //verificacao bloqueio da escala mensal

    public function verificaPessoaNaEscalaMensal($oracle, $matricula, $mesPesquisado, $loja, $departamento)
    {
        $lista = array();
        global  $retorno;
        $query =
            <<<SQL
                        WITH FOLGA AS
                        (SELECT E.MATRICULA,
                                E.NOME,
                                E.LOJA,
                                E.CARGO,
                                E.MESSELECIONADO,
                                E.STATUS,
                                E.HORAENTRADA,
                                E.HORASAIDA,
                                E.HORAINTERVALO,
                                E."01",
                                E."02",
                                E."03",
                                E."04",
                                E."05",
                                E."06",
                                E."07",
                                E."08",
                                E."09",
                                E."10",
                                E."11",
                                E."12",
                                E."13",
                                E."14",
                                E."15",
                                E."16",
                                E."17",
                                E."18",
                                E."19",
                                E."20",
                                E."21",
                                E."22",
                                E."23",
                                E."24",
                                E."25",
                                E."26",
                                E."27",
                                E."28",
                                E."29",
                                E."30",
                                E."31",
                                E.DATAINCLUSAO,
                                E.USUINCLUSAO,
                                E.USUFINALIZACAOESCALA,
                                E.USUNOVALIBERACAOESCALA,
                                E.INCLUSAODOMESANTERIOR,
                                E.DEPARTAMENTO,
                                E.DATAINICIOFERIASPROGRAMADAS,
                                E.DATAFIMFERIASPROGRAMADAS,
                                TRIM(REPLACE(CASE
                                                WHEN E."01" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                '1/'
                                            END || ' / ' || CASE
                                                WHEN E."02" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                2
                                            END || '/ ' || CASE
                                                WHEN E."03" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                3
                                            END || '/ ' || CASE
                                                WHEN E."04" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                4
                                            END || '/ ' || CASE
                                                WHEN E."05" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                5
                                            END || '/ ' || CASE
                                                WHEN E."06" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                6
                                            END || '/ ' || CASE
                                                WHEN E."07" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                7
                                            END || '/ ' || CASE
                                                WHEN E."08" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                8
                                            END || '/ ' || CASE
                                                WHEN E."09" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                9
                                            END || '/ ' || CASE
                                                WHEN E."10" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                10
                                            END || '/ ' || CASE
                                                WHEN E."11" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                11
                                            END || '/ ' || CASE
                                                WHEN E."12" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                12
                                            END || '/ ' || CASE
                                                WHEN E."13" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                13
                                            END || '/ ' || CASE
                                                WHEN E."14" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                14
                                            END || '/ ' || CASE
                                                WHEN E."15" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                15
                                            END || '/ ' || CASE
                                                WHEN E."16" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                16
                                            END || '/ ' || CASE
                                                WHEN E."17" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                17
                                            END || '/ ' || CASE
                                                WHEN E."18" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                18
                                            END || '/ ' || CASE
                                                WHEN E."19" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                19
                                            END || '/ ' || CASE
                                                WHEN E."20" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                20
                                            END || '/ ' || CASE
                                                WHEN E."21" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                21
                                            END || '/ ' || CASE
                                                WHEN E."22" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                22
                                            END || '/ ' || CASE
                                                WHEN E."23" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                23
                                            END || '/ ' || CASE
                                                WHEN E."24" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                24
                                            END || '/ ' || CASE
                                                WHEN E."25" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                25
                                            END || '/ ' || CASE
                                                WHEN E."26" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                26
                                            END || '/ ' || CASE
                                                WHEN E."27" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                27
                                            END || '/ ' || CASE
                                                WHEN E."28" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                28
                                            END || '/ ' || CASE
                                                WHEN E."29" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                CASE WHEN CAST(TO_CHAR(LAST_DAY(MESSELECIONADO),'DD') AS INTEGER) >= 29 THEN 29 ELSE NULL END
                                            END || '/ ' || CASE
                                                WHEN E."30" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                CASE WHEN CAST(TO_CHAR(LAST_DAY(MESSELECIONADO),'DD') AS INTEGER) >= 30 THEN 30 ELSE NULL END
                                            END || '/ ' || CASE
                                                WHEN E."31" IN ('FA', 'FF', 'FD', 'DSR') THEN
                                                CASE WHEN CAST(TO_CHAR(LAST_DAY(MESSELECIONADO),'DD') AS INTEGER) >= 31 THEN 31 ELSE NULL END
                                            END,
                                            ' /',
                                            '')) DIAS,
                                            CAST(TO_CHAR(LAST_DAY(MESSELECIONADO),'DD') AS INTEGER) DIA_FINAL
                                            FROM WEBMARTMINAS.WEB_ESCALA_MENSAL E
                                                where  E.Matricula = '{$matricula}'
                                                AND TO_CHAR(E.MESSELECIONADO,'YYYY-MM')= '{$mesPesquisado}'
                                                and E.departamento = '{$departamento}'
                                                and E.loja = '{$loja}' 
                            
                            
                            )
                        SELECT E.MATRICULA,
                            E.NOME,
                            E.LOJA,
                            E.CARGO,
                            E.MESSELECIONADO,
                            E.STATUS,
                            E.HORAENTRADA,
                            E.HORASAIDA,
                            E.HORAINTERVALO,
                            E."01",
                            E."02",
                            E."03",
                            E."04",
                            E."05",
                            E."06",
                            E."07",
                            E."08",
                            E."09",
                            E."10",
                            E."11",
                            E."12",
                            E."13",
                            E."14",
                            E."15",
                            E."16",
                            E."17",
                            E."18",
                            E."19",
                            E."20",
                            E."21",
                            E."22",
                            E."23",
                            E."24",
                            E."25",
                            E."26",
                            E."27",
                            E."28",
                            E."29",
                            E."30",
                            E."31",
                            E.DATAINCLUSAO,
                            E.USUINCLUSAO,
                            E.USUFINALIZACAOESCALA,
                            E.USUNOVALIBERACAOESCALA,
                            E.INCLUSAODOMESANTERIOR,
                            E.DEPARTAMENTO,
                            E.DATAINICIOFERIASPROGRAMADAS,
                            E.DATAFIMFERIASPROGRAMADAS,
                            CASE
                                WHEN DIAS2 - DIAS1 > 7 THEN
                                'ALERTA'
                                WHEN DIAS3 - DIAS2 > 7 THEN
                                'ALERTA'
                                WHEN DIAS4 - DIAS3 > 7 THEN
                                'ALERTA'
                                WHEN DIAS5 - DIAS4 > 7 THEN
                                'ALERTA'
                                WHEN DIAS6 - DIAS5 > 7 THEN
                                'ALERTA'
                                ELSE
                                ''
                            END AS VALIDA
                        FROM (SELECT F.DIAS,
                                    F.*,
                                    NVL(CAST(REPLACE(SUBSTR(DIAS, 1, 3), '/') AS INTEGER), 1) AS DIAS1,
                                    NVL(CAST(REPLACE(SUBSTR(DIAS, 4, 3), '/') AS INTEGER),
                                        CAST(REPLACE(SUBSTR(DIAS, 1, 3), '/') AS INTEGER) + 8) AS DIAS2,
                                    NVL(CAST(REPLACE(SUBSTR(DIAS, 7, 3), '/') AS INTEGER),
                                        CAST(REPLACE(SUBSTR(DIAS, 4, 3), '/') AS INTEGER) + 8) AS DIAS3,
                                    NVL(CAST(REPLACE(SUBSTR(DIAS, 10, 4), '/') AS INTEGER),
                                        CAST(REPLACE(SUBSTR(DIAS, 7, 3), '/') AS INTEGER) + 8) AS DIAS4,
                                    CASE WHEN NVL(CAST(REPLACE(SUBSTR(DIAS, 14, 4), '/') AS INTEGER),
                                                    CAST(REPLACE(SUBSTR(DIAS, 10, 4), '/') AS INTEGER) + 8) <= DIA_FINAL THEN
                                    NVL(CAST(REPLACE(SUBSTR(DIAS, 14, 4), '/') AS INTEGER),
                                        CAST(REPLACE(SUBSTR(DIAS, 10, 4), '/') AS INTEGER) + 8) ELSE DIA_FINAL END AS DIAS5,
                                    
                                    CASE WHEN NVL(CAST(REPLACE(SUBSTR(DIAS, 14, 4), '/') AS INTEGER),
                                                    CAST(REPLACE(SUBSTR(DIAS, 10, 4), '/') AS INTEGER) + 8) > DIA_FINAL THEN                    
                                            NVL(CAST(REPLACE(SUBSTR(DIAS, 18, 4), '/') AS INTEGER),
                                                CAST(REPLACE(SUBSTR(DIAS, 14, 4), '/') AS INTEGER) + 8) ELSE
                                    DIA_FINAL END AS DIAS6,
                                    DIA_FINAL AS DIAS7
                                FROM FOLGA F
                                WHERE CARGO NOT LIKE '%APRENDIZ%'
                                AND DIAS IS NOT NULL) E                 
                                            
    
SQL;
        // echo "<br><br><br><br><br><br><br>" . $query;

        $parse = oci_parse($oracle, $query);
        $retorno = oci_execute($parse);
        while ($row = oci_fetch_assoc($parse)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function verificaNomeDaPessoaComBaseNaMatricula($TotvsOracle, $matricula)
    {

        $lista = array();
        global  $retorno;
        $query =
<<<SQL
                SELECT DISTINCT PFUNC.CHAPA,
                PFUNC.NOME AS NOME,
                SUBSTR(PFUNCAO.NOME, 0, 11) AS NOME2,
                PFUNCAO.NOME AS CARGO,
                PCCUSTO.NOME AS DESCRICAO,
                PCCUSTO.NOME AS CENTROCUSTO,
                SUBSTR(PCCUSTO.NOME, 0, 8) AS TESTE,
                PCCUSTO.CODCCUSTO,
                PSECAO.CODIGO AS NROEMPRESA,
                PPESSOA.CPF,
                PFUNC.CODSITUACAO AS CODSITUACAO,
                PFUNC.DATAADMISSAO AS DATA_ADMISSAO,
                SUBSTR(PSECAO.DESCRICAO, 1, 3) AS LOJA,
                SUBSTR(PSECAO.DESCRICAO, 6) AS SETOR1,
                TRIM(SUBSTR(PSECAO.DESCRICAO, 6)) AS SETORSEMESPACO,
                TO_CHAR(PFUNC.DATAADMISSAO, 'YYYY-MM-DD') AS DATA_ADMISSAO_CONVERTIDA,
                PSECAO.DESCRICAO AS SETOR,
                PFUNCAO.CODIGO AS CODFUNCAO,
                TRUNC(MONTHS_BETWEEN(SYSDATE, PFUNC.DATAADMISSAO) / 12) AS ANOS_TEMPO_CASA,
                TRUNC(MONTHS_BETWEEN(SYSDATE, PFUNC.DATAADMISSAO)) - CASE
                WHEN TO_NUMBER(TO_CHAR(SYSDATE, 'DD')) <
                    TO_NUMBER(TO_CHAR(PFUNC.DATAADMISSAO, 'DD')) THEN
                1
                ELSE
                0
                END - 12 *
                TRUNC(MONTHS_BETWEEN(SYSDATE, PFUNC.DATAADMISSAO) / 12) AS MESES_TEMPO_CASA,
                ABS(EXTRACT(DAY FROM PFUNC.DATAADMISSAO) -
                    EXTRACT(DAY FROM SYSDATE)) AS DIAS_TEMPO_CASA,
                TRUNC(MONTHS_BETWEEN(SYSDATE, FUNCAO.DATAMUDANCA) / 12) AS ANOS_TEMPO_FUNCAO,
                TRUNC(MONTHS_BETWEEN(SYSDATE, FUNCAO.DATAMUDANCA)) - CASE
                WHEN TO_NUMBER(TO_CHAR(SYSDATE, 'DD')) <
                    TO_NUMBER(TO_CHAR(FUNCAO.DATAMUDANCA, 'DD')) THEN
                1
                ELSE
                0
                END - 12 *
                TRUNC(MONTHS_BETWEEN(SYSDATE, FUNCAO.DATAMUDANCA) / 12) AS MESES_TEMPO_FUNCAO,
                ABS(EXTRACT(DAY FROM FUNCAO.DATAMUDANCA) -
                    EXTRACT(DAY FROM SYSDATE)) AS DIAS_TEMPO_FUNCAO

            FROM PFUNC

            INNER JOIN PPESSOA
            ON PFUNC.CODPESSOA = PPESSOA.CODIGO

            INNER JOIN PFRATEIOFIXO
            ON PFUNC.CODCOLIGADA = PFRATEIOFIXO.CODCOLIGADA
            AND PFUNC.CHAPA = PFRATEIOFIXO.CHAPA

            INNER JOIN PCCUSTO
            ON PFRATEIOFIXO.CODCOLIGADA = PCCUSTO.CODCOLIGADA
            AND PFRATEIOFIXO.CODCCUSTO = PCCUSTO.CODCCUSTO

            INNER JOIN PFUNCAO
            ON PFUNC.CODCOLIGADA = PFUNCAO.CODCOLIGADA
            AND PFUNC.CODFUNCAO = PFUNCAO.CODIGO

            INNER JOIN PSECAO
            ON PFUNC.CODCOLIGADA = PSECAO.CODCOLIGADA
            AND PFUNC.CODSECAO = PSECAO.CODIGO

            LEFT JOIN (SELECT PFHSTFCO.CODCOLIGADA,
                    PFHSTFCO.CHAPA,
                    MAX(PFHSTFCO.DTMUDANCA) AS DATAMUDANCA
            FROM PFHSTFCO
            WHERE PFHSTFCO.CODCOLIGADA = 1
            GROUP BY PFHSTFCO.CODCOLIGADA, PFHSTFCO.CHAPA) FUNCAO
            ON PFUNC.CODCOLIGADA = FUNCAO.CODCOLIGADA
            AND PFUNC.CHAPA = FUNCAO.CHAPA

            WHERE PFUNC.CODCOLIGADA = 1
            AND PFUNC.CHAPA LIKE ('{$matricula}')


SQL;
      //  echo "<br><br><br><br><br><br><br>" . $query;

        $parse = oci_parse($TotvsOracle, $query);
        $retorno = oci_execute($parse);
        while ($row = oci_fetch_assoc($parse)) {
            array_push($lista, $row);
        }
        return $lista;
    }


    public function verificaSeAEscalaMensalEstaFinalizada($oracle, $mesPesquisado, $loja, $departamento)
    {

        $lista = array();
        global  $retorno;
        $query = "SELECT * from webmartminas.WEB_ESCALA_MENSAL a
        where a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM') 
        and a.departamento = '$departamento'
        and a.loja = $loja
        and status IS NULL OR TRIM(status) = '' 
        
        
        ";
        // echo "<br> verificaSeAEscalaMensalEstaFinalizada :". $query;
        //VERIFICA SE TEM LINHAS COM STATUS VAZIO NA TABELA E SE TIVER RETORNA NÃO FINALIZADA 
        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);

        if ($retorno) {
            if (oci_fetch($parse)) {
                $retorno = "NÃO FINALIZADA.";
            } else {
                $retorno = "JÁ FINALIZADA.";
            }
        } else {
            // Erro na consulta
            echo "Erro na consulta.";
        }
        while ($row = oci_fetch_assoc($parse)) {
            array_push($lista, $row);
        }
        return $lista;
    }

    //diaria

    public function verificaAlteracaoNoHorarioDiario($oracle, $matricula, $diaselecionado, $nome, $loja)
    {
        global  $retorno;
        $query = "SELECT * from webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED a 
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
        $query = "SELECT * FROM webmartminas.$tabela a
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
    public function insertEscalaMensal(
        $oracle,
        $tabela,
        $dia,
        $matricula,
        $nome,
        $loja,
        $cargoFunc,
        $mesPesquisado,
        $horarioEntradaFunc,
        $horarioSaidaFunc,
        $horarioIntervaloFunc,
        $opcaoSelect,
        $usuarioLogado,
        $departamentoFunc,
        $DATAINICIOFERIASPROGRAMADAS,
        $DATAFIMFERIASPROGRAMADAS
    ) {

        $query = "INSERT INTO webmartminas.$tabela (
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
           usuinclusao,
           Departamento,
           datainicioferiasprogramadas,
           datafimferiasprogramadas
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
           '$usuarioLogado',
          '$departamentoFunc',
          TO_DATE('$DATAINICIOFERIASPROGRAMADAS', 'YYYY-MM-DD'),
          TO_DATE('$DATAFIMFERIASPROGRAMADAS', 'YYYY-MM-DD')        
        )";
        echo $query;
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
    }

    public function insertEscalaMensalProximoMes($oracle, $tabela, $dia,  $matricula, $nome, $loja, $cargoFunc, $mesPesquisado, $horarioEntradaFunc, $horarioSaidaFunc,  $horarioIntervaloFunc, $opcaoSelect, $usuarioLogado, $inclusaodomesanterior, $departamentoFunc)
    {

        $query = "INSERT INTO webmartminas.$tabela (
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
           usuinclusao,
           inclusaodomesanterior,
           departamento
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
           '$usuarioLogado',
           '$inclusaodomesanterior',
           '$departamentoFunc'
        )";
        echo $query;
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
    }
    //diaria

    public function insertNaTblIntermediariaEscalaDiaria($oracle, $matricula, $nome, $loja, $diaSelecionado, $horaEntrada, $horaSaida, $horaIntervalo, $usuInclusao)
    {
        $query = "INSERT INTO webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED
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
            return false;
        }

        echo $query;
    }
    //escala pdv
    public function insertTabelaFuncManha($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV, $loja, $status)
    {
        $query = "INSERT INTO  webmartminas.ESCALA_PDV_MANHA (
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
        $query = "INSERT INTO  webmartminas.ESCALA_PDV_TARDE (
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
        $query = "INSERT INTO webmartminas.Web_Montagem_Escala_Diaria_PDV (
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
        $query = "UPDATE webmartminas.WEB_ESCALA_MENSAL a
         SET
            -- datainclusao = SYSDATE,
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

    public function updateDeFuncionariosNaEscalaMensalFerias($oracle, $usuarioLogado, $mesPesquisado, $nome, $dia, $opcaoSelect, $matricula, $loja, $DATAINICIOFERIASPROGRAMADAS, $DATAFIMFERIASPROGRAMADAS)
    {
        $query = "UPDATE webmartminas.WEB_ESCALA_MENSAL a
         SET
            -- datainclusao = SYSDATE,
            usuinclusao = '$usuarioLogado',
            mesSelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM'),
            nome = '$nome',
            $dia = '$opcaoSelect',
            LOJA = '$loja',
            DATAINICIOFERIASPROGRAMADAS =   TO_DATE('$DATAINICIOFERIASPROGRAMADAS', 'YYYY-MM-DD'),
            DATAFIMFERIASPROGRAMADAS =     TO_DATE('$DATAFIMFERIASPROGRAMADAS', 'YYYY-MM-DD')       
               WHERE a.matricula = '$matricula'
          and messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM')
          and loja = $loja";
        echo $query;
        $parse = oci_parse($oracle, $query);

        oci_execute($parse);
    }
    // bloqueio da escala mensal
    public function bloqueiaEscalaMensal($oracle,  $status,  $usuarioQueFinalizou, $mesPesquisado, $loja)
    {

        $query = "UPDATE webmartminas.WEB_ESCALA_MENSAL a
        SET 
        status = '$status', 
        usufinalizacaoescala = ' $usuarioQueFinalizou'
        where a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM') 
        and status IS NULL OR TRIM(status) = '' 
        and a.loja = $loja";



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

        // echo $query;
    }

    public function liberaEscalaMensal($oracle, $status, $usuarioQueliberouNovamenteAEscala, $mesPesquisado, $loja, $Departamento)
    {

        $query = "UPDATE webmartminas.WEB_ESCALA_MENSAL a
        SET 
        status = '$status', 
        usuNovaLiberacaoEscala = '$usuarioQueliberouNovamenteAEscala'
        where a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM') 
         and TRIM(status) = 'F'
        and a.loja = $loja
        and a.departamento ='$Departamento' ";

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
    }

    //diaria
    public function updateDeFuncionariosNaEscalaIntermediaria($oracle, $horaEntrada, $horaSaida, $horaIntervalo, $usuInclusao, $matricula, $nome, $loja, $diaSelecionado)
    {
        $query = "UPDATE webmartminas.WEB_ESCALA_DIARIA_HR_INTERMED
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
        $query = "UPDATE webmartminas.$tabela SET
        MATRICULA = '$matricula',
        NOME = '$nome',
        HORAENTRADA = '$entrada',
        HORASAIDA = '$saida',
        HORAINTERVALO = '$intervalo',
        USUINCLUSAO = '$usuarioLogado',
        -- DATAINCLUSAO = sysdate,
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
        $query = "UPDATE webmartminas.Web_Montagem_Escala_Diaria_PDV SET  
            
             DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD'),
            -- DATAINCLUSAO = sysdate,
            USUINCLUSAO = '$usuarioLogado',
            $periodoDeHoras =  '$nome'

            WHERE NUMPDV = '$numPDV'
            and loja = '$loja'
            and status = 'A'
           and DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD')
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

        echo $query;
    }

    public function updateRemocaoEscalaPDV($oracle, $tabela, $numPDV, $dataPesquisa, $loja)
    {
        global  $retorno;
        $query = " UPDATE webmartminas.$tabela
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

class Porcentagem
{
    public function quantidadesDePessoasPorHoraCalculo($oracle, $quantidadePorDiaDeFuncionarios, $lojaDaPessoaLogada, $dataAtual)
    {
        $lista = array();
        $query = "SELECT T.NROEMPRESA,
        T.DTA_PROGRAMAR,
        T.DTA_REFERENCIA,
        T.HORA,
        T.QTD_BIPS,
        T.QTD_CUPONS,
        T.PART_BIPS,
        ROUND($quantidadePorDiaDeFuncionarios * 6 * (PART_BIPS / 100)) AS QTD_FUNCIONARIOS
        FROM (DW_DMT.AGG_FATO_VENDAREF_LOJA@PDB_DW) T
        WHERE T.NROEMPRESA = $lojaDaPessoaLogada
            AND T.DTA_PROGRAMAR =  TO_DATE('$dataAtual', 'YYYY-MM-DD')
     ";
        // echo $query;

        $resultado = oci_parse($oracle, $query);

        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
}

class log_escala_mensal
{

    public function log_liberacao_escala_mensal($oracle, $loja, $mesSelecionadoParaLiberacao, $usuariologado, $MotivoLiberacaoEscala)
    {

        $sql2 = 'SELECT webmartminas.S_Log_escala_mensal.Nextval from dual';
        $parse = oci_parse($oracle, $sql2);
        oci_execute($parse);
        while (($row = oci_fetch_assoc($parse)) != false) {

            $id = $row['NEXTVAL'];
        }



        $query = "INSERT INTO webmartminas.WEB_ESCALA_MENSAL_log (
            id, 
            loja, 
            messelecionado, 
            dataliberacao,
             usuliberacao,
             MotivoLiberacao
             )
            VALUES (
            $id,
            $loja,
             TO_DATE('$mesSelecionadoParaLiberacao', 'YYYY-MM'),
            sysdate,
             '$usuariologado',
             '$MotivoLiberacaoEscala'
        
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
        echo  $query;
    }
}
