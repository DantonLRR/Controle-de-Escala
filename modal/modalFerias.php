<?php
include "../../base/conexao_TotvzOracle.php";
include "../config/php/CRUD_geral.php";
include "../../base/conexao_martdb.php";

session_start();
$dadosFunc = new Funcionarios();
$loja = $_POST['loja'];
$Departamento = $_POST['Departamento'];
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja, $Departamento);
$usuarioLogado = $_SESSION['nome'];

?>
<style>

</style>
<input type="hidden" id="lojaDaPessoaLogada" value="<?= $loja ?>">
<div class="tabelaCancelamentoFerias">
    <div class="modal-content">
        <div style="background: linear-gradient(to right, #00a451, #052846 85%); color: white;font-weight:bold" class="modal-header">
            <h5 class="modal-title" id="modalFeriasLabel ">Agendamento de Ferias</h5>
            <button style="color:white" type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    <div style="border:15px  solid transparent">
        <label for="validationCustom02" class="form-label">Funcionário: </label>
        <select class="form-control margin-bottom" name="" id="funcionarioFerias">
            <option value="" disabled selected></option>
            <?php
            foreach ($buscaNomeFuncionario as $nomeFunc) : ?>

                <option value="<?= $nomeFunc['MATRICULA'] ?>"><?= $nomeFunc['NOME'] ?></option>

            <?php endforeach;
            ?>
        </select>
    </div>
    <input type="hidden" value="" id="cargo">
    <input type="hidden" value="" id="horarioEntradaFunc">
    <input type="hidden" value="" id="horarioSaidaFunc">
    <input type="hidden" value="" id="horarioIntervaloFunc">
    <input type="hidden" value="<?= $Departamento ?>" id="departamento">
    <input type="hidden" value="<?= $usuarioLogado ?>" id="usuarioLogado">
    <div style="border:15px  solid transparent" class="row">
        <div class="col-lg-6">
            <div class="mb-6">
                <label for="validationCustom02" class="form-label">Data inicial: </label>
                <input type="date" class="form-control dataPesquisa margin-bottom" value="" id="dataInicialFerias">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-6">
                <label for="validationCustom02" class="form-label">Data Final: </label>
                <input type="date" class="form-control dataPesquisa margin-bottom" value="" id="dataFinalFerias">
            </div>
        </div>
    </div>
</div>
