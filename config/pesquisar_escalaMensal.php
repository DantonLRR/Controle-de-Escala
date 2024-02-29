<?php
include "../../base/Conexao_teste.php";
include "../../base/conexao_TotvzOracle.php";
include "php/CRUD_geral.php";
session_start();

$dataSelecionadaNoFiltro = $_POST['mesPesquisa'];
// var_dump($dataSelecionadaNoFiltro);
$mesAtual = date("Y-m");
$loja = $_POST['loja'];
$CPFusuarioLogado = $_SESSION['cpf'];
$usuarioLogado = $_POST['usuarioLogado'];
$InformacaoDosDias = new Dias();
$buscandoMesAno = $InformacaoDosDias->buscandoMesEDiaDaSemana($oracle, $dataSelecionadaNoFiltro);
$dadosFunc = new Funcionarios();
$verificaSeAPessoaLogadaEEncarregada = $dadosFunc->informacaoPessoaLogada($TotvsOracle, $CPFusuarioLogado, $loja);

$verifica = new verifica();
// echo $dataSelecionadaNoFiltro;
// echo"<br>".$loja;
$verificaSeJaExistemDados = $verifica->verificaSeAEscalaMensalEstaFinalizada($oracle, $dataSelecionadaNoFiltro, $loja);
// echo $retorno;
if ($retorno === "NÃO FINALIZADA.") {
    $statusDaTabelaPosPesquisa = "NÃO FINALIZADA.";
} else if ($retorno === "JÁ FINALIZADA.") {
    $statusDaTabelaPosPesquisa = "JÁ FINALIZADA.";
}
?>
<input class="" type="hidden" id="loja" value="<?= $loja ?>">
<input class="" type="hidden" id="usuarioLogado12" value="<?= $usuarioLogado ?>">

<input class="dataSelecionadaNoFiltro" type="hidden" id="dataSelecionadaNoFiltro" value="<?= $dataSelecionadaNoFiltro ?>">
<input class="dataAtual" type="hidden" id="mesAtual" value="<?= $mesAtual ?>">
<?php
foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['SETOR'];

    $buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja, $dadosDeQuemEstaLogadoSetor);

?>
    <input class="" type="hidden" id="" value="<?= $rowVerificaEncarregado['NOME'] ?>">
    <input class="" type="hidden" id="" value="<?= $rowVerificaEncarregado['FUNCAO'] ?>">
    <input class="" type="hidden" id="" value="<?= $rowVerificaEncarregado['SETOR'] ?>">

    <table id="table1" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
    <thead style="background: linear-gradient(to right, #00a451, #052846 85%) !important; color:white;">

            <tr class="trr ">
                <th class="text-center theadColor" scope="row" style="width:150px">Funcionario</th>
                <th class="text-center theadColor">Cargo</th>
                <th class="text-center theadColor">Situação</th>
                <th class="text-center" style="display:none">departamento</th>
                <th class="text-center" style="display:none">Entrada</th>
                <th class="text-center" style="display:none">Saida</th>
                <th class="text-center" style="display:none">Intervalo</th>


                <th class="text-center theadColor" scope="row" style="width:150px ;display:none">matricula</th>

                <?php
                foreach ($buscandoMesAno as $row) :
                ?>
                    <th class="text-center numeroDiaDaSemana" scope="row"><?= $row['DIA'] ?></th>

                <?php
                endforeach
                ?>
            </tr>


        </thead>


        <tbody>





            <tr class="trr" id="quantDias">
                <td></td>
                <td></td>
                <td></td>
                <td style="display:none"></td>
                <td style="display:none"></td>
                <td style="display:none"></td>
                <td style="display:none"></td>
                <td style="display:none"></td>
                <?php
                foreach ($buscandoMesAno as $row) :
                ?>
                    <td class="text-center diaDaSemana" value="" scope="row"><?= $row['DIA_SEMANA_ABREVIADO'] ?></td>

                <?php
                endforeach
                ?>
            </tr>




            <?php
            foreach ($buscaNomeFuncionario as $nomeFunc) :
                $recuperacaoDedados2 = $verifica->verificaSeOMesSelecionadoTemAlgumFuncionarioEscalado($oracle, $dataSelecionadaNoFiltro, $loja, $nomeFunc['DEPARTAMENTO']);
                // ECHO $retorno1;
                if ($retorno1 == "NÃO EXISTE CADASTRO.") {
                    $statusDaTabelaPosPesquisa = "NÃO FINALIZADA.";
                }
            ?>
                <tr class="trr">
                    <td class="text-center funcionario" scope="row"><?= $nomeFunc['NOME'] ?></td>
                    <td class="text-center cargo" scope="row"><?= $nomeFunc['FUNCAO'] ?></td>
                    <td class="text-center situacao" scope="row"><?= $nomeFunc['SITUACAO'] ?></td>
                    <td class="text-center departamento" scope="row" style="display:none"><?= $nomeFunc['DEPARTAMENTO'] ?></td>
                    <td class="text-center horarioEntradaFunc" style="display:none" scope="row"><?= $nomeFunc['HORAENTRADA'] ?></td>
                    <td class="text-center horarioSaidaFunc" style="display:none" scope="row"><?= $nomeFunc['HORASAIDA'] ?></td>
                    <td class="text-center horarioIntervaloFunc" style="display:none" scope="row"><?= $nomeFunc['SAIDAPARAALMOCO'] ?></td>
                    <td class="text-center matriculaFunc" style="display:none" scope="row"><?= $nomeFunc['MATRICULA'] ?></td>


                    <?php
                    $i = 1;
                    foreach ($buscandoMesAno as $row) :
                    ?>
                        <td class=" text-center " scope="row" id="">
                            <?php
                            $recuperaDadosVerificacao = new verifica();
                            $recuperacaoDedados = $recuperaDadosVerificacao->verificaCadastroNaEscalaMensa1($oracle,  $nomeFunc['MATRICULA'], $dataSelecionadaNoFiltro);
                            if ($i < 10) {
                                $d = "0" . $i;
                            } else {
                                $d = $i;
                            }
                            $recuperaAPrimeiraColunaComF = $verifica->verificaSeALinhaDoBancoTemFESETiverRetornaAPrimeiraColunaComF($oracle, $dataSelecionadaNoFiltro,  $loja, $nomeFunc['MATRICULA']);
                            $verficaSeAInserçãoDeFFoiFeitaNoMesAnterior = $verifica->verificaSeALinhaFFoiInseridaNoMesAnterior($oracle, $dataSelecionadaNoFiltro,  $loja, $nomeFunc['MATRICULA']);
                            // echo ($retornoVerificacaoSeOFFoiInseridoNoMesAnterior);

                            $primeiroDiaNaoF = $recuperaAPrimeiraColunaComF['nome_coluna'] ?? $d;
                            // echo "<br>" . $primeiroDiaNaoF;
                            $primeiroDiaEncontrado = false;

                            $isF = ($recuperacaoDedados[0]["$d"] ?? '') === 'F';


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
                            // echo $disabled;
                            ?>
                            <select class="estilezaSelect" id="" <?= $disabled ?>>
                                <option value="<?= $recuperacaoDedados[0]["$d"] ?? '' ?>"><?= $recuperacaoDedados[0]["$d"] ?? '' ?></option>
                                <option value="FA">FA</option>
                                <option value="FD">FD</option>
                                <option value="FF">FF</option>
                                <option value="F">F</option>
                                <option value=""></option>

                            </select>
                        </td>
                    <?php
                        $i++;
                    endforeach
                    ?>
                </tr>
            <?php
            endforeach;

            ?>
        </tbody>
    <?php
