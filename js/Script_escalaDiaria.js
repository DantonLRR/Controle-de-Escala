$('#table1').DataTable({
    scrollY: 280,
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







// Função para aplicar a cor de fundo da linha inteira manualmente
function aplicarCorFundoEClasseSelecionado(linha) {
    linha.classList.toggle("selecionado");

    if (linha.classList.contains("selecionado")) {
        linha.style.backgroundColor = "#00a550d0"; // Aplica a cor de fundo azul
        linha.style.color = "white";

    } else {
        linha.style.backgroundColor = ""; // Remove a cor de fundo
        linha.style.color = "";
    }
}



// adiciona cor na linha da primeira tabela

var tabela = $("#table1");
var linhas = tabela.find('.trr');

for (var i = 0; i < linhas.length; i++) {
    var linha = linhas[i];
    var checkbox = linha.querySelector('input[type="checkbox"]');

    checkbox.addEventListener("change", function () {
        aplicarCorFundoEClasseSelecionado(this.closest('tr'));
    });
}



// adiciona cor na linha da segunda tabela
var tabela = $("#table2");
var linhas = tabela.find('.trteste');

for (var i = 0; i < linhas.length; i++) {
    var linha = linhas[i];
    var checkbox = linha.querySelector('input[type="checkbox"]');

    checkbox.addEventListener("change", function () {
        aplicarCorFundoEClasseSelecionado(this.closest('tr'));
    });
}




