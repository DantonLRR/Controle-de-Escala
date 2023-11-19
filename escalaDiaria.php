<?php
include "../base/Conexao_teste.php";

include "../MobileNav/docs/index_menucomlogin.php";
include "config/php/CRUD_geral.php";
include "../base/conexao_TotvzOracle.php";
$verificacaoDeDados = new Verifica();
$dadosFunc = new Funcionarios();
$mesAtual = date("Y-m");
$diaAtual = date("d");
$diaAtual2 = '"' . date("d") . '"';
$hoje = date("Y-m-d");

// echo $diaAtual2;


// print_r($escalaDiaria);

$dadosDoFuncionarioAPartirDaEscalaMensal = $dadosFunc->DadosAPartirDaEscalaMensal($oracle, $diaAtual2, $_SESSION['LOJA'], $mesAtual);
// print_r($dadosDoFuncionarioAPartirDaEscalaMensal);

// print_r($buscaNomeFuncionario);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet" href="../base/fontawesome6.1.1/js/all.js">
    <link href="../base/mdb/css/bootstrap.css" rel="stylesheet">
    <link href="../base/assets/css/paper-dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="../base/DataTables/datatables.min.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link rel='stylesheet' href='http://fonts.googleapis.com/icon?family=Material+Icons' type='text/css'>
    <link rel="stylesheet" href="../base/dist/sidenav.css" type="text/css">
    <link rel="stylesheet" href="css/Style_escalaDiaria.css" type="text/css">
    <link rel="stylesheet" href="../BASE/cssGeral.css" type="text/css">

    <style>
        .row {
            display: flex;
            justify-content: space-around;

        }

        .card {
            box-shadow: 5px 10px 18px
        }
    </style>
</head>

<input class="dataAtual" id="mesAtual" type="hidden" value="<?= $mesAtual ?>">
<input class="dataAtual" id="diaAtual" type="hidden" value="<?= $diaAtual ?>">
<input id="usuLogado" type="hidden" value="<?= $_SESSION['nome'] ?>">
<input id="loja" type="hidden" value="<?= $_SESSION['LOJA'] ?>">
<input id="diaDeHoje" type="hidden" value="<?= $hoje ?>">

