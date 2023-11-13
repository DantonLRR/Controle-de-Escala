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



$('.table_man_dados').DataTable({
    dom: 'Bfrtip',
    "paging": true,
    "info": false,
    "searching": true,
    "ordering": false,
    scrollY: "280px",
    scrollX: true,
    "lengthMenu": [
        [50],
        [50]
    ],
    buttons: [
        {
            text: 'Remover',
            // action: function () {


            //     var checkede = $('.checkbox:checked');

            //     if (checkede.length > 0) {
            //         var cargos = [];

            //         checkede.each(function () {
            //             var cargo = $(this).closest('tr').find('.cargo').text().trim();

            //             cargos.push(cargo);
            //         });

            //         console.log('Cargos:', cargos);

            //         $.ajax({
            //             url: "config/crud_RemoveCargoRisco.php",
            //             method: 'get',
            //             data: 'cargos=' + cargos,
            //             success: function (filtro) {
            //                 alert(cargos)
            //                 if (filtro == 0) {
            //                     alert("cargo ja existente")
            //                 } else {
            //                     window.location.href = "cargoRisco.php"
            //                 }
            //             }
            //         });
            //     } else {
            //         alert('Selecione pelo menos um cargo');
            //     }
            // }


        }
    ],

    "language": {
        "sEmptyTable": "Nenhum registro encontrado",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
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
    }

});



var usuInclusao = $("#usuLogado").val();

var loja = $("#loja").val();

var diaDeAlteracaoDoHorario = $("#diaDeHoje").val();

$('#table1').on('blur', '.horaEntrada, .horarioSaidaFunc, .horarioIntervalo', function() {

    var $tr = $(this).closest('tr');
        console.log($tr.html());
    var nomeFuncionario = $tr.find('td.nomeFuncionario').text().trim();
    var matriculaFunc = $tr.find('td.matriculaFunc').text().trim();
    var horarioEntradaFunc = $tr.find('td.horaEntrada input').val().trim();
    var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc input').val().trim();
    var horarioIntervaloFunc = $tr.find('td.horarioIntervalo input').val().trim();
    

    console.log(nomeFuncionario);
    console.log(matriculaFunc);
    console.log(horarioEntradaFunc);
    console.log(horarioSaidaFunc);
    console.log(horarioIntervaloFunc);
    console.log(loja);
    console.log(usuInclusao);
     console.log(diaDeAlteracaoDoHorario);

        $.ajax({
        url:"config/alteracao_horario_escalaDiaria.php",
        method:"POST",
        data: 'nomeFuncionario=' +
        nomeFuncionario +
        "&matriculaFunc=" +
        matriculaFunc+
        "&horarioEntradaFunc=" +
        horarioEntradaFunc+
        "&horarioSaidaFunc=" +
        horarioSaidaFunc+
        "&horarioIntervaloFunc=" +
        horarioIntervaloFunc+
        "&loja=" +
        loja+
        "&usuInclusao=" +
        usuInclusao+
        "&diaDeAlteracaoDoHorario=" +
        diaDeAlteracaoDoHorario,
    })
});