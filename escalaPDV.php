<?php
include "../base/Conexao_teste.php";
include "../MobileNav/docs/index_menucomlogin.php";
include "config/php/CRUD_escalaMensal.php";

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link href="../base/mdb/css/bootstrap.css" rel="stylesheet">
    <link href="../base/assets/css/paper-dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="../base/DataTables/datatables.min.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../base/dist/sidenav.css" type="text/css">
    <link rel="stylesheet" href="css/style_escalaPDV.css" type="text/css">
    <link type="text/javascript" src="../base/DataTables/FixedColumns-3.3.1/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

    </link>

</head>
<?php

$InformacaoDosDias = new Dias();
$InformacaoFuncionarios = new Funcionarios();



$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$mesEAnoFiltro = $InformacaoDosDias->mesEAnoFiltro($oracle);

$hoje = date("Y-m-d");
// echo $hoje;



$horarios = array();
for ($i = 7; $i <= 21; $i++) {
    $horarios[] = sprintf("%02d:00", $i);
}

?>



<body style="background-color:#DCDCDC; ">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="margin-top: 1.2rem;">
                    <div style="font-weight: bold; background-color: #00a550; color:white" class="text-center card-header">NECESSIDADE DE OPERADORES POR HORÁRIO</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <label class="form-label">
                                    Quantidade de operadores por dia
                                </label>
                                <input value="" type="number" class="form-control dataInicialPesquisa" id="dataInicialPesquisa">
                                <div class="col-lg-2" style="margin-top: 30px;">
                                    <button type="button" class="btn btnverde">Pesquisar</button>
                                </div>
                            </div>

                            <div class="col-lg-10">
                                <label class="form-label">
                                    Bips Total:
                                </label>

                                <table id="tableHeader" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">
                                    <input class="usu" type="HIDDEN" value="<?= $_SESSION['nome'] ?>">
                                    <thead style="background-color: #00a550; color: white;">
                                        <tr class="trr">


                                            <th class="text-center" colspan=""></th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color: #DCDCDC;">
                                        <tr class="trr">
                                            <td class="text-center" scope="row" id=""></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <div class="col-lg-2">
                                <label class="form-label">
                                    Comprador
                                </label>

                                <select class="form-control" id="comprador">

                                    <option value=""></option>

                                </select>
                            </div>



                            <div class="col-lg-4">
                                <label for="validationCustom02" class="form-label">
                                    Fornecedor (Rede)
                                </label>
                                <div class="form-group atualizarforn">
                                    <select name="multiselect[]" multiple="multiple" id="tipoContrato" class="col-lg-4 form-control" style="display: inline;">
                                    </select>
                                </div>
                            </div> -->






                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-color:#00a550;  ">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background-color: #00a550;color:white;">
                        <button class="btn" style=" color: white;font-weight:bold;  opacity: 1;" type="button">
                            <i id="BTNAdicionarDescritivo" class="far fa-plus-square ocultar "> </i>
                            <i id="BTNremoverDescritivo" class="far fa-minus-square "> </i>

                        </button>
                        Escala de Operadores
                    </h6>
                    <div id="cardTable1" class="card-body ">
                        <label for="validationCustom02" class="form-label"> Mês/Ano: </label>
                        <div class="col-lg-2">
                            <input type="date" class="form-control dataPesquisa" id="dataPesquisa">
                        </div>
                        <div class="table dadosEscalaPDV" style="overflow-x:auto;">
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
                                        $qntPDV[] = $i;
                                    }
                                    // add a variavel hoje para poder filtrar a pagina inicial da tabela tendo conteudo ou nao
                                    $horariosFuncManha = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoManha($oracle, $hoje);
                                    $horariosFuncTarde = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoTarde($oracle, $hoje);
                                    $totalManha = count($horariosFuncManha);
                                    $totalTarde = count($horariosFuncTarde);

                                    foreach ($qntPDV as $row) :
                                        $row2Manha = ($row <= $totalManha) ? $horariosFuncManha[$row - 1] : ['MATRICULA' => '', 'NOME' => '', 'HORAENTRADA' => '', 'HORASAIDA' => '', 'HORAINTERVALO' => ''];
                                        $row3Tarde = ($row <= $totalTarde) ? $horariosFuncTarde[$row - 1] : ['MATRICULA' => '', 'NOME' => '', 'HORAENTRADA' => '', 'HORASAIDA' => '', 'HORAINTERVALO' => ''];
                                    ?>
                                        <tr class="trr">
                                            <td scope="row" id="">
                                                <?= $row ?>
                                            </td>
                                            <td scope="row" class="Matricula1" contenteditable="true"><?= $row2Manha['MATRICULA'] ?></td>
                                            <td scope="row" class="NomeFunc">
                                                <select class="estilezaSelect form-control" id="selectFuncionario">
                                                    <option value="<?= $row2Manha['NOME'] ?>"><?= $row2Manha['NOME'] ?></option>
                                                    <?php
                                                    foreach ($horariosFuncManha as $rowManha) :
                                                    ?>
                                                        <div>
                                                            <option style="color: black; font-weight: bold;" value="<?= $rowManha['NOME'] ?>"> <?= $rowManha['NOME'] ?> </option>
                                                        </div>
                                                    <?php
                                                    endforeach
                                                    ?>
                                                </select>
                                            </td>
                                            <td scope="row" class="text-center horaEntrada1"><?= $row2Manha['HORAENTRADA'] ?></td>
                                            <td scope="row" class="horaSaida1"><?= $row2Manha['HORASAIDA'] ?></td>
                                            <td scope="row" class="horaIntervalo1"><?= $row2Manha['HORAINTERVALO'] ?></td>

                                            <td scope="row" class="matricula2" contenteditable="true"><?= $row3Tarde['MATRICULA'] ?></td>
                                            <td scope="row" class="text-center nome2">
                                                <select class="estilizaSelect2 form-control">
                                                    <option value="<?= $row3Tarde['NOME'] ?>"><?= $row3Tarde['NOME'] ?></option>
                                                    <?php
                                                    foreach ($horariosFuncTarde as $rowTarde) :
                                                    ?>
                                                        <div>
                                                            <option style="color: black; font-weight: bold;" value="<?= $rowTarde['NOME'] ?>"> <?= $rowTarde['NOME'] ?> </option>
                                                        </div>
                                                    <?php
                                                    endforeach
                                                    ?>
                                                </select>
                                            </td>
                                            <td scope="row" class="horaEntrada2"><?= $row3Tarde['HORAENTRADA'] ?></td>
                                            <td scope="row" class="horaSaida2"><?= $row3Tarde['HORASAIDA'] ?></td>
                                            <td scope="row" class="horaIntervalo2"><?= $row3Tarde['HORAINTERVALO'] ?></td>
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


        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-color:#00a550;  ">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background-color: #00a550;color:white;">
                        <button class="btn" style=" color: white;font-weight:bold;   opacity: 1;" type="button">
                            <i id="BTNAdicionarDescritivo2" class="far fa-plus-square  ocultar"> </i>
                            <i id="BTNremoverDescritivo2" class="far fa-minus-square "> </i>
                        </button>
                        NECESSIDADE DE OPERADORES POR HORÁRIO
                    </h6>
                    <div id="CardTable2" class="card-body ">
                        <table id="table2" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">
                            <input class="usu" type="HIDDEN" value="<?= $_SESSION['nome'] ?>">
                            <thead style="background-color: #00a550; color: white;">
                                <tr class="trr">
                                    <th> PDV </th>
                                    <?php
                                    foreach ($horarios as $row) :
                                    ?>
                                        <th class="text-center" scope="row" id=""><?= $row ?></th>
                                    <?php

                                    endforeach
                                    ?>
                                </tr>

                            </thead>
                            <tbody style="background-color: #DCDCDC;">
                                <?php
                                foreach ($qntPDV as $row) : ?>

                                    <tr class="trr">

                                        <td class="text-center" scope="row" id="">
                                            <?= $row ?>
                                        </td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>
                                        <td class="text-center" scope="row" id=""></td>

                                    </tr>
                                <?php
                                endforeach ?>
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

    <script type="text/javascript" src="../base/DataTables/FixedColumns/js/dataTables.fixedColumns.min.js"></script>

    <script src="../base/dist/sidenav.js"></script>
    <script type="module" src="js/Script_escalaPDV.js" defer></script>

    <script>

    </script>

</body>

</html>