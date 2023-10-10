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

    public function buscaFuncEHorarioDeTrabalhoManha($oracle,$dataSelecionadaNoFiltro)
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
    public function buscaFuncEHorarioDeTrabalhoTarde($oracle,$dataSelecionadaNoFiltro)
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
}

class Verifica
{
    public function verificaExistenciaNumPDV($oracle,$tabela, $dataPesquisa, $numPDV)
    {
        global  $retorno;
        $query = "SELECT * FROM $tabela a
                 WHERE a.NUMPDV = '$numPDV'
                 AND a.DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD')";
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

    }
}


class Insert
{
// mensal
public function insertEscalaMensal($oracle,$tabela, $dia, $usuarioLogado, $mesPesquisado ,$nome,$opcaoSelect){
    
    $query = "INSERT INTO $tabela (
        datainclusao, 
        usuinclusao, 
        mesSelecionado,
        nome,
        $dia
    ) VALUES (
        SYSDATE,
        '$usuarioLogado',
        TO_DATE('$mesPesquisado', 'YYYY-MM'),
        '$nome',
        '$opcaoSelect'
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
    
}



//escala pdv
    public function insertTabelaFuncManha($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV)
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

    public function insertTabelaFuncTarde($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV)
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

//
    



}



Class Update{

    public function updateDeFuncionariosNoPDV($oracle,$tabela, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV){
        $query = "UPDATE $tabela SET
        MATRICULA = '$matricula',
        NOME = '$nome',
        HORAENTRADA = '$entrada',
        HORASAIDA = '$saida',
        HORAINTERVALO = '$intervalo',
        USUINCLUSAO = '$usuarioLogado',
        DATAINCLUSAO = sysdate,
        DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD'),
        NUMPDV = '$numPDV'
      WHERE NUMPDV = '$numPDV'";
       $parse = oci_parse($oracle, $query);

        oci_execute($parse);
    }
}