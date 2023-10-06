$('#table1').DataTable({
    fixedColumns: 1,
    scrollXInner: "100%",
    scrollY: 280,
    scrollX: true,
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
            text: 'Salvar Alterações',
            className: 'estilizaBotao btn',
            // action: function () {
            //     var checkede = $('.checkbox:checked');
            //     if (checkede.length > 0) {
            //         var cargos = [];
            //         checkede.each(function () {
            //             var cargo = $(this).closest('tr').find('#cargo').text().trim(); // Usando o seletor de ID
            //             cargos.push(cargo);
            //         });
            //         $.ajax({
            //             url: "config/crud_cargoRisco.php",
            //             method: 'get',
            //             data: 'cargos=' + cargos,
            //             success: function (filtro) {
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
        },
        {
            text: 'Imprimir',
            className: 'estilizaBotao btn btnverde', 
            extend: 'print',
            exportOptions: {

            }
        },
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


$('#table2').DataTable({
    scrollY: 280,
    scrollX: true,
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
            text: 'Salvar Alterações',
            className: 'estilizaBotao btn',
            // action: function () {
            //     var checkede = $('.checkbox:checked');
            //     if (checkede.length > 0) {
            //         var cargos = [];
            //         checkede.each(function () {
            //             var cargo = $(this).closest('tr').find('#cargo').text().trim(); // Usando o seletor de ID
            //             cargos.push(cargo);
            //         });
            //         $.ajax({
            //             url: "config/crud_cargoRisco.php",
            //             method: 'get',
            //             data: 'cargos=' + cargos,
            //             success: function (filtro) {
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
        },
        {
            text: 'Imprimir',
            className: 'estilizaBotao btn btnverde', 
            extend: 'print',
            exportOptions: {

            }
        },
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






$('select').on('change', function () {
    $('tr').removeClass('selecionado').css('background-color', '').css('color', '');

    var linha = $(this).closest('tr');
    var opcao = $(this).closest('.teste');
    linha.addClass('selecionado');
    linha.css('background-color', '#00a550d0');
    linha.css('color', 'white');
    opcao.css('font-weight', 'bold');


});



$("#selectMes").change(function() {
    var mesPesquisado = $(this).val() ;
    alert(mesPesquisado);
   $.ajax({
                url: "config/CRUD_geral.php",
                method: 'get',
                data: 'mesPesquisado=' + mesPesquisado,
                success: function () {
                    
                }
            });

});

