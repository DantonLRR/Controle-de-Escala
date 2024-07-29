<?php
include "../base/conexao_martdb.php";
include "../MobileNav/docs/index_menucomlogin.php";
include "config/php/CRUD_geral.php";
include "../base/conexao_TotvzOracle.php";
$loja = $_SESSION['LOJA'];

?>
<!DOCTYPE html>
<html lang="pt-BR">


<head>
    <link href="../base/mdb/css/bootstrap.css" rel="stylesheet">
    <link href="../base/assets/css/paper-dashboard.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../base/dist/sidenav.css" type="text/css">
    <link rel="stylesheet" href="css/Style_escalaMensal.css" type="text/css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    </link>
    <link rel="stylesheet" href="../BASE/cssGeral.css" type="text/css">
    <link rel="stylesheet" href="../../BASE/DataTables/FixedColumns 4.3.0/FixedColumns-4.3.0/css/fixedColumns.dataTables.min.css" type="text/css">
    <link rel="icon" type="../base/image/png" href="../base/img/martband.png">
</head>
<?php
$informacoesDaslojas = new lojas;
?>

<body>
    <div class="container-fluid">
        <div class="row" id="qntPessoasPorPDV">
            <div class="col-lg-12">
                <div class="card">
                    <div style="font-weight: bold;  background: linear-gradient(to right, #00a451, #052846 85%); color:white" class="text-center card-header">Gerenciamento de Escala</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 informacoesEsquerda1Card row">
                                <div class="mb-3">
                                    <label for="validationCustom02" class="form-label">Data inicial: </label>
                                    <input type="date" class="form-control dataPesquisa margin-bottom" value="" id="dataPesquisaInicial">
                                </div>
                                <div class="mb-3">
                                    <label for="validationCustom02" class="form-label">Data final: </label>
                                    <input type="date" class="form-control dataPesquisa margin-bottom" value="" id="dataPesquisaFinal">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">
                                    Loja
                                </label>
                                <select required id="loja" class="form-control selectloja">
                                <option value="<?= $loja ?>"> <?= $loja ?></option>
                                    <?php
                                    $recuperacaoDosNumerosDeLoja = $informacoesDaslojas->recuperacaoDasLojas($oracle);
                                    print_r($recuperacaoDosNumerosDeLoja);
                                    foreach ($recuperacaoDosNumerosDeLoja as $rowLojas) :
                                    ?>
                                        <option value="<?= $rowLojas['NROEMPRESA'] ?>"><?= $rowLojas['NROEMPRESA'] ?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>

                            </div>
                            <div class="recebeNomeNuncionario">

                            </div>

                            <div class="col-lg-2 pt-4">
                                <button type="button" id="PesquisarEscaladiaria" style="background-color: #00a550 ;color: white;font-weight:bold" class="btn">Pesquisar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row blocoVerificaELiberaEscala" style="visibility:hidden;">
            <div class="col-lg-12">
                <div class="card" style="border-color:#00a550;">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background: linear-gradient(to right, #00a451, #052846 85%); color: white;font-weight:bold">
                        Alterações da escala Diaria
                    </h6>
                    <div id="PesquisaEscaladiariaResultado" class="card-body ">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="../base/mdb/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../base/mdb/js/jquery.min.js"></script>
    <script type="text/javascript" src="../base/bootstrap-5.0.2/bootstrap-5.0.2/dist/js/bootstrap.bundle.js"></script>
    <script type="text/javascript" src="../base/mdb/js/jquery.validate.min.js"></script>

    <script type="text/javascript" src="../base/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="../base/DataTables/FixedColumns/js/dataTables.fixedColumns.min.js"></script>
    <script src="../base/dist/sidenav.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin/tableExport.min.js"></script>

    <script type="text/javascript" src="../base/mdb/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="../../base/DataTables//FixedColumns 4.3.0//FixedColumns-4.3.0/js/dataTables.fixedColumns.min.js"></script>
</body>
<script type="module" defer>
    import {
        criandoHtmlmensagemCarregamento,
        Toasty
    } from "../../../../base/jsGeral.js";
    var loja = $("#loja").val();
    if (loja != 203) {
        $("#loja").attr('disabled', 'disabled');
    }
    $('#PesquisarEscaladiaria').on('click', function() {
        var dataPesquisaInicial = $('#dataPesquisaInicial').val();
        var dataPesquisaFinal = $('#dataPesquisaFinal').val();
        var lojaSelecionada = $('#loja').val();

        if (dataPesquisaInicial == "") {
            Toasty("Atenção", "Selecione uma Data inicial", "#E20914");
        } else if (dataPesquisaFinal == "") {
            Toasty("Atenção", "Selecione uma Data Final", "#E20914");
        } else if (dataPesquisaFinal < dataPesquisaInicial) {
            Toasty("Atenção", "A Data final não pode ser maior que a inicial", "#E20914");
        } else {
            criandoHtmlmensagemCarregamento("exibir");
            $.ajax({
                url: "config/pesquisar_escalaDiaria.php",
                method: 'POST',
                data: 'dataPesquisaInicial=' +
                    dataPesquisaInicial +
                    "&dataPesquisaFinal=" +
                    dataPesquisaFinal +
                    "&lojaSelecionada=" +
                    lojaSelecionada,
                success: function(mes_Pesquisado) {
                    $('#PesquisaEscaladiariaResultado').empty().html(mes_Pesquisado);
                    $('.blocoVerificaELiberaEscala').css('visibility', 'visible');
                    criandoHtmlmensagemCarregamento("ocultar");

                }
            });

        }
    });
</script>

</html>