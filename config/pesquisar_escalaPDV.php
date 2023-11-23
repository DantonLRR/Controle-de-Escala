<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";
include "../../base/conexao_TotvzOracle.php";
session_start();

$loja = $_POST['loja'];
$dataPesquisada = $_POST['dataPesquisa'];

// Separando o dia e o mês/ano
$partesData = explode('-', $dataPesquisada);
$diaDaPesquisaComAspas = ' "' . $partesData[2] . '"'; // Dia
$mesEAnoDaPesquisa = $partesData[0] . '-' . $partesData[1]; // Ano e Mês







$InformacaoFuncionarios = new Funcionarios();


$FuncManha = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoManha($oracle, $loja, $diaDaPesquisaComAspas , $mesEAnoDaPesquisa , $dataPesquisada);
// var_dump($FuncManha);
// echo "<br><br><br>";
$FuncEscaladosMANHA = $InformacaoFuncionarios->FuncsJaEscaladosMANHA($oracle, $dataPesquisada,$loja);
// var_dump($FuncEscaladosMANHA);

// echo "<br><br><br>";
$naoRepetidosMANHA = array();

foreach ($FuncManha as $funcManha1) {
    $repetido = false;
    foreach ($FuncEscaladosMANHA as $funcEscalado) {
        if ($funcManha1['MATRICULA'] === $funcEscalado['MATRICULA']) {
            $repetido = true;
            break;
        }
    }
    if (!$repetido) {
        $naoRepetidosMANHA[] = $funcManha1;
    }
}

// var_dump($naoRepetidosMANHA);


$FuncTarde = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoTarde($oracle, $_SESSION['LOJA'], $diaDaPesquisaComAspas , $mesEAnoDaPesquisa , $dataPesquisada);
$FuncEscaladosTARDE = $InformacaoFuncionarios->FuncsJaEscaladosTARDE($oracle, $dataPesquisada,$loja);
// var_dump($FuncEscaladosTARDE);
// echo"<br><br><br>";
$naoRepetidosTARDE = array();

foreach ($FuncTarde as $funcTarde2) {
    $repetidoTARDE = false;
    foreach ($FuncEscaladosTARDE as $funcEscalado) {
        if ($funcTarde2['MATRICULA'] === $funcEscalado['MATRICULA']) {
            $repetidoTARDE = true;
            break;
        }
    }
    if (!$repetidoTARDE) {
        $naoRepetidosTARDE[] = $funcTarde2;
    }
}
// var_dump($naoRepetidosTARDE);


$quantidadePorDiaDeFuncionarios = $InformacaoFuncionarios->funcionariosDisponiveisNoDia($oracle, $diaDaPesquisaComAspas, $mesEAnoDaPesquisa, $dataPesquisada, $loja);

