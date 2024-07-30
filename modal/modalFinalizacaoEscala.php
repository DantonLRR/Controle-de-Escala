<?php
include "../../base/conexao_TotvzOracle.php";
include "../config/php/CRUD_geral.php";
include "../../base/conexao_martdb.php";

session_start();
$dadosFunc = new Funcionarios();
$Mensagem = $_POST['Mensagem'];
?>
<style>
    .dados-container {
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .dados-textarea {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 5px;
        resize: none; /* Remove o redimensionamento */
        height: 150px; /* Altura fixa do textarea */
    }
</style>
<div class="tabelaCancelamentoFerias">
    <div class="modal-content">
        <div style="background: linear-gradient(to right, #00a451, #052846 85%); color: white;font-weight:bold" class="modal-header">
            <h5 class="modal-title" id="modalFeriasLabel ">Erro ao finalizar</h5>
            <button style="color:white" type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    <div class="dados-container">
    <label for="validationCustom02" class="form-label">Funcionário:</label>
    <textarea disabled class="dados-textarea" name="mensagem" id="validationCustom02">
        <?=$Mensagem?>
    </textarea>
</div>
