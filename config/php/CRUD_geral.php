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
    public function informacoesEscalaDiaria($oracle, $dia, $matricula, $loja, $mesPesquisado)
    {
        $lista = array();
        $query = "SELECT B.NOME, nvl(A.$dia,'') AS $dia, B.CARGO
        FROM webmartminas.WEB_ESCALA_MENSAL a,web_usuario_hc b
        where a.matricula(+) = b.chapa
        AND B.EMPRESA = $loja
        AND B.CHAPA = $matricula
        AND B.CARGO LIKE '%OPERADOR DE CAIXA%'
        and to_char(b.datavigencia, 'YYYY-MM') = '2023-06'
      ";


        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;

        echo $query;
    }

    public function informacoesEscalaDiaria2($oracle, $matricula, $loja, $mesPesquisado)
    {
        $lista = array();
        $query = "select * from WEB_ESCALA_MENSAL a    
        WHERE a.loja = $loja 
        and  a.messelecionado=TO_DATE('$mesPesquisado', 'YYYY-MM')
        ";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;


        // echo $query;
    }


    //mensal

    public function informacoesOperadoresDeCaixa($TotvsOracle, $lojaDaPessoaLogada)
    {

        $lista = array();
        $query = "SELECT DISTINCT
        PFUNC.CHAPA,
        PFUNC.NOME AS NOME,
        PFUNCAO.NOME AS CARGO,
        PCCUSTO.NOME AS CENTROCUSTO,
        PCCUSTO.CODCCUSTO,
        PSECAO.CODIGO AS NROEMPRESA,
        PPESSOA.CPF,
        PFUNC.SALARIO AS SALARIO,
        TO_CHAR(PFUNC.SALARIO, 'L999G999D99', 'NLS_NUMERIC_CHARACTERS='',.'' NLS_CURRENCY=''R$''') AS FORMATOMOEDA,
        PFUNC.CODSITUACAO AS CODSITUACAO,
        PFUNC.DATAADMISSAO AS DATA_ADMISSAO,
        SUBSTR(PSECAO.DESCRICAO, 1, 4) AS LOJA,
        SUBSTR(PSECAO.DESCRICAO, 6) AS SETOR1,
        TRIM(SUBSTR(PSECAO.DESCRICAO, 6)) AS SETORSEMESPACO,
        TO_DATE(PFUNC.DATAADMISSAO, 'YYYY-MM-DD') AS DATA_ADMISSAO_CONVERTIDA,
        PSECAO.DESCRICAO AS SETOR,
        PFUNCAO.CODIGO AS CODFUNCAO,
        TRUNC(MONTHS_BETWEEN(SYSDATE, PFUNC.DATAADMISSAO) / 12) AS ANOS_TEMPO_CASA,
        TRUNC(MONTHS_BETWEEN(SYSDATE, PFUNC.DATAADMISSAO)) - 
        CASE WHEN TO_NUMBER(TO_CHAR(SYSDATE, 'DD')) < TO_NUMBER(TO_CHAR(PFUNC.DATAADMISSAO, 'DD')) THEN 1 ELSE 0 END - 
        12 * TRUNC(MONTHS_BETWEEN(SYSDATE, PFUNC.DATAADMISSAO) / 12) AS MESES_TEMPO_CASA,
        ABS(EXTRACT(DAY FROM PFUNC.DATAADMISSAO) - EXTRACT(DAY FROM SYSDATE)) AS DIAS_TEMPO_CASA,
        TRUNC(MONTHS_BETWEEN(SYSDATE, FUNCAO.DATAMUDANCA) / 12) AS ANOS_TEMPO_FUNCAO,
        TRUNC(MONTHS_BETWEEN(SYSDATE, FUNCAO.DATAMUDANCA)) - 
        CASE WHEN TO_NUMBER(TO_CHAR(SYSDATE, 'DD')) < TO_NUMBER(TO_CHAR(FUNCAO.DATAMUDANCA, 'DD')) THEN 1 ELSE 0 END - 
        12 * TRUNC(MONTHS_BETWEEN(SYSDATE, FUNCAO.DATAMUDANCA) / 12) AS MESES_TEMPO_FUNCAO,
        ABS(EXTRACT(DAY FROM FUNCAO.DATAMUDANCA) - EXTRACT(DAY FROM SYSDATE)) AS DIAS_TEMPO_FUNCAO
            FROM PFUNC
            INNER JOIN PPESSOA ON PFUNC.CODPESSOA = PPESSOA.CODIGO
            INNER JOIN PFRATEIOFIXO ON PFUNC.CODCOLIGADA = PFRATEIOFIXO.CODCOLIGADA AND PFUNC.CHAPA = PFRATEIOFIXO.CHAPA
            INNER JOIN PCCUSTO ON PFRATEIOFIXO.CODCOLIGADA = PCCUSTO.CODCOLIGADA AND PFRATEIOFIXO.CODCCUSTO = PCCUSTO.CODCCUSTO
            INNER JOIN PFUNCAO ON PFUNC.CODCOLIGADA = PFUNCAO.CODCOLIGADA AND PFUNC.CODFUNCAO = PFUNCAO.CODIGO
            INNER JOIN PSECAO ON PFUNC.CODCOLIGADA = PSECAO.CODCOLIGADA AND PFUNC.CODSECAO = PSECAO.CODIGO
            LEFT JOIN (
                SELECT PFHSTFCO.CODCOLIGADA, PFHSTFCO.CHAPA, MAX(PFHSTFCO.DTMUDANCA) AS DATAMUDANCA
                FROM PFHSTFCO
                WHERE PFHSTFCO.CODCOLIGADA = 1
                GROUP BY PFHSTFCO.CODCOLIGADA, PFHSTFCO.CHAPA
            ) FUNCAO ON PFUNC.CODCOLIGADA = FUNCAO.CODCOLIGADA AND PFUNC.CHAPA = FUNCAO.CHAPA
            WHERE PFUNC.CODCOLIGADA = 1
            AND PFUNCAO.NOME LIKE '%OPERADOR DE CAIXA%'
            AND SUBSTR(PSECAO.DESCRICAO, 1, 3) = '$lojaDaPessoaLogada'
            AND  PFUNC.CODSITUACAO = 'A'
             ORDER BY PFUNC.NOME ASC
      ";
        $resultado = oci_parse($TotvsOracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        echo $query;
    }

    //pdv
    public function buscaFuncEHorarioDeTrabalhoManha($oracle, $dataSelecionadaNoFiltro)
    {
        $lista = array();
        $query = "select * from HorariosFuncControleDeEscala a    
        WHERE a.horaentrada  BETWEEN '07:00' AND '10:00' 
        and  a.matricula not in(select b.matricula from escala_pdv_manha b where b.diaselecionado=TO_DATE('$dataSelecionadaNoFiltro', 'YYYY-MM-DD'))";

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        // echo  $lista;
    }
    public function buscaFuncEHorarioDeTrabalhoTarde($oracle, $dataSelecionadaNoFiltro)
    {
        $lista = array();
        $query = "select * from HorariosFuncControleDeEscala a    
        WHERE a.horaentrada  BETWEEN '12:00' AND '14:00' 
        and  a.matricula not in(select b.matricula from escala_pdv_tarde b where b.diaselecionado=TO_DATE('$dataSelecionadaNoFiltro', 'YYYY-MM-DD'))";


        $resultado = oci_parse($oracle, $query);
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

        // echo $query;

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
        // echo $query;
        // echo "</br>" + $retorno;
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
    public function insertEscalaMensal($oracle, $tabela, $dia, $usuarioLogado, $mesPesquisado, $nome, $opcaoSelect, $matricula, $loja)
    {

        $query = "INSERT INTO $tabela (
        datainclusao, 
        usuinclusao, 
        mesSelecionado,
        nome,
        $dia,
        matricula,
        LOJA
     ) VALUES (
        SYSDATE,
        '$usuarioLogado',
        TO_DATE('$mesPesquisado', 'YYYY-MM'),
        '$nome',
        '$opcaoSelect',
        '$matricula',
        $loja 
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

        //  echo $query;
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
         WHERE a.matricula = '$matricula'";
        //echo $query;
        $parse = oci_parse($oracle, $query);

        oci_execute($parse);
    }





    //pdv
    public function updateDeFuncionariosNoPDV($oracle, $tabela, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV, $loja, $status)
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
        LOJA = '$loja',
        STATUS = '$status'
      WHERE NUMPDV = '$numPDV'
      AND loja = $loja
      and DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD')
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

    public function updateRemocaoEscalaPDV($oracle, $numPDV, $dataPesquisa, $loja)
    {
        global  $retorno;
        $query = " UPDATE Web_Montagem_Escala_Diaria_PDV
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
        echo $retorno;
        echo $query;
    }
}
