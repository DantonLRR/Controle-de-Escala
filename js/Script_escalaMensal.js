import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";

var usuarioLogado = $("#usuarioLogado").val();
var loja = $("#loja").val();


var statusDaTabela = $("#statusDaTabela").val();
var Departamento = $('#dadosDeQuemEstaLogadoSetor').val();
var mesAtual = $("#mesAtual").val();


window.onload = function () {
    $(document).ready(function () {
        var colunas = document.querySelectorAll('.diaDaSemana');
        colunas.forEach(function (coluna) {
            var indexDomingo = $(coluna).index();
            if ($(coluna).text().includes("DOM")) {
                $('tr').each(function () {
                    $(this).find('td:eq(' + indexDomingo + ')').addClass('domingo');
                });
            }
        });
    });
};


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
        left: 2,
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



                if (mesPesquisa == "") {
                    mesPesquisa = mesAtual
                };


                //  Array para armazenar as matrículas
                var matriculas = [];

                //  Itera sobre cada linha da tabela exceto a primeira (cabeçalho)
                $('#table1 tbody tr').each(function () {
                    //  Recupera a matrícula da célula oculta
                    var matricula = $(this).find('.matriculaFunc').text().trim();
                    //  Adiciona a matrícula ao array se não estiver vazia
                    if (matricula !== "") {
                        matriculas.push(matricula);
                    }
                });

                //  Agora, matriculas[] contém todas as matrículas da tabela
                // console.log(matriculas);
                $.ajax({
                    method: 'POST',
                    url: "config/verificacao_finalizacao_escala_mensal.php",
                    dataType: 'json',
                    data: "mesPesquisa=" +
                        mesPesquisa +
                        "&mesAtual=" +
                        mesAtual +
                        "&alteraStatusEscala=" +
                        alteraStatusEscala +
                        "&loja=" +
                        loja +
                        "&matriculas=" +
                        matriculas +
                        "&usuarioLogado=" +
                        usuarioLogado +
                        "&Departamento=" +
                        Departamento,

                    success: function (retornoVerificacao) {
                        // alert();
                        // alert(retornoVerificacao.ESCALALIBERADAPARAFINALIZACAO); // Exibe true ou false
                        // alert(retornoVerificacao.MENSAGEM); // Exibe a mensagem retornada
                        // alert(retornoVerificacao.MENSAGEM);
                        if (retornoVerificacao.ESCALALIBERADAPARAFINALIZACAO == false) {
                            Toasty("Atenção", retornoVerificacao.MENSAGEM, "#E20914");
                            criandoHtmlmensagemCarregamento("ocultar");
                            // alert();
                        } else {
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
                                    "&matriculas=" +
                                    matriculas +
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

                var usuarioLogado = $("#usuarioLogado").val();
                var loja = $("#loja").val();

                var mesPesquisa = $("#dataPesquisa").val();


                var diretorioDoPdf = "contrato.php";
                if (mesPesquisa == "") {
                    mesPesquisa = mesAtual
                };


                criandoHtmlmensagemCarregamento("exibir");

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
                        let blobUrl = URL.createObjectURL(response);
                        window.open(blobUrl, "_blank");
                        criandoHtmlmensagemCarregamento("ocultar");
                    },
                    error: function (xhr, status, error) {
                        // console.log(error);

                    },
                });

            }

        },
        {
            text: '<i class="fa-solid fa-calendar" style="color: #ffffff;"></i> Lançamento De Ferias ',
            className: 'btnVermelho btnverde',
            action: function () {
                criandoHtmlmensagemCarregamento("exibir");
                $('#modalFerias').modal('show');

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
        var selects = $(this).closest('tr').find('.estilezaSelect');
        var colIndex = $(this).closest('td').index();
        var indexAtual = selects.index(this);
        var PeriodoMaximoDeDiasTrabalhados = false
        var indexUltimoPreenchido = -1;
        var indexProximoPreenchido;
        selects.slice(0, indexAtual).each(function (index) {
            if ($(this).val() !== '') {
                indexUltimoPreenchido = index;
            }
        });

        selects.slice(indexAtual + 1).each(function (index) {
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
            var textoOpcaoSelecionado = $(this).find('option:selected'); // Obtém a opção selecionada
            var texto = textoOpcaoSelecionado.text().substring(0, 3); // Trunca o texto para os primeiros 3 caracteres
            textoOpcaoSelecionado.text(texto); // Define o texto truncado na opção
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




// modal

function ultimoDiaDoMes(ano, mes) {
    return new Date(ano, mes, 0).getDate();
}

function primeiroDiaDoMes(ano, mes) {
    return new Date(ano, mes - 1, 1).getDate();
}


$('#modalFerias').on('change', '#funcionarioFerias', function () {
    let funcionarioFeriasMatricula = $('#funcionarioFerias').val();
    let loja = $('#lojaDaPessoaLogada').val();

    $.ajax({
        url: "Config/select_InformacaoUsuarioParaFerias.php",
        method: "POST",
        data: "funcionarioFeriasMatricula=" +
            funcionarioFeriasMatricula +
            "&loja=" +
            loja +
            "&Departamento=" +
            Departamento,
        dataType: 'json',
        success: function (retorno) {
            if (retorno === false || retorno === "false") {
                Toasty("Atenção", "Erro ao obter informações do usuário por favor procure o administrador", "#E20914");
            } else {
                $('#cargo').val(retorno.FUNCAO);
                $('#horarioEntradaFunc').val(retorno.HORAENTRADA);
                $('#horarioSaidaFunc').val(retorno.HORASAIDA);
                $('#horarioIntervaloFunc').val(retorno.HORAINTERVALO);
            }
        },
    });
})

$('#modalFerias').on('click', '#feriasAgendadas', function () {
    $('#feriasAgendadas').addClass('ocultarBotao');
    $('#salletFerias').addClass('ocultarBotao');
    $('#AgendamentoFerias').removeClass('ocultarBotao');
    let loja = $('#lojaDaPessoaLogada').val();
    $.ajax({
        url: "modal/modalCancelarFerias.php",
        method: 'POST',
        data: 'Departamento=' +
            Departamento +
            "&loja=" +
            loja,
        success: function (modalCancelarFerias) {
            $('.tabelaCancelamentoFerias').empty().html(modalCancelarFerias);
        }
    });

})
$('#modalFerias').on('click', '#AgendamentoFerias', function () {
    $('#AgendamentoFerias').addClass('ocultarBotao');
    $('#salvarFerias').removeClass('ocultarBotao');
    $('#feriasAgendadas').removeClass('ocultarBotao');
    let loja = $('#lojaDaPessoaLogada').val();

    $.ajax({
        url: "modal/modalFerias.php",
        method: 'POST',
        data: 'Departamento=' +
            Departamento +
            "&loja=" +
            loja,
        success: function (modalFerias) {
            $('.tabelaCancelamentoFerias').empty().html(modalFerias);
        }
    });

})
$('#modalFerias').on('click', '#salvarFerias', function () {
   
    let opcaoSelecionada = 'F';
    let dataInicialFerias = $('#dataInicialFerias').val();
    let dataFinalFerias = $('#dataFinalFerias').val();
    let funcionarioFerias = $('#funcionarioFerias').val();
    let NomefuncionarioFerias = $('#funcionarioFerias option:selected').text();
    let horarioEntradaFunc = $("#horarioEntradaFunc").val();
    let horarioSaidaFunc = $("#horarioSaidaFunc").val();
    let horarioIntervaloFunc = $("#horarioIntervaloFunc").val();
    // console.log(" correto dataInicialFerias :" + dataInicialFerias);
    // console.log(" correto dataFinalFerias :" + dataFinalFerias);
    if (dataFinalFerias == '' || dataInicialFerias == '' || funcionarioFerias == '') {
        Toasty("Atenção", "Preencha todos os campos", "#E20914");
    } else if (dataInicialFerias >= dataFinalFerias) {
        Toasty("Atenção", "Data final das ferias nao pode ser menor que a data inicial", "#E20914");

    } else {
        criandoHtmlmensagemCarregamento("exibir");
        InsereNoBanco(
            opcaoSelecionada, dataInicialFerias,
            dataFinalFerias, funcionarioFerias, NomefuncionarioFerias, horarioEntradaFunc, horarioSaidaFunc,
            horarioIntervaloFunc, Departamento
        );

        let loja = $('#lojaDaPessoaLogada').val();
        setTimeout(function () {
            $.ajax({
                url: "modal/modalFerias.php",
                method: 'POST',
                data: 'Departamento=' +
                    Departamento +
                    "&loja=" +
                    loja,
                success: function (modalCancelarFerias2) {
                    Toasty("Sucesso", "Férias do colaborador: " + NomefuncionarioFerias + " foram agendadas", "#00a550")
                    $('.tabelaCancelamentoFerias').empty().html(modalCancelarFerias2);
                    criandoHtmlmensagemCarregamento("ocultar");
                }
            });
        }, 2000); // 2000 milissegundos = 2 segundos

    }
});
$('#modalFerias').on('click', '.fa-trash', function () {
    criandoHtmlmensagemCarregamento("exibir");
    let opcaoSelecionada = '';
    let $tr = $(this).closest('tr');
    let funcionarioFerias = $tr.find('td.matricula').text().trim();
    let NomefuncionarioFerias = $tr.find('td.funcionario').text().trim();
    let horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text().trim();
    let horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text().trim();
    let horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text().trim();
    let dataInicialFerias = $tr.find('td.dataInicialFerias').text().trim();
    let dataFinalFerias = $tr.find('td.dataFinalFerias').text().trim();

    InsereNoBanco(
        opcaoSelecionada, dataInicialFerias,
        dataFinalFerias, funcionarioFerias, NomefuncionarioFerias, horarioEntradaFunc, horarioSaidaFunc,
        horarioIntervaloFunc, Departamento
    );
    let loja = $('#lojaDaPessoaLogada').val();
    setTimeout(function () {
        $.ajax({
            url: "modal/modalCancelarFerias.php",
            method: 'POST',
            data: 'Departamento=' +
                Departamento +
                "&loja=" +
                loja,
            success: function (modalCancelarFerias2) {
                Toasty("Sucesso", "Férias do colaborador: " + NomefuncionarioFerias + " foram CANCELADAS.", "#00a550")
                $('.tabelaCancelamentoFerias').empty().html(modalCancelarFerias2);
                criandoHtmlmensagemCarregamento("ocultar");
            }
        });
    }, 2000); // 2000 milissegundos = 2 segundos


});



function InsereNoBanco(opcaoSelecionadaPARAMETRO, dataInicialFeriasPARAMETRO, dataFinalFeriasPARAMETRO, funcionarioFeriasPARAMETRO, NomefuncionarioFeriasPARAMETRO, horarioEntradaFuncPARAMETRO, horarioSaidaFuncPARAMETRO,
    horarioIntervaloFuncPARAMETRO, DepartamentoPARAMETRO) {
    var opcaoSelecionada = opcaoSelecionadaPARAMETRO
    var dataInicialFerias = dataInicialFeriasPARAMETRO;
    var dataFinalFerias = dataFinalFeriasPARAMETRO;
    var funcionarioFerias = funcionarioFeriasPARAMETRO;
    var NomefuncionarioFerias = NomefuncionarioFeriasPARAMETRO;
    var horarioEntradaFunc = horarioEntradaFuncPARAMETRO;
    var horarioSaidaFunc = horarioSaidaFuncPARAMETRO;
    var horarioIntervaloFunc = horarioIntervaloFuncPARAMETRO;
    var DepartamentoFunctionInsereNoBanco = DepartamentoPARAMETRO;
    var remocaoDeFeriasProgramadas = ''
    var loja = $('#lojaDaPessoaLogada').val();
    var cargo = $("#cargo").val();
    var usuarioLogado = $('#usuarioLogado').val();
    let diaInicial = parseInt(dataInicialFerias.split('-')[2]); // Obtém o dia
    let mesInicial = parseInt(dataInicialFerias.split('-')[1]).toString().padStart(2, '0');
    let anoInicial = parseInt(dataInicialFerias.split('-')[0]); // Obtém o ano
    let diaFinal = parseInt(dataFinalFerias.split('-')[2]); // Obtém o dia
    let mesFinal = parseInt(dataFinalFerias.split('-')[1]).toString().padStart(2, '0');
    let anoFinal = parseInt(dataFinalFerias.split('-')[0]); // Obtém o ano
    let mesAnoFinal = anoFinal + "-" + mesFinal;
    let mesAnoInicial = anoInicial + "-" + mesInicial;
    let numeroDiaDaSemanaArrayParaInserirFerias = [];
    let numeroDiaDaSemanaArrayInsereNosDiasFaltantesDoProximoMes = [];
    if (mesAnoInicial < mesAtual) {
        Toasty("Atenção", "não é possivel programar ferias para meses anteriores ao atual", "#E20914");
        criandoHtmlmensagemCarregamento("ocultar");
        exit;
    }

    let programaFerias = 'sim'
    let ultimoDiaDoMesInicial = ultimoDiaDoMes(anoInicial, mesInicial);
    let DataFinalDoMesInicial = mesAnoInicial + "-" + ultimoDiaDoMesInicial;


    let primeiroDiaDoMesFinal = mesAnoFinal + "-01";
    console.log("dataInicialFerias: " + dataInicialFerias);
    console.log("dataFinalFerias: " + dataFinalFerias);
    // console.log("DataFinalDoMesInicial: " + DataFinalDoMesInicial);
    // console.log("primeiroDiaDoMesFinal: " + primeiroDiaDoMesFinal);
    // Convertendo as strings em objetos de data
    // Convertendo as strings em objetos de data
    let dataInicialFeriasObj = new Date(
        parseInt(dataInicialFerias.substring(0, 4)), // ano
        parseInt(dataInicialFerias.substring(5, 7)) - 1, // mês (começa de 0)
        parseInt(dataInicialFerias.substring(8, 10)) // dia
    );
    let dataFinalFeriasObj = new Date(
        parseInt(dataFinalFerias.substring(0, 4)), // ano
        parseInt(dataFinalFerias.substring(5, 7)) - 1, // mês (começa de 0)
        parseInt(dataFinalFerias.substring(8, 10)) // dia
    );
    let DiaFinalDoMesInicialObj = new Date(
        parseInt(DataFinalDoMesInicial.substring(0, 4)), // ano
        parseInt(DataFinalDoMesInicial.substring(5, 7)) - 1, // mês (começa de 0)
        parseInt(DataFinalDoMesInicial.substring(8, 10)) // dia
    );
    let primeiroDiaDoMesFinalObj = new Date(
        parseInt(primeiroDiaDoMesFinal.substring(0, 4)), // ano
        parseInt(primeiroDiaDoMesFinal.substring(5, 7)) - 1, // mês (começa de 0)
        parseInt(primeiroDiaDoMesFinal.substring(8, 10)) // dia
    );

    console.log("dataInicialFerias OBJ: " + dataInicialFeriasObj);
    console.log("dataFinalFerias OBJ: " + dataFinalFeriasObj);
    // console.log("DiaFinalDoMesInicial OBJ: " + DiaFinalDoMesInicialObj);
    // console.log("primeiroDiaDoMesFinal OBJ: " + primeiroDiaDoMesFinalObj);
    if (opcaoSelecionada == '') {
        if (mesAnoFinal < mesAtual) {
            Toasty("Atenção", "não é possivel cancelar férias de meses já passados!", "#E20914");
            criandoHtmlmensagemCarregamento("ocultar");
            exit;
        }
        dataInicialFerias = '';
        dataFinalFerias = '';
        remocaoDeFeriasProgramadas = 'sim';
    }


    //se a data final for maior que o ultimo dia do mes ou seja aqui tratamos de casos que iniciam em um mes e terminam em outro
    if (dataFinalFeriasObj > DiaFinalDoMesInicialObj) {
        for (let i = diaInicial; i <= ultimoDiaDoMesInicial; i++) {
            let aux = i < 10 ? "0" + i : i.toString();
            numeroDiaDaSemanaArrayParaInserirFerias.push('"' + aux + '"');
            // console.log("Dia inserido : " + numeroDiaDaSemanaArrayParaInserirFerias);
        }

        $.ajax({
            url: "Config/insertEUpdate_EscalaMensal.php",
            method: 'get',
            data: 'numeroDiaDaSemana=' +
                numeroDiaDaSemanaArrayParaInserirFerias +
                "&opcaoSelecionada=" +
                opcaoSelecionada +
                "&funcionario=" +
                NomefuncionarioFerias +
                "&mesAtual=" +
                mesAnoInicial +
                "&mesPesquisa=" +
                mesAnoInicial +
                "&usuarioLogado=" +
                usuarioLogado +
                "&matriculaFunc=" +
                funcionarioFerias +
                "&loja=" +
                loja +
                "&horarioEntradaFunc=" +
                horarioEntradaFunc +
                "&horarioSaidaFunc=" +
                horarioSaidaFunc +
                "&horarioIntervaloFunc=" +
                horarioIntervaloFunc +
                "&cargoFunc=" +
                cargo +
                "&departamentoFunc=" +
                DepartamentoFunctionInsereNoBanco +
                "&dataInicialFerias=" +
                dataInicialFerias +
                "&dataFinalFerias=" +
                dataFinalFerias +
                "&remocaoDeFeriasProgramadas=" +
                remocaoDeFeriasProgramadas +
                "&programaFerias=" +
                programaFerias,
            success: function (retorno) {

            }
        });

        if (primeiroDiaDoMesFinalObj <= dataFinalFeriasObj) {
            //se dia 01 do mes inicial for menor ou igual a data final das ferias

            for (let i = "1"; i <= diaFinal; i++) {
                let aux = i < 10 ? "0" + i : i.toString();
                numeroDiaDaSemanaArrayInsereNosDiasFaltantesDoProximoMes.push('"' + aux + '"');
                // console.log("Dia inserido final : " + numeroDiaDaSemanaArrayInsereNosDiasFaltantesDoProximoMes);
            }
            $.ajax({
                url: "Config/insertEUpdate_EscalaMensal.php",
                method: 'get',
                data: 'numeroDiaDaSemana=' +
                    numeroDiaDaSemanaArrayInsereNosDiasFaltantesDoProximoMes +
                    "&opcaoSelecionada=" +
                    opcaoSelecionada +
                    "&funcionario=" +
                    NomefuncionarioFerias +
                    "&mesAtual=" +
                    mesAnoFinal +
                    "&mesPesquisa=" +
                    mesAnoFinal +
                    "&usuarioLogado=" +
                    usuarioLogado +
                    "&matriculaFunc=" +
                    funcionarioFerias +
                    "&loja=" +
                    loja +
                    "&horarioEntradaFunc=" +
                    horarioEntradaFunc +
                    "&horarioSaidaFunc=" +
                    horarioSaidaFunc +
                    "&horarioIntervaloFunc=" +
                    horarioIntervaloFunc +
                    "&cargoFunc=" +
                    cargo +
                    "&departamentoFunc=" +
                    DepartamentoFunctionInsereNoBanco +
                    "&dataInicialFerias=" +
                    dataInicialFerias +
                    "&dataFinalFerias=" +
                    dataFinalFerias +
                    "&remocaoDeFeriasProgramadas=" +
                    remocaoDeFeriasProgramadas +
                    "&programaFerias=" +
                    programaFerias,
                success: function (retorno) {

                }
            });
        }
        $.ajax({
            url: "config/pesquisar_escalaMensal.php",
            method: 'POST',
            data: 'mesPesquisa=' +
                mesAnoInicial +
                "&loja=" +
                loja +
                "&usuarioLogado=" +
                usuarioLogado,
            success: function (mes_Pesquisado) {
                $('.atualizaTabela').empty().html(mes_Pesquisado);
            }
        });
    } else {
        for (let i = diaInicial; i <= diaFinal; i++) {
            let aux = i < 10 ? "0" + i : i.toString();
            numeroDiaDaSemanaArrayParaInserirFerias.push('"' + aux + '"');
            // console.log("Dia inserido : " + numeroDiaDaSemanaArrayParaInserirFerias);
        }
        $.ajax({
            url: "Config/insertEUpdate_EscalaMensal.php",
            method: 'get',
            data: 'numeroDiaDaSemana=' +
                numeroDiaDaSemanaArrayParaInserirFerias +
                "&opcaoSelecionada=" +
                opcaoSelecionada +
                "&funcionario=" +
                NomefuncionarioFerias +
                "&mesAtual=" +
                mesAnoInicial +
                "&mesPesquisa=" +
                mesAnoInicial +
                "&usuarioLogado=" +
                usuarioLogado +
                "&matriculaFunc=" +
                funcionarioFerias +
                "&loja=" +
                loja +
                "&horarioEntradaFunc=" +
                horarioEntradaFunc +
                "&horarioSaidaFunc=" +
                horarioSaidaFunc +
                "&horarioIntervaloFunc=" +
                horarioIntervaloFunc +
                "&cargoFunc=" +
                cargo +
                "&departamentoFunc=" +
                DepartamentoFunctionInsereNoBanco +
                "&dataInicialFerias=" +
                dataInicialFerias +
                "&dataFinalFerias=" +
                dataFinalFerias +
                "&remocaoDeFeriasProgramadas=" +
                remocaoDeFeriasProgramadas +
                "&programaFerias=" +
                programaFerias,
            success: function (retorno) {

            }
        });
        $.ajax({
            url: "config/pesquisar_escalaMensal.php",
            method: 'POST',
            data: 'mesPesquisa=' +
                mesAnoInicial +
                "&loja=" +
                loja +
                "&usuarioLogado=" +
                usuarioLogado,
            success: function (mes_Pesquisado) {
                $('.atualizaTabela').empty().html(mes_Pesquisado);

            }
        });
    }
}