endforeach;
    ?>

    <input class="statusDaTabela" type="hidden" id="statusDaTabelaPosPesquisa" value="<?= $statusDaTabelaPosPesquisa ?>">
    <?php
    // echo $statusDaTabelaPosPesquisa;
    ?>

    </table>
    <script type="module" defer>
        import {
            criandoHtmlmensagemCarregamento,
            Toasty
        } from "../../../../base/jsGeral.js";
        $(document).ready(function() {
            var statusDaTabelaPosPesquisa = $("#statusDaTabelaPosPesquisa").val();

            if (statusDaTabelaPosPesquisa === "JÁ FINALIZADA.") {
                criandoHtmlmensagemCarregamento("exibir");


                $('#table1').find('input, select, textarea, button').prop('disabled', true);
                $('.btnVermelho').addClass('ocultarBotao');
            } else {
                $('#table1').find('input, select, textarea, button').prop('disabled', false);
                $('select[name="desabilitarEsteSelect"]').prop('disabled', true);
                $('.btnVermelho').removeClass('ocultarBotao');
                var dataPesquisa = $(".dataSelecionadaNoFiltro").val();
                var mesAtual = $("#mesAtual").val();
                if (dataPesquisa < mesAtual) {
                    $('.estilezaSelect').css('background-color', 'grey');
                    $('#table1').find('input, select, textarea, button').prop('disabled', true);
                    $('.btnVermelho').addClass('ocultarBotao');
                } else {
                    $('.estilezaSelect').prop('disabled', false);
                    $('.estilezaSelect').css('background-color', '');
                }
            }
            criandoHtmlmensagemCarregamento("ocultar");

        });





        var rows = $("#table1 tr");

        rows.on('click', function() {
            rows.removeClass("selected");
            $(this).addClass("selected");
        });
        $(document).ready(function() {
            $('.estilezaSelect').on('click', function() {

                var opcaoSelecionadaAux = $(this).val();
                var $selects = $(this).closest('tr').find('.estilezaSelect');
                var colIndex = $(this).closest('td').index();
                var indexAtual = $selects.index(this);
                var PeriodoMaximoDeDiasTrabalhados = false
                var indexUltimoPreenchido = -1;
                var indexProximoPreenchido;
                $selects.slice(0, indexAtual).each(function(index) {
                    if ($(this).val() !== '') {
                        indexUltimoPreenchido = index;
                    }
                });

                $selects.slice(indexAtual + 1).each(function(index) {
                    if ($(this).val() !== '') {
                        indexProximoPreenchido = indexAtual + index + 1; // Ajuste aqui
                        return false; // Para interromper o loop assim que encontrar um select preenchido
                    }
                });

                var THDoSelectAtual = $('#table1 thead tr.trr th').eq(colIndex).text()
                console.log(THDoSelectAtual)


                // Calcular a quantidade de selects em branco entre o último e o atual
                var selectsEmBrancoEntre = indexAtual - indexUltimoPreenchido - 1;

                var thDoUltimoSelectPreenchido = THDoSelectAtual - selectsEmBrancoEntre - 1

                console.log("th Do Ultimo Select Preenchido:  " + thDoUltimoSelectPreenchido)
                // console.log('Selects em branco entre o último selecionado e o atual: ' + selectsEmBrancoEntre);

                var selectsEmBrancoEntreOProximo = indexProximoPreenchido - indexAtual - 1;
                // console.log('Selects em branco entre próximo selecionado e o atual: ' + selectsEmBrancoEntreOProximo);

                var thDoProximoSelectPreenchido = parseInt(THDoSelectAtual) + selectsEmBrancoEntreOProximo + 1;
                if (isNaN(thDoProximoSelectPreenchido)) {
                    thDoProximoSelectPreenchido = 0;
                }
                console.log("th Do Proximo Select Preenchido: " + thDoProximoSelectPreenchido);



                if (selectsEmBrancoEntre >= 7) {
                    PeriodoMaximoDeDiasTrabalhados = true
                }
                var valorINICIAL = $(this).val();
                $(this).off('change').on('change', function() {

                    var periodoParaEdicaoDeEscala = parseInt(thDoProximoSelectPreenchido) - thDoUltimoSelectPreenchido
                    var opcaoSelecionada = $(this).val();
                    // alert(valorINICIAL)
                    // alert(opcaoSelecionada)
                    if (valorINICIAL != 'F' && opcaoSelecionada == 'F' || valorINICIAL == '' && opcaoSelecionada == 'F') {
                        console.log('Valor inicial : ' + valorINICIAL);
                        console.log('opcao Escolhida :' + opcaoSelecionada)
                        console.log("caiu na primeira");
                        if (PeriodoMaximoDeDiasTrabalhados) {
                            $(this).val(' ');
                            Toasty("Atenção", "Funcionario escalado sem folga mais de SEIS dias", "#E20914");

                        } else {
                            var colIndex = $(this).closest('td').index();
                            var mesPesquisa = $("#dataPesquisa").val();
                            //console.log(mesPesquisa)

                            var mesAtual = $("#mesAtual").val();

                            if (mesPesquisa == "") {
                                mesPesquisa = mesAtual
                            }

                            var opcaoSelecionada = 'F';
                            var $tr = $(this).closest('tr');
                            var funcionario = $tr.find('td.funcionario').text();
                            var matriculaFunc = $tr.find('td.matriculaFunc').text();
                            var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
                            var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
                            var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
                            var cargoFunc = $tr.find('td.cargo').text();
                            var departamentoFunc = $tr.find('td.departamento').text();
                            //alert(departamentoFunc)
                            var colIndex = $(this).closest('td').index();
                            var mesPesquisa = $("#dataPesquisa").val();


                            var $selects = $(this).closest('tr').find('.estilezaSelect'); // Todos os selects da linha

                            var indexAtual = $('#table1 thead tr.trr th').eq(colIndex).text();

                            // alert("dia selecionado :" + indexAtual)

                            var indexUltimoDia = $selects.length;
                            //console.log(indexAtual);
                            //console.log(indexUltimoDia);



                            var indexAtualNumero = parseInt(indexAtual, 10);
                            var numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes = [];
                            for (var i = indexAtualNumero; i <= indexUltimoDia; i++) {
                                var aux = i < 10 ? "0" + i : i.toString();
                                numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes.push('"' + aux + '"');

                                console.log("dia inserido : " + numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes);

                                $selects.eq(i).prop('disabled', true).val('F');
                            }


                            $.ajax({
                                url: "config/insertEUpdate_EscalaMensal.php",
                                method: 'get',
                                data: 'numeroDiaDaSemana=' +
                                    numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes +
                                    "&opcaoSelecionada=" +
                                    opcaoSelecionada +
                                    "&funcionario=" +
                                    funcionario +
                                    "&mesAtual=" +
                                    mesAtual +
                                    "&mesPesquisa=" +
                                    mesPesquisa +
                                    "&usuarioLogado=" +
                                    usuarioLogado +
                                    "&matriculaFunc=" +
                                    matriculaFunc +
                                    "&loja=" +
                                    loja +
                                    "&horarioEntradaFunc=" +
                                    horarioEntradaFunc +
                                    "&horarioSaidaFunc=" +
                                    horarioSaidaFunc +
                                    "&horarioIntervaloFunc=" +
                                    horarioIntervaloFunc +
                                    "&cargoFunc=" +
                                    cargoFunc +
                                    "&departamentoFunc=" +
                                    departamentoFunc,
                                // dataType: 'json',
                                success: function(retorno) {
                                    console.log(retorno)

                                }
                            });



                            // Calcular quantos dias faltam até o final do mês
                            var diasRestantes = indexUltimoDia - indexAtual;
                            var diasParaProximoMes = Math.min(29 - diasRestantes, diasRestantes);
                            //console.log("faltaram  para o proximo mes: " + diasParaProximoMes);


                            // Obtém o ano e o mês a partir da string mesPesquisa
                            var ano = parseInt(mesPesquisa.split('-')[0]);
                            var mes = parseInt(mesPesquisa.split('-')[1]);



                            // Converte 'mesPesquisa' para um objeto Date
                            var data = new Date(ano, mes - 1); // O mês em JavaScript começa em zero (janeiro é 0)

                            // Verifica se a quantidade de dias é maior que 1 para avançar para o próximo mês
                            if (diasParaProximoMes > 1) {
                                // Adiciona a quantidade de dias à data atual
                                data.setMonth(data.getMonth() + 1);
                                ano = data.getFullYear();
                                mes = data.getMonth() + 1;

                                // Formata o novo mês para o formato 'AAAA-MM'
                                mesPesquisa = ano + '-' + (mes < 10 ? '0' + mes : mes);
                                var numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes = [];
                                for (var i = 1; i <= diasParaProximoMes; i++) {
                                    var aux = i < 10 ? "0" + i : i.toString();
                                    console.log("dia faltante :" + numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes);
                                    numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes.push('"' + aux + '"');
                                }
                                var inclusaoDoMesAnterior = "SIM";
                                $.ajax({
                                    url: "config/insertEUpdate_EscalaMensal_proximo_mes.php",
                                    method: 'get',
                                    data: 'numeroDiaDaSemana=' +
                                        numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes +
                                        "&opcaoSelecionada=" +
                                        opcaoSelecionada +
                                        "&funcionario=" +
                                        funcionario +
                                        "&mesAtual=" +
                                        mesAtual +
                                        "&mesPesquisa=" +
                                        mesPesquisa +
                                        "&usuarioLogado=" +
                                        usuarioLogado +
                                        "&matriculaFunc=" +
                                        matriculaFunc +
                                        "&loja=" +
                                        loja +
                                        "&horarioEntradaFunc=" +
                                        horarioEntradaFunc +
                                        "&horarioSaidaFunc=" +
                                        horarioSaidaFunc +
                                        "&horarioIntervaloFunc=" +
                                        horarioIntervaloFunc +
                                        "&cargoFunc=" +
                                        cargoFunc +
                                        "&inclusaoDoMesAnterior=" +
                                        inclusaoDoMesAnterior +
                                        "&departamentoFunc=" +
                                        departamentoFunc,

                                    // dataType: 'json',
                                    success: function(retorno) {
                                        // console.log(retorno)

                                    }
                                });
                            }
                        }
                    } else if (valorINICIAL != 'F' && opcaoSelecionada != 'F' || valorINICIAL == '' && opcaoSelecionada != 'F') {
                        // console.log('Valor INICIAL: ' + valorINICIAL);
                        // console.log('opcao Escolhida :' + opcaoSelecionada)
                        console.log("caiu na segunda");
                        var opcaoSelecionada = $(this).val();
                        var $tr = $(this).closest('tr');
                        var funcionario = $tr.find('td.funcionario').text();
                        var matriculaFunc = $tr.find('td.matriculaFunc').text();
                        var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
                        var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
                        var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
                        var cargoFunc = $tr.find('td.cargo').text();
                        var departamentoFunc = $tr.find('td.departamento').text();
                        //
                        var colIndex = $(this).closest('td').index();
                        var mesPesquisa = $("#dataPesquisa").val();
                        //console.log(mesPesquisa)

                        var mesAtual = $("#mesAtual").val();

                        if (mesPesquisa == "") {
                            mesPesquisa = mesAtual
                        }
                        var numeroDiaDaSemana = [];

                        numeroDiaDaSemana.push('"' + $('#table1 thead tr.trr th').eq(colIndex).text() + '"');
                        if (PeriodoMaximoDeDiasTrabalhados) {
                            $(this).val(' ');
                            Toasty("Atenção", "Funcionario escalado sem folga mais de SEIS dias", "#E20914");

                        } else if (opcaoSelecionada == '' && periodoParaEdicaoDeEscala >= 7) {
                            $(this).val(opcaoSelecionadaAux);
                            Toasty("Atenção", "Funcionario escalado sem folga mais de SEIS dias", "#E20914");
                        } else {
                            $.ajax({
                                url: "config/insertEUpdate_EscalaMensal.php",
                                method: 'get',
                                data: 'numeroDiaDaSemana=' +
                                    numeroDiaDaSemana +
                                    "&opcaoSelecionada=" +
                                    opcaoSelecionada +
                                    "&funcionario=" +
                                    funcionario +
                                    "&mesAtual=" +
                                    mesAtual +
                                    "&mesPesquisa=" +
                                    mesPesquisa +
                                    "&usuarioLogado=" +
                                    usuarioLogado +
                                    "&matriculaFunc=" +
                                    matriculaFunc +
                                    "&loja=" +
                                    loja +
                                    "&horarioEntradaFunc=" +
                                    horarioEntradaFunc +
                                    "&horarioSaidaFunc=" +
                                    horarioSaidaFunc +
                                    "&horarioIntervaloFunc=" +
                                    horarioIntervaloFunc +
                                    "&cargoFunc=" +
                                    cargoFunc +
                                    "&departamentoFunc=" +
                                    departamentoFunc,
                                // dataType: 'json',
                                success: function(retorno) {
                                    // console.log(retorno)
                                }
                            });
                        }

                    } else if (valorINICIAL == 'F' && opcaoSelecionada != 'F' || valorINICIAL == 'F' && opcaoSelecionada != '') {

                        console.log('Valor INICIAL: ' + valorINICIAL);
                        console.log('opcao Escolhida :' + opcaoSelecionada)
                        console.log("caiu na terceira");
                        var mesPesquisa = $("#dataPesquisa").val();
                        //console.log(mesPesquisa)

                        var mesAtual = $("#mesAtual").val();

                        if (mesPesquisa == "") {
                            mesPesquisa = mesAtual
                        }

                        var opcaoSelecionada = '';
                        var $tr = $(this).closest('tr');
                        var funcionario = $tr.find('td.funcionario').text();
                        var matriculaFunc = $tr.find('td.matriculaFunc').text();
                        var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
                        var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
                        var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
                        var cargoFunc = $tr.find('td.cargo').text();
                        var departamentoFunc = $tr.find('td.departamento').text();
                        //alert(departamentoFunc)



                        var colIndex = $(this).closest('td').index();
                        var mesPesquisa = $("#dataPesquisa").val();


                        var $selects = $(this).closest('tr').find('.estilezaSelect'); // Todos os selects da linha

                        var indexAtual = $('#table1 thead tr.trr th').eq(colIndex).text();

                        // alert("dia selecionado :" + indexAtual);

                        var indexUltimoDia = $selects.length;
                        //console.log(indexAtual);
                        //console.log(indexUltimoDia);



                        var indexAtualNumero = parseInt(indexAtual, 10);
                        // alert("Dia Selecionado " + indexAtualNumero);
                        var numeroDiaDaSemanaArrayLimpaFA = [];

                        for (var i = indexAtualNumero; i <= indexUltimoDia; i++) {
                            var aux = i < 10 ? "0" + i : i.toString();

                            numeroDiaDaSemanaArrayLimpaFA.push('"' + aux + '"');

                            console.log("dia inserido : " + numeroDiaDaSemanaArrayLimpaFA);

                            $selects.eq(i).prop('disabled', false).val(' ');
                        }


                        $.ajax({
                            url: "config/insertEUpdate_EscalaMensal.php",
                            method: 'get',
                            data: 'numeroDiaDaSemana=' +
                                numeroDiaDaSemanaArrayLimpaFA +
                                "&opcaoSelecionada=" +
                                opcaoSelecionada +
                                "&funcionario=" +
                                funcionario +
                                "&mesAtual=" +
                                mesAtual +
                                "&mesPesquisa=" +
                                mesPesquisa +
                                "&usuarioLogado=" +
                                usuarioLogado +
                                "&matriculaFunc=" +
                                matriculaFunc +
                                "&loja=" +
                                loja +
                                "&horarioEntradaFunc=" +
                                horarioEntradaFunc +
                                "&horarioSaidaFunc=" +
                                horarioSaidaFunc +
                                "&horarioIntervaloFunc=" +
                                horarioIntervaloFunc +
                                "&cargoFunc=" +
                                cargoFunc +
                                "&departamentoFunc=" +
                                departamentoFunc,

                            // dataType: 'json',
                            success: function(retorno) {
                                // console.log(retorno)

                            }
                        });



                        // Calcular quantos dias faltam até o final do mês
                        var diasRestantes = indexUltimoDia - indexAtual;
                        var diasParaProximoMes = Math.min(29 - diasRestantes, diasRestantes);
                        //console.log("faltaram  para o proximo mes: " + diasParaProximoMes);


                        // Obtém o ano e o mês a partir da string mesPesquisa
                        var ano = parseInt(mesPesquisa.split('-')[0]);
                        var mes = parseInt(mesPesquisa.split('-')[1]);



                        // Converte 'mesPesquisa' para um objeto Date
                        var data = new Date(ano, mes - 1); // O mês em JavaScript começa em zero (janeiro é 0)
                        var numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes = [];
                        // Verifica se a quantidade de dias é maior que 1 para avançar para o próximo mês
                        if (diasParaProximoMes > 1) {
                            // Adiciona a quantidade de dias à data atual
                            data.setMonth(data.getMonth() + 1);
                            ano = data.getFullYear();
                            mes = data.getMonth() + 1;

                            // Formata o novo mês para o formato 'AAAA-MM'
                            mesPesquisa = ano + '-' + (mes < 10 ? '0' + mes : mes);

                            console.log(mesPesquisa); // Aqui você terá o valor do mês atualizado, seja o mesmo ou o próximo mês

                            // Loop para contar até a quantidade de dias desejada
                            for (var i = 1; i <= diasParaProximoMes; i++) {
                                var aux = i < 10 ? "0" + i : i.toString();
                                numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes.push('"' + aux + '"');

                                console.log(numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes);

                            }
                            var inclusaoDoMesAnterior = " ";
                            $.ajax({
                                url: "config/insertEUpdate_EscalaMensal_proximo_mes.php",
                                method: 'get',
                                data: 'numeroDiaDaSemana=' +
                                    numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes +
                                    "&opcaoSelecionada=" +
                                    opcaoSelecionada +
                                    "&funcionario=" +
                                    funcionario +
                                    "&mesAtual=" +
                                    mesAtual +
                                    "&mesPesquisa=" +
                                    mesPesquisa +
                                    "&usuarioLogado=" +
                                    usuarioLogado +
                                    "&matriculaFunc=" +
                                    matriculaFunc +
                                    "&loja=" +
                                    loja +
                                    "&horarioEntradaFunc=" +
                                    horarioEntradaFunc +
                                    "&horarioSaidaFunc=" +
                                    horarioSaidaFunc +
                                    "&horarioIntervaloFunc=" +
                                    horarioIntervaloFunc +
                                    "&cargoFunc=" +
                                    cargoFunc +
                                    "&inclusaoDoMesAnterior=" +
                                    inclusaoDoMesAnterior +
                                    "&departamentoFunc=" +
                                    departamentoFunc,

                                // dataType: 'json',
                                success: function(retorno) {
                                    //console.log(retorno)

                                }
                            });
                        }




                        var opcaoSelecionada = $(this).val();
                        var $tr = $(this).closest('tr');
                        var funcionario = $tr.find('td.funcionario').text();
                        var matriculaFunc = $tr.find('td.matriculaFunc').text();
                        var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
                        var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
                        var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
                        var cargoFunc = $tr.find('td.cargo').text();

                        var colIndex = $(this).closest('td').index();
                        var mesPesquisa = $("#dataPesquisa").val();
                        //console.log(mesPesquisa)

                        var mesAtual = $("#mesAtual").val();

                        if (mesPesquisa == "") {
                            mesPesquisa = mesAtual
                        }
                        var numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF = [];

                        numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF.push('"' + $('#table1 thead tr.trr th').eq(colIndex).text() + '"');

                        $.ajax({
                            url: "config/insertEUpdate_EscalaMensal.php",
                            method: 'get',
                            data: 'numeroDiaDaSemana=' +
                                numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF +
                                "&opcaoSelecionada=" +
                                opcaoSelecionada +
                                "&funcionario=" +
                                funcionario +
                                "&mesAtual=" +
                                mesAtual +
                                "&mesPesquisa=" +
                                mesPesquisa +
                                "&usuarioLogado=" +
                                usuarioLogado +
                                "&matriculaFunc=" +
                                matriculaFunc +
                                "&loja=" +
                                loja +
                                "&horarioEntradaFunc=" +
                                horarioEntradaFunc +
                                "&horarioSaidaFunc=" +
                                horarioSaidaFunc +
                                "&horarioIntervaloFunc=" +
                                horarioIntervaloFunc +
                                "&cargoFunc=" +
                                cargoFunc +
                                "&departamentoFunc=" +
                                departamentoFunc,

                            // dataType: 'json',
                            success: function(retorno) {
                                // console.log(retorno)


                            }
                        });





                    }
                });
            });
        });


        //função anterior sem a verificação dos dias 
        // $(document).ready(function() {
        //     $('.estilezaSelect').on('click', function() {
        //         var valorINICIAL = $(this).val();
        //         $(this).off('change').on('change', function() {
        //             var opcaoSelecionada = $(this).val();
        //             // alert(valorINICIAL)
        //             // alert(opcaoSelecionada)
        //             if (valorINICIAL != 'F' && opcaoSelecionada == 'F' || valorINICIAL == '' && opcaoSelecionada == 'F') {
        //                 console.log('Valor inicial : ' + valorINICIAL);
        //                 console.log('opcao Escolhida :' + opcaoSelecionada)
        //                 console.log("caiu na primeira");
        //                 var colIndex = $(this).closest('td').index();
        //                 var mesPesquisa = $("#dataPesquisa").val();
        //                 //console.log(mesPesquisa)

        //                 var mesAtual = $("#mesAtual").val();

        //                 if (mesPesquisa == "") {
        //                     mesPesquisa = mesAtual
        //                 }

        //                 var opcaoSelecionada = 'F';
        //                 var $tr = $(this).closest('tr');
        //                 var funcionario = $tr.find('td.funcionario').text();
        //                 var matriculaFunc = $tr.find('td.matriculaFunc').text();
        //                 var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
        //                 var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
        //                 var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
        //                 var cargoFunc = $tr.find('td.cargo').text();

        //                 var colIndex = $(this).closest('td').index();
        //                 var mesPesquisa = $("#dataPesquisa").val();


        //                 var $selects = $(this).closest('tr').find('.estilezaSelect'); // Todos os selects da linha

        //                 var indexAtual = $('#table1 thead tr.trr th').eq(colIndex).text();

        //                 // alert("dia selecionado :" + indexAtual)

        //                 var indexUltimoDia = $selects.length;
        //                 //console.log(indexAtual);
        //                 //console.log(indexUltimoDia);



        //                 var indexAtualNumero = parseInt(indexAtual, 10);
        //                 var numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes = [];
        //                 for (var i = indexAtualNumero; i <= indexUltimoDia; i++) {
        //                     var aux = i < 10 ? "0" + i : i.toString();
        //                     numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes.push('"' + aux + '"');

        //                     console.log("dia inserido : " + numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes);

        //                     $selects.eq(i).prop('disabled', true).val('F');
        //                 }


        //                 $.ajax({
        //                     url: "config/insertEUpdate_EscalaMensal.php",
        //                     method: 'get',
        //                     data: 'numeroDiaDaSemana=' +
        //                         numeroDiaDaSemanaArrayInsereFTrintaDiasSeguintes +
        //                         "&opcaoSelecionada=" +
        //                         opcaoSelecionada +
        //                         "&funcionario=" +
        //                         funcionario +
        //                         "&mesAtual=" +
        //                         mesAtual +
        //                         "&mesPesquisa=" +
        //                         mesPesquisa +
        //                         "&usuarioLogado=" +
        //                         usuarioLogado +
        //                         "&matriculaFunc=" +
        //                         matriculaFunc +
        //                         "&loja=" +
        //                         loja +
        //                         "&horarioEntradaFunc=" +
        //                         horarioEntradaFunc +
        //                         "&horarioSaidaFunc=" +
        //                         horarioSaidaFunc +
        //                         "&horarioIntervaloFunc=" +
        //                         horarioIntervaloFunc +
        //                         "&cargoFunc=" +
        //                         cargoFunc,

        //                     // dataType: 'json',
        //                     success: function(retorno) {
        //                         console.log(retorno)

        //                     }
        //                 });



        //                 // Calcular quantos dias faltam até o final do mês
        //                 var diasRestantes = indexUltimoDia - indexAtual;
        //                 var diasParaProximoMes = Math.min(29 - diasRestantes, diasRestantes);
        //                 //console.log("faltaram  para o proximo mes: " + diasParaProximoMes);


        //                 // Obtém o ano e o mês a partir da string mesPesquisa
        //                 var ano = parseInt(mesPesquisa.split('-')[0]);
        //                 var mes = parseInt(mesPesquisa.split('-')[1]);



        //                 // Converte 'mesPesquisa' para um objeto Date
        //                 var data = new Date(ano, mes - 1); // O mês em JavaScript começa em zero (janeiro é 0)

        //                 // Verifica se a quantidade de dias é maior que 1 para avançar para o próximo mês
        //                 if (diasParaProximoMes > 1) {
        //                     // Adiciona a quantidade de dias à data atual
        //                     data.setMonth(data.getMonth() + 1);
        //                     ano = data.getFullYear();
        //                     mes = data.getMonth() + 1;

        //                     // Formata o novo mês para o formato 'AAAA-MM'
        //                     mesPesquisa = ano + '-' + (mes < 10 ? '0' + mes : mes);
        //                     var numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes = [];
        //                     for (var i = 1; i <= diasParaProximoMes; i++) {
        //                         var aux = i < 10 ? "0" + i : i.toString();
        //                         console.log("dia faltante :" + numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes);
        //                         numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes.push('"' + aux + '"');
        //                     }
        //                     var inclusaoDoMesAnterior = "SIM";
        //                     $.ajax({
        //                         url: "config/insertEUpdate_EscalaMensal_proximo_mes.php",
        //                         method: 'get',
        //                         data: 'numeroDiaDaSemana=' +
        //                             numeroDiaDaSemanaArrayInsereFNosDiasFaltantesDoProximoMes +
        //                             "&opcaoSelecionada=" +
        //                             opcaoSelecionada +
        //                             "&funcionario=" +
        //                             funcionario +
        //                             "&mesAtual=" +
        //                             mesAtual +
        //                             "&mesPesquisa=" +
        //                             mesPesquisa +
        //                             "&usuarioLogado=" +
        //                             usuarioLogado +
        //                             "&matriculaFunc=" +
        //                             matriculaFunc +
        //                             "&loja=" +
        //                             loja +
        //                             "&horarioEntradaFunc=" +
        //                             horarioEntradaFunc +
        //                             "&horarioSaidaFunc=" +
        //                             horarioSaidaFunc +
        //                             "&horarioIntervaloFunc=" +
        //                             horarioIntervaloFunc +
        //                             "&cargoFunc=" +
        //                             cargoFunc +
        //                             "&inclusaoDoMesAnterior=" +
        //                             inclusaoDoMesAnterior,

        //                         // dataType: 'json',
        //                         success: function(retorno) {
        //                             // console.log(retorno)

        //                         }
        //                     });

        //                 }
        //             } else if (valorINICIAL != 'F' && opcaoSelecionada != 'F' || valorINICIAL == '' && opcaoSelecionada != 'F') {
        //                 console.log('Valor INICIAL: ' + valorINICIAL);
        //                 console.log('opcao Escolhida :' + opcaoSelecionada)
        //                 console.log("caiu na segunda");
        //                 var opcaoSelecionada = $(this).val();
        //                 var $tr = $(this).closest('tr');
        //                 var funcionario = $tr.find('td.funcionario').text();
        //                 var matriculaFunc = $tr.find('td.matriculaFunc').text();
        //                 var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
        //                 var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
        //                 var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
        //                 var cargoFunc = $tr.find('td.cargo').text();

        //                 var colIndex = $(this).closest('td').index();
        //                 var mesPesquisa = $("#dataPesquisa").val();
        //                 //console.log(mesPesquisa)

        //                 var mesAtual = $("#mesAtual").val();

        //                 if (mesPesquisa == "") {
        //                     mesPesquisa = mesAtual
        //                 }
        //                 var numeroDiaDaSemana = [];

        //                 numeroDiaDaSemana.push('"' + $('#table1 thead tr.trr th').eq(colIndex).text() + '"');

        //                 $.ajax({
        //                     url: "config/insertEUpdate_EscalaMensal.php",
        //                     method: 'get',
        //                     data: 'numeroDiaDaSemana=' +
        //                         numeroDiaDaSemana +
        //                         "&opcaoSelecionada=" +
        //                         opcaoSelecionada +
        //                         "&funcionario=" +
        //                         funcionario +
        //                         "&mesAtual=" +
        //                         mesAtual +
        //                         "&mesPesquisa=" +
        //                         mesPesquisa +
        //                         "&usuarioLogado=" +
        //                         usuarioLogado +
        //                         "&matriculaFunc=" +
        //                         matriculaFunc +
        //                         "&loja=" +
        //                         loja +
        //                         "&horarioEntradaFunc=" +
        //                         horarioEntradaFunc +
        //                         "&horarioSaidaFunc=" +
        //                         horarioSaidaFunc +
        //                         "&horarioIntervaloFunc=" +
        //                         horarioIntervaloFunc +
        //                         "&cargoFunc=" +
        //                         cargoFunc,

        //                     // dataType: 'json',
        //                     success: function(retorno) {
        //                         // console.log(retorno)


        //                     }
        //                 });

        //             } else if (valorINICIAL == 'F' && opcaoSelecionada != 'F' || valorINICIAL == 'F' && opcaoSelecionada != '') {

        //                 console.log('Valor INICIAL: ' + valorINICIAL);
        //                 console.log('opcao Escolhida :' + opcaoSelecionada)
        //                 console.log("caiu na terceira");
        //                 var mesPesquisa = $("#dataPesquisa").val();
        //                 //console.log(mesPesquisa)

        //                 var mesAtual = $("#mesAtual").val();

        //                 if (mesPesquisa == "") {
        //                     mesPesquisa = mesAtual
        //                 }

        //                 var opcaoSelecionada = '';
        //                 var $tr = $(this).closest('tr');
        //                 var funcionario = $tr.find('td.funcionario').text();
        //                 var matriculaFunc = $tr.find('td.matriculaFunc').text();
        //                 var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
        //                 var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
        //                 var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
        //                 var cargoFunc = $tr.find('td.cargo').text();




        //                 var colIndex = $(this).closest('td').index();
        //                 var mesPesquisa = $("#dataPesquisa").val();


        //                 var $selects = $(this).closest('tr').find('.estilezaSelect'); // Todos os selects da linha

        //                 var indexAtual = $('#table1 thead tr.trr th').eq(colIndex).text();

        //                 // alert("dia selecionado :" + indexAtual);

        //                 var indexUltimoDia = $selects.length;
        //                 //console.log(indexAtual);
        //                 //console.log(indexUltimoDia);



        //                 var indexAtualNumero = parseInt(indexAtual, 10);
        //                 alert("Dia Selecionado " + indexAtualNumero);
        //                 var numeroDiaDaSemanaArrayLimpaF = [];

        //                 for (var i = indexAtualNumero; i <= indexUltimoDia; i++) {
        //                     var aux = i < 10 ? "0" + i : i.toString();

        //                     numeroDiaDaSemanaArrayLimpaF.push('"' + aux + '"');

        //                     console.log("dia inserido : " + numeroDiaDaSemanaArrayLimpaF);

        //                     $selects.eq(i).prop('disabled', false).val(' ');
        //                 }


        //                 $.ajax({
        //                     url: "config/insertEUpdate_EscalaMensal.php",
        //                     method: 'get',
        //                     data: 'numeroDiaDaSemana=' +
        //                         numeroDiaDaSemanaArrayLimpaF +
        //                         "&opcaoSelecionada=" +
        //                         opcaoSelecionada +
        //                         "&funcionario=" +
        //                         funcionario +
        //                         "&mesAtual=" +
        //                         mesAtual +
        //                         "&mesPesquisa=" +
        //                         mesPesquisa +
        //                         "&usuarioLogado=" +
        //                         usuarioLogado +
        //                         "&matriculaFunc=" +
        //                         matriculaFunc +
        //                         "&loja=" +
        //                         loja +
        //                         "&horarioEntradaFunc=" +
        //                         horarioEntradaFunc +
        //                         "&horarioSaidaFunc=" +
        //                         horarioSaidaFunc +
        //                         "&horarioIntervaloFunc=" +
        //                         horarioIntervaloFunc +
        //                         "&cargoFunc=" +
        //                         cargoFunc,

        //                     // dataType: 'json',
        //                     success: function(retorno) {
        //                         // console.log(retorno)

        //                     }
        //                 });



        //                 // Calcular quantos dias faltam até o final do mês
        //                 var diasRestantes = indexUltimoDia - indexAtual;
        //                 var diasParaProximoMes = Math.min(29 - diasRestantes, diasRestantes);
        //                 //console.log("faltaram  para o proximo mes: " + diasParaProximoMes);


        //                 // Obtém o ano e o mês a partir da string mesPesquisa
        //                 var ano = parseInt(mesPesquisa.split('-')[0]);
        //                 var mes = parseInt(mesPesquisa.split('-')[1]);



        //                 // Converte 'mesPesquisa' para um objeto Date
        //                 var data = new Date(ano, mes - 1); // O mês em JavaScript começa em zero (janeiro é 0)
        //                 var numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes = [];
        //                 // Verifica se a quantidade de dias é maior que 1 para avançar para o próximo mês
        //                 if (diasParaProximoMes > 1) {
        //                     // Adiciona a quantidade de dias à data atual
        //                     data.setMonth(data.getMonth() + 1);
        //                     ano = data.getFullYear();
        //                     mes = data.getMonth() + 1;

        //                     // Formata o novo mês para o formato 'AAAA-MM'
        //                     mesPesquisa = ano + '-' + (mes < 10 ? '0' + mes : mes);

        //                     console.log(mesPesquisa); // Aqui você terá o valor do mês atualizado, seja o mesmo ou o próximo mês

        //                     // Loop para contar até a quantidade de dias desejada
        //                     for (var i = 1; i <= diasParaProximoMes; i++) {
        //                         var aux = i < 10 ? "0" + i : i.toString();
        //                         numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes.push('"' + aux + '"');

        //                         console.log(numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes);

        //                     }
        //                     var inclusaoDoMesAnterior = " ";
        //                     $.ajax({
        //                         url: "config/insertEUpdate_EscalaMensal_proximo_mes.php",
        //                         method: 'get',
        //                         data: 'numeroDiaDaSemana=' +
        //                             numeroDiaDaSemanaArrayLimpaFDiasRestantesParaOProximoMes +
        //                             "&opcaoSelecionada=" +
        //                             opcaoSelecionada +
        //                             "&funcionario=" +
        //                             funcionario +
        //                             "&mesAtual=" +
        //                             mesAtual +
        //                             "&mesPesquisa=" +
        //                             mesPesquisa +
        //                             "&usuarioLogado=" +
        //                             usuarioLogado +
        //                             "&matriculaFunc=" +
        //                             matriculaFunc +
        //                             "&loja=" +
        //                             loja +
        //                             "&horarioEntradaFunc=" +
        //                             horarioEntradaFunc +
        //                             "&horarioSaidaFunc=" +
        //                             horarioSaidaFunc +
        //                             "&horarioIntervaloFunc=" +
        //                             horarioIntervaloFunc +
        //                             "&cargoFunc=" +
        //                             cargoFunc + "&inclusaoDoMesAnterior=" + inclusaoDoMesAnterior,

        //                         // dataType: 'json',
        //                         success: function(retorno) {
        //                             //console.log(retorno)

        //                         }
        //                     });
        //                 }




        //                 var opcaoSelecionada = $(this).val();
        //                 var $tr = $(this).closest('tr');
        //                 var funcionario = $tr.find('td.funcionario').text();
        //                 var matriculaFunc = $tr.find('td.matriculaFunc').text();
        //                 var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
        //                 var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
        //                 var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
        //                 var cargoFunc = $tr.find('td.cargo').text();

        //                 var colIndex = $(this).closest('td').index();
        //                 var mesPesquisa = $("#dataPesquisa").val();
        //                 //console.log(mesPesquisa)

        //                 var mesAtual = $("#mesAtual").val();

        //                 if (mesPesquisa == "") {
        //                     mesPesquisa = mesAtual
        //                 }
        //                 var numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF = [];

        //                 numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF.push('"' + $('#table1 thead tr.trr th').eq(colIndex).text() + '"');

        //                 $.ajax({
        //                     url: "config/insertEUpdate_EscalaMensal.php",
        //                     method: 'get',
        //                     data: 'numeroDiaDaSemana=' +
        //                         numeroDiaDaSemanaArrayIncluiAlteracaoFeitaParaLimparOF +
        //                         "&opcaoSelecionada=" +
        //                         opcaoSelecionada +
        //                         "&funcionario=" +
        //                         funcionario +
        //                         "&mesAtual=" +
        //                         mesAtual +
        //                         "&mesPesquisa=" +
        //                         mesPesquisa +
        //                         "&usuarioLogado=" +
        //                         usuarioLogado +
        //                         "&matriculaFunc=" +
        //                         matriculaFunc +
        //                         "&loja=" +
        //                         loja +
        //                         "&horarioEntradaFunc=" +
        //                         horarioEntradaFunc +
        //                         "&horarioSaidaFunc=" +
        //                         horarioSaidaFunc +
        //                         "&horarioIntervaloFunc=" +
        //                         horarioIntervaloFunc +
        //                         "&cargoFunc=" +
        //                         cargoFunc,

        //                     // dataType: 'json',
        //                     success: function(retorno) {
        //                         // console.log(retorno)


        //                     }
        //                 });





        //             }
        //         });
        //     });
        // });






        $('#table1').DataTable({
            language: {
                "sEmptyTable": "Nenhum registro encontrado",

                "sInfo": " _START_ até _END_ de _TOTAL_ registros...  ",

                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",

                "sInfoFiltered": "(Filtrados de _MAX_ registros)",

                "sInfoPostFix": "",

                "sInfoThousands": ".",

                "sLengthMenu": "_MENU_ resultados por página",

                "sLoadingRecords": "Carregando...",

                "sProcessing": "Processando...",

                "sZeroRecords": "Nenhum registro encontrado",

                "sSearch": "Pesquisar",

                "oPaginate": {

                    "sNext": "Próximo",

                    "sPrevious": "Anterior",

                    "sFirst": "Primeiro",

                    "sLast": "Último"

                },
            },
            dom: 'Bfrtip',
            scrollY: 450,
            scrollX: true,

            scrollXInner: "100%",
            scrollCollapse: true,
            searching: true,

            "paging": true,
            "info": false,
            "ordering": false,
            "lengthMenu": [
                [40],
            ],
            fixedColumns: {
                left: 4,
            },

            buttons: [
                // {
                //     extend: 'excelHtml5',
                //     exportOptions: {
                //         columns: [0, ':visible']
                //     }
                // },
                {
                    text: 'Escala Diaria',
                    className: 'btnverde',
                    action: function() {
                        window.location.href = "escalaDiaria.php";
                    }
                },
                {
                    text: 'Finalizar Escala',
                    className: 'btnVermelho btnverde',
                    action: function() {
                        criandoHtmlmensagemCarregamento("exibir");
                        var alteraStatusEscala = "F";
                        var usuarioLogado = $("#usuarioLogado").val();
                        var loja = $("#loja").val();

                        var mesPesquisa = $("#dataPesquisa").val();

                        var mesAtual = $("#mesAtual").val();

                        if (mesPesquisa == "") {
                            mesPesquisa = mesAtual
                        };



                        $.ajax({
                            url: "config/desabilita_ou_habilita_mensal.php",
                            method: 'POST',
                            data: "mesPesquisa=" +
                                mesPesquisa +
                                "&mesAtual=" +
                                mesAtual +
                                "&alteraStatusEscala=" +
                                alteraStatusEscala +
                                "&loja=" +
                                loja +
                                "&usuarioLogado=" +
                                usuarioLogado,
                            success: function(atualizaTabela1) {

                                $.ajax({
                                    url: "config/pesquisar_escalaMensal.php",
                                    method: 'POST',
                                    data: 'mesPesquisa=' +
                                        mesPesquisa +
                                        "&loja=" +
                                        loja +
                                        "&usuarioLogado=" +
                                        usuarioLogado,
                                    success: function(mes_Pesquisado) {

                                        $('.atualizaTabela').empty().html(mes_Pesquisado);
                                        criandoHtmlmensagemCarregamento("ocultar");
                                    }
                                });

                            }
                        });
                    }
                }, {
                    extend: 'excel',
                    className: 'btnverdeEXCEL',
                    text: '<i class="fa-solid fa-table" style="color: #ffffff;"></i> Excel ',
                    exportOptions: {
                        format: {
                            body: function(data, row, column, node) {
                                if ($(node).find('select[disabled]').length > 0) {
                                    return $(node).find('select[disabled]').val();
                                }
                                return data;
                            }
                        }
                    }

                }
            ],

        });
    </script>