if (empty($quantidadePorDiaDeFuncionarios)) {
    $quantidadePorDiaDeFuncionariosImpressao = "Nenhum funcionario escalado para este dia,";
} else {
    $quantidadePorDiaDeFuncionariosImpressao = count($quantidadePorDiaDeFuncionarios);
    $quantidadeDePessoasEscaladas = $quantidadePorDiaDeFuncionariosImpressao;
}
if ($quantidadePorDiaDeFuncionariosImpressao == "Nenhum funcionario escalado para este dia,") {
} else {

?>

<table id="table1" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">

    <input id="dataPesquisar" type="hidden" value="<?= $dataPesquisada ?>">

    <input class="usu" id="loja" type="hidden" value="<?= $loja ?>">
    <input class="usu" type="hidden" value="<?= $_SESSION['nome'] ?>">
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
            <th class="text-center">EXCLUSÃO</th>
        </tr>
    </thead>
    <tbody style="background-color: #DCDCDC;">
        <?php
        $qntPDV = array();
        for ($i = 1; $i <= 30; $i++) {
            $i;
            $horariosFuncManha = $InformacaoFuncionarios->filtroFuncionariosCadastradosManha($oracle, $dataPesquisada, $i,$_SESSION['LOJA']);
            $horariosFuncTarde = $InformacaoFuncionarios->filtroFuncionariosCadastradoTarde($oracle, $dataPesquisada, $i,$_SESSION['LOJA']);
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
                            foreach ($naoRepetidosMANHA as $rowManha) :
                            ?>
                                <div>
                                    <option style="color: black; font-weight: bold;" value="<?= $rowManha['MATRICULA'] ?>"> <?= $rowManha['NOME'] ?> </option>
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
                                <option value="<?= $rowManha['MATRICULA'] ?>"><?= $row2Manha['NOME'] ?? '' ?></option>
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
                            foreach ($naoRepetidosTARDE as $rowTarde) :
                            ?>
                                <div>
                                    <option style="color: black; font-weight: bold;" value="<?= $rowTarde['MATRICULA'] ?>"><?= $rowTarde['NOME'] ?> </option>
                                </div>
                            <?php
                            endforeach
                            ?>
                        </select>
                    </td>
                    <td scope="row" class="horaEntrada2"></td>
                    <td scope="row" class="horaSaida2"></td>
                    <td scope="row" class="horaIntervalo2"></td>
                    <td scope="row" class="btnExcluir"> <i class="fa-solid fa-trash fa-2xl" style="color:red"></i></td>
                    <?php
                } else {
                    foreach ($horariosFuncTarde as $row3Tarde) :
                    ?>
                        <td scope="row" class="matricula2" contenteditable="true"><?= $row3Tarde['MATRICULA'] ?? '' ?></td>
                        <td scope="row" class="text-center nome2">
                            <select class="estilizaSelect2 form-control">
                                <option value="<?= $row3Tarde['MATRICULA'] ?>"><?= $row3Tarde['NOME'] ?? '' ?></option>
                            </select>
                        </td>
                        <td scope="row" class="horaEntrada2"><?= $row3Tarde['HORAENTRADA'] ?? '' ?></td>
                        <td scope="row" class="horaSaida2"><?= $row3Tarde['HORASAIDA'] ?? '' ?></td>
                        <td scope="row" class="horaIntervalo2"><?= $row3Tarde['HORAINTERVALO'] ?? '' ?></td>
                        <td scope="row" class="btnExcluir"> <i class="fa-solid fa-trash fa-2xl" style="color:red"></i></td>
                <?php
                    endforeach;
                } ?>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
}
?>



