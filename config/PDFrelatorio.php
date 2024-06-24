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
$quantidadePorDiaDeFuncionarios = $InformacaoFuncionarios->funcionariosDisponiveisNoDia($oracle, $diaDaPesquisaComAspas, $mesEAnoDaPesquisa, $dadosDeQuemEstaLogadoSetor, $dataPesquisada, $loja);

if (empty($quantidadePorDiaDeFuncionarios)) {
    $quantidadePorDiaDeFuncionariosImpressao = "Nenhum funcionario escalado para este dia,";
} else {
    $quantidadePorDiaDeFuncionariosImpressao = count($quantidadePorDiaDeFuncionarios);
    $quantidadeDePessoasEscaladas = $quantidadePorDiaDeFuncionariosImpressao;
}
if ($quantidadePorDiaDeFuncionariosImpressao == "Nenhum funcionario escalado para este dia,") {
} else {


?>
    <style>
        td {
            font-size: 10px !important;
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
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        .infoDaTabela {
            border: 1px solid #000;
            text-align: center;
        }

        .assinatura {
            height: 35px !important;
            /* Ajuste o valor conforme necessário */

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
    <table id="table2" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">
        <thead>
            <tr class="trr">
                <th> PDV </th>
                <?php
                foreach ($horarios as $row) :
                ?>
                    <th class="text-center" scope="row" id=""><?= $row ?></th>
                <?php

                endforeach
                ?>
            </tr>

        </thead>
        <tbody>
            <?php
            $j = 0;
            $qntPDV = array();
            for ($i = 1; $i <= 30; $i++) {
                $i;
                $dadosEscalaDiariaDePDV = $InformacaoDosDias->escalaDiariaDePDV($oracle, $i, $dataPesquisada, $loja);
                // PRINT_R( $dadosEscalaDiariaDePDV);
            ?>
                <tr class="trr">
                    <td scope="row" class="numerosPDVS infoDaTabela" id="">
                        <b> <?= $i ?></b>
                    </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["07:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["08:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["09:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["10:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["11:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["12:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["13:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["14:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["15:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["16:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["17:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["18:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["19:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["20:00"] ?? '' ?> </td>
                    <td class="text-center infoDaTabela" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["21:00"] ?? '' ?> </td>
                </tr>
                <tr class="trr">
                    <td scope="row" class="numerosPDVS" id="">
                        <b>Assinatura: </b>
                    </td>
                    <?php
                    for ($x = 1; $x <= 15; $x++) {
                    ?>
                        <td class="text-center assinatura" scope="row" id="" style="position: relative; height: 50px;">
                            <?php
                            if ($x != 8) {
                            ?>
                                <div style="border-bottom: 1px solid #000; position: absolute; top: 75%; left: 0; width: 100%; transform: translateY(-50%);"></div>
                            <?php
                            } else {
                            ?>
                                <b>Assinatura: </b>
                            <?php

                            }
                            ?>

                        </td>
                    <?php
                    }
                    ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

<?php
}

?>