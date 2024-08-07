import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";

var quantidadePorDiaDeFuncionariosImpressao = $("#quantidadePorDiaDeFuncionariosImpressao").val();
if (quantidadePorDiaDeFuncionariosImpressao == "Nenhum funcionario escalado para hoje") {
    $('.DesabilitaClasseCasoEscalaNaoFinalizada').css('display', 'none');
    $("#quantidadePorDiaDeFuncionariosVisivel").text("Escala mensal não finalizada");
    $('#dataPesquisa').prop('disabled', true);
}

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
    buttons: [
        {
            text: '<i class="fa-solid fa-file-pdf"  style="color: #ffffff;"></i> PDF ',
            className: 'btnverde btn ',
            action: function () {
                criandoHtmlmensagemCarregamento("exibir");
                var dataPesquisa = $("#dataPesquisa").val();
                var dataAtual = $("#dataAtual").val();

                if (dataPesquisa == "") {
                    dataPesquisa = dataAtual
                }
                var diretorioDoPdf = "PdfMontagemPDV.php";
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
                    success: function (response) {
                        // Loading("ocultar");
                        criandoHtmlmensagemCarregamento("ocultar");
                        let blobUrl = URL.createObjectURL(response);
                        window.open(blobUrl, "_blank");
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                        // Loading("ocultar");
                    },
                });

            }

        }
    ],

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
    buttons: [
        {
            text: '<i class="fa-solid fa-file-pdf"  style="color: #ffffff;"></i> PDF ',
            className: 'btnverde btn ',
            action: function () {
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
                    success: function (response) {
                        // Loading("ocultar");
                        criandoHtmlmensagemCarregamento("ocultar");
                        let blobUrl = URL.createObjectURL(response);
                        window.open(blobUrl, "_blank");
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                        // Loading("ocultar");
                    },
                });

            }

        }
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
    dom: 'frtip',
    "paging": false,
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




var iconeAddTables1 = document.getElementById("BTNAdicionarDescritivo");
var iconeRemoveTable1 = document.getElementById("BTNremoverDescritivo");
var table1 = document.getElementById("cardTable1");

var iconeAddTables2 = document.getElementById("BTNAdicionarDescritivo2");
var iconeRemoveTable2 = document.getElementById("BTNremoverDescritivo2");
var table2 = document.getElementById("relatorioPDV");

iconeAddTables1.addEventListener("click", function () {
    table1.classList.remove("ocultar");
    iconeAddTables1.classList.add("ocultar");
    iconeRemoveTable1.classList.remove("ocultar");
});

iconeRemoveTable1.addEventListener("click", function () {
    table1.classList.add("ocultar");
    iconeAddTables1.classList.remove("ocultar");
    iconeRemoveTable1.classList.add("ocultar");
});

iconeAddTables2.addEventListener("click", function () {
    table2.classList.remove("ocultar");
    iconeAddTables2.classList.add("ocultar");
    iconeRemoveTable2.classList.remove("ocultar");
});

iconeRemoveTable2.addEventListener("click", function () {
    table2.classList.add("ocultar");
    iconeAddTables2.classList.remove("ocultar");
    iconeRemoveTable2.classList.add("ocultar");
});


function calcularHorasIntermediarias(horaEntrada, horaSaida, horaParaPular) {
    var horasIntermediarias = [];
    var entradaHora = parseInt(horaEntrada.substring(0, 2));
    //diminuimos uma hora da saida do operador de caixa devido esta hora ser a de fechamento de caixa
    var saidaHora = (parseInt(horaSaida.substring(0, 2))) - 1;
    var pularHora = parseInt(horaParaPular.substring(0, 2));

    while (entradaHora < saidaHora || entradaHora === saidaHora) {
        // Verifica se a hora atual não é a hora para pular nem a hora seguinte à hora para pular
        if (entradaHora !== pularHora && entradaHora !== pularHora + 1) {
            horasIntermediarias.push('"' + entradaHora.toString().padStart(2, '0') + ':00' + '"');
        }

        entradaHora++;

        // Verifica se a hora atual é a hora seguinte à hora para pular e avança para a próxima hora
        if (entradaHora === pularHora) {
            entradaHora++;
        }
    }

    return horasIntermediarias;
}




var usuarioLogado = $("#usuarioLogado").val();

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

    $selects.not(this).find('option[value="' + nomeSelecionado + '"]').remove();
    $.ajax({
        url: "filtro/busca_infosFuncionarios.php",
        method: 'get',
        data: 'MatriculaDaPessoaSelecionada=' +
            MatriculaDaPessoaSelecionada +
            "&loja=" +
            +loja +
            "&dataPesquisa=" +
            dataPesquisa +
            "&nomeSelecionado=" +
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






});



