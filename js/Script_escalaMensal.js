import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";

var usuarioLogado = $("#usuarioLogado").val();
var loja = $("#loja").val();


var statusDaTabela = $("#statusDaTabela").val();

if (statusDaTabela === "JÁ FINALIZADA.") {

    $('#table1').find('input, select, textarea, button').prop('disabled', true);;
}



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
            action: function () {
                window.location.href = "escalaDiaria.php";
            }
        },
        {
            text: 'Finalizar Escala',
            className: 'btnVermelho',
            action: function () {
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
                    success: function (atualizaTabela1) {

                        $.ajax({
                            url: "config/pesquisar_escalaMensal.php",
                            method: 'POST',
                            data: 'mesPesquisa=' +
                                mesPesquisa +
                                "&loja=" +
                                loja +
                                "&usuarioLogado=" +
                                usuarioLogado,
                            success: function (mes_Pesquisado) {

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
            action: function () {
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
                    success: function (atualizaTabela) {


                        $.ajax({
                            url: "config/pesquisar_escalaMensal.php",
                            method: 'POST',
                            data: 'mesPesquisa=' +
                                mesPesquisa +
                                "&loja=" +
                                loja +
                                "&usuarioLogado=" +
                                usuarioLogado,
                            success: function (mes_Pesquisado) {

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


$('#dataPesquisa').on('change', function () {
    criandoHtmlmensagemCarregamento("exibir");
    var mesPesquisa = $("#dataPesquisa").val();

    var mesAtual = $("#mesAtual").val();

    if (mesPesquisa == "") {
        mesPesquisa = mesAtual
    }

    $.ajax({
        url: "config/pesquisar_escalaMensal.php",
        method: 'POST',
        data: 'mesPesquisa=' +
            mesPesquisa +
            "&loja=" +
            loja +
            "&usuarioLogado=" +
            usuarioLogado,
        success: function (mes_Pesquisado) {

            $('.atualizaTabela').empty().html(mes_Pesquisado);
            criandoHtmlmensagemCarregamento("ocultar");
        }
    });
});


$('select').on('change', function () {
    $('tr').removeClass('selecionado').css('background-color', '').css('color', '');

    var linha = $(this).closest('tr');
    var opcao = $(this).closest('.estilezaSelect');
    linha.addClass('selecionado');
    linha.css('background-color', '#00a550d0');

    opcao.css('font-weight', 'bold');



});




$('#table1').on('change', '.estilezaSelect', function () {
    var opcaoSelecionada = $(this).val();
    var $tr = $(this).closest('tr');
    var funcionario = $tr.find('td.funcionario').text();
    var matriculaFunc = $tr.find('td.matriculaFunc').text();
    var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
    var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
    var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
    var cargoFunc = $tr.find('td.cargo').text();

    var colIndex = $(this).closest('td').index();
    var numeroDiaDaSemana = $('#table1 thead tr.trr th').eq(colIndex).text();
    var mesPesquisa = $("#dataPesquisa").val();
    // alert(mesPesquisa)

    var mesAtual = $("#mesAtual").val();

    if (mesPesquisa == "") {
        mesPesquisa = mesAtual
    }
    //alert(mesPesquisa)
    // alert(mesPesquisa);
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
        success: function (retorno) {
            console.log(retorno)


        }
    });
});




