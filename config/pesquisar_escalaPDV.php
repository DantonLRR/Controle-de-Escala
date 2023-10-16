<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";

$dataPesquisada = $_POST['dataPesquisa'];


$InformacaoFuncionarios = new Funcionarios();
$FuncManha = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoManha($oracle,$dataPesquisada);
$FuncTarde = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoTarde($oracle,$dataPesquisada);

// session_start();
?>
<input id="dataPesquisa" type="hidden" value="<?= $dataPesquisada ?>">

<table id="table1" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">
    <input class="usu" type="HIDDEN" value="<?= $_SESSION['nome'] ?>">
    <thead style="background-color: #00a550; color: white;">
        <tr class="trr">
            <th class="text-center" colspan="6">Manhã</th>

            <th class="vertical-line text-center" style=" border-left: 1px solid #000;" colspan="6">Tarde</th>
        </tr>
        <tr class="trr">
            <th>pdv</th>
            <th class="text-center">MATRICULA</th>
            <th class="text-center">NOME</th>
            <th class="text-center">ENTRADA:</th>
            <th class="text-center">SAIDA</th>
            <th class="text-center">INTERVALO</th>
            <th class="vertical-line text-center" style=" border-left: 1px solid #000;">MATRICULA</th>
            <th class="text-center">NOME</th>
            <th class="text-center">ENTRADA:</th>
            <th class="text-center">SAIDA</th>
            <th class="text-center">INTERVALO</th>
        </tr>
    </thead>
    <tbody style="background-color: #DCDCDC;">
        <?php
        $qntPDV = array();
        for ($i = 1; $i <= 30; $i++) {
            $i;
            $horariosFuncManha = $InformacaoFuncionarios->filtroFuncionariosCadastradosManha($oracle, $dataPesquisada, $i);
            $horariosFuncTarde = $InformacaoFuncionarios->filtroFuncionariosCadastradoTarde($oracle, $dataPesquisada, $i);
            $totalManha = count($horariosFuncManha);
            $totalTarde = count($horariosFuncTarde);
        ?>
            <tr class="trr">
                <td scope="row" class="numerosPDVS" id="">
                    <?= $i ?>
                </td>
                <?php
                if (empty($horariosFuncManha)) {
                ?>
                    <td scope="row" class="Matricula1" contenteditable="true"></td>
                    <td scope="row" class="NomeFunc">
                        <select class="estilezaSelect form-control" id="selectFuncionario">
                            <option value=""></option>
                            <?php
                            foreach ($FuncManha as $rowManha) :
                            ?>
                                <div>
                                    <option style="color: black; font-weight: bold;" value="<?= $rowManha['NOME'] ?>"> <?= $rowManha['NOME'] ?> </option>
                                </div>
                            <?php
                            endforeach
                            ?>
                        </select>
                    </td>
                    <td scope="row" class="text-center horaEntrada1"></td>
                    <td scope="row" class="horaSaida1"></td>
                    <td scope="row" class="horaIntervalo1"></td>
                    <?php
                } else {
                    foreach ($horariosFuncManha as $row2Manha) :
                    ?>
                        <td scope="row" class="Matricula1" contenteditable="true"><?= $row2Manha['MATRICULA'] ?? '' ?></td>
                        <td scope="row" class="NomeFunc">
                            <select class="estilezaSelect form-control" id="selectFuncionario">
                                <option value="<?= $row2Manha['NOME'] ?>"><?= $row2Manha['NOME'] ?? '' ?></option>
                                <?php
                                foreach ($FuncManha as $rowManha) :
                                ?>
                                    <div>
                                        <option style="color: black; font-weight: bold;" value="<?= $rowManha['NOME'] ?>"> <?= $rowManha['NOME'] ?> </option>
                                    </div>
                                <?php
                                endforeach
                                ?>
                            </select>
                        </td>
                        <td scope="row" class="text-center horaEntrada1"><?= $row2Manha['HORAENTRADA'] ?? '' ?></td>
                        <td scope="row" class="horaSaida1"><?= $row2Manha['HORASAIDA'] ?? '' ?></td>
                        <td scope="row" class="horaIntervalo1"><?= $row2Manha['HORAINTERVALO'] ?? '' ?></td>
                <?php
                    endforeach;
                } ?>
                <?php
                if (empty($horariosFuncTarde)) {
                ?>
                    <td scope="row" class="matricula2" contenteditable="true"></td>
                    <td scope="row" class="text-center nome2">
                        <select class="estilizaSelect2 form-control">
                            <option value=""></option>
                            <?php
                            foreach ($FuncTarde as $rowTarde) :
                            ?>
                                <div>
                                    <option style="color: black; font-weight: bold;" value="<?= $rowTarde['NOME'] ?>"><?= $rowTarde['NOME'] ?> </option>
                                </div>
                            <?php
                            endforeach
                            ?>
                        </select>
                    </td>
                    <td scope="row" class="horaEntrada2"></td>
                    <td scope="row" class="horaSaida2"></td>
                    <td scope="row" class="horaIntervalo2"></td>
                    <?php
                } else {
                    foreach ($horariosFuncTarde as $row3Tarde) :
                        // print_r($horariosFuncTarde);                                                ?>
                        <td scope="row" class="matricula2" contenteditable="true"><?= $row3Tarde['MATRICULA'] ?? '' ?></td>
                        <td scope="row" class="text-center nome2">
                            <select class="estilizaSelect2 form-control">
                                <option value="<?= $row3Tarde['NOME'] ?>"><?= $row3Tarde['NOME'] ?? '' ?></option>
                                <?php
                                foreach ($FuncTarde as $rowTarde) :
                                ?>
                                    <div>
                                        <option style="color: black; font-weight: bold;" value="<?= $rowTarde['NOME'] ?>"> <?= $rowTarde['NOME'] ?> </option>
                                    </div>
                                <?php
                                endforeach
                                ?>
                            </select>
                        </td>
                        <td scope="row" class="horaEntrada2"><?= $row3Tarde['HORAENTRADA'] ?? '' ?></td>
                        <td scope="row" class="horaSaida2"><?= $row3Tarde['HORASAIDA'] ?? '' ?></td>
                        <td scope="row" class="horaIntervalo2"><?= $row3Tarde['HORAINTERVALO'] ?? '' ?></td>
                <?php
                    endforeach;
                } ?>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>




<script type="module" src="js/Script_escalaPDV.js" defer></script>
<script defer> 

    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = new Date().toISOString().slice(0, 10);

    if (dataPesquisa < dataAtual) {
        $('.estilezaSelect').prop('disabled', true);
        $('.estilizaSelect2').prop('disabled', true);
    } else {
        $('.estilizaSelect2').prop('disabled', false);
        $('.estilezaSelect').prop('disabled', false);
    }

</script>


<script>
    $('#table1').DataTable({

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
        buttons: [{
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




    
var usuarioLogado = $("#usuarioLogado").val();
var opcoesSelecionadas = [];

$('#table1').on('change', '.estilezaSelect', function () {
    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = $("#dataAtual").val();

    if (dataPesquisa == "") {
        dataPesquisa = dataAtual
    }

    var nomeSelecionado = $(this).val();
    var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();
    alert(numPDV);
    var $selects = $('#table1 .estilezaSelect');
    var matricula = $(this).parent().parent().find(".Matricula1").closest(".Matricula1");
    var entrada = $(this).parent().parent().find(".horaEntrada1").closest(".horaEntrada1");
    var saida = $(this).parent().parent().find(".horaSaida1").closest(".horaSaida1");
    var intervalo = $(this).parent().parent().find(".horaIntervalo1").closest(".horaIntervalo1");


    if (nomeSelecionado !== "") {
        if (opcoesSelecionadas.includes(nomeSelecionado)) {
            alert('Opção já selecionada em outra linha.');
            $(this).val("");
        } else {
            opcoesSelecionadas.push(nomeSelecionado);
            $selects.not(this).find('option[value="' + nomeSelecionado + '"]').remove();
            $.ajax({
                url: "filtro/busca_infosFuncionarios.php",
                method: 'get',
                data: 'nomeSelecionado=' + nomeSelecionado,
                dataType: 'json',
                success: function (retorno) {
                    matricula.text(retorno.matricula);
                    entrada.text(retorno.horaEntrada);
                    saida.text(retorno.horaSaida);
                    intervalo.text(retorno.horaIntervalo);


                    var DadosMatricula = retorno.matricula;
                    var DadosEntrada = retorno.horaEntrada;
                    var DadosSaida = retorno.horaSaida;
                    var DadosIntervalo = retorno.horaIntervalo;

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
                            dataPesquisa+
                            "&numPDV=" +
                            numPDV,

                        // dataType: 'json',
                        success: function (retorno2) {

                            criandoHtmlmensagemCarregamento("ocultar");


                        }
                    });


                }
            });




        }
    }
});



$('#table1').on('change', '.estilizaSelect2', function () {
    var dataPesquisa = $("#dataPesquisa").val();
    var dataAtual = $("#dataAtual").val();

    if (dataPesquisa == "") {
        dataPesquisa = dataAtual
    }

    var nomeSelecionado2 = $(this).val();
    var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();
    var $selects2 = $('#table1 .estilizaSelect2');
    var matricula2 = $(this).parent().parent().find(".matricula2").closest(".matricula2");
    var entrada2 = $(this).parent().parent().find(".horaEntrada2").closest(".horaEntrada2");
    var saida2 = $(this).parent().parent().find(".horaSaida2").closest(".horaSaida2");
    var intervalo2 = $(this).parent().parent().find(".horaIntervalo2").closest(".horaIntervalo2");


    if (nomeSelecionado2 !== "") {
        if (opcoesSelecionadas.includes(nomeSelecionado2)) {
            alert('Opção já selecionada em outra linha.');
            $(this).val("");
        } else {
            opcoesSelecionadas.push(nomeSelecionado2);

            $selects2.not(this).find('option[value="' + nomeSelecionado2 + '"]').remove();

            $.ajax({
                url: "filtro/busca_infosFuncionarios.php",
                method: 'get',
                data: 'nomeSelecionado=' + nomeSelecionado2,
                dataType: 'json',
                success: function (retorno2) {
                    matricula2.text(retorno2.matricula);
                    entrada2.text(retorno2.horaEntrada);
                    saida2.text(retorno2.horaSaida);
                    intervalo2.text(retorno2.horaIntervalo);

                    var DadosMatricula1 = retorno2.matricula;
                    var DadosEntrada1 = retorno2.horaEntrada;
                    var DadosSaida1 = retorno2.horaSaida;
                    var DadosIntervalo1 = retorno2.horaIntervalo;
                  


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
                            dataPesquisa+
                            "&numPDV=" +
                            numPDV,
                        // dataType: 'json',
                        success: function (retorno2) {
                            criandoHtmlmensagemCarregamento("ocultar");
                        }
                    });
                }
            });
        }
    }
});

</script>