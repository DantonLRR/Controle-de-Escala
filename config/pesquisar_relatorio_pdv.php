<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";
$InformacaoDosDias = new Dias();
$dataPesquisada = $_POST['dataPesquisa'];


$horarios = array();
for ($i = 7; $i <= 21; $i++) {
    $horarios[] = sprintf("%02d:00", $i);
}

?>


<table id="table2" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">

    <thead style="background-color: #00a550; color: white;">
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
            $dadosEscalaDiariaDePDV = $InformacaoDosDias->escalaDiariaDePDV($oracle, $i, $dataPesquisada);
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



<script>
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