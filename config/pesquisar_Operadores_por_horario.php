<?php
include "../../base/Conexao_teste.php";
include "php/CRUD_geral.php";
$calculoDeFuncionariosNecessariosPorHora = new Porcentagem();
$InformacaoFuncionarios = new Funcionarios();

$dataPesquisa = $_POST['dataPesquisa'];
$loja = $_POST['loja'];

$partesData = explode('-', $dataPesquisa);
$diaDaPesquisaComAspas = ' "' . $partesData[2] . '"';
$mesEAnoDaPesquisa = $partesData[0] . '-' . $partesData[1];

$quantidadeDePessoasEscaladas = 0;
$quantidadePorDiaDeFuncionarios = $InformacaoFuncionarios->funcionariosDisponiveisNoDia($oracle, $diaDaPesquisaComAspas, $mesEAnoDaPesquisa, $dataPesquisa, $loja);

if (empty($quantidadePorDiaDeFuncionarios)) {
    $quantidadePorDiaDeFuncionariosImpressao = "Nenhum funcionario escalado para este dia,";
} else {
    $quantidadePorDiaDeFuncionariosImpressao = count($quantidadePorDiaDeFuncionarios);
    $quantidadeDePessoasEscaladas = $quantidadePorDiaDeFuncionariosImpressao;
}
if ($quantidadePorDiaDeFuncionariosImpressao == "Nenhum funcionario escalado para este dia,") {
} else {

?>
    <label class="form-label">
        Operadores por horário :
    </label>
    <table id="tableHeader" class="table table-bordered table-striped text-center row-border order-colum" style="width: 100%;">

        <thead style="background-color: #00a550; color: white;">
            <tr class="trr">
                <?php
                $pessoasPorHora = $calculoDeFuncionariosNecessariosPorHora->quantidadesDePessoasPorHoraCalculo($oracle, $quantidadeDePessoasEscaladas, $loja, $dataPesquisa);
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
                $pessoasPorHora = $calculoDeFuncionariosNecessariosPorHora->quantidadesDePessoasPorHoraCalculo($oracle, $quantidadeDePessoasEscaladas, $loja, $dataPesquisa);
                foreach ($pessoasPorHora as $ROWporcentagemDePessoasPorHora) :
                ?>

                    <td class="text-center" colspan=""><?= $ROWporcentagemDePessoasPorHora['QTD_FUNCIONARIOS'] ?></td>

                <?php
                endforeach;
                ?>

            </tr>
        </tbody>
    </table>
<?php
}
?>