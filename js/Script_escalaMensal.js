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


$(document).ready(function () {
    $('.estilezaSelect').on('click', function () {
        var valorINICIAL = $(this).val();
        $(this).off('change').on('change', function () {
            var opcaoSelecionada = $(this).val();
            // alert(valorINICIAL)
            // alert(opcaoSelecionada)
            if (valorINICIAL != 'F' && opcaoSelecionada == 'F' || valorINICIAL == '' && opcaoSelecionada == 'F') {
                console.log('Valor inicial : ' + valorINICIAL);
                console.log('opcao Escolhida :' + opcaoSelecionada)
                console.log("caiu na primeira");
                var colIndex = $(this).closest('td').index();
                var mesPesquisa = $("#dataPesquisa").val();
                //console.log(mesPesquisa)

                var mesAtual = $("#mesAtual").val();

                if (mesPesquisa == "") {
                    mesPesquisa = mesAtual
                }

                var opcaoSelecionada = 'F';
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

                // alert("dia selecionado :" + indexAtual)

                var indexUltimoDia = $selects.length;
                //console.log(indexAtual);
                //console.log(indexUltimoDia);



                var indexAtualNumero = parseInt(indexAtual, 10);
                var numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes = [];
                for (var i = indexAtualNumero; i <= indexUltimoDia; i++) {
                    var aux = i < 10 ? "0" + i : i.toString();
                    numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes.push('"' + aux + '"');

                    console.log("dia inserido : " + numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes);

                    $selects.eq(i).prop('disabled', true).val('F');                  
                }


                $.ajax({
                    url: "config/insertEUpdate_EscalaMensal.php",
                    method: 'get',
                    data: 'numeroDiaDaSemana=' +
                        numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes +
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



                // Calcular quantos dias faltam até o final do mês
                var diasRestantes = indexUltimoDia - indexAtual;
                var diasParaProximoMes = Math.min(29 - diasRestantes, diasRestantes);
                //console.log("faltaram  para o proximo mes: " + diasParaProximoMes);


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
                    var numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes = [];
                    for (var i = 1; i <= diasParaProximoMes; i++) {
                        var aux = i < 10 ? "0" + i : i.toString();
                        console.log("dia faltante :" + numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes);
                        numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes.push('"' + aux + '"');
                    }
                   var inclusaoDoMesAnterior = "SIM";
                    $.ajax({
                        url: "config/insertEUpdate_EscalaMensal_proximo_mes.php",
                        method: 'get',
                        data: 'numeroDiaDaSemana=' +
                            numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes +
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
                            cargoFunc+
                            "&inclusaoDoMesAnterior="+
                            inclusaoDoMesAnterior,

                        // dataType: 'json',
                        success: function (retorno) {
                            // console.log(retorno)

                        }
                    });

                }
            }
            else if (valorINICIAL != 'F' && opcaoSelecionada != 'F' || valorINICIAL == '' && opcaoSelecionada != 'F') {
                console.log('Valor INICIAL: ' + valorINICIAL);
                console.log('opcao Escolhida :' + opcaoSelecionada)
                console.log("caiu na segunda");
                var opcaoSelecionada = $(this).val();
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

                var mesAtual = $("#mesAtual").val();

                if (mesPesquisa == "") {
                    mesPesquisa = mesAtual
                }
                var numeroDiaDaSemana = [];

                numeroDiaDaSemana.push('"' + $('#table1 thead tr.trr th').eq(colIndex).text() + '"');

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
                        // console.log(retorno)


                    }
                });

            }
            else if (valorINICIAL == 'F' && opcaoSelecionada != 'F' || valorINICIAL == 'F' && opcaoSelecionada != '') {

                console.log('Valor INICIAL: ' + valorINICIAL);
                console.log('opcao Escolhida :' + opcaoSelecionada)
                console.log("caiu na terceira");
                var mesPesquisa = $("#dataPesquisa").val();
                //console.log(mesPesquisa)

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

                // alert("dia selecionado :" + indexAtual);

                var indexUltimoDia = $selects.length;
                //console.log(indexAtual);
                //console.log(indexUltimoDia);



                var indexAtualNumero = parseInt(indexAtual, 10);
                // alert("Dia Selecionado " + indexAtualNumero);
                var numeroDiaDaSemanaArrayLimpaFA = [];

                for (var i = indexAtualNumero; i <= indexUltimoDia; i++) {
                    var aux = i < 10 ? "0" + i : i.toString();

                    numeroDiaDaSemanaArrayLimpaFA.push('"' + aux + '"');

                    console.log("dia inserido : " + numeroDiaDaSemanaArrayLimpaFA);

                    $selects.eq(i).prop('disabled', false).val(' ');
                }


                $.ajax({
                    url: "config/insertEUpdate_EscalaMensal.php",
                    method: 'get',
                    data: 'numeroDiaDaSemana=' +
                        numeroDiaDaSemanaArrayLimpaFA +
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
                        // console.log(retorno)

                    }
                });



                // Calcular quantos dias faltam até o final do mês
                var diasRestantes = indexUltimoDia - indexAtual;
                var diasParaProximoMes = Math.min(29 - diasRestantes, diasRestantes);
                //console.log("faltaram  para o proximo mes: " + diasParaProximoMes);


                // Obtém o ano e o mês a partir da string mesPesquisa
                var ano = parseInt(mesPesquisa.split('-')[0]);
                var mes = parseInt(mesPesquisa.split('-')[1]);



                // Converte 'mesPesquisa' para um objeto Date
                var data = new Date(ano, mes - 1); // O mês em JavaScript começa em zero (janeiro é 0)
                var numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes = [];
                // Verifica se a quantidade de dias é maior que 1 para avançar para o próximo mês
                if (diasParaProximoMes > 1) {
                    // Adiciona a quantidade de dias à data atual
                    data.setMonth(data.getMonth() + 1);
                    ano = data.getFullYear();
                    mes = data.getMonth() + 1;

                    // Formata o novo mês para o formato 'AAAA-MM'
                    mesPesquisa = ano + '-' + (mes < 10 ? '0' + mes : mes);

                    console.log(mesPesquisa); // Aqui você terá o valor do mês atualizado, seja o mesmo ou o próximo mês

                    // Loop para contar até a quantidade de dias desejada
                    for (var i = 1; i <= diasParaProximoMes; i++) {
                        var aux = i < 10 ? "0" + i : i.toString();
                        numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes.push('"' + aux + '"');

                        console.log(numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes);

                    }
                    var inclusaoDoMesAnterior = " ";
                    $.ajax({
                        url: "config/insertEUpdate_EscalaMensal_proximo_mes.php",
                        method: 'get',
                        data: 'numeroDiaDaSemana=' +
                            numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes +
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
                            cargoFunc+"&inclusaoDoMesAnterior="+inclusaoDoMesAnterior,

                        // dataType: 'json',
                        success: function (retorno) {
                            //console.log(retorno)

                        }
                    });
                }




                var opcaoSelecionada = $(this).val();
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

                var mesAtual = $("#mesAtual").val();

                if (mesPesquisa == "") {
                    mesPesquisa = mesAtual
                }
                var numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF = [];

                numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF.push('"' + $('#table1 thead tr.trr th').eq(colIndex).text() + '"');

                $.ajax({
                    url: "config/insertEUpdate_EscalaMensal.php",
                    method: 'get',
                    data: 'numeroDiaDaSemana=' +
                        numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF +
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
                        // console.log(retorno)


                    }
                });





            }
        });
    });
});
