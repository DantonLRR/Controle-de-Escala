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
                success: function (retorno) {
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
                    success: function (retorno) {
                        console.log(retorno)

                    }
                });
            }
        }

    }
    else if ($(this).val() !== 'FA'){

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
                    success: function (retorno) {
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
                        success: function (retorno) {
                            console.log(retorno)
    
                        }
                    });
                }
            }
    
        
    }
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
});


