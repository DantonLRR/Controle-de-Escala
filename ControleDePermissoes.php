<!DOCTYPE html>
<html lang="pt-br">
<meta charset="utf-8" />

<head>
    <link rel="icon" type="../base/image/png" href="../base/img/martband.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@200;400;600&family=Martian+Mono:wght@700&family=Nunito+Sans:wght@300;400;600&family=Poppins:wght@100;300;400;500&family=Righteous&family=Roboto:wght@300;400;500;700&family=Ubuntu:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="../BASE/mdb/css/bootstrap.css" rel="stylesheet">
    <link href="../BASE/mdb/css/mdb.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../BASE/datetimepicker/jquery.datetimepicker.min.css" />
    <link href="../BASE/assets/css/paper-dashboard.css" rel="stylesheet" />
    <link href="../BASE/jquery_ui/jquery/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../BASE/DataTables/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../BASE/cssGeral.css" type="text/css">
    <link rel="stylesheet" href="css/cargoRisco.css" type="text/css">
</head>

<?php

$hoje = date('d/m/Y');
$hoje = implode('-', array_reverse(explode('/', $hoje)));

// include "../BASE/menulateral.php";
include "../MobileNav/docs/index_menucomlogin.php";
include "../base/conexao_TotvzOracle.php";

include "../base/Conexao_martdb.php";
include "config/crud_cargoRisco.php";
$dados = new dados();

$USUARIO = $_SESSION['nome'];
$cpf = $_SESSION['cpf'];
// echo $USUARIO;
?>