<script type="module" src="js/Script_escalaPDV.js" defer></script>
<script defer>
    var dataPesquisa = $("#dataPesquisar").val();
    var dataAtual = new Date().toISOString().slice(0, 10);
    console.log("dataPesquisa: " + dataPesquisa)

    console.log("dataAtual: " + dataAtual)
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

        scrollY: 400,

        scrollCollapse: true,
        searching: true,
        dom: 'Bfrtip',
        "paging": true,
        "info": false,
        "ordering": false,
        "lengthMenu": [
            [15],

        ],
        buttons: [{
            text: 'Imprimir',
            className: 'estilizaBotao btn btnverde',
            extend: 'print',
            exportOptions: {

            }
        }, ],
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
    var loja = $("#loja").val();
    var opcoesSelecionadas = [];

    function calcularHorasIntermediarias(horaEntrada, horaSaida, horaParaPular) {
        var horasIntermediarias = [];
        var [entradaHora, entradaMinutos] = horaEntrada.split(':').map(Number);
        var [saidaHora, saidaMinutos] = horaSaida.split(':').map(Number);

        while (entradaHora < saidaHora || (entradaHora === saidaHora && entradaMinutos <= saidaMinutos)) {
            var horaFormatada = entradaHora.toString().padStart(2, '0') + ':' + entradaMinutos.toString().padStart(2, '0');

            // Verifica se a horaFormatada é igual à horaParaPular e exclui se for diferente.
            if (horaFormatada !== horaParaPular) {
                horasIntermediarias.push('"' + horaFormatada + '"');
            }

            if (entradaMinutos === 0) {
                entradaHora++;
                entradaMinutos = 0;
            } else {
                entradaMinutos = 0;
            }
        }

        return horasIntermediarias;
    }





    $('#table1').on('change', '.estilezaSelect', function() {
        var dataPesquisa = $("#dataPesquisa").val();
        var dataAtual = $("#dataAtual").val();

        if (dataPesquisa == "") {
            dataPesquisa = dataAtual
        }

        var MatriculaDaPessoaSelecionada = $(this).val();
        var nomeSelecionado = $(this).find('option:selected').text();
        var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();
        var $selects = $('#table1 .estilezaSelect');
        var matricula = $(this).parent().parent().find(".Matricula1").closest(".Matricula1");
        var entrada = $(this).parent().parent().find(".horaEntrada1").closest(".horaEntrada1");
        var saida = $(this).parent().parent().find(".horaSaida1").closest(".horaSaida1");
        var intervalo = $(this).parent().parent().find(".horaIntervalo1").closest(".horaIntervalo1");
        opcoesSelecionadas.push(nomeSelecionado);
        $selects.not(this).find('option[value="' + nomeSelecionado + '"]').remove();
        $.ajax({
            url: "filtro/busca_infosFuncionarios.php",
            method: 'get',
            data: 'MatriculaDaPessoaSelecionada=' +
                MatriculaDaPessoaSelecionada +
                "&loja=" +
                +loja +
                "&dataPesquisa=" +
                dataPesquisa +
                "&nomeSelecionado=" +
                nomeSelecionado,
            dataType: 'json',
            success: function(retorno) {
                matricula.text(retorno.MATRICULA);
                entrada.text(retorno.HORAENTRADA);
                saida.text(retorno.HORASAIDA);
                intervalo.text(retorno.SAIDAPARAALMOCO);
                var DadosMatricula = retorno.MATRICULA;
                var DadosEntrada = retorno.HORAENTRADA;
                var DadosSaida = retorno.HORASAIDA;
                var DadosIntervalo = retorno.SAIDAPARAALMOCO;
                var horasIntermediarias = calcularHorasIntermediarias(DadosEntrada, DadosSaida, DadosIntervalo);

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
                        dataPesquisa +
                        "&numPDV=" +
                        numPDV +
                        "&loja=" +
                        loja,

                    // dataType: 'json',
                    success: function(retorno2) {

                        $.ajax({
                            url: "config/pesquisar_escalaPDV.php",
                            method: 'POST',
                            data: 'dataPesquisa=' +
                                dataPesquisa +
                                "&loja=" +
                                loja,
                            success: function(data_pesquisada2) {

                                $('.dadosEscalaPDV').empty().html(data_pesquisada2);

                            }
                        });

                    }
                });

                $.ajax({
                    url: "config/exibicao_escala_diaria_pdv.php",
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
                        dataPesquisa +
                        "&numPDV=" +
                        numPDV +
                        "&horasIntermediarias=" +
                        horasIntermediarias +
                        "&loja=" +
                        loja,

                    // dataType: 'json',
                    success: function(retorno2) {

                        $.ajax({
                            url: "config/pesquisar_relatorio_pdv.php",
                            method: 'POST',
                            data: 'dataPesquisa=' +
                                dataPesquisa +
                                "&loja=" +
                                loja,
                            success: function(relatorio_atualizado2) {

                                $('#relatorioPDV').empty().html(relatorio_atualizado2);
                                criandoHtmlmensagemCarregamento("ocultar");

                            }
                        });


                    }
                });







            }
        });






    });



    $('#table1').on('change', '.estilizaSelect2', function() {
        var dataPesquisa = $("#dataPesquisa").val();
        var dataAtual = $("#dataAtual").val();

        if (dataPesquisa == "") {
            dataPesquisa = dataAtual
        }
        var MatriculaDaPessoaSelecionada2 = $(this).val();
        var nomeSelecionado2 = $(this).find('option:selected').text();
        var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();
        var $selects2 = $('#table1 .estilizaSelect2');
        var matricula2 = $(this).parent().parent().find(".matricula2").closest(".matricula2");
        var entrada2 = $(this).parent().parent().find(".horaEntrada2").closest(".horaEntrada2");
        var saida2 = $(this).parent().parent().find(".horaSaida2").closest(".horaSaida2");
        var intervalo2 = $(this).parent().parent().find(".horaIntervalo2").closest(".horaIntervalo2");

        opcoesSelecionadas.push(nomeSelecionado2);

        $selects2.not(this).find('option[value="' + nomeSelecionado2 + '"]').remove();

        $.ajax({
            url: "filtro/busca_infosFuncionarios.php",
            method: 'get',
            data: 'MatriculaDaPessoaSelecionada=' +
                MatriculaDaPessoaSelecionada2 +
                "&loja=" +
                +loja +
                "&dataPesquisa=" +
                dataPesquisa +
                "&nomeSelecionado=" +
                nomeSelecionado2,
            dataType: 'json',
            success: function(retorno2) {
                matricula2.text(retorno2.MATRICULA);
                entrada2.text(retorno2.HORAENTRADA);
                saida2.text(retorno2.HORASAIDA);
                intervalo2.text(retorno2.SAIDAPARAALMOCO);

                var DadosMatricula1 = retorno2.MATRICULA;
                var DadosEntrada1 = retorno2.HORAENTRADA;
                var DadosSaida1 = retorno2.HORASAIDA;
                var DadosIntervalo1 = retorno2.SAIDAPARAALMOCO;

                var horasIntermediarias = calcularHorasIntermediarias(DadosEntrada1, DadosSaida1, DadosIntervalo1);
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
                        dataPesquisa +
                        "&numPDV=" +
                        numPDV +
                        "&numPDV=" +
                        numPDV +
                        "&loja=" +
                        loja,
                    // dataType: 'json',
                    success: function(retorno2) {
                        $.ajax({
                            url: "config/pesquisar_escalaPDV.php",
                            method: 'POST',
                            data: 'dataPesquisa=' +
                                dataPesquisa +
                                "&loja=" +
                                loja,
                            success: function(data_pesquisada2) {

                                $('.dadosEscalaPDV').empty().html(data_pesquisada2);

                            }
                        });
                    }
                });

                $.ajax({
                    url: "config/exibicao_escala_diaria_pdv.php",
                    method: 'get',
                    data: 'DadosMatricula=' +
                        DadosMatricula1 +
                        "&nomeSelecionado=" +
                        nomeSelecionado2 +
                        "&DadosEntrada=" +
                        DadosEntrada1 +
                        "&DadosSaida=" +
                        DadosSaida1 +
                        "&DadosIntervalo=" +
                        DadosIntervalo1 +
                        "&usuarioLogado=" +
                        usuarioLogado +
                        "&dataPesquisa=" +
                        dataPesquisa +
                        "&numPDV=" +
                        numPDV +
                        "&horasIntermediarias=" +
                        horasIntermediarias +
                        "&loja=" +
                        loja,
                    // dataType: 'json',
                    success: function(retorno2) {
                        $.ajax({
                            url: "config/pesquisar_relatorio_pdv.php",
                            method: 'POST',
                            data: 'dataPesquisa=' +
                                dataPesquisa +
                                "&loja=" +
                                loja,
                            success: function(relatorio_atualizado2) {

                                $('#relatorioPDV').empty().html(relatorio_atualizado2);
                                criandoHtmlmensagemCarregamento("ocultar");

                            }
                        });
                    }
                });




            }
        });


    });




    $('.fa-trash').on('click', function() {
        var dataPesquisa = $("#dataPesquisa").val();
        var dataAtual = $("#dataAtual").val();

        if (dataPesquisa == "") {
            dataPesquisa = dataAtual
        }
        var numPDV = $(this).parent().parent().find(".numerosPDVS").closest(".numerosPDVS").text().trim();

        $.ajax({
            url: "config/remove_linha_relatorio_pdv.php",
            method: 'get',
            data: "dataPesquisa=" +
                dataPesquisa +
                "&numPDV=" +
                numPDV +
                "&loja=" +
                loja,

            // dataType: 'json',
            success: function(atualizaTabela) {

                $.ajax({
                    url: "config/pesquisar_relatorio_pdv.php",
                    method: 'POST',
                    data: 'dataPesquisa=' +
                        dataPesquisa +
                        "&loja=" +
                        loja,
                    success: function(relatorio_atualizado2) {

                        $('#relatorioPDV').empty().html(relatorio_atualizado2);
                        criandoHtmlmensagemCarregamento("ocultar");
                    }
                });

                $.ajax({
                    url: "config/pesquisar_escalaPDV.php",
                    method: 'POST',
                    data: 'dataPesquisa=' +
                        dataPesquisa +
                        "&loja=" +
                        loja,
                    success: function(data_pesquisada2) {

                        $('.dadosEscalaPDV').empty().html(data_pesquisada2);

                    }
                });

            }
        });


    });
</script>