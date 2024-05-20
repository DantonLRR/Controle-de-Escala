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

?>
<style>
    @page {
        footer: page-footer;
    }

    #page-footer {
        position: fixed;
        bottom: -50px;
        left: 0;
        right: 0;
        height: 50px;
        text-align: center;
    }

    .page-number:before {
        content: counter(page);
    }
</style>

<body>
<?php
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja, $Departamento);
foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
?>
	<span style="text-transform: capitalize !important;">
	<b>Escala Mensal setor
	 <?= ucfirst(strtolower($rowVerificaEncarregado['DEPARTAMENTO2'])) ?>
	  Referente ao mês de
	   <?= $mesAtualformatado ?>.
</b>
	</span>
	<br>
	<span style="text-transform: capitalize !important;">
		Expedido dia <?= $dataFormatada ?> por:
		<b>
			<?= ucfirst(strtolower($rowVerificaEncarregado['NOME'])) ?>,
			<?= ucfirst(strtolower($rowVerificaEncarregado['FUNCAO'])) ?> de
			<?= ucfirst(strtolower($rowVerificaEncarregado['DEPARTAMENTO2'])) ?>
		</b>
		Assinatura : __________________________
	</span>
	<hr>
	<?php
endforeach
?>
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
					<td style="border-bottom:1px solid black !important; border-right:1px solid black !important;"class="text-center funcionario" scope="row"><?= $nomeFunc['NOME'] ?></td>
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
						$recuperaAPrimeiraColunaComF = $verifica->verificaSeALinhaDoBancoTemFESETiverRetornaAPrimeiraColunaComF($oracle, $dataSelecionadaNoFiltro,  $loja, $nomeFunc['MATRICULA']);
						$verficaSeAInserçãoDeFFoiFeitaNoMesAnterior = $verifica->verificaSeALinhaFFoiInseridaNoMesAnterior($oracle, $dataSelecionadaNoFiltro,  $loja, $nomeFunc['MATRICULA']);
						// echo ($retornoVerificacaoSeOFFoiInseridoNoMesAnterior);

						$primeiroDiaNaoF = $recuperaAPrimeiraColunaComF['nome_coluna'] ?? $d;
						// echo "<br>" . $primeiroDiaNaoF;
						$primeiroDiaEncontrado = false;

						$isF = ($recuperacaoDedados[0][$d] ?? '') === 'F';


						if ($retornoVerificacaoSeOFFoiInseridoNoMesAnterior == 1) {
							// Se a inserção de 'FA' foi feita no mês anterior, desabilitar todos os 'FA'
							if ($isF) {
								$disabled = ' disabled  name="desabilitarEsteSelect"';
								// echo $disabled;
							} else {
								$disabled = '';
							}
						} else {
							// Desabilitar "FA" exceto pelo primeiro dia não FA encontrado
							if ($isF && !$primeiroDiaEncontrado && $d !== $primeiroDiaNaoF) {
								$disabled = ' disabled name="desabilitarEsteSelect"';
							} else {
								$disabled = '';
								if ($d === $primeiroDiaNaoF) {
									$primeiroDiaEncontrado = true;
								}
							}
						}
						?>
						<td style="border-bottom:1px solid black !important; border-right:1px solid black !important; text-align: center; !important"  scope="row" id=""> <?= $recuperacaoDedados[0][$d] ?? 'T' ?> </td>
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
<div id="page-footer">
    <span class="page-number"></span>
</div>
</body>