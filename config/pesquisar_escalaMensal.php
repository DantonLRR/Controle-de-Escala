<?php
include "../../base/Conexao_teste.php";
include "../../base/conexao_TotvzOracle.php";
include "php/CRUD_geral.php";


$dataSelecionadaNoFiltro = $_POST['mesPesquisa'];
$mesAtual = date("Y-m");
$loja = $_POST['loja'];
$usuarioLogado = $_POST['usuarioLogado'];
$InformacaoDosDias = new Dias();
$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$dadosFunc = new Funcionarios();
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja);

$verifica = new verifica();
// echo $dataSelecionadaNoFiltro;
// echo"<br>".$loja;
$verificaSeJaExistemDados = $verifica->verificaSeAEscalaMensalEstaFinalizada($oracle, $dataSelecionadaNoFiltro, $loja);
// echo $retorno;
if ($retorno === "NÃO FINALIZADA.") {
    $statusDaTabelaPosPesquisa = "NÃO FINALIZADA.";
} else if ($retorno === "JÁ FINALIZADA.") {
    $statusDaTabelaPosPesquisa = "JÁ FINALIZADA.";
}


$recuperacaoDedados2 = $verifica->verificaSeOMesSelecionadoTemAlgumFuncionarioEscalado($oracle, $dataSelecionadaNoFiltro, $loja);
// ECHO $retorno1;
if ($retorno1 == "NÃO EXISTE CADASTRO.") {
    $statusDaTabelaPosPesquisa = "NÃO FINALIZADA.";
}


?>
<input class="" type="hidden" id="usuarioLogado" value="<?= $usuarioLogado ?>">
<input class="" type="hidden" id="loja" value="<?= $loja ?>">

<input class="dataSelecionadaNoFiltro" type="hidden" id="dataSelecionadaNoFiltro" value="<?= $dataSelecionadaNoFiltro ?>">
<input class="dataAtual" type="hidden" id="mesAtual" value="<?= $mesAtual ?>">
<table id="table1" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
    <thead>

        <tr class="trr ">
            <th class="text-center theadColor" scope="row" style="width:150px">Funcionario</th>
            <th class="text-center theadColor">Entrada</th>
            <th class="text-center theadColor">Saida</th>
            <th class="text-center theadColor">Intervalo</th>

            <th class="text-center theadColor" style="display:none"> cargo</th>
            <th class="text-center theadColor" scope="row" style="width:150px ;display:none">matricula</th>

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
            <td></td>
            <td></td>
            <td style="display:none"></td>
            <td style="display:none"></td>
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
                <td class="text-center horarioEntradaFunc" scope="row"><?= $nomeFunc['HORAENTRADA'] ?></td>
                <td class="text-center horarioSaidaFunc" scope="row"><?= $nomeFunc['HORASAIDA'] ?></td>
                <td class="text-center horarioIntervaloFunc" scope="row"><?= $nomeFunc['SAIDAPARAALMOCO'] ?></td>
                <td class="text-center cargo" style="display:none" scope="row"><?= $nomeFunc['FUNCAO'] ?></td>
                <td class="text-center matriculaFunc" style="display:none" scope="row"><?= $nomeFunc['MATRICULA'] ?></td>

                <?php
                $i = 1;
                foreach ($buscandoMesAno as $row) :
                ?>
                    <td class=" text-center " scope="row" id="">
                        <?php
                        $recuperaDadosVerificacao = new verifica();
                        $recuperacaoDedados = $recuperaDadosVerificacao->verificaCadastroNaEscalaMensa1($oracle,  $nomeFunc['MATRICULA'], $dataSelecionadaNoFiltro);
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
                            <option value=""></option>

                        </select>
                    </td>
                <?php
                    $i++;
                endforeach
                ?>
            </tr>
        <?php
        endforeach;

        ?>
    </tbody>

    <input class="statusDaTabela" type="hidden" id="statusDaTabelaPosPesquisa" value="<?= $statusDaTabelaPosPesquisa ?>">

