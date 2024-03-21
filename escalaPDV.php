<?php
include "../base/conexao_martdb.php";
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../base/dist/sidenav.css" type="text/css">
    <link rel="stylesheet" href="css/style_escalaPDV.css" type="text/css">
    <link type="text/javascript" src="../base/DataTables/FixedColumns-3.3.1/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    </link>
    <link rel="stylesheet" href="../BASE/cssGeral.css" type="text/css">
    <link rel="icon" type="../base/image/png" href="../base/img/martband.png">
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css"> -->
</head>
<?php
$hoje = date("Y-m-d");
$diaDeHoje = date("d");
$mesEAnoAtual = date("Y-m");


$diaDeHojeComAspas = '"' . $diaDeHoje . '"';

$dadosDeQuemEstaLogadoNome = '';
$dadosDeQuemEstaLogadoFuncao = '';
$dadosDeQuemEstaLogadoSetor = '';

$InformacaoDosDias = new Dias();
$InformacaoFuncionarios = new Funcionarios();
$verificaSeAPessoaLogadaEEncarregada = $InformacaoFuncionarios->informacaoPessoaLogada($TotvsOracle, $_SESSION['cpf'], $_SESSION['LOJA']);
// print_r($verificaSeAPessoaLogadaEEncarregada);
foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['SETOR'];
endforeach;
$calculoDeFuncionariosNecessariosPorHora = new Porcentagem();

$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$mesEAnoFiltro = $InformacaoDosDias->mesEAnoFiltro($oracle);

$FuncManha = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoManha($oracle, $_SESSION['LOJA'], $diaDeHojeComAspas, $mesEAnoAtual, $dadosDeQuemEstaLogadoSetor, $hoje);
// var_dump($FuncManha);
// echo "<br><br><br>";
$FuncEscaladosMANHA = $InformacaoFuncionarios->FuncsJaEscaladosMANHA($oracle, $hoje, $_SESSION['LOJA']);
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


$FuncTarde = $InformacaoFuncionarios->buscaFuncEHorarioDeTrabalhoTarde($oracle, $_SESSION['LOJA'], $diaDeHojeComAspas, $mesEAnoAtual, $dadosDeQuemEstaLogadoSetor, $hoje);
$FuncEscaladosTARDE = $InformacaoFuncionarios->FuncsJaEscaladosTARDE($oracle, $hoje, $_SESSION['LOJA']);
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



$horarios = array();
for ($i = 7; $i <= 21; $i++) {
    $horarios[] = sprintf("%02d:00", $i);
}


?>