<style>
    .linhaMarcada tr:hover {
        background-color: lightblue !important;
    }

    .degrade {
        background: linear-gradient(to right, #00a451, #052846 85%);
        font-weight: bold;
        color: white
    }

    .trr {
        cursor: pointer;
    }
</style>

<body style="background-color: #ECECEC;">
    <div class="row">
        <div class="col-lg-12">
            <button type="button" class="btn degrade btnGerenciamentoPermissao"> Gerenciamento de permissão</button>
            <button type="button" class="btn degrade btnCargoInativos">Cargo Inativos</button>
            <button type="button" class="btn degrade btnCargoRisco">Cargo Risco</button>
            <button type="button" class="btn degrade btnTipoMovimentacao">Tipo de Movimentação</button>
            <button type="button" class="btn degrade btnGerenciarMovimentacao">Gerenciar Tipo Movimentação</button>
        </div>
    </div>
    <div class="container-fluid ">
        <div class="row  cardGerenciamentoPermissao">
            <div class="col-lg-12 " style="margin-top: 25px;">
                <div class="card center">
                    <div class="card-header  text-center degrade">Gerenciamento de permissão</div>
                    <div class="card-body  ">
                        <div class="table-editable table_agendamentos ">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="card" style="height:525px;">
                                        <h6 class="card-header text-center font-weight-bold text-uppercase degrade">Gerenciamento Aplicação</h6>
                                        <div class="card-body ">
                                            <table class="table table-bordered table-hover table-striped text-center  table1 tableaplicacao" id="table1">
                                                <input class="usu" type="HIDDEN" value="<?= $_SESSION['nome'] ?>">
                                                <thead class="degrade">
                                                    <tr>
                                                        <!-- style="display:none" -->
                                                        <th style="display:none" class="text-center">ID</th>
                                                        <th style="display:none" class="text-center">STATUS</th>
                                                        <th class="text-center">DESCRICAO</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="linhaMarcada">
                                                    <?php
                                                    $buscandoCargosDisponiveis = $dados->buscandoPermissaoMP($oracle);
                                                    foreach ($buscandoCargosDisponiveis as $row) :
                                                        $ID = $row["ID"];
                                                        $STATUS = $row["STATUS"];
                                                        $DESCRICAO = $row["DESCRICAO"];
                                                    ?>
                                                        <tr class="trr">
                                                            <td style="display:none" class="IDDisponivel"><?= $ID ?></td>
                                                            <td style="display:none" class="STATUSDisponivel"><?= $STATUS ?></td>
                                                            <td class="DESCRICAODisponivel"><?= $DESCRICAO ?></td>
                                                        </tr>
                                                    <?php
                                                    endforeach
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="card" style="height:525px;">
                                        <h6 class="card-header text-center font-weight-bold text-uppercase degrade">Gerenciar Aplicação</h6>

                                        <div class="card-body">
                                            <div id="table" class="table-editable">
                                                <div class="tablereq">

                                                </div>

                                            </div>
                                            <div class="d-flex justify-content-center">

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row cardCargoIativos ">

            <div class="col-lg-12  " style="margin-top: 25px;">
                <div class="card center">
                    <div class="card-header  text-center degrade">Cargo Inativos</div>
                    <div class="card-body  ">

                        <div class="table-editable  ">
                            <div class="row">
                                <div class="col-6">

                                    <table class="table table-bordered table-striped text-center  tableCargoDisponiveisInativar" id="table2" style="width:100%">
                                        <button type="button" class="btn button ml-4 inativarCargo">Adicionar</button>
                                        <div>
                                            <thead>
                                                <tr class="degrade">
                                                    <!-- style="display:none" -->
                                                    <th class="text-center"> <input type="checkbox" class="atrrcheckInativarCargo" name="checkbox" id="atrrcheckInativarCargo" value=""></th>

                                                    <th class="text-center">seleção</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $buscandoCargosDisponiveis = $dados->buscandoCargosDisponiveis($TotvsOracle);
                                                foreach ($buscandoCargosDisponiveis as $row) :
                                                    $CARGO = $row["CARGO"];

                                                ?>

                                                    <tr class="trteste tr">
                                                        <td class="text-center">
                                                            <input type="checkbox" class="checkboxInativarCargo" name="checkbox" id="checkboxInativarCargo" value="">
                                                        </td>
                                                        <td class="cargoAtivo"><?= $CARGO ?></td>

                                                    </tr>
                                                <?php
                                                endforeach
                                                ?>
                                            </tbody>
                                        </div>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table class="table table-bordered table-striped text-center tableCargoInativos" style="width:100%">
                                        <button type="button" class="btn reativarCargoInativos button ml-4 Remover">Remover</button>
                                        <div>
                                            <thead class="degrade">
                                                <tr>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">Cargos selecionados</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $buscandoCargosComRisco = $dados->buscandoCargosInativos($oracle);
                                                foreach ($buscandoCargosComRisco as $row) :
                                                    $ID = $row["ID"];
                                                    $CARGO = $row["CARGO"];
                                                ?>

                                                    <tr class="trppermitido">
                                                        <td class="text-center">
                                                            <input type="checkbox" class="checkboxReativarCargoInativos" name="checkboxReativarCargoInativos" id="checkbox1" value="">
                                                        </td>

                                                        <td class="idCargoInativo"><?= $ID ?> </td>
                                                        <td class="cargoInativo"><?= $CARGO ?> </td>

                                                    </tr>
                                                <?php
                                                endforeach
                                                ?>
                                            </tbody>
                                        </div>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row cardCargoRisco ">

            <div class="col-lg-12  " style="margin-top: 25px;">
                <div class="card center">
                    <div class="card-header  text-center degrade">Cargo Risco</div>
                    <div class="card-body  ">

                        <div class="table-editable table_agendamentos ">
                            <div class="row">
                                <div class="col-6">

                                    <table class="table table-bordered table-striped text-center  tableCargodisponivel" id="table2" style="width:100%">
                                        <button type="button" class="btn button ml-4 Adicionar">Adicionar</button>
                                        <div>
                                            <thead>
                                                <tr class="degrade">
                                                    <!-- style="display:none" -->
                                                    <th class="text-center"> <input type="checkbox" class="atrrcheck" name="checkbox" id="atrrcheck" value=""></th>

                                                    <th class="text-center">seleção</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $buscandoCargosDisponiveis = $dados->buscandoCargosDisponiveis($TotvsOracle);
                                                foreach ($buscandoCargosDisponiveis as $row) :
                                                    $CARGO = $row["CARGO"];

                                                ?>

                                                    <tr class="trteste tr">
                                                        <td class="text-center">
                                                            <input type="checkbox" class="checkbox" name="checkbox" id="checkbox" value="">
                                                        </td>
                                                        <td class="cargoDisponivel"><?= $CARGO ?></td>

                                                    </tr>
                                                <?php
                                                endforeach
                                                ?>
                                            </tbody>
                                        </div>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table class="table table-bordered table-striped text-center tableCargoSelecionado" style="width:100%">
                                        <button type="button" class="btn button ml-4 Remover">Remover</button>
                                        <div>
                                            <thead class="degrade">
                                                <tr>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">Cargos selecionados</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $buscandoCargosComRisco = $dados->buscandoCargosComRisco($oracle);
                                                foreach ($buscandoCargosComRisco as $row) :
                                                    $ID = $row["ID"];
                                                    $CARGO = $row["CARGO"];
                                                ?>

                                                    <tr class="trppermitido">
                                                        <td class="text-center">
                                                            <input type="checkbox" class="checkbox1" name="checkbox" id="checkbox1" value="">
                                                        </td>

                                                        <td class="idCargoComRisco"><?= $ID ?> </td>
                                                        <td class="cargo"><?= $CARGO ?> </td>

                                                    </tr>
                                                <?php
                                                endforeach
                                                ?>
                                            </tbody>
                                        </div>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row cardTipoMovimentacao ">

            <div class="col-lg-12 " style="margin-top: 25px;">
                <div class="card center">
                    <div class="card-header  text-center degrade">Tipo de Movimentação</div>
                    <div class="card-body ">
                        <div class="table-editable table_agendamentos">
                            <div class="row">
                                <div class="col-6">

                                    <table class="table table-bordered table-striped text-center  tableTipoMovimentacao" id="table2" style="min-width:100%">
                                        <button type="button" class="btn button ml-4 AdicionarMovimentacao">Adicionar</button>
                                        <div>
                                            <thead class="degrade" style="width: 100%;">
                                                <tr>
                                                    <!-- style="display:none" -->
                                                    <th class="text-center"> <input type="checkbox" class="atrrcheckAdicionarMovimentacao" name="checkbox" id="atrrcheck" value=""></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">seleção</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $buscandoTipoMovimentacao = $dados->buscandoTipoMovimentacao($oracle, 'A');
                                                foreach ($buscandoTipoMovimentacao as $row) :
                                                    $TIPODEMOVIMENTACAO = $row["WEB_TIPODEMOVIMENTACAO"];
                                                    $idTIPODEMOVIMENTACAO = $row["ID"];

                                                ?>

                                                    <tr class="trteste tr">
                                                        <td class="text-center">
                                                            <input type="checkbox" class="checkboxAdicionarMovimentacao" name="checkbox" id="checkbox" value="">
                                                        </td>
                                                        <td class="IDMovimentacao"><?= $idTIPODEMOVIMENTACAO ?></td>
                                                        <td class="MovimentacaoDisponivel">R</td>
                                                        <td class="TipoMovimentacaoDisponivel"><?= $TIPODEMOVIMENTACAO ?></td>

                                                    </tr>
                                                <?php
                                                endforeach
                                                ?>


                                            </tbody>
                                        </div>
                                    </table>

                                </div>

                                <div class="col-6">


                                    <table class="table table-bordered table-striped text-center tableCargoSelecionado" style="width:100%">
                                        <button type="button" class="btn button ml-4 RemoverMovimentacao">Remover</button>
                                        <div>
                                            <thead class="degrade">


                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="checkboxRemoverMovimentacao" name="checkbox" id="checkbox" value="">
                                                    </td>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">Cargos selecionados</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $buscandoTipoMovimentacao = $dados->buscandoTipoMovimentacao($oracle, 'R');
                                                foreach ($buscandoTipoMovimentacao as $row) :
                                                    $ID = $row["ID"];
                                                    $WEB_TIPODEMOVIMENTACAO = $row["WEB_TIPODEMOVIMENTACAO"];

                                                ?>

                                                    <tr class="trteste tr">
                                                        <td class="text-center">
                                                            <input type="checkbox" class="checkboxRemoverMovimentacao" name="checkbox" id="checkbox" value="">
                                                        </td>
                                                        <td class="IDMovimentacao"><?= $ID ?></td>
                                                        <td class="MovimentacaoRisco">A</td>
                                                        <td class="cargoDisponivel"><?= $WEB_TIPODEMOVIMENTACAO ?></td>

                                                    </tr>
                                                <?php
                                                endforeach
                                                ?>



                                            </tbody>
                                        </div>
                                    </table>


                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row cardGerenciarMovimentacao ">

            <div class="col-lg-12 " style="margin-top: 25px;">
                <div class="card center">
                    <div class="card-header  text-center degrade">Gerenciar Tipo Movimentação</div>

                    <div class="card-body ">

                        <div class="col-12">

                            <table class="table table-bordered table-striped text-center  tableGerenciarTipoMovimentacao" id="table2" style="width:100%">
                                <button data-toggle="modal" data-target=".bd-example-modal-lg" type="button" class="btn button ml-4 AdicionarTipoMovimentacao">Adicionar</button>
                                <div>
                                    <thead class="degrade">
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center">Tipo Movimentação</th>
                                            <th class="text-center">STATUS</th>
                                            <th class="text-center">Excluir</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $buscandoTipoMovimentacao = $dados->TiposMovimentacao($oracle);
                                        foreach ($buscandoTipoMovimentacao as $row) :
                                            $TIPODEMOVIMENTACAO = $row["WEB_TIPODEMOVIMENTACAO"];
                                            $idTIPODEMOVIMENTACAO = $row["ID"];
                                            $STATUS = $row["STATUS"];

                                        ?>

                                            <tr class="trteste tr">

                                                <td class="IDMovimentacaoDisponivel"><?= $idTIPODEMOVIMENTACAO ?></td>
                                                <td class=""><?= $TIPODEMOVIMENTACAO ?></td>
                                                <td class=""><?= $STATUS ?></td>
                                                <td class="excluirMovimentacaoDisponivel"><i class="far fa-trash-alt"></i></td>

                                            </tr>
                                        <?php
                                        endforeach
                                        ?>
                                    </tbody>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header degrade">
                <h5 class="modal-title " id="TituloModalCentralizado">Adicionar tipo de movimentação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span style="color: #ECECEC;" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-lg-8">
                        <label for="validationCustom02" class="form-label">Tipo de Movimentação:</label>
                        <input type="text" value="" class="form-control novoTipoMovimentacao" id="Nome" id="validationCustom02" required>
                        <div class="invalid-feedback">
                            Campo obrigatório.
                        </div>
                    </div>
                    <div class="col-lg-4 pt-3">
                        <button type="button" style="background-color: #00a451;color:#ECECEC;" class="btn btnnverde AdicionarNovoTipoMovimentacao">Adicionar</button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<script type="module" src="js/cargoRisco.js"></script>
<script type="text/javascript" src="../base/mdb/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script type="text/javascript" src="../BASE/mdb/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../base/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../base/mdb/js/mdb.min.js"></script>
<script type="text/javascript" src="../BASE/bootstrap-multiselect/bootstrap-select-1.13.14/dist/js/bootstrap-select.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"> </script> -->
<script type="text/javascript" src="../base/jquery_ui/jquery/jquery-ui.js"></script>
<script src="../BASE/mdb/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../BASE/formulario7/formulario/js/out/jquery.idealforms.js"></script>
<script src="../BASE/formulario7/formulario/js/i18n/jquery.idealforms.i18n.pt.js"></script>
<script type="text/javascript" src="../BASE/bootstrap-multiselect/bootstrap-select-1.13.14/dist/js/bootstrap-select.js"></script>
<script src="../base/dist/sidenav.js"></script>

<script type="module" defer>
    import {
        criandoHtmlmensagemCarregamento,
        Toasty
    } from "../base/jsGeral.js";
    $('.tableCargodisponivel').DataTable({
        dom: 'ft',
        "paging": true,
        "info": false,
        "searching": true,
        "ordering": false,
        scrollY: "280px",
        "lengthMenu": [
            [50],
            [50]
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

    $('.tableGerenciarTipoMovimentacao').DataTable({
        dom: 'ft',
        "paging": true,
        "info": false,
        "searching": true,
        "ordering": false,
        scrollY: "280px",
        "lengthMenu": [
            [50],
            [50]
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
    $('.tableTipoMovimentacao').DataTable({
        dom: 'ft',
        "paging": true,
        "info": false,
        "searching": true,
        "ordering": false,
        scrollY: "280px",
        "lengthMenu": [
            [50],
            [50]
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
    $('.tableCargoDisponiveisInativar').DataTable({
        dom: 'ft',
        "paging": true,
        "info": false,
        "searching": true,
        "ordering": false,
        scrollY: "280px",
        "lengthMenu": [
            [50],
            [50]
        ],

        buttons: [

            {
                // Permitir no modulo
                text: 'Remover',
                action: function(e) {

                    var checkede = $('.checkbox1').toArray().map(function(checkede) {
                        return $(checkede).is(':checked');
                    });

                    for (var i = 0, l = checkede.length; i < l; i++) {

                        if (checkede[i] == true) {

                            var checkedvazio = 'true'
                        }
                    }

                    if (checkedvazio == 'true') {

                        var dados = $('.checkbox1:checked').parent().parent().find(".SEQCODUSUARIO").closest('.SEQCODUSUARIO').toArray().map(function(dados) {
                            return $(dados).text();
                        });
                        seqmodulosel = $('.seqmodulosel').val();

                        //  alert(seqmodulosel)

                        $.ajax({
                            url: "delete_permissaoaplic.php",
                            method: 'get',
                            data: 'dados=' + dados + '&seqaplicacao=' + seqmodulosel,
                            success: function(filtro) {
                                var selecionados = $(".selecionado");

                                var dados = "";

                                for (var i = 0; i < selecionados.length; i++) {
                                    var selecionado = selecionados[i];
                                    selecionado = selecionado.getElementsByClassName("td");
                                    dados += selecionado[0].innerHTML + "\n";
                                }
                                //var acomp = $(this).text();
                                //alert(dados);

                                $.ajax({
                                    url: "gerenciamento_aplicacao.php",
                                    method: 'get',
                                    data: 'SEQOPCAO=' + dados,
                                    success: function(filtro) {
                                        $('.tablereq').empty().html(filtro);

                                    }
                                });

                            }
                        });


                    } else {

                        alert('selecione um usuário');
                    }

                }
            },
            // 'colvis'
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
    $('.tableCargoInativos').DataTable({
        dom: 'ft',
        "paging": true,
        "info": false,
        "searching": true,
        "ordering": false,
        scrollY: "280px",
        "lengthMenu": [
            [50],
            [50]
        ],

        buttons: [

            {
                // Permitir no modulo
                text: 'Remover',
                action: function(e) {

                    var checkede = $('.checkbox1').toArray().map(function(checkede) {
                        return $(checkede).is(':checked');
                    });

                    for (var i = 0, l = checkede.length; i < l; i++) {

                        if (checkede[i] == true) {

                            var checkedvazio = 'true'
                        }
                    }

                    if (checkedvazio == 'true') {

                        var dados = $('.checkbox1:checked').parent().parent().find(".SEQCODUSUARIO").closest('.SEQCODUSUARIO').toArray().map(function(dados) {
                            return $(dados).text();
                        });
                        seqmodulosel = $('.seqmodulosel').val();

                        //  alert(seqmodulosel)

                        $.ajax({
                            url: "delete_permissaoaplic.php",
                            method: 'get',
                            data: 'dados=' + dados + '&seqaplicacao=' + seqmodulosel,
                            success: function(filtro) {
                                var selecionados = $(".selecionado");

                                var dados = "";

                                for (var i = 0; i < selecionados.length; i++) {
                                    var selecionado = selecionados[i];
                                    selecionado = selecionado.getElementsByClassName("td");
                                    dados += selecionado[0].innerHTML + "\n";
                                }
                                //var acomp = $(this).text();
                                //alert(dados);

                                $.ajax({
                                    url: "gerenciamento_aplicacao.php",
                                    method: 'get',
                                    data: 'SEQOPCAO=' + dados,
                                    success: function(filtro) {
                                        $('.tablereq').empty().html(filtro);

                                    }
                                });

                            }
                        });


                    } else {

                        alert('selecione um usuário');
                    }

                }
            },
            // 'colvis'
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
    $('.tableCargoSelecionado').DataTable({
        dom: 'ft',
        "paging": true,
        "info": false,
        "searching": true,
        "ordering": false,
        scrollY: "280px",
        "lengthMenu": [
            [50],
            [50]
        ],

        buttons: [

            {
                // Permitir no modulo
                text: 'Remover',
                action: function(e) {

                    var checkede = $('.checkbox1').toArray().map(function(checkede) {
                        return $(checkede).is(':checked');
                    });

                    for (var i = 0, l = checkede.length; i < l; i++) {

                        if (checkede[i] == true) {

                            var checkedvazio = 'true'
                        }
                    }

                    if (checkedvazio == 'true') {

                        var dados = $('.checkbox1:checked').parent().parent().find(".SEQCODUSUARIO").closest('.SEQCODUSUARIO').toArray().map(function(dados) {
                            return $(dados).text();
                        });
                        seqmodulosel = $('.seqmodulosel').val();

                        //  alert(seqmodulosel)

                        $.ajax({
                            url: "delete_permissaoaplic.php",
                            method: 'get',
                            data: 'dados=' + dados + '&seqaplicacao=' + seqmodulosel,
                            success: function(filtro) {
                                var selecionados = $(".selecionado");

                                var dados = "";

                                for (var i = 0; i < selecionados.length; i++) {
                                    var selecionado = selecionados[i];
                                    selecionado = selecionado.getElementsByClassName("td");
                                    dados += selecionado[0].innerHTML + "\n";
                                }
                                //var acomp = $(this).text();
                                //alert(dados);

                                $.ajax({
                                    url: "gerenciamento_aplicacao.php",
                                    method: 'get',
                                    data: 'SEQOPCAO=' + dados,
                                    success: function(filtro) {
                                        $('.tablereq').empty().html(filtro);

                                    }
                                });

                            }
                        });


                    } else {

                        alert('selecione um usuário');
                    }

                }
            },
            // 'colvis'
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
    $('.tableaplicacao').DataTable({
        dom: 'ft',
        scrollY: "350px",
        "language": {

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


        },        "lengthMenu": [
            [50],
            [50]
        ],
        "order": [
            [2, "asc"]
        ]

    });
    var tabela = $("#table1");
    //var linhas = tabela.getElementsByClassName("tr");
    var linhas = $('.trr');
    for (var i = 0; i < linhas.length; i++) {
        var linha = linhas[i];
        linha.addEventListener("click", function() {

            //Adicionar ao atual
            selLinha(this, false); //Selecione apenas um
            //selLinha(this, true); //Selecione quantos quiser
        });
    }

    function selLinha(linha, multiplos) {

        if (!multiplos) {
            var linhas = linha.parentElement.getElementsByTagName("tr");
            for (var i = 0; i < linhas.length; i++) {
                var linha_ = linhas[i];
                linha_.classList.remove("selecionado");
            }
        }
        linha.classList.toggle("selecionado");
    }

    $(".trr").click(function() {
        var linhaClicada = $(this).closest('tr');
        var dados = linhaClicada.find(".IDDisponivel").text();;
        var dadosDESCRICAODisponivel = linhaClicada.find(".DESCRICAODisponivel").text();
        criandoHtmlmensagemCarregamento("exibir");
        $.ajax({
            url: "Config/GerenciamentoPermissaoCargo.php",
            method: 'get',
            data: 'SEQOPCAO=' + dados + '&dadosDESCRICAODisponivel=' + dadosDESCRICAODisponivel,
            success: function(filtro) {
                $('.tablereq').empty().html(filtro);
                criandoHtmlmensagemCarregamento("ocultar");
            }
        });
    });
    $(".AdicionarNovoTipoMovimentacao").click(function() {
        var novoTipoMovimentacao = $('.novoTipoMovimentacao').val();

        criandoHtmlmensagemCarregamento("exibir");
        $.ajax({
            url: "Config/incluirTipoMovimentacao.php",
            method: 'get',
            data: 'novoTipoMovimentacao=' + novoTipoMovimentacao,
            success: function(filtro) {
                $('.tablereq').empty().html(filtro);
                criandoHtmlmensagemCarregamento("ocultar");
            }
        });
    });

    var tabela = $(".table_man_dados");
    //var linha1s1 = tabela.getElementsByClassName("tr");
    var linha1s1 = $('.tr');


    for (var i = 0; i < linha1s1.length; i++) {
        var linha1 = linha1s1[i];
        linha1.addEventListener("click", function() {
            //Adicionar ao atual
            sellinha1(this, false); //Selecione apenas um
            //sellinha1(this, true); //Selecione quantos quiser

        });
    }

    function sellinha1(linha1, multiplos) {
        if (!multiplos) {
            var linha1s1 = linha1.parentElement.getElementsByClassName("tr");
            for (var i = 0; i < linha1s1.length; i++) {
                var linha1_ = linha1s1[i];
                linha1_.classList.remove("selecionado2");
            }
        }
        linha1.classList.toggle("selecionado2");

    }
</script>