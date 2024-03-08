import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";
var usuarioLogado = $("#usuarioLogado").val();

$('#PesquisarEscalaMensal').on('click', function () {


    var mesPesquisa = $("#dataPesquisa").val();
    var loja = $("#loja").val();
    var mesAtual = $("#mesAtual").val();
    var Departamento = $('#DEPARTAMENTO').val();
    if (mesPesquisa == "") {
        mesPesquisa = mesAtual
    }
    if (loja == "") {
        Toasty("Atenção", "Selecione uma loja para Continuar", "#E20914");
    } else {
        criandoHtmlmensagemCarregamento("exibir");
        $.ajax({
            url: "config/Pesquisar_gerencEscalaPDV.php",
            method: 'POST',
            data: 'mesPesquisa=' +
                mesPesquisa +
                "&loja=" +
                loja +
                "&usuarioLogado=" +
                usuarioLogado +
                "&Departamento=" +
                Departamento,
            success: function (mes_Pesquisado) {
                $('#PesquisaEscalaMensal').empty().html(mes_Pesquisado);
                $('.blocoVerificaELiberaEscala').css('visibility', 'visible');
                $('.estilezaSelect').attr('disabled', 'disabled');
                $('.estilezaSelect').css('font-weight', 'bold');
                $('.statusDaTabela').prop('type', 'text');
                $('.statusDaTabela').css('border', 'none');
                $('.statusDaTabela').attr('disabled', 'disabled');
                if ($('.statusDaTabela').val() === "JÁ FINALIZADA.") {
                    $('#LiberarEscala').attr('disabled', false);
                    $('.statusDaTabela').after("<p class='retornoDoStatusDaTabela'>A Escala do mês está finalizada</p>");
                    $('.statusDaTabela').hide();
                    $('#chamaModal').removeAttr('disabled');
                } else {
                    $('#LiberarEscala').attr('disabled', 'disabled');
                    $('.statusDaTabela').after("<p class='retornoDoStatusDaTabela'>A Escala do mês não está finalizada</p>");
                    $('.statusDaTabela').hide();
                    $('#chamaModal').attr('disabled', 'disabled');
                }

                criandoHtmlmensagemCarregamento("ocultar");

            }
        });

    }
});

$('#LiberarEscala').on('click', function () {


    var alteraStatusEscala = '';
    var loja = $("#loja").val();
    var mesPesquisa = $("#dataPesquisa").val();

    var mesAtual = $("#mesAtual").val();

    if (mesPesquisa == "") {
        mesPesquisa = mesAtual
    }
    var Departamento = $('#DEPARTAMENTO').val();
    var MotivoLiberacaoEscala = $('#MotivoLiberacaoEscala').val();

    if (MotivoLiberacaoEscala == '') {
        Toasty("Atenção", "Digite o motivo da liberação da escala", "#E20914");
    } else {
        criandoHtmlmensagemCarregamento("exibir");
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
            success: function (atualizaTabela) {
                $.ajax({
                    url: "config/insert_log_liberacao.php",
                    method: 'POST',
                    data: 'mesPesquisa=' +
                        mesPesquisa +
                        "&loja=" +
                        loja +
                        "&usuarioLogado=" +
                        usuarioLogado +
                        "&Departamento=" +
                        Departamento +
                        "&MotivoLiberacaoEscala=" +
                        MotivoLiberacaoEscala,
                    success: function () {
                    }
                });
                $.ajax({
                    url: "config/pesquisar_gerencEscalaPDV.php",
                    method: 'POST',
                    data: 'mesPesquisa=' +
                        mesPesquisa +
                        "&loja=" +
                        loja +
                        "&usuarioLogado=" +
                        usuarioLogado+
                        "&Departamento=" +
                        Departamento,
                    success: function (mes_Pesquisado) {
                        
                        criandoHtmlmensagemCarregamento("ocultar");
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
                            Toasty("Sucesso", "A escala foi Liberada !", "#00a550");                             
                            $('#chamaModal').removeAttr('disabled');
                        } else {
                            $('#LiberarEscala').attr('disabled', 'disabled');
                            $('.estilezaSelect').css('font-weight', 'bold');
                            $('.statusDaTabela').after("<p class='retornoDoStatusDaTabela'>A Escala do mês não está finalizada</p>");
                            $('.statusDaTabela').remove();
                            $('#chamaModal').attr('disabled', 'disabled');
                        }
                        $('#exampleModal').modal('hide');
                        //não está fechando o modal ainda
                    }
                });
            }
        });
    }
});