$('#table1').on('change', '.estilizaSelect2', function () {
    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = $("#dataAtual").val();

    if (dataPesquisa == "") {
        dataPesquisa = dataAtual
    }
    var MatriculaDaPessoaSelecionada2 = $(this).val();
    var nomeSelecionado2 = $(this).find('option:selected').text();
    var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();
    var $selects2 = $('#table1 .estilizaSelect2');
    var matricula2 = $(this).parent().parent().find(".matricula2").closest(".matricula2");
    var entrada2 = $(this).parent().parent().find(".horaEntrada2").closest(".horaEntrada2");
    var saida2 = $(this).parent().parent().find(".horaSaida2").closest(".horaSaida2");
    var intervalo2 = $(this).parent().parent().find(".horaIntervalo2").closest(".horaIntervalo2");


    $selects2.not(this).find('option[value="' + nomeSelecionado2 + '"]').remove();

    $.ajax({
        url: "filtro/busca_infosFuncionarios.php",
        method: 'get',
        data: 'MatriculaDaPessoaSelecionada=' +
            MatriculaDaPessoaSelecionada2 +
            "&loja=" +
            +loja +
            "&dataPesquisa=" +
            dataPesquisa +
            "&nomeSelecionado=" +
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
                    $.ajax({
                        url: "config/pesquisar_quantidade_De_Pessoas.php",
                        method: 'POST',
                        data: 'dataPesquisa=' +
                            dataPesquisa +
                            "&loja=" +
                            loja,
                        success: function (quant_pessoas_att) {

                            $('.atualizaOpPorDia').empty().html(quant_pessoas_att);


                        }
                    });

                    $.ajax({
                        url: "config/pesquisar_Operadores_por_horario.php",
                        method: 'POST',
                        data: 'dataPesquisa=' +
                            dataPesquisa +
                            "&loja=" +
                            loja,
                        success: function (porcentagemDePessoasConformePesquisa) {

                            $('.CalculoDosOperadoresPorHorario').empty().html(porcentagemDePessoasConformePesquisa);


                        }
                    });

                    criandoHtmlmensagemCarregamento("ocultar");

                }
            });
        }
    });




});

$('#table1').on('click', '.fa-trash', function () {
    var $row = $(this).closest('tr'); // Captura a linha atual
    var opcaoDeExclusao = $(this).closest('td').attr('value');

    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = $("#dataAtual").val();

    if (dataPesquisa == "") {
        dataPesquisa = dataAtual;
    }
    var numPDV = $row.find(".numerosPDVS").text().trim();
    $.ajax({
        url: "config/remove_linha_relatorio_pdv.php",
        method: 'get',
        data: {
            dataPesquisa: dataPesquisa,
            numPDV: numPDV,
            opcaoDeExclusao: opcaoDeExclusao,
            loja: loja
        },
        success: function (atualizaTabela) {
            if (opcaoDeExclusao == "ExcluirManha") {
                var matricula = $row.find('.Matricula1').text().trim();
                var nome = $row.find('.NomeFunc select option:selected').text().trim();
                var entrada = $row.find('.horaEntrada1').text().trim();
                var saida = $row.find('.horaSaida1').text().trim();
                var intervalo = $row.find('.horaIntervalo1').text().trim();
            } else if (opcaoDeExclusao == "ExcluirTarde") {
                var matricula = $row.find('.Matricula2').text().trim();
                var nome = $row.find('.NomeFunc select option:selected').text().trim();
                var entrada = $row.find('.horaEntrada2').text().trim();
                var saida = $row.find('.horaSaida2').text().trim();
                var intervalo = $row.find('.horaIntervalo2').text().trim();
            }
            if (matricula == '' || nome == '' || entrada == '' || saida == '' || intervalo == '') {
                Toasty("Atenção", "não há funcionarios cadastrado neste PDV", "#E20914");

            } else {
                console.log('Matricula: ' + matricula + '\nNome: ' + nome + '\nEntrada: ' + entrada + '\nSaída: ' + saida + '\nIntervalo: ' + intervalo);
                var horasIntermediarias = calcularHorasIntermediarias(entrada, saida, intervalo);
                nome = '';
                $.ajax({
                    url: "config/exibicao_escala_diaria_pdv.php",
                    method: 'get',
                    data: 'DadosMatricula=' +
                        matricula +
                        "&nomeSelecionado=" +
                        nome +
                        "&DadosEntrada=" +
                        entrada +
                        "&DadosSaida=" +
                        saida +
                        "&DadosIntervalo=" +
                        intervalo +
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
            }

        }
    });


});