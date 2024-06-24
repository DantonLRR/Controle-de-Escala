<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impressão Relatório PDV</title>
    <link rel="stylesheet" href="css/contrato.css">
</head>
<?php
include "../../base/conexao_martdb.php";
include "php/CRUD_geral.php";
include "../../base/conexao_TotvzOracle.php";
session_start();
$InformacaoDosDias = new Dias();
$dataPesquisada = $_POST['dataPesquisa'];
$partesData = explode('-', $dataPesquisada);
$diaDaPesquisaComAspas = ' "' . $partesData[2] . '"'; // Dia
$mesEAnoDaPesquisa = $partesData[0] . '-' . $partesData[1]; // Ano e Mês
$loja = $_POST['loja'];


$hoje = date("Y-m-d");
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');

// Cria o objeto DateTime com a data atual
$dataAtual = new DateTime();

// Formata a data de acordo com o formato desejado e a localidade definida
$dataFormatada = strftime('%d de %B de %Y', $dataAtual->getTimestamp());



$dataPesquisada = $_POST['dataPesquisa'];

// Criar um objeto DateTime a partir da data pesquisada
$date = new DateTime($dataPesquisada);

// Formatar a data no formato dd/mm/yyyy
$dataFormatadaMesReferencia = $date->format('d/m/Y');


$mesAtual = date("Y-m");
$mesAtualformatado = date("m/Y");


$horarios = array();
for ($i = 7; $i <= 21; $i++) {
    $horarios[] = sprintf("%02d:00", $i);
}

$InformacaoFuncionarios = new Funcionarios();
$verificaSeAPessoaLogadaEEncarregada = $InformacaoFuncionarios->informacaoPessoaLogada($TotvsOracle, $_SESSION['cpf'], $_SESSION['LOJA']);
// print_r($verificaSeAPessoaLogadaEEncarregada);
foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['SETOR'];
endforeach;



$hoje = date("Y-m-d");
$diaDeHoje = date("d");
$mesEAnoAtual = date("Y-m");


$diaDeHojeComAspas = '"' . $diaDeHoje . '"';

$dadosDeQuemEstaLogadoNome = '';
$dadosDeQuemEstaLogadoFuncao = '';
$dadosDeQuemEstaLogadoSetor = '';

$InformacaoDosDias = new Dias();
$InformacaoFuncionarios = new Funcionarios();
$verificaSeAPessoaLogadaEEncarregada = $InformacaoFuncionarios->informacaoPessoaLogada($TotvsOracle, $_SESSION['cpf'], $_SESSION['LOJA']);
// print_r($verificaSeAPessoaLogadaEEncarregada);
foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['SETOR'];
endforeach;
$calculoDeFuncionariosNecessariosPorHora = new Porcentagem();

$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$mesEAnoFiltro = $InformacaoDosDias->mesEAnoFiltro($oracle);

$FuncManha = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoManha($oracle, $_SESSION['LOJA'], $diaDeHojeComAspas, $mesEAnoAtual, $dadosDeQuemEstaLogadoSetor, $hoje);
// var_dump($FuncManha);
// echo "<br><br><br>";
$FuncEscaladosMANHA = $InformacaoFuncionarios->FuncsJaEscaladosMANHA($oracle, $hoje, $_SESSION['LOJA']);
// var_dump($FuncEscaladosMANHA);

// echo "<br><br><br>";
$naoRepetidosMANHA = array();

foreach ($FuncManha as $funcManha1) {
    $repetido = false;
    foreach ($FuncEscaladosMANHA as $funcEscalado) {
        if ($funcManha1['MATRICULA'] === $funcEscalado['MATRICULA']) {
            $repetido = true;
            break;
        }
    }
    if (!$repetido) {
        $naoRepetidosMANHA[] = $funcManha1;
    }
}

// var_dump($naoRepetidosMANHA);


$FuncTarde = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoTarde($oracle, $_SESSION['LOJA'], $diaDeHojeComAspas, $mesEAnoAtual, $dadosDeQuemEstaLogadoSetor, $hoje);
$FuncEscaladosTARDE = $InformacaoFuncionarios->FuncsJaEscaladosTARDE($oracle, $hoje, $_SESSION['LOJA']);
// var_dump($FuncEscaladosTARDE);
// echo"<br><br><br>";
$naoRepetidosTARDE = array();

foreach ($FuncTarde as $funcTarde2) {
    $repetidoTARDE = false;
    foreach ($FuncEscaladosTARDE as $funcEscalado) {
        if ($funcTarde2['MATRICULA'] === $funcEscalado['MATRICULA']) {
            $repetidoTARDE = true;
            break;
        }
    }
    if (!$repetidoTARDE) {
        $naoRepetidosTARDE[] = $funcTarde2;
    }
}
// var_dump($naoRepetidosTARDE);



$horarios = array();
for ($i = 7; $i <= 21; $i++) {
    $horarios[] = sprintf("%02d:00", $i);
}






?>
    <style>
        td {
            font-size: 14px !important;
            /* Ou qualquer tamanho que você preferir */
            font-family: Arial, sans-serif !important;
        }

        @page {
            footer: page-footer;
            margin-left: 5mm;
            /* Define a margem esquerda para 10mm */
            margin-right: 5mm;
            /* Define a margem direita para 15mm */
            margin-top: 5mm;
            /* Define a margem superior para 20mm */
            margin-bottom: 5mm;
            /* Define a margem inferior para 20mm */
        }

        #page-footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: left;
        }

        .page-number1:before {}

        /* Ajuste de outras partes do layout, se necessário */
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th{
            border: 1px solid #000;
           
        }table,
        .bordaDireita {
    border-right: 1px solid #000 !important;
     text-align: center;
}

    </style>

