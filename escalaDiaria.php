<?php
include "../base/Conexao_teste.php";
include "../base/conexao_tovs.php";
include "../MobileNav/docs/index_menucomlogin.php";
include "config/php/CRUD_geral.php";

$dadosFunc = new Funcionarios();
$mesAtual = date("Y-m");
$escalaDiaria = $dadosFunc->informacoesEscalaDiaria($oracle,$_SESSION['LOJA'],$mesAtual );
print_r($escalaDiaria);
echo"</br>";
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($dbDB, $_SESSION['LOJA']);
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

<input class="dataAtual" id="mesAtual" value="<?= $mesAtual ?>">
<body style="background-color:#DCDCDC; ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="card" style="height:525px;border-color:#00a550;  ">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background-color: #00a550;color:white;">Todos os Cargos</h6>
                    <div class="card-body">
                        <table id="table1" class="table table-bordered table-striped text-center tableaplicacao">
                            <input class="usu" type="HIDDEN" value="<?= $_SESSION['nome'] ?>">
                            <thead style="background-color: #00a550;">
                                <tr>
                                    <th></th>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">Cargo</th>
                                    <th class="text-center">Escala mensal</th>
                                    <th class="text-center">Início LJ</th>
                                    <th class="text-center">Inicio Intervalo</th>
                                    <th class="text-center">Fim Intervalo</th>
                                    <th class="text-center">Fim LJ</th>
                                    <th>
                                        ajuste na escala padrao
                                    </th>
                                    <th>
                                        Periodo de validade
                                    </th>
                                </tr>
                            </thead>


                            <tbody style="background-color: #DCDCDC;">
                                <?php
                                foreach ($dadosFunc as $row) :
                                ?>
                                    <tr class="trr">
                                        <td class="text-center td checkboxTabela" scope="row"><input type="checkbox" class="checkbox" name="checkbox" id="checkbox" value=""></td>
                                        <td class="text-center td" scope="row" id="cargo"><?= $row['NOME'] ?></td>
                                        <td class="text-center td" scope="row">Operador de Caixa</td>
                                        <td class="text-center td" scope="row"><a style="color:#00a550" href="escalaMensal.php">link p/ escala</a></td>
                                        <td class="text-center td" scope="row">Início LJ</td>
                                        <td class="text-center td" scope="row">Inicio Intervalo</td>
                                        <td class="text-center td" scope="row">Fim Intervalo</td>
                                        <td class="text-center td" scope="row">Fim LJ</td>
                                        <td></td>
                                        <td>

                                        </td>
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
    <script src="js/Script_escalaDiaria.js" defer></script>
    <script>

    </script>

</body>

</html>