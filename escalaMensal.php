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
$CPFusuarioLogado = $_SESSION['cpf'];
$dadosFunc = new Funcionarios();
$verificaSeAPessoaLogadaEEncarregada = $dadosFunc->informacaoPessoaLogada($TotvsOracle, $_SESSION['cpf'], $_SESSION['LOJA']);
$verifica = new verifica();
$dadosDeQuemEstaLogadoNome = '';
$dadosDeQuemEstaLogadoFuncao = '';
$dadosDeQuemEstaLogadoSetor = '';

foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['DEPARTAMENTO2'];
endforeach;

$verificaSeJaExistemDados = $verifica->verificaSeAEscalaMensalEstaFinalizada($oracle, $dataSelecionadaNoFiltro, $_SESSION['LOJA'],$dadosDeQuemEstaLogadoSetor);

if ($retorno === "NÃO FINALIZADA.") {
    $statusDaTabela = "NÃO FINALIZADA.";
} else if ($retorno === "JÁ FINALIZADA.") {
    $statusDaTabela = "JÁ FINALIZADA.";
}
?>

<body style="background-color:#DCDCDC; ">
    <div class="container-fluid">
        <input class="usu" type="hidden" id="usuarioLogado" value="<?= $_SESSION['nome'] ?>">
        <input class="loja" type="hidden" id="loja" value="<?= $_SESSION['LOJA'] ?>">
        <input class="dataAtual" type="hidden" id="mesAtual" value="<?= $mesAtual ?>">

        <input class="" type="hidden" id="" value="<?= $CPFusuarioLogado ?>">


        <div class="row">
            <div class="col-lg-12  ">
                <div class="card " style="border-color:#00a550;  ">
                    <h6 class="card-header text-center font-weight-bold text-uppercase " style="background: linear-gradient(to right, #00a451, #052846 85%); color:white;">Escala Mensal</h6>
                    <div class="card-body">
                        <input class="" type="hidden" id="dadosDeQuemEstaLogadoNome" value="<?= $dadosDeQuemEstaLogadoNome ?>">
                        <input class="" type="hidden" id="dadosDeQuemEstaLogadoFuncao" value="<?= $dadosDeQuemEstaLogadoFuncao ?>">
                        <input class="" type="hidden" id="dadosDeQuemEstaLogadoSetor" value="<?= $dadosDeQuemEstaLogadoSetor ?>">
                        <?php

                        if ($dadosDeQuemEstaLogadoFuncao === "ENCARREGADO") {
                            $buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $_SESSION['LOJA'], $dadosDeQuemEstaLogadoSetor); ?>

                            <div class="mb-4">
                                <label for="validationCustom02" class="form-label">Mês/Ano: </label>

                                <div class="col-lg-2">
                                    <input type="month" class="form-control dataPesquisa margin-bottom" value="<?= $mesAtual ?>" id="dataPesquisa">
                                </div>
                            </div>

                            <div class="atualizaTabela">
                                <table id="table1" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
                                    <thead style="background: linear-gradient(to right, #00a451, #052846 85%) !important; color:white;">

                                        <tr class="trr ">
                                            <th class="text-center theadColor" scope="row" style="width:150px">Funcionario</th>
                                            <th class="text-center theadColor">Cargo</th>
                                            <th class="text-center theadColor">Situação</th>
                                            <th class="text-center " style="display:none">Departamento</th>
                                            <th class="text-center " style="display:none">Entrada</th>
                                            <th class="text-center " style="display:none">Saida</th>
                                            <th class="text-center " style="display:none">Intervalo</th>
                                            <th class="text-center " scope="row" style="width:150px ;display:none">matricula</th>


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
                                            <td></td>
                                            <td></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <?php
                                            foreach ($buscandoMesAno as $row) :
                                            ?>
                                                <td class="text-center diaDaSemana" scope="row" style="font-weight:bold"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>

                                            <?php
                                            endforeach
                                            ?>
                                        </tr>




                                        <?php
                                        foreach ($buscaNomeFuncionario as $nomeFunc) :
                                            $recuperacaoDedados2 = $verifica->verificaSeOMesSelecionadoTemAlgumFuncionarioEscalado($oracle, $dataSelecionadaNoFiltro, $_SESSION['LOJA'], $dadosDeQuemEstaLogadoSetor);
                                            // ECHO $retorno1;
                                            if ($retorno1 == "NÃO EXISTE CADASTRO.") {
                                                $statusDaTabela = "NÃO FINALIZADA.";
                                            }

                                        ?>
                                            <tr class="trr">
                                                <td class="text-center funcionario" scope="row"><?= $nomeFunc['NOME'] ?></td>
                                                <td class="text-center cargo" scope="row"><?= $nomeFunc['FUNCAO'] ?></td>
                                                <td class="text-center situacao" scope="row"><?= $nomeFunc['SITUACAO'] ?></td>
                                                <td class="text-center departamento" style="display:none" scope="row"><?= $dadosDeQuemEstaLogadoSetor ?></td>
                                                <td class="text-center horarioEntradaFunc" style="display:none" scope="row"><?= $nomeFunc['HORAENTRADA'] ?></td>
                                                <td class="text-center horarioSaidaFunc" style="display:none" scope="row"><?= $nomeFunc['HORASAIDA'] ?></td>
                                                <td class="text-center horarioIntervaloFunc" style="display:none" scope="row"><?= $nomeFunc['SAIDAPARAALMOCO'] ?></td>
                                                <td class="text-center matriculaFunc" style="display:none" scope="row"><?= $nomeFunc['MATRICULA'] ?></td>


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
                                                        }
                                                        $recuperaAPrimeiraColunaComF = $verifica->verificaSeALinhaDoBancoTemFESETiverRetornaAPrimeiraColunaComF($oracle, $dataSelecionadaNoFiltro,  $_SESSION['LOJA'], $nomeFunc['MATRICULA']);
                                                        $verficaSeAInserçãoDeFFoiFeitaNoMesAnterior = $verifica->verificaSeALinhaFFoiInseridaNoMesAnterior($oracle, $dataSelecionadaNoFiltro,  $_SESSION['LOJA'], $nomeFunc['MATRICULA']);
                                                        //    echo $retornoVerificacaoSeOFFoiInseridoNoMesAnterior;



                                                        $primeiroDiaNaoF = $recuperaAPrimeiraColunaComF['nome_coluna'] ?? $d;
                                                        $primeiroDiaEncontrado = false;

                                                        $isF = ($recuperacaoDedados[0]["$d"] ?? '') === 'F';

                                                        // Desabilitar "FA" exceto pelo primeiro dia não FA encontrado
                                                        if ($retornoVerificacaoSeOFFoiInseridoNoMesAnterior == 1) {
                                                            // Se a inserção de 'F' foi feita no mês anterior, desabilitar todos os 'FA'
                                                            if ($isF) {
                                                                $disabled = ' disabled  name="desabilitarEsteSelect"';
                                                            } else {
                                                                $disabled = '';
                                                            }
                                                        } else {
                                                            if ($isF && !$primeiroDiaEncontrado && $d !== $primeiroDiaNaoF) {
                                                                $disabled = ' disabled  name="desabilitarEsteSelect"';
                                                            } else {
                                                                $disabled = '';
                                                                if ($d === $primeiroDiaNaoF) {
                                                                    $primeiroDiaEncontrado = true;
                                                                }
                                                            }
                                                        }
                                                        // echo $disabled;
                                                        $DadoDoDiaSalVoNoBancoDeDados = $recuperacaoDedados[0]["$d"] ?? '';
                                                        ?>
                                                                            
                                                        <select <?= $disabled ?> class="estilezaSelect" name="" id="">
                                                            <option value="<?= $DadoDoDiaSalVoNoBancoDeDados ?? '' ?>"> <?= $DadoDoDiaSalVoNoBancoDeDados ?? '' ?></option>
                                                            <!-- Se o dado -->
                                                            <option value="FA" <?= $DadoDoDiaSalVoNoBancoDeDados == 'FA' ? "style='display: none'" : "" ?> >FA</option>
                                                            <option value="FD"<?= $DadoDoDiaSalVoNoBancoDeDados == 'FD' ? "style='display: none'" : "" ?>  >FD</option>
                                                            <option value="FF"<?= $DadoDoDiaSalVoNoBancoDeDados == 'FF' ? "style='display: none'" : "" ?>  >FF</option>
                                                            <option value="F" <?= $DadoDoDiaSalVoNoBancoDeDados == 'F' ? "style='display: none'" : "" ?> >F</option>
                                                            <option value="T" <?= $DadoDoDiaSalVoNoBancoDeDados == '' ? "style='display: none'" : "" ?> ></option>
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

                        <?php
                        } else {
                        ?>
                            <p style="color:red">Acesso negado!</p>
                            <p>Apenas Encarregados podem realizar a escala mensal.</p>
                        <?php
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>

        <input class="statusDaTabela" type="hidden" id="statusDaTabela" value="<?= $statusDaTabela ?>">
        <?php
        // echo $statusDaTabela;
        ?>



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

    </div>
</body>

</html>