</table>
<script type="module" defer>
    import {
        criandoHtmlmensagemCarregamento,
        Toasty
    } from "../../../../base/jsGeral.js";
    $(document).ready(function() {
        var statusDaTabelaPosPesquisa = $("#statusDaTabelaPosPesquisa").val();

        if (statusDaTabelaPosPesquisa === "JÁ FINALIZADA.") {
            criandoHtmlmensagemCarregamento("exibir");

            $('#table1').find('input, select, textarea, button').prop('disabled', true);
        }
        criandoHtmlmensagemCarregamento("ocultar");

    });


    $('#table1').on('change', '.estilezaSelect', function() {


        if ($(this).val() === 'FA') {
            var colIndex = $(this).closest('td').index();
            var mesPesquisa = $("#dataPesquisa").val();
            // alert(mesPesquisa)

            var mesAtual = $("#mesAtual").val();

            if (mesPesquisa == "") {
                mesPesquisa = mesAtual
            }

            var opcaoSelecionada = 'FA';
            var $tr = $(this).closest('tr');
            var funcionario = $tr.find('td.funcionario').text();
            var matriculaFunc = $tr.find('td.matriculaFunc').text();
            var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
            var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
            var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
            var cargoFunc = $tr.find('td.cargo').text();

            var colIndex = $(this).closest('td').index();
            var mesPesquisa = $("#dataPesquisa").val();


            var $selects = $(this).closest('tr').find('.estilezaSelect'); // Todos os selects da linha

            var indexAtual = $('#table1 thead tr.trr th').eq(colIndex).text();

            // alert(indexAtual)

            var indexUltimoDia = $selects.length;
            // alert(indexAtual);
            // alert(indexUltimoDia);



            var indexAtualNumero = parseInt(indexAtual, 10);

            for (var i = indexAtualNumero; i <= indexUltimoDia; i++) {
                var aux = i < 10 ? "0" + i : i.toString();

                var numeroDiaDaSemana = aux;

                // console.log(numeroDiaDaSemana);

                $.ajax({
                    url: "config/insertEUpdate_EscalaMensal.php",
                    method: 'get',
                    data: 'numeroDiaDaSemana=' +
                        numeroDiaDaSemana +
                        "&opcaoSelecionada=" +
                        opcaoSelecionada +
                        "&funcionario=" +
                        funcionario +
                        "&mesAtual=" +
                        mesAtual +
                        "&mesPesquisa=" +
                        mesPesquisa +
                        "&usuarioLogado=" +
                        usuarioLogado +
                        "&matriculaFunc=" +
                        matriculaFunc +
                        "&loja=" +
                        loja +
                        "&horarioEntradaFunc=" +
                        horarioEntradaFunc +
                        "&horarioSaidaFunc=" +
                        horarioSaidaFunc +
                        "&horarioIntervaloFunc=" +
                        horarioIntervaloFunc +
                        "&cargoFunc=" +
                        cargoFunc,

                    // dataType: 'json',
                    success: function(retorno) {
                        // console.log(retorno)

                    }
                });
                $selects.eq(i).val('FA');
            }


            // Calcular quantos dias faltam até o final do mês
            var diasRestantes = indexUltimoDia - indexAtual;
            var diasParaProximoMes = Math.min(30 - diasRestantes, diasRestantes);
            alert("faltaram  para o proximo mes: " + diasParaProximoMes);


            // Obtém o ano e o mês a partir da string mesPesquisa
            var ano = parseInt(mesPesquisa.split('-')[0]);
            var mes = parseInt(mesPesquisa.split('-')[1]);



            // Converte 'mesPesquisa' para um objeto Date
            var data = new Date(ano, mes - 1); // O mês em JavaScript começa em zero (janeiro é 0)

            // Verifica se a quantidade de dias é maior que 1 para avançar para o próximo mês
            if (diasParaProximoMes > 1) {
                // Adiciona a quantidade de dias à data atual
                data.setMonth(data.getMonth() + 1);
                ano = data.getFullYear();
                mes = data.getMonth() + 1;

                // Formata o novo mês para o formato 'AAAA-MM'
                mesPesquisa = ano + '-' + (mes < 10 ? '0' + mes : mes);
            }

            console.log(mesPesquisa); // Aqui você terá o valor do mês atualizado, seja o mesmo ou o próximo mês

            // Loop para contar até a quantidade de dias desejada
            for (var i = 1; i <= diasParaProximoMes; i++) {
                var numeroDiaDaSemana = i < 10 ? "0" + i : i.toString();
                console.log(numeroDiaDaSemana);
                if (diasParaProximoMes > 1) {
                    $.ajax({
                        url: "config/insertEUpdate_EscalaMensal.php",
                        method: 'get',
                        data: 'numeroDiaDaSemana=' +
                            numeroDiaDaSemana +
                            "&opcaoSelecionada=" +
                            opcaoSelecionada +
                            "&funcionario=" +
                            funcionario +
                            "&mesAtual=" +
                            mesAtual +
                            "&mesPesquisa=" +
                            mesPesquisa +
                            "&usuarioLogado=" +
                            usuarioLogado +
                            "&matriculaFunc=" +
                            matriculaFunc +
                            "&loja=" +
                            loja +
                            "&horarioEntradaFunc=" +
                            horarioEntradaFunc +
                            "&horarioSaidaFunc=" +
                            horarioSaidaFunc +
                            "&horarioIntervaloFunc=" +
                            horarioIntervaloFunc +
                            "&cargoFunc=" +
                            cargoFunc,

                        // dataType: 'json',
                        success: function(retorno) {
                            console.log(retorno)

                        }
                    });
                }
            }

        } else if ($(this).val() !== 'FA') {

            var colIndex = $(this).closest('td').index();
            var mesPesquisa = $("#dataPesquisa").val();
            // alert(mesPesquisa)

            var mesAtual = $("#mesAtual").val();

            if (mesPesquisa == "") {
                mesPesquisa = mesAtual
            }

            var opcaoSelecionada = '';
            var $tr = $(this).closest('tr');
            var funcionario = $tr.find('td.funcionario').text();
            var matriculaFunc = $tr.find('td.matriculaFunc').text();
            var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
            var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
            var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
            var cargoFunc = $tr.find('td.cargo').text();

            var colIndex = $(this).closest('td').index();
            var mesPesquisa = $("#dataPesquisa").val();


            var $selects = $(this).closest('tr').find('.estilezaSelect'); // Todos os selects da linha

            var indexAtual = $('#table1 thead tr.trr th').eq(colIndex).text();

            // alert(indexAtual)

            var indexUltimoDia = $selects.length;
            // alert(indexAtual);
            // alert(indexUltimoDia);



            var indexAtualNumero = parseInt(indexAtual, 10);

            for (var i = indexAtualNumero; i <= indexUltimoDia; i++) {
                var aux = i < 10 ? "0" + i : i.toString();

                var numeroDiaDaSemana = aux;

                // console.log(numeroDiaDaSemana);

                $.ajax({
                    url: "config/insertEUpdate_EscalaMensal.php",
                    method: 'get',
                    data: 'numeroDiaDaSemana=' +
                        numeroDiaDaSemana +
                        "&opcaoSelecionada=" +
                        opcaoSelecionada +
                        "&funcionario=" +
                        funcionario +
                        "&mesAtual=" +
                        mesAtual +
                        "&mesPesquisa=" +
                        mesPesquisa +
                        "&usuarioLogado=" +
                        usuarioLogado +
                        "&matriculaFunc=" +
                        matriculaFunc +
                        "&loja=" +
                        loja +
                        "&horarioEntradaFunc=" +
                        horarioEntradaFunc +
                        "&horarioSaidaFunc=" +
                        horarioSaidaFunc +
                        "&horarioIntervaloFunc=" +
                        horarioIntervaloFunc +
                        "&cargoFunc=" +
                        cargoFunc,

                    // dataType: 'json',
                    success: function(retorno) {
                        // console.log(retorno)

                    }
                });
                $selects.eq(i).val(' ');
            }


            // Calcular quantos dias faltam até o final do mês
            var diasRestantes = indexUltimoDia - indexAtual;
            var diasParaProximoMes = Math.min(30 - diasRestantes, diasRestantes);
            alert("faltaram  para o proximo mes: " + diasParaProximoMes);


            // Obtém o ano e o mês a partir da string mesPesquisa
            var ano = parseInt(mesPesquisa.split('-')[0]);
            var mes = parseInt(mesPesquisa.split('-')[1]);



            // Converte 'mesPesquisa' para um objeto Date
            var data = new Date(ano, mes - 1); // O mês em JavaScript começa em zero (janeiro é 0)

            // Verifica se a quantidade de dias é maior que 1 para avançar para o próximo mês
            if (diasParaProximoMes > 1) {
                // Adiciona a quantidade de dias à data atual
                data.setMonth(data.getMonth() + 1);
                ano = data.getFullYear();
                mes = data.getMonth() + 1;

                // Formata o novo mês para o formato 'AAAA-MM'
                mesPesquisa = ano + '-' + (mes < 10 ? '0' + mes : mes);
            }

            console.log(mesPesquisa); // Aqui você terá o valor do mês atualizado, seja o mesmo ou o próximo mês

            // Loop para contar até a quantidade de dias desejada
            for (var i = 1; i <= diasParaProximoMes; i++) {
                var numeroDiaDaSemana = i < 10 ? "0" + i : i.toString();
                console.log(numeroDiaDaSemana);
                if (diasParaProximoMes > 1) {
                    $.ajax({
                        url: "config/insertEUpdate_EscalaMensal.php",
                        method: 'get',
                        data: 'numeroDiaDaSemana=' +
                            numeroDiaDaSemana +
                            "&opcaoSelecionada=" +
                            opcaoSelecionada +
                            "&funcionario=" +
                            funcionario +
                            "&mesAtual=" +
                            mesAtual +
                            "&mesPesquisa=" +
                            mesPesquisa +
                            "&usuarioLogado=" +
                            usuarioLogado +
                            "&matriculaFunc=" +
                            matriculaFunc +
                            "&loja=" +
                            loja +
                            "&horarioEntradaFunc=" +
                            horarioEntradaFunc +
                            "&horarioSaidaFunc=" +
                            horarioSaidaFunc +
                            "&horarioIntervaloFunc=" +
                            horarioIntervaloFunc +
                            "&cargoFunc=" +
                            cargoFunc,

                        // dataType: 'json',
                        success: function(retorno) {
                            console.log(retorno)

                        }
                    });
                }
            }


        }
    });







    $('#table1').DataTable({
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
        "ordering": false,
        "lengthMenu": [
            [40],
        ],
        fixedColumns: {
            left: 4,
        },

        buttons: [
            // {
            //     extend: 'excelHtml5',
            //     exportOptions: {
            //         columns: [0, ':visible']
            //     }
            // },
            {
                text: 'Escala Diaria',
                className: 'btnverde',
                action: function() {
                    window.location.href = "escalaDiaria.php";
                }
            },
            {
                text: 'Finalizar Escala',
                className: 'btnVermelho',
                action: function() {
                    criandoHtmlmensagemCarregamento("exibir");
                    var alteraStatusEscala = "F";
                    var usuarioLogado = $("#usuarioLogado").val();
                    var loja = $("#loja").val();

                    var mesPesquisa = $("#dataPesquisa").val();

                    var mesAtual = $("#mesAtual").val();

                    if (mesPesquisa == "") {
                        mesPesquisa = mesAtual
                    };



                    $.ajax({
                        url: "config/desabilita_ou_habilita_mensal.php",
                        method: 'POST',
                        data: "mesPesquisa=" +
                            mesPesquisa +
                            "&mesAtual=" +
                            mesAtual +
                            "&alteraStatusEscala=" +
                            alteraStatusEscala +
                            "&loja=" +
                            loja +
                            "&usuarioLogado=" +
                            usuarioLogado,
                        success: function(atualizaTabela1) {

                            $.ajax({
                                url: "config/pesquisar_escalaMensal.php",
                                method: 'POST',
                                data: 'mesPesquisa=' +
                                    mesPesquisa +
                                    "&loja=" +
                                    loja +
                                    "&usuarioLogado=" +
                                    usuarioLogado,
                                success: function(mes_Pesquisado) {

                                    $('.atualizaTabela').empty().html(mes_Pesquisado);
                                    criandoHtmlmensagemCarregamento("ocultar");
                                }
                            });

                        }
                    });
                }
            },
            {
                text: 'Liberar Escala',
                className: 'btnVermelho',
                action: function() {
                    criandoHtmlmensagemCarregamento("exibir");
                    var alteraStatusEscala = '';
                    var usuarioLogado = $("#usuarioLogado").val();
                    var loja = $("#loja").val();

                    var mesPesquisa = $("#dataPesquisa").val();

                    var mesAtual = $("#mesAtual").val();

                    if (mesPesquisa == "") {
                        mesPesquisa = mesAtual
                    }

                    $.ajax({
                        url: "config/desabilita_ou_habilita_mensal.php",
                        method: 'POST',
                        data: "mesPesquisa=" +
                            mesPesquisa +
                            "&mesAtual=" +
                            mesAtual +
                            "&alteraStatusEscala=" +
                            alteraStatusEscala +
                            "&loja=" +
                            loja +
                            "&usuarioLogado=" +
                            usuarioLogado,
                        success: function(atualizaTabela) {


                            $.ajax({
                                url: "config/pesquisar_escalaMensal.php",
                                method: 'POST',
                                data: 'mesPesquisa=' +
                                    mesPesquisa +
                                    "&loja=" +
                                    loja +
                                    "&usuarioLogado=" +
                                    usuarioLogado,
                                success: function(mes_Pesquisado) {

                                    $('.atualizaTabela').empty().html(mes_Pesquisado);
                                    criandoHtmlmensagemCarregamento("ocultar");
                                }
                            });



                        }
                    });
                }
            },
        ],

    });
</script>


<Script>
    $('select').on('change', function() {
        $('tr').removeClass('selecionado').css('background-color', '').css('color', '');

        var linha = $(this).closest('tr');
        var opcao = $(this).closest('.estilezaSelect');
        linha.addClass('selecionado');
        linha.css('background-color', '#00a550d0');

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

    var usuarioLogado = $("#usuarioLogado").val();
    var loja = $("#loja").val();
</Script>
<script>

</script>