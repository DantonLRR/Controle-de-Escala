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
<style>
.table-container {
    width: 100%;
    height: 200px; /* Ajuste a altura conforme necessário */
    overflow-x: auto; /* Para barra de rolagem horizontal */
    overflow-y: auto; /* Para barra de rolagem vertical */
}

</style>
<div class="modal-content">

    <div style="background: linear-gradient(to right, #00a451, #052846 85%); color: white;font-weight:bold" class="modal-header">
        <h5 class="modal-title" id="modalFeriasLabel ">Acompanhamento de Ferias</h5>
        <button style="color:white" type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
<label for="validationCustom02" class="form-label">Ferias agendadas: </label>
<div class="table-container">
    <table id="tabelaCancelamentoDeFerias" class="tabelaCancelamentoDeFerias stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100% !important">
        <thead>
            <tr class="trr ">
                <th class="text-center">
                    Funcionario
                </th>
                <th class="text-center">
                    Data Inicial
                </th>
                <th class="text-center">
                    Data Final
                </th>
                <th class="text-center">
                    Excluir:
                </th>
                <th style="display:none" class="text-center">
                    Cargo
                </th>
                <th style="display:none" class="text-center">
                    matricula
                </th>
                <th class="text-center " style="display:none">
                    Entrada
                </th>
                <th class="text-center " style="display:none">
                    Saida
                </th>
                <th class="text-center " style="display:none">
                    Intervalo
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($buscaNomeFuncionario as $rowFuncionariosComFeriasProgramadas) :
            ?>
                <tr>
                    <td class="text-center funcionario nowrap" scope="row">
                        <?= $rowFuncionariosComFeriasProgramadas['NOME'] ?>
                    </td>
                    <td style="display:none" class="text-center cargo" scope="row">
                        <?= $rowFuncionariosComFeriasProgramadas['CARGO'] ?>
                    </td>
                    <td style="display:none" class="text-center matricula" scope="row">
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
        </tbody>
    </table>
</div>
