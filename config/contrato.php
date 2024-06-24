<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Impressão Escala Mensal</title>
	<link rel="stylesheet" href="css/contrato.css">
</head>
<?php
include "../../base/conexao_martdb.php";
include "../../base/conexao_TotvzOracle.php";
include "php/CRUD_geral.php";
session_start();

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
$dataAtual = new DateTime();
$dataFormatada = utf8_encode(strftime('%d de %B de %Y', $dataAtual->getTimestamp()));




$dataSelecionadaNoFiltro = $_POST['mesPesquisa'];
// var_dump($dataSelecionadaNoFiltro);
$mesAtual = date("Y-m");
$mesAtualformatado = date("m/Y");
$loja = $_POST['loja'];
$CPFusuarioLogado = $_SESSION['cpf'];
$usuarioLogado = $_POST['usuarioLogado'];
$Departamento = $_POST['Departamento'];
$InformacaoDosDias = new Dias();
$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$dadosFunc = new Funcionarios();
$verificaSeAPessoaLogadaEEncarregada = $dadosFunc->informacaoPessoaLogada($TotvsOracle, $CPFusuarioLogado, $loja);

$verifica = new verifica();

$verificaSeJaExistemDados = $verifica->verificaSeAEscalaMensalEstaFinalizada($oracle, $dataSelecionadaNoFiltro, $loja, $Departamento);

$dataFormatadaMesReferencia = $dataAtual->format('d/m/Y');
?>
<style>
	td {
		font-size: 10px !important;
		/* Ou qualquer tamanho que você preferir */
		font-family: Arial, sans-serif !important;
	}

	@page {
		footer: page-footer;
		margin-left: 5mm;
		/* Define a margem esquerda para 10mm */
		margin-right: 5mm;
		/* Define a margem direita para 15mm */
		margin-top: 5mm;
		/* Define a margem superior para 20mm */
		margin-bottom: 5mm;
		/* Define a margem inferior para 20mm */
	}
	#page-footer {
		position: fixed;
		bottom: -38px;
		left: 0;
		right: 34px;
		height: 50px;
		text-align: center;
	}

	.page-number:before {
		content: counter(page);
	}

	body {
		font-family: Arial, sans-serif;
		font-size: 12px;
	}
</style>



<body>
	<?php
	$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja, $Departamento);
	?>
	<div id="page-footer">
		<span class="page-number1">
			<?php
			foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
			?>
				<b>Escala Referente ao dia: <?= $dataFormatadaMesReferencia ?>.
				</b>
				Expedido dia <?= $dataFormatada ?> por:
				<b>
					<?php
					foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
					?>
						<?= ucfirst(strtolower($rowVerificaEncarregado['NOME'])) ?>,
						<?= ucfirst(strtolower($rowVerificaEncarregado['FUNCAO'])) ?> de
						<?= ucfirst(strtolower($rowVerificaEncarregado['DEPARTAMENTO2'])) ?>
					<?php
					endforeach
					?>
				</b>
				Assinatura :__________________________
			<?php
			endforeach;
			?>
		</span>
	</div>
	<table id="table1" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
		<thead>
			<tr class="trr">
				<th class="text-center" scope="row">Funcionario</th>

				<?php
				foreach ($buscandoMesAno as $row) :
				?>
					<th style="border-bottom:1px solid black !important; border-right:1px solid black !important;" class="text-center numeroDiaDaSemana" scope="row"><?= $row['DIA'] ?></th>
				<?php
				endforeach
				?>
				<th></th>
			</tr>
		</thead>
		<tbody style="font-size:10px">
			<tr class="trr" id="quantDias">
				<td style="border-bottom:1px solid black !important;"></td>

				<?php
				foreach ($buscandoMesAno as $row) :
				?>
					<td style="border-bottom:1px solid black !important; border-right:1px solid black !important;" class="text-center diaDaSemana" value="" scope="row"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>

				<?php
				endforeach
				?>
				<th>Assinatura dos Funcionarios</th>
			</tr>
			<?php
			foreach ($buscaNomeFuncionario as $nomeFunc) :
				$recuperacaoDedados2 = $verifica->verificaSeOMesSelecionadoTemAlgumFuncionarioEscalado($oracle, $dataSelecionadaNoFiltro, $loja, $Departamento);
				if ($retorno1 == "NÃO EXISTE CADASTRO.") {
					$statusDaTabelaPosPesquisa = "NÃO FINALIZADA.";
				}
			?>
				<tr class="trr">
					<td style="border-bottom:1px solid black !important; border-right:1px solid black !important;" class="text-center funcionario" scope="row"><?= $nomeFunc['NOME'] ?></td>
					<?php
					$i = 1;
					foreach ($buscandoMesAno as $row) :
					?>
						<?php
						$recuperaDadosVerificacao = new verifica();
						$recuperacaoDedados = $recuperaDadosVerificacao->verificaCadastroNaEscalaMensa1($oracle,  $nomeFunc['MATRICULA'], $dataSelecionadaNoFiltro);
						if ($i < 10) {
							$d = 0 . $i;
						} else {
							$d = $i;
						}
						// echo ($retornoVerificacaoSeOFFoiInseridoNoMesAnterior);
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
						<td style="border-bottom:1px solid black !important; border-right:1px solid black !important; text-align: center; !important" scope="row" id=""> <?= $recuperacaoDedados[0][$d] ?? 'T' ?> </td>
					<?php
						$i++;
					endforeach
					?>
					<td>_______________________________</td>
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
</body>