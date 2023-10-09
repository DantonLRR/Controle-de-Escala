<?php
$dataSelecionadaNoFiltro = $_GET['mesPesquisado'] ?? date("Y-m");

class Dias
{
    public function mesEAnoFiltro($oracle)
    {
        $lista = array();
        // $query = "SELECT TO_DATE(ADD_MONTHS(TRUNC(SYSDATE, 'YYYY'), LEVEL - 1), 'YYYY-MM') AS mes
        // FROM DUAL
        // CONNECT BY LEVEL <= 12";
        // $resultado = oci_parse($oracle, $query);
        // oci_execute($resultado);
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
}
class Funcionarios
{

    public function buscaFuncEHorarioDeTrabalhoManha($oracle)
    {
        $lista = array();
        $query = "select * from HorariosFuncControleDeEscala a 
        WHERE a.horaentrada  BETWEEN '07:00' AND '11:00'";

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        echo  $lista;
    }
    public function buscaFuncEHorarioDeTrabalhoTarde($oracle)
    {
        $lista = array();
        $query = "select * from HorariosFuncControleDeEscala a 
        WHERE a.horaentrada  BETWEEN '12:00' AND '22:00'";

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);
        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        echo  $lista;
    }
   



    public function filtroFuncionariosCadastradosManha($oracle, $dia,$i)
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

    public function  filtroFuncionariosCadastradoTarde($oracle, $dia,$i)
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
}






class Insert
{

    public function insertTabelaFuncManha($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa,$numPDV)
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
        NUMPDV
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
        '$numPDV'   
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





    public function insertTabelaFuncTarde($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa,$numPDV)
    {
        $query = "INSERT INTO  ESCALA_PDV_TARDE (
        MATRICULA,
        NOME,
        HORAENTRADA,
        HORASAIDA,
        HORAINTERVALO,
        USUINCLUSAO,
        DATAINCLUSAO,
        DIASELECIONADO
        NUMPDV
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
        '$numPDV'        
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
}
