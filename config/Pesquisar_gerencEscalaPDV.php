<?php
include "../../base/conexao_martdb.php";
include "../../base/conexao_TotvzOracle.php";
include "php/CRUD_geral.php";
session_start();

$dataSelecionadaNoFiltro = $_POST['mesPesquisa'];
// var_dump($dataSelecionadaNoFiltro);
$mesAtual = date("Y-m");
$loja = $_POST['loja'];
$CPFusuarioLogado = $_SESSION['cpf'];
$usuarioLogado = $_POST['usuarioLogado'];
$Departamento = $_POST['Departamento'];
$InformacaoDosDias = new Dias();
$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$dadosFunc = new Funcionarios();
$verificaSeAPessoaLogadaEEncarregada = $dadosFunc->informacaoPessoaLogada($TotvsOracle, $CPFusuarioLogado, $loja);

$verifica = new verifica();
// echo $dataSelecionadaNoFiltro;
// echo"<br>".$loja;
$verificaSeJaExistemDados = $verifica->verificaSeAEscalaMensalEstaFinalizada($oracle, $dataSelecionadaNoFiltro, $loja, $Departamento);
// echo $retorno;
if ($retorno === "NÃO FINALIZADA.") {
    $statusDaTabelaPosPesquisa = "NÃO FINALIZADA.";
} else if ($retorno === "JÁ FINALIZADA.") {
    $statusDaTabelaPosPesquisa = "JÁ FINALIZADA.";
}
?>
<input class="" type="hidden" id="loja" value="<?= $loja ?>">
<input class="" type="hidden" id="usuarioLogado12" value="<?= $usuarioLogado ?>">

<input class="dataSelecionadaNoFiltro" type="hidden" id="dataSelecionadaNoFiltro" value="<?= $dataSelecionadaNoFiltro ?>">
<input class="dataAtual" type="hidden" id="mesAtual" value="<?= $mesAtual ?>">
<?php
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja, $Departamento);
$dadosDeQuemEstaLogadoNome = '';
$dadosDeQuemEstaLogadoFuncao = '';
$dadosDeQuemEstaLogadoSetor = '';

foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['DEPARTAMENTO2'];
endforeach;
?>
<input class="" type="hidden" id="" value="<?= $dadosDeQuemEstaLogadoNome ?>">
<input class="" type="hidden" id="" value="<?= $dadosDeQuemEstaLogadoFuncao ?>">
<input class="" type="hidden" id="" value="<?= $dadosDeQuemEstaLogadoSetor ?>">

<table id="table1" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
    <thead style="background: linear-gradient(to right, #00a451, #052846 85%) !important; color:white;">

        <tr class="trr ">
            <th class="text-center theadColor" scope="row" style="width:150px">Funcionario</th>
            <th class="text-center theadColor">Cargo</th>
            <th class="text-center theadColor">Situação</th>
            <th class="text-center" style="display:none">departamento</th>
            <th class="text-center" style="display:none">Entrada</th>
            <th class="text-center" style="display:none">Saida</th>
            <th class="text-center" style="display:none">Intervalo</th>


            <th class="text-center theadColor" scope="row" style="width:150px ;display:none">matricula</th>

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
                <td class="text-center diaDaSemana" value="" scope="row"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>

            <?php
            endforeach
            ?>
        </tr>




        <?php
        foreach ($buscaNomeFuncionario as $nomeFunc) :
            $recuperacaoDedados2 = $verifica->verificaSeOMesSelecionadoTemAlgumFuncionarioEscalado($oracle, $dataSelecionadaNoFiltro, $loja, $Departamento);
            // ECHO $retorno1;
            if ($retorno1 == "NÃO EXISTE CADASTRO.") {
                $statusDaTabelaPosPesquisa = "NÃO FINALIZADA.";
            }
        ?>
            <tr class="trr">
                <td class="text-center funcionario" scope="row"><?= $nomeFunc['NOME'] ?></td>
                <td class="text-center cargo" scope="row"><?= $nomeFunc['FUNCAO'] ?></td>
                <td class="text-center situacao" scope="row"><?= $nomeFunc['SITUACAO'] ?></td>
                <td class="text-center departamento" scope="row" style="display:none"><?= $nomeFunc['DEPARTAMENTO'] ?></td>
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
                        $recuperacaoDedados = $recuperaDadosVerificacao->verificaCadastroNaEscalaMensa1($oracle,  $nomeFunc['MATRICULA'], $dataSelecionadaNoFiltro);
                        if ($i < 10) {
                            $d = "0" . $i;
                        } else {
                            $d = $i;
                        }
                        //    echo $retornoVerificacaoSeOFFoiInseridoNoMesAnterior;
                        $isF = ($recuperacaoDedados[0]["$d"] ?? '') === 'F';
                        if ($isF) {
                            $disabled = ' disabled  name="desabilitarEsteSelect"';
                        } else {
                            $disabled = '';
                        }
                        // echo $disabled;
                        $DadoDoDiaSalVoNoBancoDeDados = $recuperacaoDedados[0]["$d"] ?? '';
                        ?>

                        <select <?= $disabled ?> class="estilezaSelect" name="" id="">
                            <option value="<?= $DadoDoDiaSalVoNoBancoDeDados ?? '' ?>"> <?= $DadoDoDiaSalVoNoBancoDeDados ?? '' ?></option>
                            <!-- Se o dado -->
                            <option value="DSR" <?= $DadoDoDiaSalVoNoBancoDeDados == 'DSR' ? "style='display: none'" : "" ?>>DSR</option>
                            <option value="FA" <?= $DadoDoDiaSalVoNoBancoDeDados == 'FA' ? "style='display: none'" : "" ?>>FA</option>
                            <option value="FD" <?= $DadoDoDiaSalVoNoBancoDeDados == 'FD' ? "style='display: none'" : "" ?>>FD</option>
                            <option value="FF" <?= $DadoDoDiaSalVoNoBancoDeDados == 'FF' ? "style='display: none'" : "" ?>>FF</option>
                            <option value="T" <?= $DadoDoDiaSalVoNoBancoDeDados == '' ? "style='display: none'" : "" ?>></option>

                        </select>
                    </td>
                <?php
                    $i++;
                endforeach
                ?>
            </tr>
        <?php
        endforeach;

        ?>
    </tbody>


    <input class="statusDaTabela" style="display:none" id="statusDaTabelaPosPesquisa" value="<?= $statusDaTabelaPosPesquisa ?>">
    <?php
    // echo $statusDaTabelaPosPesquisa;
    ?>

</table>
<script type="module" defer>
    import {
        criandoHtmlmensagemCarregamento,
        Toasty
    } from "../../../../base/jsGeral.js";
    $('#table1').DataTable({
        dom: 'frtip',
        scrollY: 450,
        scrollX: true,
        searching: true,
        "paging": true,
        "info": true,
        "ordering": false,
        "lengthMenu": [
            [40],
        ],
        fixedColumns: {
            left: 4,
        },
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