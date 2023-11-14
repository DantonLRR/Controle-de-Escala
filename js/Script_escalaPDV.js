import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";


$('#table1').DataTable({

    scrollY: 400,

    scrollCollapse: true,
    searching: true,
    dom: 'Bfrtip',
    "paging": true,
    "info": false,
    "ordering": false,
    "lengthMenu": [
        [15],

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


var tabela2 = $('#table2').DataTable({


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


$('#tableHeader').DataTable({
    searching: false,
    dom: 'Bfrtip',
    "paging": true,
    "info": false,
    "ordering": false,
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




var iconeAddTables = document.getElementById("BTNAdicionarDescritivo");
var iconeRemoveTable = document.getElementById("BTNremoverDescritivo");
var table1 = document.getElementById("cardTable1");

iconeAddTables.addEventListener("click", function () {
    table1.classList.remove("ocultar");
    iconeAddTables.classList.add("ocultar");
    iconeRemoveTable.classList.remove("ocultar");
});

iconeRemoveTable.addEventListener("click", function () {
    table1.classList.add("ocultar");
    iconeAddTables.classList.remove("ocultar");
    iconeRemoveTable.classList.add("ocultar");
});




function calcularHorasIntermediarias(horaEntrada, horaSaida, horaParaPular) {
    var horasIntermediarias = [];
    var [entradaHora, entradaMinutos] = horaEntrada.split(':').map(Number);
    var [saidaHora, saidaMinutos] = horaSaida.split(':').map(Number);

    while (entradaHora < saidaHora || (entradaHora === saidaHora && entradaMinutos <= saidaMinutos)) {
        var horaFormatada = entradaHora.toString().padStart(2, '0') + ':' + entradaMinutos.toString().padStart(2, '0');

        // Verifica se a horaFormatada é igual à horaParaPular e exclui se for diferente.
        if (horaFormatada !== horaParaPular) {
            horasIntermediarias.push('"' + horaFormatada + '"');
        }

        if (entradaMinutos === 0) {
            entradaHora++;
            entradaMinutos = 0;
        } else {
            entradaMinutos = 0;
        }
    }

    return horasIntermediarias;
}







var usuarioLogado = $("#usuarioLogado").val();
var opcoesSelecionadas = [];
var loja = $("#loja").val();
$('#table1').on('change', '.estilezaSelect', function () {
    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = $("#dataAtual").val();

    if (dataPesquisa == "") {
        dataPesquisa = dataAtual
    }

    var MatriculaDaPessoaSelecionada = $(this).val();
    var nomeSelecionado = $(this).find('option:selected').text();
    var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();
    var $selects = $('#table1 .estilezaSelect');
    var matricula = $(this).parent().parent().find(".Matricula1").closest(".Matricula1");
    var entrada = $(this).parent().parent().find(".horaEntrada1").closest(".horaEntrada1");
    var saida = $(this).parent().parent().find(".horaSaida1").closest(".horaSaida1");
    var intervalo = $(this).parent().parent().find(".horaIntervalo1").closest(".horaIntervalo1");

    if (nomeSelecionado !== "") {
        if (opcoesSelecionadas.includes(nomeSelecionado)) {
            alert('Opção já selecionada em outra linha.');
            $(this).val("");
        } else {
            opcoesSelecionadas.push(nomeSelecionado);
            $selects.not(this).find('option[value="' + nomeSelecionado + '"]').remove();
            $.ajax({
                url: "filtro/busca_infosFuncionarios.php",
                method: 'get',
                data: 'MatriculaDaPessoaSelecionada=' +
                    MatriculaDaPessoaSelecionada +
                    "&loja=" +
                    +loja +
                    "&dataAtual=" +
                    dataAtual+
                    "&nomeSelecionado="+
                    nomeSelecionado,
                dataType: 'json',
                success: function (retorno) {
                    matricula.text(retorno.MATRICULA);
                    entrada.text(retorno.HORAENTRADA);
                    saida.text(retorno.HORASAIDA);
                    intervalo.text(retorno.SAIDAPARAALMOCO);
                    var DadosMatricula = retorno.MATRICULA;
                    var DadosEntrada = retorno.HORAENTRADA;
                    var DadosSaida = retorno.HORASAIDA;
                    var DadosIntervalo = retorno.SAIDAPARAALMOCO;

                    var horasIntermediarias = calcularHorasIntermediarias(DadosEntrada, DadosSaida, DadosIntervalo);

                    $.ajax({
                        url: "config/insertManha_escalaPDV.php",
                        method: 'get',
                        data: 'DadosMatricula=' +
                            DadosMatricula +
                            "&nomeSelecionado=" +
                            nomeSelecionado +
                            "&DadosEntrada=" +
                            DadosEntrada +
                            "&DadosSaida=" +
                            DadosSaida +
                            "&DadosIntervalo=" +
                            DadosIntervalo +
                            "&usuarioLogado=" +
                            usuarioLogado +
                            "&dataPesquisa=" +
                            dataPesquisa +
                            "&numPDV=" +
                            numPDV +
                            "&loja=" +
                            loja,

                        // dataType: 'json',
                        success: function (retornoinsertManha) {
                            alert("insert manha ")
                            criandoHtmlmensagemCarregamento("ocultar");


                        }
                    });

                    $.ajax({
                        url: "config/exibicao_escala_diaria_pdv.php",
                        method: 'get',
                        data: 'DadosMatricula=' +
                            DadosMatricula +
                            "&nomeSelecionado=" +
                            nomeSelecionado +
                            "&DadosEntrada=" +
                            DadosEntrada +
                            "&DadosSaida=" +
                            DadosSaida +
                            "&DadosIntervalo=" +
                            DadosIntervalo +
                            "&usuarioLogado=" +
                            usuarioLogado +
                            "&dataPesquisa=" +
                            dataPesquisa +
                            "&numPDV=" +
                            numPDV +
                            "&horasIntermediarias=" +
                            horasIntermediarias +
                            "&loja=" +
                            loja,
                        success: function (retorno2) {
                            $.ajax({
                                url: "config/pesquisar_relatorio_pdv.php",
                                method: 'POST',
                                data: 'dataPesquisa=' +
                                    dataPesquisa +
                                    "&loja=" +
                                    loja,
                                success: function (relatorio_atualizado2) {

                                    $('#relatorioPDV').empty().html(relatorio_atualizado2);
                                    criandoHtmlmensagemCarregamento("ocultar");

                                }
                            });
                        }
                    });







                }
            });




        }
    }
});



$('#table1').on('change', '.estilizaSelect2', function () {
    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = $("#dataAtual").val();

    if (dataPesquisa == "") {
        dataPesquisa = dataAtual
    }
    var MatriculaDaPessoaSelecionada2 = $(this).val();
    var nomeSelecionado2 =  $(this).find('option:selected').text();
    var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();
    var $selects2 = $('#table1 .estilizaSelect2');
    var matricula2 = $(this).parent().parent().find(".matricula2").closest(".matricula2");
    var entrada2 = $(this).parent().parent().find(".horaEntrada2").closest(".horaEntrada2");
    var saida2 = $(this).parent().parent().find(".horaSaida2").closest(".horaSaida2");
    var intervalo2 = $(this).parent().parent().find(".horaIntervalo2").closest(".horaIntervalo2");


    if (nomeSelecionado2 !== "") {
        if (opcoesSelecionadas.includes(nomeSelecionado2)) {
            alert('Opção já selecionada em outra linha.');
            $(this).val("");
        } else {
            opcoesSelecionadas.push(nomeSelecionado2);

            $selects2.not(this).find('option[value="' + nomeSelecionado2 + '"]').remove();

            $.ajax({
                url: "filtro/busca_infosFuncionarios.php",
                method: 'get',
                data:'MatriculaDaPessoaSelecionada=' +
                MatriculaDaPessoaSelecionada2 +
                    "&loja=" +
                    +loja +
                    "&dataAtual=" +
                    dataAtual+
                    "&nomeSelecionado="+
                    nomeSelecionado2,
                dataType: 'json',
                success: function (retorno2) {
                    matricula2.text(retorno2.MATRICULA);
                    entrada2.text(retorno2.HORAENTRADA);
                    saida2.text(retorno2.HORASAIDA);
                    intervalo2.text(retorno2.SAIDAPARAALMOCO);

                    var DadosMatricula1 = retorno2.MATRICULA;
                    var DadosEntrada1 = retorno2.HORAENTRADA;
                    var DadosSaida1 = retorno2.HORASAIDA;
                    var DadosIntervalo1 = retorno2.SAIDAPARAALMOCO;
                    var horasIntermediarias = calcularHorasIntermediarias(DadosEntrada1, DadosSaida1, DadosIntervalo1);
                    $.ajax({
                        url: "config/insertTarde_escalaPDV.php",
                        method: 'get',
                        data: 'DadosMatricula1=' +
                            DadosMatricula1 +
                            "&nomeSelecionado2=" +
                            nomeSelecionado2 +
                            "&DadosEntrada1=" +
                            DadosEntrada1 +
                            "&DadosSaida1=" +
                            DadosSaida1 +
                            "&DadosIntervalo1=" +
                            DadosIntervalo1 +
                            "&usuarioLogado=" +
                            usuarioLogado +
                            "&dataPesquisa=" +
                            dataPesquisa +
                            "&numPDV=" +
                            numPDV +
                            "&numPDV=" +
                            numPDV +
                            "&loja=" +
                            loja,
                        // dataType: 'json',
                        success: function (retorno2) {
                            criandoHtmlmensagemCarregamento("ocultar");
                        }
                    });

                    $.ajax({
                        url: "config/exibicao_escala_diaria_pdv.php",
                        method: 'get',
                        data: 'DadosMatricula=' +
                            DadosMatricula1 +
                            "&nomeSelecionado=" +
                            nomeSelecionado2 +
                            "&DadosEntrada=" +
                            DadosEntrada1 +
                            "&DadosSaida=" +
                            DadosSaida1 +
                            "&DadosIntervalo=" +
                            DadosIntervalo1 +
                            "&usuarioLogado=" +
                            usuarioLogado +
                            "&dataPesquisa=" +
                            dataPesquisa +
                            "&numPDV=" +
                            numPDV +
                            "&horasIntermediarias=" +
                            horasIntermediarias +
                            "&loja=" +
                            loja,
                        success: function (retorno2) {
                            $.ajax({
                                url: "config/pesquisar_relatorio_pdv.php",
                                method: 'POST',
                                data: 'dataPesquisa=' +
                                    dataPesquisa +
                                    "&loja=" +
                                    loja,
                                success: function (relatorio_atualizado2) {

                                    $('#relatorioPDV').empty().html(relatorio_atualizado2);
                                    criandoHtmlmensagemCarregamento("ocultar");

                                }
                            });
                        }
                    });




                }
            });
        }
    }
});




$('#dataPesquisa').on('change', function () {
    criandoHtmlmensagemCarregamento("exibir");
    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = $("#dataAtual").val();

    if (dataPesquisa == "") {
        dataPesquisa = dataAtual
    }


    $.ajax({
        url: "config/pesquisar_escalaPDV.php",
        method: 'POST',
        data: 'dataPesquisa=' +
            dataPesquisa +
            "&loja=" +
            loja,
        success: function (data_pesquisada) {

            $('.dadosEscalaPDV').empty().html(data_pesquisada);

            $.ajax({
                url: "config/pesquisar_relatorio_pdv.php",
                method: 'POST',
                data: 'dataPesquisa=' +
                    dataPesquisa +
                    "&loja=" +
                    loja,
                success: function (relatorio_atualizado) {

                    $('#relatorioPDV').empty().html(relatorio_atualizado);
                    criandoHtmlmensagemCarregamento("ocultar");

                }
            });
        }
    });

});

$('.fa-trash').on('click', function () {
    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = $("#dataAtual").val();

    if (dataPesquisa == "") {
        dataPesquisa = dataAtual
    }
    var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();

    $.ajax({
        url: "config/remove_linha_relatorio_pdv.php",
        method: 'get',
        data:
            "dataPesquisa=" +
            dataPesquisa +
            "&numPDV=" +
            numPDV +
            "&loja=" +
            loja,

        // dataType: 'json',
        success: function (atualizaTabela) {

            $.ajax({
                url: "config/pesquisar_relatorio_pdv.php",
                method: 'POST',
                data: 'dataPesquisa=' +
                    dataPesquisa +
                    "&loja=" +
                    loja,
                success: function (relatorio_atualizado2) {

                    $('#relatorioPDV').empty().html(relatorio_atualizado2);
                    criandoHtmlmensagemCarregamento("ocultar");
                }
            });

            $.ajax({
                url: "config/pesquisar_escalaPDV.php",
                method: 'POST',
                data: 'dataPesquisa=' +
                    dataPesquisa +
                    "&loja=" +
                    loja,
                success: function (data_pesquisada2) {

                    $('.dadosEscalaPDV').empty().html(data_pesquisada2);

                }
            });

        }
    });


});