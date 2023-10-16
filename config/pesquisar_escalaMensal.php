<?php
include "../../base/Conexao_teste.php";
include "../../base/conexao_tovs.php";
include "php/CRUD_geral.php";


$dataSelecionadaNoFiltro = $_POST['mesPesquisa'];
$mesAtual = date("Y-m");
$loja = $_POST['loja'];
$InformacaoDosDias = new Dias();
$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$dadosFunc = new Funcionarios();
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($dbDB, $loja);

?>

<input class="dataSelecionadaNoFiltro" id="loja" type="hidden" value="<?= $dataSelecionadaNoFiltro ?>">
<input class="dataAtual" id="mesAtual" type="hidden" value="<?= $mesAtual ?>">
<table id="table1" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
    <thead>

        <tr class="trr ">
            <th class="text-center theadColor" scope="row" style="width:150px">Funcionario</th>
            <th class="text-center theadColor" scope="row" style="width:150px">matricula</th>
            <?php
            foreach ($buscandoMesAno as $row) :
            ?>
                <th class="text-center numeroDiaDaSemana" scope="row"><?= $row['DIA'] ?></th>

            <?php
            endforeach
            ?>
        </tr>


    </thead>


    <tbody>




        <tr class="trr" id="quantDias">
            <td></td>
            <td></td>
            <?php
            foreach ($buscandoMesAno as $row) :
            ?>
                <td class="text-center diaDaSemana" scope="row"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>

            <?php
            endforeach
            ?>
        </tr>




        <?php
        foreach ($buscaNomeFuncionario as $nomeFunc) :
        ?>
            <tr class="trr">
                <td class="text-center funcionario" scope="row"><?= $nomeFunc['NOME'] ?></td>
                <td class="text-center matriculaFunc" scope="row"><?= $nomeFunc['MATRICULA'] ?></td>

                <?php
                $i = 1;
                foreach ($buscandoMesAno as $row) :
                ?>
                    <td class=" text-center " scope="row" id="">
                        <?php
                        $recuperaDadosVerificacao = new verifica();
                        $recuperacaoDedados = $recuperaDadosVerificacao->verificaCadastroNaEscalaMensa1($oracle, $nomeFunc['MATRICULA'], $dataSelecionadaNoFiltro);
                        if ($i < 10) {
                            $d = "0" . $i;
                        } else {
                            $d = $i;
                        }  ?>

                        <select class="estilezaSelect" name="" id="">
                            <option value=""><?= $recuperacaoDedados[0]["$d"] ?? '' ?></option>

                            <option value="F">F</option>
                            <option value="FA">FA</option>
                            <option value="V">V</option>

                        </select>
                    </td>
                <?php
                    $i++;
                endforeach
                ?>
            </tr>
        <?php
        endforeach
        ?>
    </tbody>


</table>

<Script>
    $('#dataPesquisa').on('change', function() {

        var mesPesquisa = $("#dataPesquisa").val();

        var mesAtual = $("#mesAtual").val();

        if (mesPesquisa == "") {
            mesPesquisa = mesAtual
        }
        criandoHtmlmensagemCarregamento("exibir");

        $.ajax({
            url: "config/pesquisar_escalaMensal.php",
            method: 'POST',
            data: 'mesPesquisa=' + mesPesquisa +
                "&loja=" +
                loja,
            success: function(mes_Pesquisado) {

                $('.atualizaTabela').empty().html(mes_Pesquisado);
                criandoHtmlmensagemCarregamento("ocultar");
            }
        });
    });


    $('select').on('change', function() {
        $('tr').removeClass('selecionado').css('background-color', '').css('color', '');
        var linha = $(this).closest('tr');
        var opcao = $(this).closest('.estilezaSelect');

        linha.addClass('selecionado');
        linha.css('background-color', '#00a550d0');
        linha.css('color', 'white');
        opcao.css('font-weight', 'bold');


    });


    var dataPesquisa = $(".dataSelecionadaNoFiltro").val();
var mesAtual = $("#mesAtual").val();

if (dataPesquisa < mesAtual) {
    $('.estilezaSelect').prop('disabled', true);
    $('.estilezaSelect').css('background-color', 'grey');
} else {
    $('.estilezaSelect').prop('disabled', false);
    $('.estilezaSelect').css('background-color', ''); 
}


</Script>   
<script>
    $('#table1').DataTable({
        dom: 'Bfrtip',
        scrollY: 450,
        scrollX: true,

        scrollXInner: "100%",
        scrollCollapse: true,
        searching: true,

        "paging": true,
        "info": false,
        "ordering": false,
        "lengthMenu": [
            [50],
            [50]
        ],
        fixedColumns: {
            left: 2,
        },
        buttons: [{
            text: 'Imprimir',
            className: 'estilizaBotao btn btnverde',
            extend: 'print',
            exportOptions: {

            }
        }, ],
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