import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";
var usuarioLogado = $("#usuarioLogado").val();

$('#PesquisarEscalaMensal').on('click', function () {


    var mesPesquisa = $("#dataPesquisa").val();
    var loja = $("#loja").val();
    var mesAtual = $("#mesAtual").val();

    if (mesPesquisa == "") {
        mesPesquisa = mesAtual
    }
    if (loja == "") {
        Toasty("Atenção", "Selecione uma loja para Continuar", "#E20914");
    } else {
        criandoHtmlmensagemCarregamento("exibir");
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
                $('#PesquisaEscalaMensal').empty().html(mes_Pesquisado);
                $('.blocoVerificaELiberaEscala').css('visibility', 'visible');
                $('.estilezaSelect').prop('disabled', true);
                $('.estilezaSelect').css('font-weight', 'bold');
                $('.statusDaTabela').prop('type', 'text');
                $('.statusDaTabela').css('border', 'none');
                $('.statusDaTabela').attr('disabled', 'disabled');

                if ($('.statusDaTabela').val() === "JÁ FINALIZADA.") {
                    $('#LiberarEscala').attr('disabled', false);
                    $('.statusDaTabela').after("<p class='retornoDoStatusDaTabela'>A Escala do mês está finalizada</p>");
                    $('.statusDaTabela').remove();
                }else{
                    $('#LiberarEscala').attr('disabled', 'disabled');
                    $('.statusDaTabela').after("<p class='retornoDoStatusDaTabela'>A Escala do mês não está finalizada</p>");
                    $('.statusDaTabela').remove();
                  
                }


                criandoHtmlmensagemCarregamento("ocultar");

            }
        });

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

    ],

});
$('#LiberarEscala').on('click', function () {

    criandoHtmlmensagemCarregamento("exibir");
    var alteraStatusEscala = '';
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

                    $('#PesquisaEscalaMensal').empty().html(mes_Pesquisado);

                    $('.estilezaSelect').attr('disabled', 'disabled');
                    $('.statusDaTabela').prop('type', 'text');
                    $('.statusDaTabela').css('border', 'none');
                    $('.statusDaTabela').attr('disabled', 'disabled');
                    if ($('.statusDaTabela').val() === "JÁ FINALIZADA.") {
                        $('#LiberarEscala').attr('disabled', false);
                        $('.estilezaSelect').css('font-weight', 'bold');
                        $('.statusDaTabela').after("<p class='retornoDoStatusDaTabela'>A Escala do mês está finalizada</p>");
                        $('.statusDaTabela').remove();
                        criandoHtmlmensagemCarregamento("ocultar");
                        Toasty("Sucesso", "A escala foi Liberada !", "#00a550");
                    }else{
                        $('#LiberarEscala').attr('disabled', 'disabled');
                        $('.estilezaSelect').css('font-weight', 'bold');
                        $('.statusDaTabela').after("<p class='retornoDoStatusDaTabela'>A Escala do mês não está finalizada</p>");
                        $('.statusDaTabela').remove();
                    }

                }
            });



        }
    });



});
