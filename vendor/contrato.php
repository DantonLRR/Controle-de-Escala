<?php

include "../base/conexao_martdb.php";
include "Config/contratosCrud.php";

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
$dataAtual = new DateTime();
$dataFormatada = strftime('%d de %B de %Y', $dataAtual->getTimestamp());

$buscar = new Buscar();

if (isset($_POST['contratoId'])) {
	$id = isset($_POST['contratoId']) ? $_POST['contratoId'] : "";
	
	$dadosContrato = $buscar->dadosContratos($oracle, $id);
	
	$comprador = $dadosContrato[0]['COMPRADOR'];
	$nomeFornecedor = $dadosContrato[0]['NOMERAZAO'];
	$cnpjFornecedor = $dadosContrato[0]['CNPJ'];
	$prazo = $dadosContrato[0]['PRAZO'];
	$frete = $dadosContrato[0]['FRETE'];
	$promotor = $dadosContrato[0]['PROMOTOR'];
	$trocas = $dadosContrato[0]['TROCAS'];
	$devolucoes = $dadosContrato[0]['DEVOLUCAO'];
	$acordos = $dadosContrato[0]['ACORDO'];
	$itens = $dadosContrato[0]['VERBA_ITENS'];
	$inauguracao = $dadosContrato[0]['VERBA_INAUGURACAO'];
	$aniversario = $dadosContrato[0]['VERBA_ANIVERSARIO'];
	$anuncio = $dadosContrato[0]['VERBA_ANUNCIO'];
	$check = $dadosContrato[0]['VERBA_CHECKSTAND'];
	$porcentagem = $dadosContrato[0]['PORCENTAGEM'];
	$faturamento = $dadosContrato[0]['FATURAMENTO'];
	$compra = $dadosContrato[0]['PERIODO_APURACAO'];
	$icms = $dadosContrato[0]['ICMS'];
	$icmsSt = $dadosContrato[0]['ICMSST'];
	$ipi = $dadosContrato[0]['IPI'];
	$pis = $dadosContrato[0]['PIS'];
	$cofins = $dadosContrato[0]['COFINS'];
	$icmsFcp = $dadosContrato[0]['ICMSFCP'];
	$icmsStFcp = $dadosContrato[0]['ICMSSTFCP'];
	$observacoes = $dadosContrato[0]['OBSERVACAO'];
	$excecoes = $buscar->excecoes($oracle, $id, false);
	$porcentagemPorExtenso = $dadosContrato[0]['PORC_EXTENSO'];
} else {
	$id = isset($_POST['idContrato']) ? $_POST['idContrato'] : "";
	$fornecedor = isset($_POST['fornecedor']) ? $_POST['fornecedor'] : "";
	
	$dadosFornecedor = $buscar->dadosFornecedor($oracle, $fornecedor);
	
	$comprador = isset($_POST['comprador']) ? $_POST['comprador'] : "";
	$nomeFornecedor = $dadosFornecedor[0]['NOMERAZAO'];
	$cnpjFornecedor = $dadosFornecedor[0]['CNPJ'];
	$prazo = isset($_POST['prazo']) ? $_POST['prazo'] : "";
	$frete = isset($_POST['frete']) ? $_POST['frete'] : "";
	$promotor = isset($_POST['promotor']) ? $_POST['promotor'] : "";
	$trocas = isset($_POST['trocas']) ? $_POST['trocas'] : "";
	$devolucoes = isset($_POST['devolucoes']) ? $_POST['devolucoes'] : "";
	$acordos = isset($_POST['acordos']) ? $_POST['acordos'] : "";
	$itens = isset($_POST['itens']) ? $_POST['itens'] : "";
	$inauguracao = isset($_POST['inauguracao']) ? $_POST['inauguracao'] : "";
	$aniversario = isset($_POST['aniversario']) ? $_POST['aniversario'] : "";
	$anuncio = isset($_POST['anuncio']) ? $_POST['anuncio'] : "";
	$check = isset($_POST['check']) ? $_POST['check'] : "";
	$porcentagem = isset($_POST['porcentagem']) ? $_POST['porcentagem'] : "";
	$faturamento = isset($_POST['faturamento']) ? $_POST['faturamento'] : "";
	$compra = isset($_POST['compra']) ? $_POST['compra'] : "";
	$icms = isset($_POST['icms']) ? $_POST['icms'] : "";
	$icmsSt = isset($_POST['icmsSt']) ? $_POST['icmsSt'] : "";
	$ipi = isset($_POST['ipi']) ? $_POST['ipi'] : "";
	$pis = isset($_POST['pis']) ? $_POST['pis'] : "";
	$cofins = isset($_POST['cofins']) ? $_POST['cofins'] : "";
	$icmsFcp = isset($_POST['icmsFcp']) ? $_POST['icmsFcp'] : "";
	$icmsStFcp = isset($_POST['icmsStFcp']) ? $_POST['icmsStFcp'] : "";
	$observacoes = isset($_POST['observacoes']) ? $_POST['observacoes'] : "";
	$excecoes = $buscar->excecoes($oracle, $id, false);
	$porcentagemPorExtenso = retornaNumeroPorExtenso($porcentagem);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contrato Comercial</title>
	<link rel="stylesheet" href="css/contrato.css">
</head>

<body>
	<div class="page">
		<div><img src="img/contratoCabecalho.jpg" alt=""></div>
		<div class="alinhamento">
			<b>TERMO DE PARCERIA COMERCIAL</b><br>
			<span> <?= $dataAtual->format(format: "d/m/Y"); ?> </span>
		</div>
		<div class="camposDePreenchimento alinhamento">
			<b>CLIENTE:</b> MART MINAS DISTRIBUIÇÃO LTDA – Avenida Barão Homem de Melo, n°3090, Bairro Estoril,<br> Belo
			Horizonte/MG, CEP 30494-080&nbsp;&nbsp; CNPJ: 04.737.552/0003-08
		</div>
		<div class="camposDePreenchimento">
			<b>FORNECEDORA:</b> <?php echo $nomeFornecedor ?> <br>
			CNPJ: <?php echo $cnpjFornecedor ?> <br>
			OBS: A qualificação obriga CNPJ principal e eventuais filiais a ele vinculadas.
		</div>
		<div style="margin: 12px; text-align: justify;">
			As partes acima convencionam o presente Termo de Parceria Comercial, com intuito de um maior fortalecimento
			de suas relações comerciais que se regerão pelas condições abaixo:
		</div>
		<div class="camposDePreenchimento">
			<b>1. CONDIÇÕES COMERCIAIS:</b>
			<table>
				<tr>
					<td id="col1">
						<div class="condicoes">
							<b>1.1 - PRAZO</b><br>
							<span>A) <?php echo $prazo ?> dias a contar da entrega da mercadoria. <br>
								Vide Notas Explicativas Anexas.</span>
						</div>
						<div class="condicoes">
							<b>1.2 - FRETE</b><br>
							<span>A) (<?php echo ($frete === 'CIF') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) CIF</span><br>
							<span>B) (<?php echo ($frete === 'FOB') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) FOB</span>
						</div>
						<div class="condicoes">
							<b>1.3 - PROMOTOR</b><br>
							<span>A) (<?php echo ($promotor === 'Fixo') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Fixo</span><br>
							<span>B) (<?php echo ($promotor === 'Rotativo') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Rotativo</span><br>
							<span>C) (<?php echo ($promotor === 'Sem Promotor') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Sem Promotor</span>
						</div>
					</td>
					<td>
						<div>
							<div class="condicoes">
								<b>1.4 - TROCAS/INDENIZAÇÕES/PERDAS</b><br>
								<span>A) (<?php echo ($trocas === 'Total, produto por produto') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Total, produto por produto</span><br>
								<span>B) (<?php echo ($trocas === 'Total, mediante NF de devolução') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Total, mediante NF de devolução</span><br>
								<span>C) (<?php echo ($trocas === 'Total, via Indenização com verba') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Total, via Indenização com verba</span>
							</div>
							<div class="condicoes">
								<b>1.5 - DEVOLUÇÕES</b><br>
								<b>Forma de Quitação:</b><br>
								<span>A) (<?php echo ($devolucoes === 'Depósito / Bonificação') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Depósito/Bonificação</span><br>
								<span>B) (<?php echo ($devolucoes === 'Desconto no Boleto') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Desconto no Boleto</span>
							</div>
							<div class="condicoes">
								<b>1.6 - ACORDOS</b><br>
								<span>A) (<?php echo ($acordos === 'Depósito / Bonificação') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Depósito/Bonificação</span><br>
								<span>B) (<?php echo ($acordos === 'Desconto no Boleto') ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) Desconto no Boleto</span>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<div class="alinhamento">
				<b>OBS.: Todos os pagamentos à fornecedores serão realizados EXCLUSIVAMENTE via depósito bancário.</b>
			</div>
		</div>
		<div class="camposDePreenchimento">
			<b>2. AÇÕES</b>
			<div>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1 VERBA DE INTRODUÇÃO NOVOS ITENS: Negociação com valor........................................</b> <i><?php echo $itens ?></i><br>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.2 VERBA DE INAUGURAÇÃO E REINAUGURAÇÃO DE LOJAS: Negociação com valor...</b> <i><?php echo $inauguracao ?></i><br>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.3 VERBA DE ANIVERSÁRIO: Negociação com valor...................................................................</b> <i><?php echo $aniversario ?></i><br>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.4 VERBA DE ANÚNCIO/JORNAL/TABLÓIDE/ENCARTE: Negociação com valor................</b> <i><?php echo $anuncio ?></i><br>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.5 VERBA DE CHECK STAND: Negociação com percentual.........................................................</b> <i><?php echo $check ?></i>
			</div>
		</div>
		<div class="camposDePreenchimento">
			<b>3. ADENDO AO TERMO DE ACORDO</b>
			<div>
				<span>Para confirmação das negociações dos itens 2 (ações) deverá ser emitido pela Cliente e assinado por ambas as partes.</span>
			</div>
		</div>
		<div class="camposDePreenchimento">
			<b>4. INVESTIMENTOS</b>
			<div style="text-align: justify;">
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.1</b> - Acordo para incremento dos negócios em <?php echo $porcentagem ?> % (<?php echo $porcentagemPorExtenso ?> por cento) em verba sobre o faturamento <?php echo $faturamento ?> (Valor Total da Nota Fiscal) da Fornecedora apurado nas entregas dos produtos nas unidades da Cliente.<br><br>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.2 - PERÍODO PARA APURAÇÃO:</b><br>
				<span style="margin-left: 30px;">A (<?php echo ($compra) ? '&nbsp;&nbsp;X&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>) A cada compra faturada.</span><br><br>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.3 - PAGAMENTO DO TERMO DE ACORDO: Deverá ser efetuado via desconto no pagamento correspondente à própria Nota Fiscal de entrada da mercadoria.</b><br><br>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.4</b> - Prazo de Validade do Acordo de Parceria Comercial:
				<b>INDETERMINADO</b>
			</div>
		</div>
		<div class="camposDePreenchimento">
			<b>5. OBSERVAÇÕES:</b>
			<div style="text-align: justify;">
				<?php echo $observacoes ?>
			</div>
		</div>
	</div>

	<div class="page">
		<div><img src="img/contratoCabecalho.jpg" alt=""></div>
		<div class="camposDePreenchimento">
			<b>EXCEÇÕES:</b>
			<div style="text-align: justify;">
				<?php
				if (count($excecoes) > 0) {
				?>
					<table>
						<thead>
							<tr>
								<th style="text-align: left">Família(s)</th>
								<th style="text-align: left">Porcentagem</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($excecoes as $excecao) :
							?>
								<tr>
									<td><?php echo $excecao["DESCRICAO"]; ?></td>
									<td><?php echo $excecao["PORCENTAGEM"]; ?> %</td>
								</tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
				<?php
				} else {
				?>
					<br><span>Não existem exceções para esse contrato.</span>
				<?php
				}
				?>
			</div>
		</div>
	</div>

	<div class="page">
		<div><img src="img/contratoCabecalho.jpg" alt=""></div>
		<div class="camposDePreenchimento">
			<b>6. QUANTO A CONFIDENCIALIDADE:</b>
			<div style="text-align: justify;">
				<br>Entende-se por “Informações Confidenciais” quaisquer informações financeiras, comerciais,
				operacionais e
				a estratégia de negócios, seja das PARTES, seus respectivos clientes, empregados, subcontratados e
				sociedades coligadas, afiliadas e/ou associadas, que não são disponíveis ao público em geral e que
				venham a
				ser reveladas ou disponibilizadas por força do presente Termo de Compromisso, seja oralmente, por meio
				de
				documentos ou outra forma física <br><br>
				<b>Parágrafo Primeiro</b>: Não serão consideradas Informações Confidenciais para fins deste instrumento,
				as
				informações que:<br><br>
				(i) sejam ou venham a se tornar disponíveis ao público em geral sem violação deste Instrumento por
				qualquer
				das PARTES;<br><br>
				(ii) já eram de conhecimento da PARTE e que as houver recebido, anteriormente à sua divulgação pela
				outra
				PARTE;<br><br>
				(iii) tenham sido recebidas por uma das PARTES licitamente de terceiros que, no melhor conhecimento de
				tal
				PARTE, não obtiveram ou revelaram tais informações por meio de ato ilícito ou descumprimento de
				obrigação
				contratual; e/ou<br><br>
				(iv) tenham sido desenvolvidas independentemente por qualquer das PARTES anteriormente ao seu acesso às
				Informações Confidenciais.<br>
				<b>Parágrafo Segundo</b>: Por este instrumento cada uma das PARTES, por si, seus funcionários,
				acionistas,
				sócios, assessores ou representantes, obriga-se a não revelar, por qualquer forma, quaisquer Informações
				Confidenciais pertinentes à outra PARTE ou a qualquer pessoa, sem o prévio e expresso consentimento da
				outra
				PARTE. <br>
				<b>Parágrafo Terceiro</b>: Cada uma das PARTES obriga-se a somente fazer uso de qualquer Informação
				Confidencial para execução do objeto do presente contrato, sendo certo que a disponibilização de
				Informações
				Confidenciais por uma PARTE à outra não implicará a cessão ou constituição de quaisquer direitos sobre
				as
				Informações Confidenciais em benefício da PARTE que as houver recebido. <br>
				<b>Parágrafo Quarto</b>: As Informações Confidenciais recebidas por uma PARTE poderão ser transmitidas a
				pessoas a ela vinculadas, inclusive seus administradores, advogados, auditores, consultores e empregados
				que
				necessitem ter conhecimento de tais Informações Confidenciais para os fins aqui previstos. Tais pessoas
				vinculadas deverão ser previamente informadas acerca da natureza confidencial das Informações
				Confidenciais
				e das restrições quanto ao seu uso e divulgação.<br>
				<b>Parágrafo Quinto</b>: Cada PARTE responsabiliza-se pela utilização de Informações Confidenciais por
				seus
				administradores, advogados, auditores, consultores e empregados, no que se refere à manutenção da
				confidencialidade ora estabelecida.<br>
				<b>Parágrafo Sexto</b>: Caso uma das PARTES venha a ser obrigada a revelar Informações Confidenciais
				recebidas da outra Parte em atendimento à legislação vigente ou à ordem judicial ou administrativa, tal
				PARTE enviará, prontamente, à outra PARTE, comunicação por escrito de forma a permitir que esta tome as
				providências que entender apropriadas visando à proteção de tais Informações Confidenciais. Tal
				comunicação
				não deverá de qualquer forma ser interpretada como uma vedação à que a PARTE compelida a divulgar
				Informações Confidenciais o faça, observado que esta deverá revelar tão somente as Informações
				Confidenciais
				(ou parte delas) que forem legalmente exigíveis.<br>
				<b>Parágrafo Sétimo</b>: A obrigação de confidencialidade ora pactuada permanecerá em vigor durante a
				vigência do presente Instrumento e pelo prazo de 60 (sessenta) meses após seu término.
			</div>
		</div>
		<div class="camposDePreenchimento">
			<b>7. QUANTO O COMBATE A CORRUPÇÃO E SANÇÕES:</b>
			<div style="text-align: justify;">
				<br>As partes garantem e se obrigam reciprocamente a cumprir todas as leis, regulamentos, normas,
				decretos e/ou ordens aplicáveis e os requisitos governamentais oficiais das Nações Unidas, da Convenção
				da OECD Sobre Combate à Corrupção de Funcionários Públicos Estrangeiros em Transações Comerciais
				Internacionais, da Foreign Corrupt Practices Act of the United States (“FCPA”), além da Lei 12.846/13 –
				Lei Brasileira Anticorrupção ou de qualquer outra jurisdição pertinente relacionada ao combate à
				corrupção no setor público, no setor privado ou à lavagem de dinheiro. <br>
				<b>Parágrafo Primeiro</b>: Cada parte declara, garante e aceita que não pagará, oferecerá, dará,
				solicitará nem prometerá pagar ou autorizará o pagamento de dinheiro, de outros itens de valor ou a
				concessão de benefícios ou vantagens pessoais direta ou indiretamente, a: <br>
				(i) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;um funcionário público nacional ou estrangeiro
				ou a terceira pessoa a ele relacionada; <br>
				(ii) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;um diretor, funcionário, empregado ou
				agente/representante de uma contraparte, fornecedor ou
				cliente atual ou potencial de qualquer das partes; ou <br>
				(iii) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a qualquer outra pessoa, indivíduo ou entidade por
				sugestão, solicitação ou indicação ou em benefício de qualquer das pessoas ou entidades descritas
				anteriormente, nem realizará outros atos ou transações se constituam uma violação ou estejam em
				desacordo com a legislação contra corrupção ou a lavagem de dinheiro em qualquer governo e com a
				legislação aplicável no país que implementa o Convênio da OCDE de combate ao
				suborno de funcionários públicos estrangeiros em transações comerciais internacionais.
			</div>
		</div>
	</div>

	<div class="page" id="page3">
		<div><img src="img/contratoCabecalho.jpg" alt=""></div>
		<div style="text-align: justify;" class="camposDePreenchimento">
			<b>Parágrafo Segundo</b>: Cada parte poderá rescindir o Contrato, imediatamente e a qualquer tempo, mediante
			notificação por escrito para a outra parte se, de acordo com seu julgamento razoável, a outra parte
			descumprir qualquer das declarações, garantias ou promessas contidas na presente cláusula. <br>
			<b>Parágrafo Terceiro</b>: Se a qualquer momento durante a execução do presente Contrato qualquer uma das
			partes tiver conhecimento de que a outra parte descumpriu a garantia supracitada, a parte cumpridora deverá
			observar as leis e normativas de qualquer governo a que esteja sujeita tal parte e seguir qualquer ordem ou
			instrução que possa ser transmitida por qualquer órgão regulador ou administrativo com poderes para obrigar
			o cumprimento. Na ausência de tais ordens, instruções, leis ou normativas, a parte cumpridora poderá
			rescindir o presente Contrato imediatamente. <br>
			<b>Parágrafo Quarto</b>:Independentemente de qualquer disposição em contrário contida nesta cláusula, as
			partes não estão obrigadas a fazer nada que constitua uma violação das leis e normativas de qualquer Estado
			a que esteja sujeita qualquer uma das partes. <br>
			<b>Parágrafo Quinto</b>:A parte em descumprimento será responsável por indenizar a outra parte contra
			qualquer reclamação, incluindo a devolução de qualquer pagamento, perda, dano, custo ou multa que sofra a
			outra parte como consequência de um descumprimento da garantia tal como foi mencionada na presente cláusula.
		</div>
		<div class="camposDePreenchimento">
			<b>8. QUANTO A PROTEÇÃO DE DADOS PESSOAIS:</b>
			<div style="text-align: justify;">
				8.1. - <b>Proteção dos Dados Pessoais.</b> As partes, por si e por seus colaboradores, obrigam-se,
				sempre que aplicável, a atuar no presente Contrato em conformidade com a Legislação vigente sobre
				proteção
				de dados relativos a uma pessoa física (“Titular”) identificada ou identificável (“Dados Pessoais”) e as
				determinações de órgãos reguladores/fiscalizadores sobre a matéria, em especial a Lei 13.709/2018 (“Lei
				Geral de Proteção de Dados”), além das demais normas e políticas de proteção de dados de cada país onde
				houver qualquer tipo de tratamento de dados pessoais em decorrência do presente Contrato, incluindo mas
				não
				se limitando aos dados pessoais de colaboradores e clientes das partes. <br>
				8.2. - <b>Confidencialidade dos Dados Pessoais.</b> As Partes, incluindo seus funcionários, procuradores
				e
				contratados, comprometem-se a tratar todos os Dados Pessoais a que eventualmente tiverem acesso por
				força
				deste Contrato como confidenciais, ainda que este Contrato venha a ser resolvido e independentemente dos
				motivos que derem causa ao seu término ou resolução. <br>
				8.3. - <b>Conformidade das Partes.</b> Cada Parte deverá monitorar, por meios adequados, sua própria
				conformidade, a de seus funcionários e de seus contratados com os controles de Segurança da Informação e
				com
				as respectivas obrigações de proteção dos Dados Pessoais que porventura sejam tratados no âmbito deste
				Contrato. <br>
				8.4. - <b>Propriedade dos Dados.</b> O presente Contrato não transfere a propriedade ou controle dos
				dados
				de uma das partes, dos clientes, fornecedores e funcionários desta, inclusive dados pessoais, à outra
				parte.
				Os dados gerados, obtidos ou coletados em decorrência do presente contrato são e continuarão sendo de
				propriedade da parte que detém a sua propriedade, inclusive sobre qualquer novo elemento de dados,
				produto
				ou subproduto que seja criado a partir do tratamento de dados decorrente deste Contrato. <br>
				8.5. - <b>Adequação legislativa.</b> As Partes se comprometem, desde já, a cumprir eventuais alterações
				de
				qualquer legislação nacional ou internacional que interfiram no tratamento dos Dados Pessoais aplicável
				ao
				presente Contrato.
			</div>
		</div>
		<div class="alinhamento" style="margin-top: 320px;">
			<b>Continuação do TERMO DE PARCERIA COMERCIAL<br> 05 de julho de 2018</b>
		</div>
	</div>

	<div class="page">
		<div><img src="img/contratoCabecalho.jpg" alt=""></div>
		<b>NOTAS EXPLICATIVAS PARA OS ITENS 1.1, 1.3, 1.5 e 1.6 da página 1 de 3.</b>
		<div style="text-align: justify;">
			<br>1.1 – Os prazos de pagamento dos valores representados pelas Notas Fiscais são unificados, a contar
			sempre da data de entrega da
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mercadoria, sendo realizados obrigatoriamente em datas fixas de acordo
			com o vencimento inicial da seguinte forma: <br><br>

			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) Notas com vencimento entre os dias 02 e 06 do mês: Pagamento no dia
			05.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) Notas com vencimento entre os dias 07 e 11: Pagamento no dia 10.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c) Notas com vencimento entre os dias 12 e 16: Pagamento no dia 15.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d) Notas com vencimento entre os dias 17 e 21: Pagamento no dia 20.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e) Notas com vencimento entre os dias 22 e 26: Pagamento no dia 25.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;f) Notas com vencimento entre os dias 27 e 01: Pagamento no dia 30, à
			exceção de fevereiro quando pagamento deste cluster
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;será efetuado no último dia do mês.<br><br>

			1.3 – A presença do promotor de vendas nos moldes previstos neste Instrumento é obrigatória e de inteira
			responsabilidade do
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;fornecedor. A comprovação de presença do promotor será realizada por
			meio de procedimento próprio, declarando neste ato o
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;fornecedor ter pleno conhecimento das regras da Mart Minas, se obrigando
			a cumpri-las integralmente. Da mesma forma, a
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;confirmação de presença por meio do procedimento acima citado é de
			responsabilidade do próprio promotor de vendas. Ressalte-se
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;que a indústria e os próprios promotores são responsáveis por todo o mix
			de produtos cadastrados junto à contratante, respondendo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;integralmente
			por todos os produtos disponíveis pela venda independentemente de variação para de mais ou para
			menos.<br><br>

			1.5 – Verificada a não liquidação da nota fiscal de devolução no vencimento, fica a Mart Minas Distribuição
			Ltda. desde já autorizada
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a promover os abatimentos dos valores pactuados nos títulos vincendos em
			favor da Fornecedora, independente notificação.<br><br>

			1.6 – Do mesmo modo, verificada a não liquidação de Acordos pactuados entre as partes e gerados na vigência
			do presente Termo no
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;vencimento, fica a Mart Minas Distribuição Ltda. desde já autorizada a
			promover os abatimentos dos valores pactuados nos títulos
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;vincendos em favor da Fornecedora, independentemente de notificação.
			<br><br><br>

			<div class="alinhamento">Contagem, <?php echo $dataFormatada; ?>.</div><br>

			<blockquote>
				<br><br>
				Mart Minas Distribuição Ltda. <br>
				Filipe Belizário Martins de Andrade <br>
				CPF 012.509.196-65
			</blockquote>
		</div>
	</div>

	<div class="page">
		<div><img src="img/contratoCabecalho.jpg" alt=""></div>
		<div class="alinhamento"><b>Continuação do TERMO DE PARCERIA COMERCIAL <br> CONDIÇÕES GERAIS</b></div><br>
		<div style="text-align: justify;">
			1) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Caso as partes não tenham novos negócios ou pedidos pactuados,
			fica mantida a obrigação prevista nas condições comerciais, item 1.4, no que diz respeito a
			trocas/indenizações/perdas, sendo que a inércia do fornecedor autoriza a cobrança do valor de eventuais
			produtos impróprios, independentemente de prévia notificação. <br><br>

			2) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Em caso de ser decidida a retirada e esta não ser realizada ou
			não ser viável, os produtos impróprios para consumo/revenda ou com prazo de validade vencido, poderão ser
			imediatamente descartados a critério da Mart Minas. Da mesma forma, o valor destes produtos poderá ser
			descontado diretamente dos pagamentos vencidos ou vincendos ao fornecedor, independente de nova ou prévia
			autorização deste. <br><br>

			3) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanto os “pedidos de compra” e seus eventuais anexos e acordos
			que passarão a ser firmados em decorrência do presente Termo, poderão ser negociados e assinados de forma
			única e direta pelos representantes comerciais das partes, passando a ser parte integrante do presente
			Instrumento. <br><br>

			4) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A não entrega dos produtos conforme condições comerciais
			negociadas, especialmente preço e quantidade, autoriza a Mart Minas a realizar o desconto da diferença entre
			o preço praticado e o preço negociado, nos pagamentos vincendos, independente de prévia notificação.
			<br><br>

			5) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O fornecedor acorda desde já em ressarcir mercadorias
			eventualmente recolhidas para análise de órgãos fiscalizadores, por meio de pagamento de Notas de Débitos
			emitidas pela Mart Minas, que por sua vez enviará ao fornecedor cópia do termo de coleta ou depósito das
			mercadorias, juntamente com o valor correspondente a estas. <br><br>

			6) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O fornecedor se obriga a resguardar a Mart Minas, assumindo
			integralmente a responsabilidade civil e criminal, multas administrativas, condenações de qualquer natureza,
			perdas, danos, despesas, inclusive honorários advocatícios, que tenham origem da compra, venda ou uso do
			produto comercializado, se responsabilizando ainda pela conduta dos promotores de venda atuantes nos
			estabelecimentos da Mart Minas. <br><br>

			7) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O fornecedor, neste ato, declara que tem pleno conhecimento de
			todos os termos da Lei Anticorrupção Brasileira (Lei n.º 12.846/13).<br><br>

			As partes acordadas se obrigam ao fiel cumprimento das condições estipuladas neste Termo de Acordo,
			declarando ter pleno conhecimento das tabelas de prazo aqui mencionadas, anuindo expressamente com as
			definições acima. <br><br>

			Qualquer uma das partes poderá renunciar ao presente Termo de Acordo, sem nenhuma multa ou penalidade, desde
			que avisando a outra parte com 30 (trinta) dias de antecedência. <br><br>

			Fica desde já, eleito o foro da Cidade de Contagem – MG para dirimir quaisquer dúvidas oriundas deste termo
			de parceria. <br><br><br>

			<div class="alinhamento">Contagem, <?php echo $dataFormatada; ?>.</div><br><br>

			<blockquote>
				<br><br>
				Mart Minas Distribuição Ltda. <br>
				Filipe Belizário Martins de Andrade <br>
				CPF 012.509.196-65
			</blockquote>

			<div>
				<b><u>Observações:</u></b>
				<blockquote style="text-align: justify;">
					1.&nbsp;&nbsp;&nbsp;Deverá ser apresentada cópia do Contrato Social e/ou Última Alteração Contratual
					para comprovação dos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;poderes de quem assina como Administrador/Procurador. <br>
					2.&nbsp;&nbsp;&nbsp;O fornecedor deverá apresentar este termo com firma reconhecida em Cartório.
					<br>
					3.&nbsp;&nbsp;&nbsp;O que está negociado em termos de prazo e frequência dos promotores de venda é
					automaticamente válido para &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;novas lojas inauguradas após a assinatura do presente Contrato.
				</blockquote>
			</div>
		</div>
	</div>
</body>

</html>