<?php
include "../../base/conexao_martdb.php";
include "../config/php/CRUD_geral.php";
session_start();
$dadosFunc = new Funcionarios();
$loja = $_POST['loja'];
$Departamento = $_POST['Departamento'];
$buscaNomeFuncionario = $dadosFunc->recuperaFuncionariosQueTiveramFeriasAgendadas($oracle, $loja, $Departamento);
$usuarioLogado = $_SESSION['nome'];

?>
    <?php
    foreach ($buscaNomeFuncionario as $rowFuncionariosComFeriasProgramadas) :
    ?>
        <tr>
            <td class="text-center funcionario nowrap" scope="row">
                <?= $rowFuncionariosComFeriasProgramadas['NOME'] ?>
            </td>
            <td  style="display:none" class="text-center cargo" scope="row">
                <?= $rowFuncionariosComFeriasProgramadas['CARGO'] ?>
            </td>
            <td  style="display:none" class="text-center matricula" scope="row">
                <?= $rowFuncionariosComFeriasProgramadas['MATRICULA'] ?>
            </td>
            <td class="text-center horarioEntradaFunc" style="display:none" scope="row">
                <?= $rowFuncionariosComFeriasProgramadas['HORAENTRADA'] ?>
            </td>
            <td class="text-center horarioSaidaFunc" style="display:none" scope="row">
                <?= $rowFuncionariosComFeriasProgramadas['HORASAIDA'] ?>
            </td>
            <td class="text-center horarioIntervaloFunc" style="display:none" scope="row">
                <?= $rowFuncionariosComFeriasProgramadas['HORAINTERVALO'] ?>
            </td>
            <td class="text-center dataInicialFerias" scope="row">
                <?= $rowFuncionariosComFeriasProgramadas['DATAINICIOFERIASPROGRAMADAS'] ?>
            </td>
            <td class="text-center dataFinalFerias" scope="row">
                <?= $rowFuncionariosComFeriasProgramadas['DATAFIMFERIASPROGRAMADAS'] ?>
            </td>
            <td scope="row" class="btnExcluir">
                <i style="color:red; cursor:pointer" class="fa-solid fa-trash"></i>
            </td>
        </tr>
    <?php
    endforeach;
    ?>