<?php
foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['SETOR'];
endforeach;
?>
<div id="page-footer">
    <span class="page-number1">
        <?php
        foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
        ?>
            <b>Escala de PDV Referente ao dia: <?= $dataFormatadaMesReferencia ?>.
            </b>
            Expedido dia <?= $dataFormatada ?> por:
            <b>
                <?php
                foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
                ?>
                    <?= ucfirst(strtolower($dadosDeQuemEstaLogadoNome)) ?>,
                    <?= ucfirst(strtolower($dadosDeQuemEstaLogadoFuncao)) ?>
                    <?= ucfirst(strtolower($rowVerificaEncarregado['DEPARTAMENTO2'])) ?>
                <?php
                endforeach
                ?>
            </b>
            Assinatura :__________________________
        <?php
        endforeach;
        ?>
    </span>
</div>
<table id="table1" class="table table-bordered table-striped text-center row-border order-colum">
    <thead>
        <tr class="trr">
            <th class="text-center" colspan="5">Manhã</th>
            <th class=" text-center" colspan="5">Tarde</th>
        </tr>
        <tr class="trr">
            <th>PDV</th>
            <th class="text-center">NOME</th>
            <th class="text-center">ENTRADA</th>
            <th class="text-center" >INTERVALO</th>
            <th class="text-center">SAIDA</th>
            <th class="">NOME</th>
            <th class="text-center">ENTRADA</th>
          
            <th class="text-center " >INTERVALO</th>
            <th class="text-center">SAIDA</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $qntPDV = array();
        for ($i = 1; $i <= 30; $i++) {
            $i;
            $horariosFuncManha = $InformacaoFuncionarios->filtroFuncionariosCadastradosManha($oracle, $hoje, $i, $loja);
            $horariosFuncTarde = $InformacaoFuncionarios->filtroFuncionariosCadastradoTarde($oracle, $hoje, $i, $loja);
            $totalManha = count($horariosFuncManha);
            $totalTarde = count($horariosFuncTarde);
        ?>
            <tr class="trr">
                <td scope="row" class="numerosPDVS" id="">
                    <?= $i ?>
                </td>
                <?php
                if (empty($horariosFuncManha)) {
                ?>
                    <!-- SE NÃO TIVER DADOS NO BANCO MOSTRA TODAS OPÇÕES DE FUNCIONARIOS -->
                  
                    <td scope="row" class="NomeFunc">
                        <?php
                        foreach ($naoRepetidosMANHA as $rowManha) :

                            $rowManha['NOME'];

                        endforeach;
                        ?>

                    </td>
                    <td scope="row" class="text-center horaEntrada1"></td>
                    <td scope="row" class="horaSaida1"></td>
                    <td scope="row" class="horaIntervalo1 bordaDireita"></td>
                    <?php
                } else {
                    foreach ($horariosFuncManha as $row2Manha) :
                        // print_r($horariosFuncManha);  
                    ?>
                        <!-- RETORNO DE DADOS EXISTENTE NO BANCO MOSTRA APENAS O FUNCIONARIO SELECIONADO -->
                        <!-- <td scope="row" class="Matricula1"><//?= $row2Manha['MATRICULA'] ?? '' ?></td> -->
                        <td scope="row" class="NomeFunc">
                            <?= $row2Manha['NOME'] ?? '' ?>
                        <td scope="row" class="text-center horaEntrada1"><?= $row2Manha['HORAENTRADA'] ?? '' ?></td>
                        <td scope="row" class="horaIntervalo1 "><?= $row2Manha['HORAINTERVALO'] ?? '' ?></td>
                        <td scope="row" class="horaSaida1 bordaDireita"><?= $row2Manha['HORASAIDA'] ?? '' ?></td>
                <?php
                    endforeach;
                } ?>
                <?php
                if (empty($horariosFuncTarde)) {
                ?>
                    <!-- SE NÃO TIVER DADOS NO BANCO MOSTRA TODAS OPÇÕES DE FUNCIONARIOS -->
                    <td scope="row" class="text-center nome2">
                        <?php
                        foreach ($naoRepetidosTARDE as $rowTarde) :
                             $rowTarde['NOME'] ;
                        endforeach;
                        ?>

                    </td>
                    <td scope="row" class="horaEntrada2"></td>
                    <td scope="row" class="horaSaida2"></td>
                    <td scope="row" class="horaIntervalo2"></td>
                    <?php
                } else {
                    foreach ($horariosFuncTarde as $row3Tarde) :
                        // print_r($horariosFuncTarde);                                                
                    ?>
                        <!-- RETORNO DE DADOS EXISTENTE NO BANCO MOSTRA APENAS O FUNCIONARIO SELECIONADO -->
                       
                        <td scope="row" class="text-center nome2"><?= $row3Tarde['NOME'] ?? '' ?>
                        <td scope="row" class="horaEntrada2"><?= $row3Tarde['HORAENTRADA'] ?? '' ?></td>
                        <td scope="row" class="horaIntervalo2"><?= $row3Tarde['HORAINTERVALO'] ?? '' ?></td>
                        <td scope="row" class="horaSaida2 bordaDireita"><?= $row3Tarde['HORASAIDA'] ?? '' ?></td>
                <?php
                    endforeach;
                } ?>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>