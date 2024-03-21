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
<input type="hidden" id="lojaDaPessoaLogada" value="<?= $loja ?>">
<div class="modal-content">
    <div style="background: linear-gradient(to right, #00a451, #052846 85%); color: white;font-weight:bold" class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel ">Cancelamento de Ferias</h5>
        <button style="color:white" type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
<div style="border:15px  solid transparent">
    <label for="validationCustom02" class="form-label">Agendamentos: </label>
    <table id="tabelaCancelamentoDeFerias" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
        <thead style="background: linear-gradient(to right, #00a451, #052846 85%) !important; color:white;">

            <tr class="trr ">
                <th class="text-center">
                    Funcionario
                </th>
                <th class="text-center">
                    Cargo
                </th>
                <th class="text-center">
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
                <th class="text-center">
                    Data Inicial
                </th>
                <th class="text-center">
                    Data Final
                </th>
                <th class="text-center">
                    Excluir:
                </th>
            </tr>

        </thead>
        <tbody>
            <?php
            foreach ($buscaNomeFuncionario as $rowFuncionariosComFeriasProgramadas) :
            ?>
                <tr>
                    <td class="text-center funcionario" scope="row">
                        <?= $rowFuncionariosComFeriasProgramadas['NOME'] ?>
                    </td>
                    <td class="text-center cargo" scope="row">
                        <?= $rowFuncionariosComFeriasProgramadas['CARGO'] ?>
                    </td>
                    <td class="text-center matricula" scope="row">
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
<input type="hidden" value="" id="cargo">
<input type="hidden" value="" id="horarioEntradaFunc">
<input type="hidden" value="" id="horarioSaidaFunc">
<input type="hidden" value="" id="horarioIntervaloFunc">
<input type="hidden" value="<?= $Departamento ?>" id="departamento">
<input type="hidden" value="<?= $usuarioLogado ?>" id="usuarioLogado">



<div class="modal-footer d-flex justify-content-between">
    <button id="AgendamentoFerias" style="background-color:#00a550; color:white; font-weight:bold" type="button" class="btn">
        Agendar férias
    </button>
</div>