<body style="background-color:#DCDCDC; ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="border-color:#00a550;  ">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background-color: #00a550;color:white;">Escala Diaria</h6>
                    <div class="card-body">
                        <table id="table1" class="table table-bordered table-striped text-center " style="width:100%">
                            <input class="usu" type="HIDDEN" value="<?= $_SESSION['nome'] ?>">
                            <thead style="background-color: #00a550;">
                                <tr>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">matricula</th>
                                    <th class="text-center">Cargo</th>
                                    <th class="text-center"><a style="color:white" href="escalaMensal.php">Escala Mensal</a></th>
                                    <th class="text-center">Hora Entrada</th>
                                    <th class="text-center">Hora Saida</th>
                                    <th class="text-center">Hora Intervalo</th>

                                    <th class="text-center">Periodo de validade</th>
                                </tr>
                            </thead>


                            <tbody style="background-color: #DCDCDC;">
                                <?php



                                foreach ($dadosDoFuncionarioAPartirDaEscalaMensal as $ROWconsultaNomeFunc) :
                                    // echo "dados abaixo recuperados do banco PLSQL <br><br>";
                                    // print_r($ROWconsultaNomeFunc);
                                    // echo "<br><br>";
                                ?>

                                    <tr class="trr">
                                        <td class="text-center td nomeFuncionario" scope="row" id="nome">
                                            <?= $ROWconsultaNomeFunc['NOME'] ?>
                                        </td>
                                        <td class=" text-center td matriculaFunc">
                                            <?= $ROWconsultaNomeFunc['MATRICULA'] ?>
                                        </td>
                                        <td class="text-center td cargo" scope="row" id="cargo">
                                            <?= $ROWconsultaNomeFunc['CARGO'] ?>
                                        </td>
                                        <td class="text-center td" scope="row" id="escalaMensal">
                                            <?= $ROWconsultaNomeFunc[$diaAtual] ?? '' ?>
                                        </td>

                                        <?php
                                        $verifica = $verificacaoDeDados->verificaAlteracaoNoHorarioDiario($oracle, $ROWconsultaNomeFunc['MATRICULA'], $hoje,  $ROWconsultaNomeFunc['NOME'], $_SESSION['LOJA']);
                                        if ($retorno == "Já existem dados.") {
                                            $dadosAPartirDaEscalaIntermed = $dadosFunc->recuperaDadosDaEscalaIntermed($oracle, $ROWconsultaNomeFunc['MATRICULA'],   $ROWconsultaNomeFunc['NOME'], $_SESSION['LOJA'], $hoje,);
                                            foreach ($dadosAPartirDaEscalaIntermed as $RowDadosIntermed) :
                                        ?>
                                                <td class="text-center td horaEntrada " scope="row">
                                                <input class="" id="" type="time" value="<?= $RowDadosIntermed['HORAENTRADA'] ?>" <?= isset($ROWconsultaNomeFunc[$diaAtual]) ? 'readonly' : '' ?>>                                               
                                                </td>
                                                <td class="text-center td horaSaida horarioSaidaFunc" scope="row">
                                                    <input class="" id="" type="time" value="<?= $RowDadosIntermed['HORASAIDA'] ?>" <?= isset($ROWconsultaNomeFunc[$diaAtual]) ? 'readonly' : '' ?>>

                                                </td>
                                                <td class="text-center td horaSaida horarioIntervalo" scope="row">
                                                    <input class="" id="" type="time" value="<?= $RowDadosIntermed['HORAINTERVALO'] ?>" <?= isset($ROWconsultaNomeFunc[$diaAtual]) ? 'readonly' : '' ?>>
                                                </td>
                                            <?php
                                            endforeach;
                                        } else if ($retorno == "Não existem dados.") {
                                            ?>
                                            <td class="text-center td horaEntrada " scope="row">
                                                <input class=" " id="" type="time" value="<?= $ROWconsultaNomeFunc['HORAENTRADA'] ?>" <?= isset($ROWconsultaNomeFunc[$diaAtual]) ? 'readonly' : '' ?>>

                                            </td>
                                            <td class="text-center td horaSaida horarioSaidaFunc" scope="row">
                                                <input class="" id="" type="time" value="<?= $ROWconsultaNomeFunc['HORASAIDA'] ?>" <?= isset($ROWconsultaNomeFunc[$diaAtual]) ? 'readonly' : '' ?>>

                                            </td>
                                            <td class="text-center td horaSaida horarioIntervalo" scope="row">
                                                <input class="" id="" type="time" value="<?= $ROWconsultaNomeFunc['HORAINTERVALO'] ?>" <?= isset($ROWconsultaNomeFunc[$diaAtual]) ? 'readonly' : '' ?>>
                                            </td>
                                        <?php

                                        }
                                        ?>
                                        <td class="text-center td" scope="row">
                                            <?= $ROWconsultaNomeFunc['MESSELECIONADOFORMATADO'] ?>
                                        </td>

                                    </tr>
                                <?php
                                endforeach;

                                ?>
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script type="text/javascript" src="../base/mdb/js/jquery.min.js"></script>
    <script src="../base/bootstrap-5.0.2/bootstrap-5.0.2/dist/js/bootstrap.bundle.js"></script>
    <script type="text/javascript" src="../base/mdb/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../base/mdb/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../base/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="../base/Buttons/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="../base/JSZip/jszip.js"></script>
    <script type="text/javascript" src="../base/Buttons/js/buttons.html5.js"></script>
    <script type="text/javascript" src="../base/Buttons/js/buttons.print.js"></script>
    <script src="../base/dist/sidenav.js"></script>
    <script type="module" src="js/Script_escalaDiaria.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


</body>
<script>

</script>

</html>