<body>
    <input class="usu" id="usuarioLogado" type="hidden" value="<?= $_SESSION['nome'] ?>">
    <input class="usu" id="loja" type="hidden" value="<?= $_SESSION['LOJA'] ?>">
    <input class="dataAtual" id="dataAtual" type="hidden" value="<?= $hoje ?>">
    <input class="" id="" type="hidden" value="<?= $mesEAnoAtual ?>">
    <input class="" type="hidden" id="dadosDeQuemEstaLogadoNome" value="<?= $dadosDeQuemEstaLogadoNome ?>">
    <input class="" type="hidden" id="dadosDeQuemEstaLogadoFuncao" value="<?= $dadosDeQuemEstaLogadoFuncao ?>">
    <input class="" type="hidden" id="dadosDeQuemEstaLogadoSetor" value="<?= $dadosDeQuemEstaLogadoSetor ?>">

    <div class="container-fluid">

        <div class="row" id="qntPessoasPorPDV">
            <div class="col-lg-12">
                <div class="card">
                    <div style="font-weight: bold; background: linear-gradient(to right, #00a451, #052846 85%); color:white;" class="text-center card-header">NECESSIDADE DE OPERADORES POR HORÁRIO</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 informacoesEsquerda1Card">
                                <label class="form-label">
                                    Dia Vigente Da pesquisa
                                </label>
                                <input type="date" class="form-control dataPesquisa" id="dataPesquisa" value="<?= $hoje ?>">
                                <label class="form-label ">
                                    Quantidade de operadores :
                                </label>
                                <div class="atualizaOpPorDia">
                                    <?php
                                    $quantidadePorDiaDeFuncionarios = $InformacaoFuncionarios->funcionariosDisponiveisNoDia($oracle, $diaDeHojeComAspas, $mesEAnoAtual, $dadosDeQuemEstaLogadoSetor, $hoje, $_SESSION['LOJA']);
                                    $quantidadeDePessoasEscaladas = 0;
                                    if (empty($quantidadePorDiaDeFuncionarios)) {
                                        $quantidadePorDiaDeFuncionariosImpressao = "Nenhum funcionario escalado para hoje";
                                    } else {
                                        $quantidadePorDiaDeFuncionariosImpressao = count($quantidadePorDiaDeFuncionarios);
                                        $quantidadeDePessoasEscaladas = $quantidadePorDiaDeFuncionariosImpressao;
                                    }

                                    ?>
                                    <input type="hidden" id="quantidadePorDiaDeFuncionariosImpressao" value="<?= $quantidadePorDiaDeFuncionariosImpressao ?>">
                                    <p id="quantidadePorDiaDeFuncionariosVisivel"><?= $quantidadePorDiaDeFuncionariosImpressao ?></p>
                                </div>
                            </div>
                            <div class="col-lg-10 DesabilitaClasseCasoEscalaNaoFinalizada CalculoDosOperadoresPorHorario">
                                <label class="form-label">
                                    Operadores por horário :
                                </label>
                                <div class="">
                                    <table id="tableHeader" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">

                                        <thead style="background: linear-gradient(to right, #00a451, #052846 85%); color:white;">
                                            <tr class="trr">
                                                <?php
                                                $pessoasPorHora = $calculoDeFuncionariosNecessariosPorHora->quantidadesDePessoasPorHoraCalculo($oracle, $quantidadeDePessoasEscaladas, $_SESSION['LOJA'], $hoje);
                                                foreach ($pessoasPorHora as $ROWpessoasPorHora) :
                                                ?>

                                                    <th class="text-center" colspan=""><?= $ROWpessoasPorHora['HORA'] ?></th>

                                                <?php
                                                endforeach;
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody style="background-color: #DCDCDC;">
                                            <tr class="trr">
                                                <?php
                                                $pessoasPorHora = $calculoDeFuncionariosNecessariosPorHora->quantidadesDePessoasPorHoraCalculo($oracle, $quantidadeDePessoasEscaladas, $_SESSION['LOJA'], $hoje);
                                                foreach ($pessoasPorHora as $ROWporcentagemDePessoasPorHora) :
                                                ?>

                                                    <td class="text-center" colspan=""><?= $ROWporcentagemDePessoasPorHora['QTD_FUNCIONARIOS'] ?></td>

                                                <?php
                                                endforeach;
                                                ?>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row DesabilitaClasseCasoEscalaNaoFinalizada">
            <div class="col-lg-12">
                <div class="card" style="border-color:#00a550;">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background: linear-gradient(to right, #00a451, #052846 85%); color:white;">

                        <i id="BTNAdicionarDescritivo" class="far fa-plus-square ocultar "> </i>
                        <i id="BTNremoverDescritivo" class="far fa-minus-square "> </i>


                        Escala de Operadores
                    </h6>
                    <div id="cardTable1" class="card-body ">
                        <div class="table dadosEscalaPDV table-responsive" style="overflow-x:auto;">
                            <table id="table1" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">

                                <thead style="background: linear-gradient(to right, #00a451, #052846 85%); color:white;">
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
                                        $horariosFuncManha = $InformacaoFuncionarios->filtroFuncionariosCadastradosManha($oracle, $hoje, $i, $_SESSION['LOJA']);
                                        $horariosFuncTarde = $InformacaoFuncionarios->filtroFuncionariosCadastradoTarde($oracle, $hoje, $i, $_SESSION['LOJA']);
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
                                                <!-- SE NÃO TIVER DADOS NO BANCO MOSTRA TODAS OPÇÕES DE FUNCIONARIOS -->
                                                <td scope="row" class="Matricula1"></td>
                                                <td scope="row" class="NomeFunc">
                                                    <select class="estilezaSelect form-control" id="selectFuncionario">
                                                        <option value=" "></option>
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
                                                    // print_r($horariosFuncManha);  
                                                ?>
                                                    <!-- RETORNO DE DADOS EXISTENTE NO BANCO MOSTRA APENAS O FUNCIONARIO SELECIONADO -->
                                                    <td scope="row" class="Matricula1"><?= $row2Manha['MATRICULA'] ?? '' ?></td>
                                                    <td scope="row" class="NomeFunc">
                                                        <select class="estilezaSelect form-control" id="selectFuncionario">
                                                            <option value="<?= $row2Manha['NOME'] ?>"><?= $row2Manha['NOME'] ?? '' ?></option>
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
                                                <!-- SE NÃO TIVER DADOS NO BANCO MOSTRA TODAS OPÇÕES DE FUNCIONARIOS -->
                                                <td scope="row" class="matricula2"></td>
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
                                                <td scope="row" class="btnExcluir"> <i class="fa-solid fa-trash fa-2xl"></i></td>
                                                <?php
                                            } else {
                                                foreach ($horariosFuncTarde as $row3Tarde) :
                                                    // print_r($horariosFuncTarde);                                                
                                                ?>
                                                    <!-- RETORNO DE DADOS EXISTENTE NO BANCO MOSTRA APENAS O FUNCIONARIO SELECIONADO -->
                                                    <td scope="row" class="matricula2"><?= $row3Tarde['MATRICULA'] ?? '' ?></td>
                                                    <td scope="row" class="text-center nome2">
                                                        <select class="estilizaSelect2 form-control">
                                                            <option value="<?= $row3Tarde['NOME'] ?>"><?= $row3Tarde['NOME'] ?? '' ?></option>
                                                        </select>
                                                    </td>
                                                    <td scope="row" class="horaEntrada2"><?= $row3Tarde['HORAENTRADA'] ?? '' ?></td>
                                                    <td scope="row" class="horaSaida2"><?= $row3Tarde['HORASAIDA'] ?? '' ?></td>
                                                    <td scope="row" class="horaIntervalo2"><?= $row3Tarde['HORAINTERVALO'] ?? '' ?></td>
                                                    <td scope="row" class="btnExcluir"><i class="fa-solid fa-trash fa-2xl"></i></td>
                                            <?php
                                                endforeach;
                                            } ?>
                                        </tr>
                                    <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row DesabilitaClasseCasoEscalaNaoFinalizada">
            <div class="col-lg-12">
                <div class="card" style="border-color:#00a550;">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background: linear-gradient(to right, #00a451, #052846 85%); color:white;">

                        <i id="BTNAdicionarDescritivo2" class="far fa-plus-square ocultar "> </i>
                        <i id="BTNremoverDescritivo2" class="far fa-minus-square "> </i>

                        ESCALA DE OPERADORES POR HORÁRIO
                    </h6>
                    <div id="relatorioPDV" class="card-body ">
                        <table id="table2" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">

                            <thead style="background: linear-gradient(to right, #00a451, #052846 85%); color:white;">
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
                                <td></td>
                                <?php
                                for ($i = 7; $i <= 21; $i++) {
                                ?>
                                    <td class="text-center recebeQuantPessoasPorPDV" scope="row" id="">

                                    </td>
                                <?php

                                }
                                ?>
                                <?php
                                $j = 0;
                                $qntPDV = array();
                                for ($i = 1; $i <= 30; $i++) {
                                    $i;
                                    $dadosEscalaDiariaDePDV = $InformacaoDosDias->escalaDiariaDePDV($oracle, $i, $hoje, $_SESSION['LOJA']);
                                    // print_r($dadosEscalaDiariaDePDV) ;
                                ?>
                                    <tr class="trr">
                                        <td scope="row" class="numerosPDVS" id="">
                                            <?= $i ?>
                                        </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["07:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["08:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["09:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["10:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["11:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["12:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["13:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["14:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["15:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["16:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["17:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["18:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["19:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["20:00"] ?? '' ?> </td>
                                        <td class="text-center" scope="row" id=""><?= $dadosEscalaDiariaDePDV[$j]["21:00"] ?? '' ?> </td>

                                    </tr>
                                <?php

                                }
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
    <script type="text/javascript" src="../base/DataTables/FixedColumns/js/dataTables.fixedColumns.min.js"></script>
    <script src="../base/dist/sidenav.js"></script>
    <script type="module" src="js/Script_escalaPDV.js" defer></script>
    <!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script> -->
</body>

</html>