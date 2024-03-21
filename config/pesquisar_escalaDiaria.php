<?php
include "../../base/conexao_martdb.php";
include "../../base/conexao_TotvzOracle.php";
include "php/CRUD_geral.php";
session_start();

$lojaSelecionada = $_POST['lojaSelecionada'];
$dataPesquisaInicial = $_POST['dataPesquisaInicial'];
$dataPesquisaFinal = $_POST['dataPesquisaFinal'];
$dadosFunc = new Funcionarios();
$dadosEscalaDiaria = $dadosFunc->gerencGPRselectNaEscalaDiaria($oracle, $dataPesquisaInicial, $dataPesquisaFinal, $lojaSelecionada);
// print_r($dadosEscalaDiaria);
?>
<table id="table11" class="table table-bordered table-striped text-center " style="width:100%">
    <thead style="background: linear-gradient(to right, #00a451, #052846 85%); color:white;">
        <tr>
            <th class="text-center">Nome</th>
            <th class="text-center">Matricula</th>
            <th class="text-center">Loja</th>
            <th class="text-center">Data Alteração</th>
            <th class="text-center">Hora Entrada</th>
            <th class="text-center">Hora Intervalo</th>
            <th class="text-center">Hora Saida</th>
            <th class="text-center">Usuario que alterou</th>
        </tr>
    </thead>
    <tbody style="background-color: #DCDCDC;">
        <?php
        foreach ($dadosEscalaDiaria as $ROWconsultaNomeFunc) :
        ?>
            <tr class="trr">
                <td class="text-center td nomeFuncionario" scope="row" id="nome">
                    <?= $ROWconsultaNomeFunc['NOME'] ?>
                </td>
                <td class=" text-center td matriculaFunc">
                    <?= $ROWconsultaNomeFunc['MATRICULA'] ?>
                </td>
                <td class="text-center td cargo" scope="row" id="cargo">
                    <?= $ROWconsultaNomeFunc['LOJA'] ?>
                </td>
                <td class="text-center td " scope="row" id="">
                    <?= $ROWconsultaNomeFunc['DIASELECIONADOFORMATADO'] ?>
                </td>
                <td class="text-center td horaEntrada " scope="row">
                    <?= $ROWconsultaNomeFunc['HORAENTRADA'] ?>
                </td>
                <td class="text-center td horaSaida horarioIntervalo" scope="row">
                    <?= $ROWconsultaNomeFunc['HORASAIDA'] ?>
                </td>
                <td class="text-center td horaSaida horarioSaidaFunc" scope="row">
                    <?= $ROWconsultaNomeFunc['HORAINTERVALO'] ?>
                </td>
                <td class="text-center td" scope="row">
                    <?= $ROWconsultaNomeFunc['USUINCLUSAO'] ?>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
    </tbody>
</table>

<Script>
    $('#table11').DataTable({
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
        dom: 'Bfrtip',
        scrollY: 450,
        scrollX: true,
        scrollXInner: "100%",
        scrollCollapse: true,
        searching: true,
        "paging": true,
        "info": false,
        order: [
                [0, "asc"],[3, "asc"]
            ],
        "lengthMenu": [
            [40],
        ],
    });
</Script>