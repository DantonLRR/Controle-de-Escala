<?php
$dataSelecionadaNoFiltro = $_GET['mesPesquisado'] ??date("Y-m") ;

class Dias
{

    public function mesEAnoFiltro($oracle )
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



    public function buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro )
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
class Funcionarios{

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
        WHERE a.horaentrada  BETWEEN '12:00' AND '22:00'" ;


         

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
        echo  $lista;
    }

}





class Insert
{

    public function insertAceitarTipoAcordo($oracle, $matricula, $nome, $entrada, $saida, $intervalo)
    {
        $query = "INSERT INTO  ESCALA_PDV_MANHA (
        MATRICULA,
        NOME,
        HORAENTRADA,
        HORASAIDA,
        HORAINTERVALO,
        DATAINCLUSAO
    )
    VALUES (
        '$matricula',
        '$nome',
        '$entrada',
        '$saida',
        '$intervalo',
        sysdate        
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
    }








}
