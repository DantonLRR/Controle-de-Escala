import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";

$('.atrrcheckAdicionarMovimentacao').on('click', function (e) {
    if ($('.atrrcheckAdicionarMovimentacao').is(':checked')) {
        $('.checkboxAdicionarMovimentacao').prop('checked', true);
    } else {
        $('.checkboxAdicionarMovimentacao').prop('checked', false);
    }

});
$('.atrrcheckInativarCargo').on('click', function (e) {
    if ($('.atrrcheckInativarCargo').is(':checked')) {
        $('.checkboxInativarCargo').prop('checked', true);
    } else {
        $('.checkboxInativarCargo').prop('checked', false);
    }

});
$('.checkboxRemoverMovimentacao').on('click', function (e) {
    if ($('.checkboxRemoverMovimentacao').is(':checked')) {
        $('.checkboxRemoverMovimentacao').prop('checked', true);
    } else {
        $('.checkboxRemoverMovimentacao').prop('checked', false);
    }

});
$('.atrrcheck').on('click', function (e) {
    if ($('.atrrcheck').is(':checked')) {
        $('.checkbox').prop('checked', true);
    } else {
        $('.checkbox').prop('checked', false);
    }

});
$('.atrrcheck1').on('click', function (e) {
    if ($('.atrrcheck1').is(':checked')) {
        $('.checkbox1').prop('checked', true);
    } else {
        $('.checkbox1').prop('checked', false);
    }

});
$('.MinimizarTipoMovimentacao').on('click', function (e) {
    $(".cardTipoMovimentacao").addClass("ocultar");
    $(".MinimizarTipoMovimentacao").addClass("ocultar");
    $(".maximizarTipoMovimentacao").removeClass("ocultar");


});
$('.maximizarTipoMovimentacao').on('click', function (e) {
    $(".cardTipoMovimentacao").removeClass("ocultar");
    $(".MinimizarTipoMovimentacao").removeClass("ocultar");
    $(".maximizarTipoMovimentacao").addClass("ocultar");
});
$('.MinimizarGerenciarMovimentacao').on('click', function (e) {
    $(".cardGerenciarMovimentacao").addClass("ocultar");
    $(".MinimizarGerenciarMovimentacao").addClass("ocultar");
    $(".maximizarGerenciarMovimentacao").removeClass("ocultar");


});
$('.maximizarGerenciarMovimentacao').on('click', function (e) {
    $(".cardGerenciarMovimentacao").removeClass("ocultar");
    $(".MinimizarGerenciarMovimentacao").removeClass("ocultar");
    $(".maximizarGerenciarMovimentacao").addClass("ocultar");
});
$('.MinimizarCargoRisco').on('click', function (e) {
    $(".cardCargoRisco").addClass("ocultar");
    $(".MinimizarCargoRisco").addClass("ocultar");
    $(".maximizarCargoRisco").removeClass("ocultar");


});
$('.maximizarCargoRisco').on('click', function (e) {
    $(".cardCargoRisco").removeClass("ocultar");
    $(".MinimizarCargoRisco").removeClass("ocultar");
    $(".maximizarCargoRisco").addClass("ocultar");
});
$('.Remover').on('click', function (a) {

    var checkede = $('.checkbox1').toArray().map(function (checkede) {
        return $(checkede).is(':checked');
    });

    for (var i = 0, l = checkede.length; i < l; i++) {

        if (checkede[i] == true) {

            var checkedvazio = 'true'
        }
    }

    if (checkedvazio == 'true') {

        var idCargoComRisco = $('.checkbox1:checked').parent().parent().find(".idCargoComRisco").closest('.idCargoComRisco').toArray().map(function (idCargoComRisco) {
            return $(idCargoComRisco).text();
        });

        console.log(idCargoComRisco);

        $.ajax({
            url: "Config/removerCargoRisco.php",
            method: 'get',
            data: 'idCargoComRisco=' + idCargoComRisco,
            success: function (filtro) {
                Toasty("Sucesso", "Cargo Removido", "Red");
                window.location.href = "cargoRisco.php";


            }
        });
    }



});
$('.Adicionar').on('click', function (a) {
    console.log('Adicionar');
    var checkede = $('.checkbox').toArray().map(function (checkede) {
        return $(checkede).is(':checked');
    });

    for (var i = 0, l = checkede.length; i < l; i++) {

        if (checkede[i] == true) {

            var checkedvazio = 'true'
        }
    }

    if (checkedvazio == 'true') {

        var cargoDisponivel = $('.checkbox:checked').parent().parent().find(".cargoDisponivel").closest('.cargoDisponivel').toArray().map(function (cargoDisponivel) {
            return $(cargoDisponivel).text();
        });

        console.log(cargoDisponivel);

        $.ajax({
            url: "Config/AdicionarCargoRisco.php",
            method: 'get',
            data: 'cargoDisponivel=' + cargoDisponivel,
            success: function (filtro) {
                Toasty("Sucesso", "Cargo Cadastrado", "#00a550");
                window.location.href = "cargoRisco.php";


            }
        });
    }
});
$('.AdicionarMovimentacao').on('click', function (a) {

    var checkede = $('.checkboxAdicionarMovimentacao').toArray().map(function (checkede) {
        return $(checkede).is(':checked');
    });

    for (var i = 0, l = checkede.length; i < l; i++) {

        if (checkede[i] == true) {

            var checkedvazio = 'true'
        }
    }

    if (checkedvazio == 'true') {

        var IDMovimentacao = $('.checkboxAdicionarMovimentacao:checked').parent().parent().find(".IDMovimentacao").closest('.IDMovimentacao').toArray().map(function (IDMovimentacao) {
            return $(IDMovimentacao).text();
        });
        var MovimentacaoDisponivel = $('.checkboxAdicionarMovimentacao:checked').parent().parent().find(".MovimentacaoDisponivel").closest('.MovimentacaoDisponivel').toArray().map(function (MovimentacaoDisponivel) {
            return $(MovimentacaoDisponivel).text();
        });

        console.log(MovimentacaoDisponivel);

        $.ajax({
            url: "Config/AdicionarTipoMovimentacao.php",
            method: 'get',
            data: 'IDMovimentacao=' + IDMovimentacao + '&STATUS=' + MovimentacaoDisponivel,
            success: function (filtro) {
                Toasty("Sucesso", "Cargo Cadastrado", "#00a550");
                window.location.href = "cargoRisco.php";


            }
        });
    }
});
$('.reativarCargoInativos').on('click', function (a) {

    var checkede = $('.checkboxReativarCargoInativos').toArray().map(function (checkede) {
        return $(checkede).is(':checked');
    });

    for (var i = 0, l = checkede.length; i < l; i++) {

        if (checkede[i] == true) {

            var checkedvazio = 'true'
        }
    }

    if (checkedvazio == 'true') {

        var idCargoInativo = $('.checkboxReativarCargoInativos:checked').parent().parent().find(".idCargoInativo").closest('.idCargoInativo').toArray().map(function (idCargoInativo) {
            return $(idCargoInativo).text();
        });

        console.log(idCargoInativo);

         $.ajax({
             url: "Config/inativarCargo.php",
             method: 'get',
             data: 'idCargoInativo=' + idCargoInativo,
             success: function (filtro) {
                 Toasty("Sucesso", "Cargo Cadastrado", "#00a550");
                 window.location.href = "cargoRisco.php";
             }
         });
    }
});
$('.inativarCargo').on('click', function (a) {

    var checkede = $('.checkboxInativarCargo').toArray().map(function (checkede) {
        return $(checkede).is(':checked');
    });

    for (var i = 0, l = checkede.length; i < l; i++) {

        if (checkede[i] == true) {

            var checkedvazio = 'true'
        }
    }

    if (checkedvazio == 'true') {

        var cargoAtivo = $('.checkboxInativarCargo:checked').parent().parent().find(".cargoAtivo").closest('.cargoAtivo').toArray().map(function (cargoAtivo) {
            return $(cargoAtivo).text();
        });

        console.log(cargoAtivo);

        $.ajax({
            url: "Config/inativarCargo.php",
            method: 'get',
            data: 'cargoAtivo=' + cargoAtivo,
            success: function (filtro) {
                Toasty("Sucesso", "Cargo Cadastrado", "#00a550");
                window.location.href = "cargoRisco.php";


            }
        });
    }
});
$('.RemoverMovimentacao').on('click', function (a) {
    console.log('STATUS');
    var checkede = $('.checkboxRemoverMovimentacao').toArray().map(function (checkede) {
        return $(checkede).is(':checked');
    });

    for (var i = 0, l = checkede.length; i < l; i++) {

        if (checkede[i] == true) {

            var checkedvazio = 'true'
        }
    }

    if (checkedvazio == 'true') {

        var IDMovimentacao = $('.checkboxRemoverMovimentacao:checked').parent().parent().find(".IDMovimentacao").closest('.IDMovimentacao').toArray().map(function (IDMovimentacao) {
            return $(IDMovimentacao).text();
        });
        var MovimentacaoRisco = $('.checkboxRemoverMovimentacao:checked').parent().parent().find(".MovimentacaoRisco").closest('.MovimentacaoRisco').toArray().map(function (MovimentacaoRisco) {
            return $(MovimentacaoRisco).text();
        });
        console.log(STATUS);

        $.ajax({
            url: "Config/AdicionarTipoMovimentacao.php",
            method: 'get',
            data: 'IDMovimentacao=' + IDMovimentacao + '&STATUS=' + MovimentacaoRisco,
            success: function (filtro) {
                Toasty("Sucesso", "Cargo Cadastrado", "#00a550");
                window.location.href = "cargoRisco.php";


            }
        });
    }
});


