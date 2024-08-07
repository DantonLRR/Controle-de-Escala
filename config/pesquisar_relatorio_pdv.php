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

$horarios = array();
for ($i = 7; $i <= 21; $i++) {
    $horarios[] =  $i;
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
<input type="hidden" value<?=$loja?> id="loja">

    <table id="table2" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">

        <thead style="background: linear-gradient(to right, #00a451, #052846 85%); color:white;">
            <tr class="trr">
                <th> PDV </th>
                <?php foreach ($horarios as $row) :
                    $proximaHora = $row + 1; ?>
                    <th class="text-center" scope="row" id="">
                        <?= "DE " . $row . "h ÀS " . $proximaHora . "h" ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>

        </thead>
        <tbody style="background-color: #DCDCDC;">
            <td></td>
            <?php
            for ($i = 7; $i <= 21; $i++) {
            ?>
                <td class="text-center recebeQuantPessoasPorPDV" scope="row" id="">

                </td>
            <?php

            }
            ?>
            <?php
            $j = 0;
            $qntPDV = array();
            for ($i = 1; $i <= 30; $i++) {
                $i;
                $dadosEscalaDiariaDePDV = $InformacaoDosDias->escalaDiariaDePDV($oracle, $i, $dataPesquisada, $loja);
                // PRINT_R( $dadosEscalaDiariaDePDV);
            ?>
                <tr class="trr">
                    <td scope="row" class="numerosPDVS" id="">
                        <?= $i ?>
                    </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["07:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["08:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["09:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["10:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["11:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["12:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["13:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["14:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["15:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["16:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["17:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["18:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["19:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["20:00"] ?? '' ?> </td>
                    <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["21:00"] ?? '' ?> </td>

                </tr>
            <?php

            }
            ?>
        </tbody>
    </table>

<?php
}

?>

<script type="module">
    var loja = $("#loja").val();
    import {
        criandoHtmlmensagemCarregamento,
        Toasty
    } from "../../../base/jsGeral.js";
    $('#table2').DataTable({
        scrollY: 400,
        scrollCollapse: true,
        searching: true,
        dom: 'Bfrtip',
        "paging": true,
        "info": false,
        "ordering": false,
        "lengthMenu": [
            [50],
            [50]
        ],
        buttons: [{
            text: '<i class="fa-solid fa-file-pdf"  style="color: #ffffff;"></i> PDF ',
            className: 'btnverde btn ',
            action: function() {
                criandoHtmlmensagemCarregamento("exibir");
                var dataPesquisa = $("#dataPesquisa").val();
                var dataAtual = $("#dataAtual").val();

                if (dataPesquisa == "") {
                    dataPesquisa = dataAtual
                }
                var diretorioDoPdf = "PDFrelatorio.php";
                $.ajax({
                    url: "config/gerarPdf.php",
                    method: 'POST',
                    data: 'dataPesquisa=' +
                        dataPesquisa +
                        "&loja=" +
                        loja +
                        "&diretorioDoPdf=" +
                        diretorioDoPdf,
                    xhrFields: {
                        responseType: "blob",
                    },
                    success: function(response) {
                        // Loading("ocultar");
                        criandoHtmlmensagemCarregamento("ocultar");
                        let blobUrl = URL.createObjectURL(response);
                        window.open(blobUrl, "_blank");
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        // Loading("ocultar");
                    },
                });

            }

        }],
        language: {
            "sEmptyTable": "Nenhum registro encontrado",

            "sInfo": " _START_ até _END_ de _TOTAL_ registros...  ",

            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",

            "sInfoFiltered": "(Filtrados de _MAX_ registros)",

            "sInfoPostFix": "",

            "sInfoThousands": ".",

            "sLengthMenu": "_MENU_ resultados por página",

            "sLoadingRecords": "Carregando...",

            "sProcessing": "Processando...",

            "sZeroRecords": "Nenhum registro encontrado",

            "sSearch": "Pesquisar",

            "oPaginate": {

                "sNext": "Próximo",

                "sPrevious": "Anterior",

                "sFirst": "Primeiro",

                "sLast": "Último"

            },
        },
    });
</script>