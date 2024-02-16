<?php
include "php/CRUD_geral.php";
include "../../base/conexao_TotvzOracle.php";
$loja = trim($_POST['lojaSelecionada']);
$dadosFunc = new Funcionarios();
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja);
?>
<div class="col-lg-12">
    <label class="form-label">
        Escolha um Funcionario..
    </label>
    <select required id="funcionario" class="form-control ">
        <?php
        foreach ($buscaNomeFuncionario as $nomeFunc) :
        ?>
            <option value="<?= $nomeFunc['MATRICULA'] ?>" class="FuncEscolhido"><?= $nomeFunc['NOME'] ?></option>
        <?php
        endforeach;
        ?>

    </select>
</div>
