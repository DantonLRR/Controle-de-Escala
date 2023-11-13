<?php
include "../base/Conexao_teste.php";

include "../MobileNav/docs/index_menucomlogin.php";
include "config/php/CRUD_geral.php";
include "../base/conexao_TotvzOracle.php";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">


    <link rel="stylesheet" href="../../BASE/DataTables/FixedColumns 4.3.0/FixedColumns-4.3.0/css/fixedColumns.dataTables.min.css" type="text/css">

    <link rel="stylesheet" href="../BASE/cssGeral.css" type="text/css">
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
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $_SESSION['LOJA']);
?>


<body style="background-color:#DCDCDC; ">
    <div class="container-fluid">
        <input class="usu" type="hidden" id="usuarioLogado" value="<?= $_SESSION['nome'] ?>">
        <input class="usu" type="hidden" id="loja" value="<?= $_SESSION['LOJA'] ?>">
        <input class="dataAtual" type="hidden" id="mesAtual" value="<?= $mesAtual ?>">
        <div class="row">
            <div class="col-lg-12  ">
                <div class="card " style="border-color:#00a550;  ">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background-color: #00a550;color:white;">Escala Mensal</h6>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="validationCustom02" class="form-label">Mês/Ano: </label>

                            <div class="col-lg-2">
                                <input type="month" class="form-control dataPesquisa margin-bottom" id="dataPesquisa">
                            </div>
                        </div>

                        <div class="atualizaTabela " >
                            <table id="table1" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
                                <thead>

                                    <tr class="trr ">
                                        <th class="text-center theadColor" scope="row" style="width:150px">Funcionario</th>
                                        <th class="text-center theadColor" scope="row" style="width:150px ;display:none">matricula</th>
                                        <th class="text-center theadColor" style="display:none"> HoraEntrada</th>
                                        <th class="text-center theadColor" style="display:none"> HoraSaida</th>
                                        <th class="text-center theadColor" style="display:none"> HoraIntervalo</th>
                                        <th class="text-center theadColor" style="display:none"> cargo</th>
                                        <?php
                                        foreach ($buscandoMesAno as $row) :
                                        ?>
                                            <th class="text-center numeroDiaDaSemana" scope="row"><?= $row['DIA'] ?></th>

                                        <?php
                                        endforeach
                                        ?>
                                    </tr>


                                </thead>


                                <tbody>




                                    <tr class="trr" id="quantDias">
                                        <td></td>
                                        <td style="display:none"></td>
                                        <td style="display:none"></td>
                                        <td style="display:none"></td>
                                        <td style="display:none"></td>
                                        <td style="display:none"></td>
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
                                            <td class="text-center matriculaFunc" style="display:none" scope="row"><?= $nomeFunc['MATRICULA'] ?></td>
                                            <td class="text-center horarioEntradaFunc" style="display:none" scope="row"><?= $nomeFunc['HORAENTRADA'] ?></td>
                                            <td class="text-center horarioSaidaFunc" style="display:none" scope="row"><?= $nomeFunc['HORASAIDA'] ?></td>
                                            <td class="text-center horarioIntervaloFunc" style="display:none" scope="row"><?= $nomeFunc['SAIDAPARAALMOCO'] ?></td>
                                            <td class="text-center cargo" style="display:none" scope="row"><?= $nomeFunc['FUNCAO'] ?></td>
                                            <?php
                                            $i = 1;
                                            foreach ($buscandoMesAno as $row) :
                                            ?>
                                                <td class=" text-center " scope="row" id="">
                                                    <?php
                                                    $recuperaDadosVerificacao = new verifica();
                                                    $recuperacaoDedados = $recuperaDadosVerificacao->verificaCadastroNaEscalaMensa1($oracle,  $nomeFunc['MATRICULA'], $mesAtual);
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
                                                        <option value=""></option>

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
        </div>





        <script type="text/javascript" src="../base/mdb/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../base/mdb/js/jquery.min.js"></script>
        <script type="text/javascript" src="../base/bootstrap-5.0.2/bootstrap-5.0.2/dist/js/bootstrap.bundle.js"></script>
        <script type="text/javascript" src="../base/mdb/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="../base/mdb/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="../base/DataTables/datatables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

        <script type="text/javascript" src="../../base/DataTables//FixedColumns 4.3.0//FixedColumns-4.3.0/js/dataTables.fixedColumns.min.js"></script>
        <script src="../base/dist/sidenav.js"></script>
        <script type="module" src="js/Script_escalaMensal.js" defer></script>
        <script>
            $('#table1').DataTable({
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
                dom: 'Bfrtip',
                scrollY: 450,
                scrollX: true,

                scrollXInner: "100%",
                scrollCollapse: true,
                searching: true,

                "paging": true,
                "info": false,
                "ordering": false,
                "lengthMenu": [
                    [40],
                ],
                fixedColumns: {
                    left: 2,
                },

                buttons: [{
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, ':visible']
                        }
                    },
                    {
                        text: 'Escala Diaria',
                        className: '',
                        action: function() {
                            window.location.href = "escalaDiaria.php";
                        }
                    },
                ],

            });
        </script>
    </div>
</body>

</html>