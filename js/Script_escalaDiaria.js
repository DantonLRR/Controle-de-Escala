import { criandoHtmlmensagemCarregamento, Toasty } from "../../base/jsGeral.js";


var usuInclusao = $("#usuLogado").val();

var loja = $("#loja").val();

var diaDeAlteracaoDoHorario = $("#diaDeHoje").val();


function adicionarHoras(horario, horasParaAdicionar) {
    if (horario) {
        var partes = horario.split(':'); // Divide o horário nas partes de horas e minutos
        var horas = parseInt(partes[0], 10); // Obtém as horas
        var minutos = parseInt(partes[1], 10); // Obtém os minutos

        horas += horasParaAdicionar; // Adiciona as horas informadas ao valor das horas

        // Lida com a mudança de dia se ultrapassar 24 horas
        horas = horas % 24;

        // Formata a hora e os minutos para exibir no formato desejado
        var horaNova = horas.toString().padStart(2, '0');
        var minutosNovos = minutos.toString().padStart(2, '0');

        return horaNova + ':' + minutosNovos; // Retorna o horário resultante
    } else {
        return "Horário não definido"; // Retorna uma mensagem se não houver horário
    }
}



$('#table1').DataTable({
    scrollY: 480,
    scrollX: false,
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
    // columns: [
    //     { data: '' },
    //     { data: 'Cargo' },
    // ]
});
$('#table1').on('blur', '.horaEntrada, .horarioSaidaFunc, .horarioIntervalo', function () {

    var $tr = $(this).closest('tr');
    // console.log($tr.html());
    var nomeFuncionario = $tr.find('td.nomeFuncionario').text().trim();
    var matriculaFunc = $tr.find('td.matriculaFunc').text().trim();
    var horarioEntradaFunc = $tr.find('td.horaEntrada input').val().trim();
    var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc input').val().trim();
    var horarioIntervaloFunc = $tr.find('td.horarioIntervalo input').val().trim();
    // console.log(nomeFuncionario);
    // console.log(matriculaFunc);
    // console.log(horarioEntradaFunc);
    // console.log(horarioSaidaFunc);
    // console.log(horarioIntervaloFunc);
    // console.log(loja);
    // console.log(usuInclusao);
    //  console.log(diaDeAlteracaoDoHorario);


});
$('#table1').on('change', '.horaEntrada, .horarioSaidaFunc, .horarioIntervalo', function () {
    var $tr = $(this).closest('tr');
    console.log($tr.html());
    var nomeFuncionario = $tr.find('td.nomeFuncionario').text().trim();
    var matriculaFunc = $tr.find('td.matriculaFunc').text().trim();
    var horarioEntradaFunc = $tr.find('td.horaEntrada input').val().trim();
    var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc input').val().trim();
    var horarioIntervaloFunc = $tr.find('td.horarioIntervalo input').val().trim();


    var PeriodoDeTRabalhoAPartirDaHoraEntrada = adicionarHoras(horarioEntradaFunc, 6)
    var PeriodoMaximoParaSaidaDeAlmoço = adicionarHoras(horarioEntradaFunc, 5)
    var PeriodoMaximoHoraExtra = adicionarHoras(horarioEntradaFunc, 8)
    if (horarioEntradaFunc > horarioSaidaFunc) {
        Toasty("Atenção", "A hora de Entrada Não pode ser maior que a de Saida", "#E20914");
    } else if (horarioSaidaFunc < PeriodoDeTRabalhoAPartirDaHoraEntrada) {
        Toasty("Atenção", "o Funcionario precisa cumprir 6 horas de trabalho por dia", "#E20914");
    } else if (horarioIntervaloFunc > PeriodoMaximoParaSaidaDeAlmoço) {
        Toasty("Atenção", "Nenhum funcionario pode cumprir carga maior de 5 horas sem Intervalo", "#E20914");
    } else if (horarioSaidaFunc > PeriodoMaximoHoraExtra) {
        Toasty("Atenção", "o Maximo de Horas Extras Permitidas é de 2 Horas", "#E20914");
    } else {
        $.ajax({
            url: "config/alteracao_horario_escalaDiaria.php",
            method: "POST",
            data: 'nomeFuncionario=' +
                nomeFuncionario +
                "&matriculaFunc=" +
                matriculaFunc +
                "&horarioEntradaFunc=" +
                horarioEntradaFunc +
                "&horarioSaidaFunc=" +
                horarioSaidaFunc +
                "&horarioIntervaloFunc=" +
                horarioIntervaloFunc +
                "&loja=" +
                loja +
                "&usuInclusao=" +
                usuInclusao +
                "&diaDeAlteracaoDoHorario=" +
                diaDeAlteracaoDoHorario,
                sucess: function(){

                }
        })
        Toasty("Sucesso", "o Horario do Operador foi alterado", "#00a550");
    }

})





