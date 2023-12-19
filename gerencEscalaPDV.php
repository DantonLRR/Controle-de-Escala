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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../base/dist/sidenav.css" type="text/css">
    <link rel="stylesheet" href="css/Style_escalaMensal.css" type="text/css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    </link>
    <link rel="stylesheet" href="../BASE/cssGeral.css" type="text/css">
    <link rel="stylesheet" href="../../BASE/DataTables/FixedColumns 4.3.0/FixedColumns-4.3.0/css/fixedColumns.dataTables.min.css" type="text/css">

</head>
<?php
$hoje = date("Y-m-d");
$diaDeHoje = date("d");
$mesEAnoAtual = date("Y-m");
$informacoesDaslojas = new lojas;
?>

<body>
    <input class="usu" id="usuarioLogado" type="hidden" value="<?= $_SESSION['nome'] ?>">
    <input class="dataAtual" id="dataAtual" type="hidden" value="<?= $hoje ?>">
    <input class="" id="" type="hidden" value="<?= $mesEAnoAtual ?>">
    <div class="container-fluid">

        <div class="row" id="qntPessoasPorPDV">
            <div class="col-lg-12">
                <div class="card">
                    <div style="font-weight: bold;  background: linear-gradient(to right, #00a451, #052846 85%); color:white" class="text-center card-header">Gerenciamento de Escala de PDV</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 informacoesEsquerda1Card">
                                <div class="mb-4">
                                    <label for="validationCustom02" class="form-label">Mês/Ano: </label>


                                    <input type="month" class="form-control dataPesquisa margin-bottom" value="<?= $mesEAnoAtual ?>" id="dataPesquisa">

                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">
                                    Loja
                                </label>
                                <select required id="loja" class="form-control statusocul">
                                    <option></option>
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
                            <div class="col-lg-2 pt-4">
                                <button type="button" id="PesquisarEscalaMensal" style="background-color: #00a550 ;color: white;font-weight:bold" class="btn">Pesquisar</button>
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


    <div class="row blocoVerificaELiberaEscala" style="visibility:hidden;">
        <div class="col-lg-12">
            <div class="card" style="border-color:#00a550;">
                <h6 class="card-header text-center font-weight-bold text-uppercase " style="background: linear-gradient(to right, #00a451, #052846 85%); color: white;font-weight:bold">

                    <i id="BTNAdicionarDescritivo2" class="far fa-plus-square ocultar "> </i>
                    <i id="BTNremoverDescritivo2" class="far fa-minus-square "> </i>

                    Visualização da escala Mensal
                </h6>

                    <div class="col-lg-6 mt-3">
                        <button type="button" id="LiberarEscala" style="background-color: #00a550 ;color: white;font-weight:bold" class="btn">Liberar Escala</button>
                        <button id="exportButton" style="background-color: #00a550 ;color: white;font-weight:bold" class="btn">
                        <i class="fa-solid fa-table" style="color: #ffffff;"></i> 
                        Excel
                    </button>
                    </div>
                <div id="PesquisaEscalaMensal" class="card-body ">
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
    <script type="module" src="js/Script_gerenciamentoEscalaMensal.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin/tableExport.min.js"></script>

    <script type="text/javascript" src="../base/mdb/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="../../base/DataTables//FixedColumns 4.3.0//FixedColumns-4.3.0/js/dataTables.fixedColumns.min.js"></script>
</body>
<script>
    
</script>
</html>