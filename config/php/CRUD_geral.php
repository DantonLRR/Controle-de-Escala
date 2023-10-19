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

    //montagem de escala PDV
public function escalaDiariaDePDV($oracle,$numPDV,$dataAtual){

    $lista = array();
    $query = "SELECT *
    FROM Web_Montagem_Escala_Diaria_PDV a
    WHERE NUMPDV = '$numPDV'
    AND a.diaselecionado = TO_DATE('$dataAtual' , 'YYYY-MM-DD')
    ORDER BY NUMPDV ASC
    ";

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
    //mensal

    public function informacoesOperadoresDeCaixa($dbDB, $lojaDaPessoaLogada)
    {

        $lista = array();
        $statement = $dbDB->prepare("SELECT DISTINCT
 

        PFUNC.CHAPA AS MATRICULA,



        PFUNC.NOME,



        CONVERT(VARCHAR(10), PFUNC.DATAADMISSAO, 103) AS 'DATA ADMISSAO',



        SUBSTRING (PSECAO.DESCRICAO, 6,99) AS DEPARTAMENTO,



        SUBSTRING (PSECAO.DESCRICAO, 0,4) AS LOJA,



        PFUNC.CODFUNCAO AS 'CODIGO FUNCAO',



        PFUNCAO.NOME AS FUNCAO,

     ((SELECT DISTINCT 
                LEFT(FORMAT(MIN(ABATHOR.BATIDA) / 60,'00')+':'+FORMAT((MIN(ABATHOR.BATIDA) % 60.0),'00'),5)

            FROM ABATHOR

            INNER JOIN AHORARIO ON
                ABATHOR.CODCOLIGADA = AHORARIO.CODCOLIGADA
                AND ABATHOR.CODHORARIO = AHORARIO.CODIGO

            WHERE ABATHOR.TIPO = 0 AND ABATHOR.INDICE = 1	AND ABATHOR.NATUREZA = 0 AND AHORARIO.CODCOLIGADA = PFUNC.CODCOLIGADA AND AHORARIO.CODIGO = PFUNC.CODHORARIO) + ' - ' +



     (SELECT DISTINCT 
                LEFT(FORMAT(MIN(ABATHOR.BATIDA) / 60,'00')+':'+FORMAT((MIN(ABATHOR.BATIDA) % 60.0),'00'),5)

            FROM ABATHOR

            INNER JOIN AHORARIO ON
                ABATHOR.CODCOLIGADA = AHORARIO.CODCOLIGADA
                AND ABATHOR.CODHORARIO = AHORARIO.CODIGO

            WHERE ABATHOR.TIPO = 0 AND ABATHOR.INDICE = 1	AND ABATHOR.NATUREZA = 1 AND AHORARIO.CODCOLIGADA = PFUNC.CODCOLIGADA AND AHORARIO.CODIGO = PFUNC.CODHORARIO) + ' - ' +



     (SELECT DISTINCT 
                LEFT(FORMAT(MAX(ABATHOR.BATIDA) / 60,'00')+':'+FORMAT((MAX(ABATHOR.BATIDA) % 60.0),'00'),5)
     FROM ABATHOR

            INNER JOIN AHORARIO ON
                ABATHOR.CODCOLIGADA = AHORARIO.CODCOLIGADA
                AND ABATHOR.CODHORARIO = AHORARIO.CODIGO

            WHERE ABATHOR.TIPO = 0 AND ABATHOR.INDICE = 1	AND ABATHOR.NATUREZA = 0 AND AHORARIO.CODCOLIGADA = PFUNC.CODCOLIGADA AND AHORARIO.CODIGO = PFUNC.CODHORARIO) + ' - ' +



     (SELECT DISTINCT 
                LEFT(FORMAT(MAX(ABATHOR.BATIDA) / 60,'00')+':'+FORMAT((MAX(ABATHOR.BATIDA) % 60.0),'00'),5)

            FROM ABATHOR

            INNER JOIN AHORARIO ON
                ABATHOR.CODCOLIGADA = AHORARIO.CODCOLIGADA
                AND ABATHOR.CODHORARIO = AHORARIO.CODIGO

            WHERE ABATHOR.TIPO = 0 AND ABATHOR.INDICE = 1	AND ABATHOR.NATUREZA = 1 AND AHORARIO.CODCOLIGADA = PFUNC.CODCOLIGADA AND AHORARIO.CODIGO = PFUNC.CODHORARIO)) AS HORARIO



     FROM PFUNC (NOLOCK)



     INNER JOIN PFUNCAO (NOLOCK) ON
                        (PFUNC.CODCOLIGADA = PFUNCAO.CODCOLIGADA
                        AND PFUNC.CODFUNCAO = PFUNCAO.CODIGO)



     INNER JOIN AHORARIO (NOLOCK) ON
                        (PFUNC.CODCOLIGADA = AHORARIO.CODCOLIGADA
                        AND PFUNC.CODHORARIO = AHORARIO.CODIGO)



     INNER JOIN PSECAO (NOLOCK) ON
                        (PFUNC.CODCOLIGADA = PSECAO.CODCOLIGADA
                        AND PFUNC.CODSECAO = PSECAO.CODIGO)



     WHERE 
     PFUNC.CODCOLIGADA = 1 
     AND PFUNC.CODSITUACAO <> 'D' 
     AND SUBSTRING(PSECAO.DESCRICAO,0,4) LIKE $lojaDaPessoaLogada
     and PFUNCAO.NOME = 'OPERADOR DE CAIXA'
 


     ORDER BY 
     PFUNC.NOME
    

    
     ");


        $statement->execute();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {



            array_push($lista, $row);
        }

        return $lista;
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
}

class Verifica
{
    //mensal

    public function verificaCadastroNaEscalaMensal($oracle, $matricula, $mesPesquisado,$loja)
    {
        $lista = array();
        global  $retorno;
        $query = "SELECT * FROM WEB_ESCALA_MENSAL a
        WHERE a.matricula = $matricula
        AND a.messelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM')
        AND a.loja = $loja ";

        echo $query;

        $parse = oci_parse($oracle, $query);

        $retorno = oci_execute($parse);

        if ($retorno) {
            if (oci_fetch($parse)) {
                $retorno = "Já existem dados.";

            } else {
                $retorno = "Não existem dados.";
            }
        
        
        }
        
        else {
            // Erro na consulta
            echo "Erro na consulta.";
        }
        while ($row = oci_fetch_assoc($parse)) {
            array_push($lista, $row);
        }
        return $lista;


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
    public function verificaExistenciaNumPDV($oracle, $tabela, $dataPesquisa, $numPDV)
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
        echo $query;
    }

    //montagem escala pdv

    public function verificaMontagemEscalaPDV($oracle, $numPDV, $dataPesquisa,$loja)
    {

        global  $retorno;
        $query = "SELECT * from Web_Montagem_Escala_Diaria_PDV a
        WHERE a.NUMPDV = $numPDV
        and a.diaselecionado =TO_DATE( '$dataPesquisa','YYYY-MM-DD')
        and a.loja = $loja
        ";
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

        // echo $query;
    }
}


class Insert
{
    // mensal
    public function insertEscalaMensal($oracle, $tabela, $dia, $usuarioLogado, $mesPesquisado, $nome, $opcaoSelect,$matricula)
    {

        $query = "INSERT INTO $tabela (
        datainclusao, 
        usuinclusao, 
        mesSelecionado,
        nome,
        $dia,
        matricula
     ) VALUES (
        SYSDATE,
        '$usuarioLogado',
        TO_DATE('$mesPesquisado', 'YYYY-MM'),
        '$nome',
        '$opcaoSelect',
        $matricula
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
    public function insertTabelaFuncManha($oracle, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV,$loja)
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
        LOJA
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
        $loja
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
        echo $query;
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

    //montagem de escala PDV
    public function insertMontagemEscalaPDV($oracle, $periodoDeHoras, $numPDV, $dataPesquisa, $usuarioLogado, $nome,$loja)
    {
        global  $retorno;
        $query = "INSERT INTO Web_Montagem_Escala_Diaria_PDV (
            NUMPDV,
            DIASELECIONADO,
            DATAINCLUSAO,
            USUINCLUSAO,
            $periodoDeHoras,
            LOJA
            ) 
            VALUES (
            '$numPDV',
            TO_DATE( '$dataPesquisa','YYYY-MM-DD'),
            sysdate,
            '$usuarioLogado',
            '$nome',
            '$loja'
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
}



class Update
{
    //mensal
    public function updateDeFuncionariosNaEscalaMensal($oracle,$usuarioLogado, $mesPesquisado, $nome,$dia,$opcaoSelect, $matricula)
    {
        $query = "UPDATE WEB_ESCALA_MENSAL a SET
            datainclusao = SYSDATE,
            usuinclusao = '$usuarioLogado',
            mesSelecionado = TO_DATE('$mesPesquisado', 'YYYY-MM'),
            nome = '$nome',
            $dia = '$opcaoSelect'
         WHERE a.matricula = $matricula"; // Substitua $id pelo valor adequado
        echo $query;
        $parse = oci_parse($oracle, $query);

        oci_execute($parse);
    }





    //pdv
    public function updateDeFuncionariosNoPDV($oracle, $tabela, $matricula, $nome, $entrada, $saida, $intervalo, $usuarioLogado, $dataPesquisa, $numPDV,$loja)
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
      AND loja = $loja";
        $parse = oci_parse($oracle, $query);

        oci_execute($parse);
        echo $query;
    }


    //montagem de escala PDV


    public function updateMontagemEscalaPDV($oracle, $numPDV, $dataPesquisa, $usuarioLogado,  $periodoDeHoras, $nome,$loja)
    {
        global  $retorno;
        $query = "UPDATE Web_Montagem_Escala_Diaria_PDV SET  
            
             DIASELECIONADO = TO_DATE('$dataPesquisa', 'YYYY-MM-DD'),
            DATAINCLUSAO = sysdate,
            USUINCLUSAO = '$usuarioLogado',
            $periodoDeHoras =  '$nome'

            WHERE NUMPDV = '$numPDV'
            and loja = '$loja'
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
}
