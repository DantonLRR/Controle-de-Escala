<?php
include "../base/Conexao_teste.php";
include "../base/conexao_tovs.php";
include "../MobileNav/docs/index_menucomlogin.php";
include "config/php/CRUD_geral.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link href="../base/mdb/css/bootstrap.css" rel="stylesheet">
    <link href="../base/assets/css/paper-dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="../base/DataTables/datatables.min.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../base/dist/sidenav.css" type="text/css">
    <link rel="stylesheet" href="css/Style_escalaMensal.css" type="text/css">
    <link type="text/javascript" src="../base/DataTables/FixedColumns-3.3.1/css/fixedColumns.dataTables.min.css">
    </link>

</head>
<?php

$InformacaoDosDias = new Dias();
$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$mesEAnoFiltro = $InformacaoDosDias->mesEAnoFiltro($oracle);
$InsertDeDados = new Insert();
$updateDeDados = new Update();
$mesAtual = date("Y-m");
$usuarioLogado = $_SESSION['nome'];
$dadosFunc = new Funcionarios();
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($dbDB, $_SESSION['LOJA']);
?>
<style>


</style>

<body style="background-color:#DCDCDC; ">
    <div class="container-fluid">
        <input class="usu" id="usuarioLogado" value="<?= $_SESSION['nome'] ?>">
        <input class="usu" id="loja" value="<?= $_SESSION['LOJA'] ?>">
        <input class="dataAtual" id="mesAtual" value="<?= $mesAtual ?>">
        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-color:#00a550;  ">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background-color: #00a550;color:white;">Escala Mensal</h6>
                    <div class="card-body">
                        <label for="validationCustom02" class="form-label">Mês/Ano: </label>

                        <div class="col-lg-2">
                            <input type="month" class="form-control dataPesquisa" id="dataPesquisa">
                        </div>



                        <table id="table1" class="table table-bordered table-striped text-center row-border order-colum" style="width:100%">
                            <thead style="background-color: #00a550;">

                                <tr class="trr ">
                                    <th class="text-center" scope="row">Funcionario</th>
                                    <th class="text-center" scope="row">matricula</th>
                                    <?php
                                    foreach ($buscandoMesAno as $row) :
                                    ?>
                                        <th class="text-center numeroDiaDaSemana" scope="row"><?= $row['DIA'] ?></th>

                                    <?php
                                    endforeach
                                    ?>
                                </tr>


                            </thead>


                            <tbody style="background-color: #DCDCDC;">




                                <tr class="trr" id="quantDias">
                                    <td></td>
                                    <td></td>
                                    <?php
                                    foreach ($buscandoMesAno as $row) :
                                    ?>
                                        <td class="text-center diaDaSemana" scope="row"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>

                                    <?php
                                    endforeach
                                    ?>
                                </tr>




                                <?php
                                foreach ($buscaNomeFuncionario as $nomeFunc) :
                                ?>
                                    <tr class="trr">
                                        <td class="text-center funcionario" scope="row"><?= $nomeFunc['NOME'] ?></td>
                                        <td class="text-center matriculaFunc" scope="row"><?= $nomeFunc['MATRICULA'] ?></td>

                                        <?php
                                        $i = 1;
                                        foreach ($buscandoMesAno as $row) :
                                        ?>
                                            <td class=" text-center " scope="row" id="">
                                                <?php
                                                $recuperaDadosVerificacao = new verifica();
                                                $recuperacaoDedados = $recuperaDadosVerificacao->verificaCadastroNaEscalaMensa1($oracle, $nomeFunc['MATRICULA'], $mesAtual);
                                                if ($i < 10) {
                                                    $d = "0" . $i;
                                                } else {
                                                    $d = $i;
                                                }  ?>

                                                <select class="estilezaSelect" name="" id="">
                                                    <option value=""><?= $recuperacaoDedados[0]["$d"] ?? '' ?></option>

                                                    <option value="F">F</option>
                                                    <option value="FA">FA</option>
                                                    <option value="V">V</option>

                                                </select>
                                            </td>
                                        <?php
                                            $i++;
                                        endforeach
                                        ?>
                                    </tr>
                                <?php
                                endforeach
                                ?>
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-color:#00a550;  ">
                    <div class="card-body">
                        <table id="table2" class="table table-bordered table-striped text-center row-border order-colum" style="width:100%">
                            <input class="usu" type="HIDDEN" value="<?= $_SESSION['nome'] ?>">

                            <thead style="background-color: #00a550;">
                                <tr class="trr ">
                                    <th class="text-center" scope="row">Funcionario</th>
                                    <th class="text-center" scope="row">Horário de trabalho</th>
                                    <th class="text-center" scope="row">Horário de almoço</th>
                                    <th class="text-center" scope="row">Observação</th>
                                </tr>


                            </thead>


                            <tbody style="background-color: #DCDCDC;">
                                <tr class="trr">
                                    <?php
                                    foreach ($buscandoMesAno as $row) :
                                    ?>
                                <tr class="trr">
                                    <td class="text-center" scope="row" id="cargo"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>
                                    <td class="text-center" scope="row" id="cargo"></td>
                                    <td class="text-center" scope="row" id="cargo"></td>
                                    <td class=" text-center " scope="row" id="" contenteditable></textarea>
                                    </td>
                                <?php
                                    endforeach
                                ?>
                                </tr>
                                <?php

                                ?>
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="../base/mdb/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../base/mdb/js/jquery.min.js"></script>
        <script type="text/javascript" src="../base/bootstrap-5.0.2/bootstrap-5.0.2/dist/js/bootstrap.bundle.js"></script>
        <script type="text/javascript" src="../base/mdb/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="../base/mdb/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="../base/DataTables/datatables.min.js"></script>
        <script type="text/javascript" src="../base/Buttons/js/dataTables.buttons.js"></script>
        <script type="text/javascript" src="../base/Buttons/js/buttons.html5.js"></script>
        <script type="text/javascript" src="../base/Buttons/js/buttons.print.js"></script>
        <script type="text/javascript" src="../base/DataTables/FixedColumns/js/dataTables.fixedColumns.min.js"></script>

        <script src="../base/dist/sidenav.js"></script>
        <script src="js/Script_escalaMensal.js" defer></script>
        <script>
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
        </script>

</body>

</html>