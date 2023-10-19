import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";

var usuarioLogado = $("#usuarioLogado").val();
var loja = $("#loja").val();



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
        data: 'mesPesquisa=' + mesPesquisa+
        "&loja=" +
        loja,
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
    
    var colIndex = $(this).closest('td').index();
    var numeroDiaDaSemana = $('#table1 thead tr.trr th').eq(colIndex).text();
    var mesPesquisa = $("#dataPesquisa").val();

    var mesAtual = $("#mesAtual").val();
    
    if (mesPesquisa == "") {
        mesPesquisa = mesAtual
    }
 
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
            "&usuarioLogado=" +
            usuarioLogado +
            "&matriculaFunc=" +
            matriculaFunc+
            "&loja=" +
            loja,

        // dataType: 'json',
        success: function (retorno) {
            console.log(retorno)

           
        }
    });
});




