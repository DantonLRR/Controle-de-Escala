<?php
include "../../base/conexao_martdb.php";
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
$dadosDeQuemEstaLogadoNome = '';
$dadosDeQuemEstaLogadoFuncao = '';
$dadosDeQuemEstaLogadoSetor = '';

foreach ($verificaSeAPessoaLogadaEEncarregada as $rowVerificaEncarregado) :
    $dadosDeQuemEstaLogadoNome =  $rowVerificaEncarregado['NOME'];
    $dadosDeQuemEstaLogadoFuncao = $rowVerificaEncarregado['FUNCAO'];
    $dadosDeQuemEstaLogadoFuncaoSeLider = $rowVerificaEncarregado['FUNCAOLIDER'];

    $dadosDeQuemEstaLogadoSetor =  $rowVerificaEncarregado['DEPARTAMENTO2'];

    if ($dadosDeQuemEstaLogadoFuncaoSeLider == "LIDER DE") {
        $dadosDeQuemEstaLogadoSetor = str_replace("LIDER DE ", "", $dadosDeQuemEstaLogadoSetor);
    }
    $buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja, $dadosDeQuemEstaLogadoSetor);


    $verificaSeJaExistemDados = $verifica->verificaSeAEscalaMensalEstaFinalizada($oracle, $dataSelecionadaNoFiltro, $loja, $dadosDeQuemEstaLogadoSetor);
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
    <input class="" type="hidden" id="" value="<?= $dadosDeQuemEstaLogadoNome ?>">
    <input class="" type="hidden" id="" value="<?= $dadosDeQuemEstaLogadoFuncao ?>">
    <input id="dadosDeQuemEstaLogadoSetor" class="" type="hidden" id="" value="<?= $dadosDeQuemEstaLogadoSetor ?>">

    <table id="table1" class="stripe row-border order-column table table-bordered table-striped text-center row-border" style="width:100%">
        <thead style="background: linear-gradient(to right, #00a451, #052846 85%) !important; color:white;">

            <tr class="trr ">
                <th class="text-center theadColor" scope="row" style="width:150px">Funcionario</th>
                <th class="text-center theadColor">Cargo</th>
                <th class="text-center theadColor" style="display:none">Situação</th>
                <th class="text-center" style="display:none">departamento</th>
                <th class="text-center" style="display:none">Entrada</th>
                <th class="text-center" style="display:none">Saida</th>
                <th class="text-center" style="display:none">Intervalo</th>
                <th class="text-center theadColor" scope="row" style="width:150px ;">matricula</th>
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





            <tr class="trr fixed-row" id="quantDias">
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
                $recuperacaoDedados2 = $verifica->verificaSeOMesSelecionadoTemAlgumFuncionarioEscalado($oracle, $dataSelecionadaNoFiltro, $loja, $dadosDeQuemEstaLogadoSetor);
                // ECHO $retorno1;
                if ($retorno1 == "NÃO EXISTE CADASTRO.") {
                    $statusDaTabelaPosPesquisa = "NÃO FINALIZADA.";
                }
            ?>
                <tr class="trr">
                    <td class="text-center funcionario" scope="row"><?= $nomeFunc['NOME'] ?></td>
                    <td class="text-center cargo" scope="row"><?= $nomeFunc['FUNCAO'] ?></td>
                    <td class="text-center situacao" scope="row" style="display:none"><?= $nomeFunc['SITUACAO'] ?></td>
                    <td class="text-center departamento" scope="row" style="display:none"><?= $nomeFunc['DEPARTAMENTO'] ?></td>
                    <td class="text-center horarioEntradaFunc" style="display:none" scope="row"><?= $nomeFunc['HORAENTRADA'] ?></td>
                    <td class="text-center horarioSaidaFunc" style="display:none" scope="row"><?= $nomeFunc['HORASAIDA'] ?></td>
                    <td class="text-center horarioIntervaloFunc" style="display:none" scope="row"><?= $nomeFunc['SAIDAPARAALMOCO'] ?></td>
                    <td class="text-center matriculaFunc" scope="row"><?= $nomeFunc['MATRICULA'] ?></td>


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
                            $isF = ($recuperacaoDedados[0]["$d"] ?? '') === 'F';
                            if ($isF) {
                                $disabled = ' disabled  name="desabilitarEsteSelect"';
                            } else {
                                $disabled = '';
                            }
                            // echo $disabled;
                            $DadoDoDiaSalVoNoBancoDeDados = $recuperacaoDedados[0]["$d"] ?? '';
                            ?>
                            <select <?= $disabled ?> class="estilezaSelect" name="" id="" style="width:52px !important;">
                                <option value="<?= $DadoDoDiaSalVoNoBancoDeDados ?? '' ?>"> <?= $DadoDoDiaSalVoNoBancoDeDados ?? '' ?></option>
                                <!-- Se o dado -->
                                <option value="DSR" <?= $DadoDoDiaSalVoNoBancoDeDados == 'DSR' ? "style='display: none'" : "" ?>>DSR - DESCANSO SEMANAL REMUNERADO </option>
                                <option value="FA" <?= $DadoDoDiaSalVoNoBancoDeDados == 'FA' ? "style='display: none'" : "" ?>>FA - FOLGA ABONADA</option>
                                <!-- <option value="FD" <//= $DadoDoDiaSalVoNoBancoDeDados == 'FD' ? "style='display: none'" : "" ?>>FD – FOLGA DOMINGO </option> -->
                                <option value="FF" <?= $DadoDoDiaSalVoNoBancoDeDados == 'FF' ? "style='display: none'" : "" ?>>FF - FOLGA FERIADO</option>
                                <option value="T" <?= $DadoDoDiaSalVoNoBancoDeDados == '' ? "style='display: none'" : "" ?>></option>

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
        var Departamento = $('#dadosDeQuemEstaLogadoSetor').val();
        var mesAtual = $("#mesAtual").val();
        $(document).ready(function() {
            var colunas = document.querySelectorAll('.diaDaSemana');
            colunas.forEach(function(coluna) {
                var indexDomingo = $(coluna).index();
                if ($(coluna).text().includes("DOM")) {
                    $('tr').each(function() {
                        $(this).find('td:eq(' + indexDomingo + ')').addClass('domingo');
                    });
                }
            });
        });


        var loja = $("#loja").val();
        var usuarioLogado = $("#usuarioLogado").val();
        var dataPesquisa = $(".dataSelecionadaNoFiltro").val();
        import {
            criandoHtmlmensagemCarregamento,
            Toasty
        } from "../../../../base/jsGeral.js";
        $(document).ready(function() {
            var dataPesquisa = $(".dataSelecionadaNoFiltro").val();
            var mesAtual = $("#mesAtual").val();
            if (dataPesquisa < mesAtual) {
                $('.estilezaSelect').css('background-color', 'grey');
                $('#table1').find('select').prop('disabled', true);
                $('.btnVermelho').addClass('ocultarBotao');
                $('.btnverdeEXCEL').addClass('ocultarBotao');
            } else {
                $('.estilezaSelect').prop('disabled', false);
                $('.estilezaSelect').css('background-color', '');
            }
            var statusDaTabelaPosPesquisa = $("#statusDaTabelaPosPesquisa").val();

            if (statusDaTabelaPosPesquisa === "JÁ FINALIZADA.") {
                criandoHtmlmensagemCarregamento("exibir");
                $('#table1').find('select').prop('disabled', true);
                $('.btnVermelho').addClass('ocultarBotao');
                $('.btnverdeEXCEL').removeClass('ocultarBotao');
                criandoHtmlmensagemCarregamento("ocultar");
            } else {
                criandoHtmlmensagemCarregamento("exibir")
                $('#table1').find('select').prop('disabled', false);
                $('select[name="desabilitarEsteSelect"]').prop('disabled', true);
                $('.btnVermelho').removeClass('ocultarBotao');
                $('.btnverdeEXCEL').addClass('ocultarBotao');
                criandoHtmlmensagemCarregamento("ocultar");
            }
        });

        var rows = $("#table1 tr");

        rows.on('click', function() {
            rows.removeClass("selected");
            $(this).addClass("selected");
        });
        $(document).ready(function() {
            $('#table1').on('click', '.estilezaSelect', function() {

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
                // console.log(THDoSelectAtual)


                // Calcular a quantidade de selects em branco entre o último e o atual
                var selectsEmBrancoEntre = indexAtual - indexUltimoPreenchido - 1;

                var thDoUltimoSelectPreenchido = THDoSelectAtual - selectsEmBrancoEntre - 1

                // console.log("th Do Ultimo Select Preenchido:  " + thDoUltimoSelectPreenchido)
                // console.log('Selects em branco entre o último selecionado e o atual: ' + selectsEmBrancoEntre);

                var selectsEmBrancoEntreOProximo = indexProximoPreenchido - indexAtual - 1;
                // console.log('Selects em branco entre próximo selecionado e o atual: ' + selectsEmBrancoEntreOProximo);

                var thDoProximoSelectPreenchido = parseInt(THDoSelectAtual) + selectsEmBrancoEntreOProximo + 1;
                if (isNaN(thDoProximoSelectPreenchido)) {
                    thDoProximoSelectPreenchido = 0;
                }
                // console.log("th Do Proximo Select Preenchido: " + thDoProximoSelectPreenchido);



                if (selectsEmBrancoEntre >= 7) {
                    PeriodoMaximoDeDiasTrabalhados = true
                }
                var valorINICIAL = $(this).val();
                $(this).off('change').on('change', function() {
                    var periodoParaEdicaoDeEscala = parseInt(thDoProximoSelectPreenchido) - thDoUltimoSelectPreenchido
                    var opcaoSelecionada = $(this).val();
                    // alert(valorINICIAL)
                    // alert(opcaoSelecionada)
                    if (valorINICIAL != 'F' && opcaoSelecionada != 'F' || valorINICIAL == '' && opcaoSelecionada != 'F') {
                        // console.log('Valor INICIAL: ' + valorINICIAL);
                        // console.log('opcao Escolhida :' + opcaoSelecionada)
                        // console.log("caiu na segunda");
                        var opcaoSelecionada = $(this).val();
                        if (opcaoSelecionada == 'T') {
                            opcaoSelecionada = ''
                        }
                        var $tr = $(this).closest('tr');
                        var funcionario = $tr.find('td.funcionario').text();
                        var matriculaFunc = $tr.find('td.matriculaFunc').text();
                        var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text();
                        var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text();
                        var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text();
                        var cargoFunc = $tr.find('td.cargo').text();
                        //var departamentoFunc = $tr.find('td.departamento').text();
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
                            //  alert("(PeriodoMaximoDeDiasTrabalhados >=7")
                        } else if (opcaoSelecionada == '' && periodoParaEdicaoDeEscala >= 7) {
                            $(this).val(opcaoSelecionadaAux);
                            Toasty("Atenção", "Funcionario escalado sem folga mais de SEIS dias", "#E20914");
                            //  alert("opcaoSelecionada == '' && periodoParaEdicaoDeEscala >= 7")
                        } else {
                            //  alert("caiu no ajax")
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
                                    Departamento,
                                // dataType: 'json',
                                success: function(retorno) {
                                    // console.log(retorno)
                                }
                            });
                        }

                    }
                    var textoOpcaoSelecionado = $(this).find('option:selected'); // Obtém a opção selecionada
                    var texto = textoOpcaoSelecionado.text().substring(0, 3); // Trunca o texto para os primeiros 3 caracteres
                    textoOpcaoSelecionado.text(texto); // Define o texto truncado na opção
                });
            });
        });


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
            "info": true,
            "ordering": false,
            "lengthMenu": [
                [100],
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
                    text: 'Escala De Horários',
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

                        var loja = $("#loja").val();

                        var mesPesquisa = $("#dataPesquisa").val();



                        if (mesPesquisa == "") {
                            mesPesquisa = mesAtual
                        };


                        //  Array para armazenar as matrículas
                        var matriculas = [];

                        //  Itera sobre cada linha da tabela exceto a primeira (cabeçalho)
                        $('#table1 tbody tr').each(function() {
                            //  Recupera a matrícula da célula oculta
                            var matricula = $(this).find('.matriculaFunc').text().trim();
                            //  Adiciona a matrícula ao array se não estiver vazia
                            if (matricula !== "") {
                                matriculas.push(matricula);
                            }
                        });

                        //  Agora, matriculas[] contém todas as matrículas da tabela
                        // console.log(matriculas);
                        $.ajax({
                            method: 'POST',
                            url: "config/verificacao_finalizacao_escala_mensal.php",
                            dataType: 'json',
                            data: "mesPesquisa=" +
                                mesPesquisa +
                                "&mesAtual=" +
                                mesAtual +
                                "&alteraStatusEscala=" +
                                alteraStatusEscala +
                                "&loja=" +
                                loja +
                                "&matriculas=" +
                                matriculas +
                                "&usuarioLogado=" +
                                usuarioLogado +
                                "&Departamento=" +
                                Departamento,

                            success: function(retornoVerificacao) {
                                // alert();
                                // alert(retornoVerificacao.ESCALALIBERADAPARAFINALIZACAO); // Exibe true ou false
                                // alert(retornoVerificacao.MENSAGEM); // Exibe a mensagem retornada
                                // alert(retornoVerificacao.MENSAGEM);
                                if (retornoVerificacao.ESCALALIBERADAPARAFINALIZACAO == false) {
                                    // Toasty("Atenção", retornoVerificacao.MENSAGEM, "#E20914");
                                    criandoHtmlmensagemCarregamento("ocultar");
                                    // alert()

                                    $('#modalFerias').modal('show')
                                    $('#feriasAgendadas').addClass('ocultarBotao');
                                    $('#salvarFerias').addClass('ocultarBotao');
                                    $('#AgendamentoFerias').addClass('ocultarBotao');
                                    $.ajax({
                                        url: "modal/modalFinalizacaoEscala.php",
                                        method: 'POST',
                                        data: 'Mensagem=' +
                                            retornoVerificacao.MENSAGEM,
                                        success: function(modalFerias) {
                                            $('.cadastroFerias').empty()
                                            $('.cadastroFerias').empty().html(modalFerias);
                                            criandoHtmlmensagemCarregamento("ocultar");
                                        }
                                    });
                                } else {
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
                                            "&matriculas=" +
                                            matriculas +
                                            "&usuarioLogado=" +
                                            usuarioLogado +
                                            "&Departamento=" +
                                            Departamento,
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

                            }
                        });





                    }
                },
                {
                    text: '<i class="fa-solid fa-file-pdf" style="color: #ffffff;"></i> PDF ',
                    className: ' btnverdeEXCEL',
                    action: function() {
                        criandoHtmlmensagemCarregamento("exibir");

                        var loja = $("#loja").val();

                        var mesPesquisa = $("#dataPesquisa").val();

                        var mesAtual = $("#mesAtual").val();
                        var diretorioDoPdf = "contrato.php";
                        if (mesPesquisa == "") {
                            mesPesquisa = mesAtual
                        };

                        $.ajax({
                            url: "config/gerarPdf.php",
                            method: "POST",
                            data: 'mesPesquisa=' +
                                mesPesquisa +
                                "&loja=" +
                                loja +
                                "&usuarioLogado=" +
                                usuarioLogado +
                                "&Departamento=" +
                                Departamento +
                                "&diretorioDoPdf=" +
                                diretorioDoPdf,
                            xhrFields: {
                                responseType: "blob",
                            },
                            success: function(response) {
                                // Loading("ocultar");
                                criandoHtmlmensagemCarregamento("ocultar");
                                let blobUrl = URL.createObjectURL(response);
                                window.open(blobUrl, "_blank");
                            },
                            error: function(xhr, status, error) {
                                // console.log(error);
                                // Loading("ocultar");
                            },
                        });

                    }


                },
                {
                    text: '<i class="fa-solid fa-calendar" style="color: #ffffff;"></i> Lançamento De Ferias ',
                    className: 'btnVermelho btnverde',
                    action: function() {
                        criandoHtmlmensagemCarregamento("exibir");
                        $('#modalFerias').modal('show');

                        var loja = $('#loja').val();
                        $.ajax({
                            url: "modal/modalFerias.php",
                            method: 'POST',
                            data: 'Departamento=' +
                                Departamento +
                                "&loja=" +
                                loja,
                            success: function(modalFerias) {
                                $('.cadastroFerias').empty().html(modalFerias);
                                criandoHtmlmensagemCarregamento("ocultar");
                            }
                        });
                    }
                }
                // {
                //     extend: 'excel',
                //     className: 'btnverdeEXCEL',
                //     text: '<i class="fa-solid fa-table" style="color: #ffffff;"></i> Excel ',
                //     exportOptions: {
                //         format: {
                //             body: function(data, row, column, node) {
                //                 if ($(node).find('select[disabled]').length > 0) {
                //                     return $(node).find('select[disabled]').val();
                //                 }
                //                 return data;
                //             }
                //         }
                //     }

                // }
            ],

        });
    </script>