<?php
include "../base/Conexao_teste.php";
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
$mesEAnoFiltro = $InformacaoDosDias->mesEAnoFiltro($oracle)
?>
<style>


</style>

<body style="background-color:#DCDCDC; ">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-color:#00a550;  ">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background-color: #00a550;color:white;">Escala Mensal</h6>
                    <div class="card-body">
                        <label for="validationCustom02" class="form-label">Mês/Ano: </label>
                        <select name="selectMes" id="selectMes" class="col-lg-1 form-control">>
                            <?php
                            foreach ($mesEAnoFiltro as $row) :
                            ?>
                                <div>
                                    <option style="color: black; font-weight: bold;" value="<?= $row['MES'] ?>"> <?= $row['MES'] ?> </option>
                                </div>
                            <?php
                            endforeach
                            ?>
                        </select>



                        <table id="table1" class="table table-bordered table-striped text-center row-border order-colum" style="width:100%">
                            <input class="usu" type="HIDDEN" value="<?= $_SESSION['nome'] ?>">


                            <thead style="background-color: #00a550;">



                                <tr class="trr ">
                                    <th class="text-center" scope="row">Funcionario</th>
                                    <?php
                                    foreach ($buscandoMesAno as $row) :
                                    ?>
                                        <th class="text-center" scope="row" id="cargo"><?= $row['DIA'] ?></th>

                                    <?php
                                    endforeach
                                    ?>
                                </tr>


                            </thead>


                            <tbody style="background-color: #DCDCDC;">




                                <tr class="trr" id="quantDias">
                                    <td></td>

                                    <?php
                                    foreach ($buscandoMesAno as $row) :
                                    ?>
                                        <td class="text-center" scope="row" id="cargo"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>

                                    <?php
                                    endforeach
                                    ?>
                                </tr>




                                <?php
                                foreach ($buscandoMesAno as $row) :
                                ?>
                                    <tr class="trr">
                                        <td class="text-center" scope="row" id="cargo"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>

                                        <?php
                                        foreach ($buscandoMesAno as $row) :
                                        ?>
                                            <td class=" text-center " scope="row" id="">

                                                <select class="estilezaSelect" name="" id="">
                                                    <option value=""></option>
                                                    <option value="">F</option>
                                                    <option value="">FA</option>
                                                    <option value="">V</option>

                                                </select>
                                            </td>
                                        <?php
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


</body>

</html>