$('.btnGerenciamentoPermissao').on('click', function (e) {
    $(".cardGerenciarMovimentacao").addClass("ocultar");
    $(".cardCargoRisco").addClass("ocultar");
    $(".cardTipoMovimentacao").addClass("ocultar");
    $(".cardGerenciamentoPermissao").removeClass("ocultar");
    $(".cardCargoIativos").addClass("ocultar");
});
$('.btnCargoRisco').on('click', function (e) {
    $(".cardGerenciarMovimentacao").addClass("ocultar");
    $(".cardGerenciamentoPermissao").addClass("ocultar");
    $(".cardTipoMovimentacao").addClass("ocultar");
    $(".cardCargoRisco").removeClass("ocultar");
    $(".cardCargoIativos").addClass("ocultar");
});
$('.btnTipoMovimentacao').on('click', function (e) {
    $(".cardGerenciamentoPermissao").addClass("ocultar");
    $(".cardCargoRisco").addClass("ocultar");
    $(".cardGerenciarMovimentacao").addClass("ocultar");
    $(".cardTipoMovimentacao").removeClass("ocultar");
    $(".cardCargoIativos").addClass("ocultar");
});
$('.btnGerenciarMovimentacao').on('click', function (e) {
    $(".cardGerenciarMovimentacao").removeClass("ocultar");
    $(".cardGerenciamentoPermissao").addClass("ocultar");
    $(".cardCargoRisco").addClass("ocultar");
    $(".cardTipoMovimentacao").addClass("ocultar");
    $(".cardCargoIativos").addClass("ocultar");
});
$('.btnCargoInativos').on('click', function (e) {
    $(".cardCargoIativos").removeClass("ocultar");
    $(".cardGerenciarMovimentacao").addClass("ocultar");
    $(".cardGerenciamentoPermissao").addClass("ocultar");
    $(".cardCargoRisco").addClass("ocultar");
    $(".cardTipoMovimentacao").addClass("ocultar");
});