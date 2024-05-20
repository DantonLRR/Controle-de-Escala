import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";

var usuarioLogado = $("#usuarioLogado").val();
var loja = $("#loja").val();


var statusDaTabela = $("#statusDaTabela").val();




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
    "info": true,
    "ordering": false,
    "lengthMenu": [
        [100],
    ],
    fixedColumns: {
        left: 3,
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
            className: 'btnVermelho btnverde',
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
                var Departamento = $('#dadosDeQuemEstaLogadoSetor').val();

                //  Array para armazenar as matrículas
                // var matriculas = [];

                //  Itera sobre cada linha da tabela exceto a primeira (cabeçalho)
                // $('#table1 tbody tr').each(function () {
                //      Recupera a matrícula da célula oculta
                //     var matricula = $(this).find('.matriculaFunc').text().trim();
                //      Adiciona a matrícula ao array se não estiver vazia
                //     if (matricula !== "") {
                //         matriculas.push(matricula);
                //     }
                // });

                //  Agora, matriculas[] contém todas as matrículas da tabela
                //  console.log(matriculas);
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
                        usuarioLogado +
                        "&Departamento=" +
                        Departamento,
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
        // {
        //     extend: 'excel',
        //     className: 'btnverdeEXCEL',
        //     text: '<i class="fa-solid fa-table" style="color: #ffffff;"></i> Excel ',
        //     exportOptions: {
        //         format: {
        //             body: function (data, row, column, node) {
        //                 if ($(node).find('select[disabled]').length > 0) {
        //                     return $(node).find('select[disabled]').val();
        //                 }
        //                 return data;
        //             }
        //         }
        //     }

        // }
        {
            text: '<i class="fa-solid fa-file-pdf" style="color: #ffffff;"></i> PDF ',
            className: ' btnverdeEXCEL',
            action: function () {
                criandoHtmlmensagemCarregamento("exibir");
                var usuarioLogado = $("#usuarioLogado").val();
                var loja = $("#loja").val();

                var mesPesquisa = $("#dataPesquisa").val();

                var mesAtual = $("#mesAtual").val();
                var diretorioDoPdf = "contrato.php";
                if (mesPesquisa == "") {
                    mesPesquisa = mesAtual
                };
                var Departamento = $('#dadosDeQuemEstaLogadoSetor').val();
                $.ajax({
                    url: "config/gerarPdf.php",
                    method: "POST",
                    data: 'mesPesquisa=' +
                        mesPesquisa +
                        "&loja=" +
                        loja +
                        "&usuarioLogado=" +
                        usuarioLogado +
                        "&Departamento=" +
                        Departamento +
                        "&diretorioDoPdf=" +
                        diretorioDoPdf,
                    xhrFields: {
                        responseType: "blob",
                    },
                    success: function (response) {
                        // Loading("ocultar");
                        criandoHtmlmensagemCarregamento("ocultar");
                        let blobUrl = URL.createObjectURL(response);
                        window.open(blobUrl, "_blank");
                    },
                    error: function (xhr, status, error) {
                        // console.log(error);
                        // Loading("ocultar");
                    },
                });

            }

        },
        {
            text: '<i class="fa-solid fa-calendar" style="color: #ffffff;"></i> Lançamento De Ferias ',
            className: 'btnverde',
            action: function () {
                criandoHtmlmensagemCarregamento("exibir");
                $('#modalFerias').modal('show');
                var Departamento = $('#dadosDeQuemEstaLogadoSetor').val();
                var loja = $('#loja').val();
                $.ajax({
                    url: "modal/modalFerias.php",
                    method: 'POST',
                    data: 'Departamento=' +
                        Departamento +
                        "&loja=" +
                        loja,
                    success: function (modalFerias) {
                        $('.cadastroFerias').empty().html(modalFerias);
                        criandoHtmlmensagemCarregamento("ocultar");
                    }
                });
            }
        }
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


var rows = $("#table1 tr");

rows.on('click', function () {
    rows.removeClass("selected");
    $(this).addClass("selected");
});

$(document).ready(function () {
    $('#table1').on('click', '.estilezaSelect', function () {

        var opcaoSelecionadaAux = $(this).val();
        var $selects = $(this).closest('tr').find('.estilezaSelect');
        var colIndex = $(this).closest('td').index();
        var indexAtual = $selects.index(this);
        var PeriodoMaximoDeDiasTrabalhados = false
        var indexUltimoPreenchido = -1;
        var indexProximoPreenchido;
        $selects.slice(0, indexAtual).each(function (index) {
            if ($(this).val() !== '') {
                indexUltimoPreenchido = index;
            }
        });

        $selects.slice(indexAtual + 1).each(function (index) {
            if ($(this).val() !== '') {
                indexProximoPreenchido = indexAtual + index + 1; // Ajuste aqui
                return false; // Para interromper o loop assim que encontrar um select preenchido
            }
        });

        var THDoSelectAtual = $('#table1 thead tr.trr th').eq(colIndex).text()
        // console.log(THDoSelectAtual)


        // Calcular a quantidade de selects em branco entre o último e o atual
        var selectsEmBrancoEntre = indexAtual - indexUltimoPreenchido - 1;

        var thDoUltimoSelectPreenchido = THDoSelectAtual - selectsEmBrancoEntre - 1

        // console.log("th Do Ultimo Select Preenchido:  " + thDoUltimoSelectPreenchido)
        // console.log('Selects em branco entre o último selecionado e o atual: ' + selectsEmBrancoEntre);

        var selectsEmBrancoEntreOProximo = indexProximoPreenchido - indexAtual - 1;
        // console.log('Selects em branco entre próximo selecionado e o atual: ' + selectsEmBrancoEntreOProximo);

        var thDoProximoSelectPreenchido = parseInt(THDoSelectAtual) + selectsEmBrancoEntreOProximo + 1;
        if (isNaN(thDoProximoSelectPreenchido)) {
            thDoProximoSelectPreenchido = 0;
        }
        // console.log("th Do Proximo Select Preenchido: " + thDoProximoSelectPreenchido);



        if (selectsEmBrancoEntre >= 7) {
            PeriodoMaximoDeDiasTrabalhados = true
        }
        var valorINICIAL = $(this).val();
        $(this).off('change').on('change', function () {

            var periodoParaEdicaoDeEscala = parseInt(thDoProximoSelectPreenchido) - thDoUltimoSelectPreenchido
            var opcaoSelecionada = $(this).val();

            // alert(valorINICIAL)
            // alert(opcaoSelecionada)

            if (valorINICIAL != 'F' && opcaoSelecionada != 'F' || valorINICIAL == '' && opcaoSelecionada != 'F') {
                // console.log('Valor INICIAL: ' + valorINICIAL);
                // console.log('opcao Escolhida :' + opcaoSelecionada)
                // console.log("caiu na segunda");
                var opcaoSelecionada = $(this).val();
                if (opcaoSelecionada == 'T') {
                    opcaoSelecionada = ''
                }
                var $tr = $(this).closest('tr');
                var funcionario = $tr.find('td.funcionario').text();
                var matriculaFunc = $tr.find('td.matriculaFunc').text();
                var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
                var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
                var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
                var cargoFunc = $tr.find('td.cargo').text();

                var colIndex = $(this).closest('td').index();
                var mesPesquisa = $("#dataPesquisa").val();
                //console.log(mesPesquisa)

                var departamentoFunc = $tr.find('td.departamento').text();
                //alert(departamentoFunc)
                var mesAtual = $("#mesAtual").val();

                if (mesPesquisa == "") {
                    mesPesquisa = mesAtual
                }
                var numeroDiaDaSemana = [];

                numeroDiaDaSemana.push('"' + $('#table1 thead tr.trr th').eq(colIndex).text() + '"');
                if (PeriodoMaximoDeDiasTrabalhados) {
                    $(this).val(' ');
                    Toasty("Atenção", "Funcionario escalado sem folga mais de SEIS dias", "#E20914");

                } else if (opcaoSelecionada == '' && periodoParaEdicaoDeEscala >= 7) {
                    $(this).val(opcaoSelecionadaAux);
                    Toasty("Atenção", "Funcionario escalado sem folga mais de SEIS dias", "#E20914");
                } else {

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
                            cargoFunc +
                            "&departamentoFunc=" +
                            departamentoFunc,
                        // dataType: 'json',
                        success: function (retorno) {
                            // console.log(retorno)
                        }
                    });
                }

            }
        });
    });
});

if (statusDaTabela === "JÁ FINALIZADA.") {
    $('#table1').find('select').prop('disabled', true);
    $('.btnVermelho').addClass('ocultarBotao');
    $('.btnverdeEXCEL').removeClass('ocultarBotao');
} else {
    $('#table1').find('select').prop('disabled', false);
    $('select[name="desabilitarEsteSelect"]').prop('disabled', true);
    $('.btnVermelho').removeClass('ocultarBotao');
    $('.btnverdeEXCEL').addClass('ocultarBotao');
}

