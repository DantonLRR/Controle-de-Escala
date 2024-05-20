<?php
include "../../base/conexao_TotvzOracle.php";
include "../config/php/CRUD_geral.php";
include "../../base/conexao_martdb.php";

session_start();
$dadosFunc = new Funcionarios();
$loja = $_POST['loja'];
$Departamento = $_POST['Departamento'];
$buscaNomeFuncionario = $dadosFunc->informacoesOperadoresDeCaixa($TotvsOracle, $loja, $Departamento);
$usuarioLogado = $_SESSION['nome'];

?>

<input type="hidden" id="lojaDaPessoaLogada" value="<?= $loja ?>">
<div class="modal-content">
    <div style="background: linear-gradient(to right, #00a451, #052846 85%); color: white;font-weight:bold" class="modal-header">
        <h5 class="modal-title" id="modalFeriasLabel ">Agendamento de Ferias</h5>
        <button style="color:white" type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
<div style="border:15px  solid transparent">
    <label for="validationCustom02" class="form-label">Funcionário: </label>
    <select class="form-control margin-bottom" name="" id="funcionarioFerias">
        <option value="" disabled selected></option>
        <?php
        foreach ($buscaNomeFuncionario as $nomeFunc) : ?>

            <option value="<?= $nomeFunc['MATRICULA'] ?>"><?= $nomeFunc['NOME'] ?></option>

        <?php endforeach;
        ?>
    </select>
</div>
<input type="hidden" value="" id="cargo">
<input type="hidden" value="" id="horarioEntradaFunc">
<input type="hidden" value="" id="horarioSaidaFunc">
<input type="hidden" value="" id="horarioIntervaloFunc">
<input type="hidden" value="<?= $Departamento ?>" id="departamento">
<input type="hidden" value="<?= $usuarioLogado ?>" id="usuarioLogado">
<div style="border:15px  solid transparent" class="row">
    <div class="col-lg-6">
        <div class="mb-6">
            <label for="validationCustom02" class="form-label">Data inicial: </label>
            <input type="date" class="form-control dataPesquisa margin-bottom" value="" id="dataInicialFerias">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-6">
            <label for="validationCustom02" class="form-label">Data Final: </label>
            <input type="date" class="form-control dataPesquisa margin-bottom" value="" id="dataFinalFerias">
        </div>
    </div>
</div>
<script type="module">
    import {
        criandoHtmlmensagemCarregamento,
        Toasty
    } from "../../base/jsGeral.js";

    function ultimoDiaDoMes(ano, mes) {
        return new Date(ano, mes, 0).getDate();
    }

    function primeiroDiaDoMes(ano, mes) {
        return new Date(ano, mes - 1, 1).getDate();
    }
    $('#modalFerias').on('change', '#funcionarioFerias', function() {
        let funcionarioFeriasMatricula = $('#funcionarioFerias').val();
        let loja = $('#lojaDaPessoaLogada').val();
        let Departamento = $('#departamento').val();
        $.ajax({
            url: "Config/select_InformacaoUsuarioParaFerias.php",
            method: "POST",
            data: "funcionarioFeriasMatricula=" +
                funcionarioFeriasMatricula +
                "&loja=" +
                loja +
                "&Departamento=" +
                Departamento,
            dataType: 'json',
            success: function(retorno) {
                if (retorno === false || retorno === "false") {
                    Toasty("Atenção", "Erro ao obter informações do usuário por favor procure o administrador", "#E20914");
                } else {
                    $('#cargo').val(retorno.FUNCAO);
                    $('#horarioEntradaFunc').val(retorno.HORAENTRADA);
                    $('#horarioSaidaFunc').val(retorno.HORASAIDA);
                    $('#horarioIntervaloFunc').val(retorno.HORAINTERVALO);
                }
            },
        });
    })
    $('#modalFerias').on('click', '#salvarFerias', function() {
        let opcaoSelecionada = 'F';
        var dataInicialFerias = $('#dataInicialFerias').val();
        var dataFinalFerias = $('#dataFinalFerias').val();
        var funcionarioFerias = $('#funcionarioFerias').val();
        var NomefuncionarioFerias = $('#funcionarioFerias option:selected').text();
        var horarioEntradaFunc = $("#horarioEntradaFunc").val();
        var horarioSaidaFunc = $("#horarioSaidaFunc").val();
        var horarioIntervaloFunc = $("#horarioIntervaloFunc").val();
        var Departamento = $('#departamento').val();


        // console.log(" correto dataInicialFerias :" + dataInicialFerias);
        // console.log(" correto dataFinalFerias :" + dataFinalFerias);
        if (dataFinalFerias == '' || dataInicialFerias == '' || funcionarioFerias == '') {
            Toasty("Atenção", "Preencha todos os campos", "#E20914");
        } else if (dataInicialFerias >= dataFinalFerias) {
            Toasty("Atenção", "Data final das ferias nao pode ser menor que a data inicial", "#E20914");
        } else {
            criandoHtmlmensagemCarregamento("exibir");
            InsereNoBanco(
                opcaoSelecionada, dataInicialFerias,
                dataFinalFerias, funcionarioFerias, NomefuncionarioFerias, horarioEntradaFunc, horarioSaidaFunc,
                horarioIntervaloFunc, Departamento,
                function() {
                    $('#modalFerias').modal('show');
                    criandoHtmlmensagemCarregamento("exibir");
                    let loja = $('#lojaDaPessoaLogada').val();
                    let Departamento = $('#departamento').val();
                    $.ajax({
                        url: "modal/modalFerias.php",
                        method: 'POST',
                        data: 'Departamento=' +
                            Departamento +
                            "&loja=" +
                            loja,
                        success: function(modalFerias) {
                            $('.modal-content').empty().html(modalFerias);
                            criandoHtmlmensagemCarregamento("ocultar");
                            Toasty("Sucesso", "Férias do colaborador: " + NomefuncionarioFerias + " foram agendadas", "#00a550")
                        }
                    });
                }
            );
        }
    });
    $('#modalFerias').on('click', '#feriasAgendadas', function() {


        $('#AgendamentoFerias').addClass('ocultarBotao');
        $('#feriasAgendadas').addClass('ocultarBotao');
        $('#AgendamentoFerias').removeClass('ocultarBotao');

        criandoHtmlmensagemCarregamento("exibir");
        let loja = $('#lojaDaPessoaLogada').val();
        let Departamento = $('#departamento').val();
        $.ajax({
            url: "modal/modalCancelarFerias.php",
            method: 'POST',
            data: 'Departamento=' +
                Departamento +
                "&loja=" +
                loja,
            success: function(modalFerias) {
                $('.tabelaCancelamentoFerias').empty().html(modalFerias);
                criandoHtmlmensagemCarregamento("ocultar");
            }
        });

    })
    $('#modalFerias').on('click', '#AgendamentoFerias', function() {
        $('#AgendamentoFerias').addClass('ocultarBotao');
        $('#feriasAgendadas').removeClass('ocultarBotao');
        criandoHtmlmensagemCarregamento("exibir");
        let loja = $('#lojaDaPessoaLogada').val();
        let Departamento = $('#departamento').val();
        $.ajax({
            url: "modal/modalFerias.php",
            method: 'POST',
            data: 'Departamento=' +
                Departamento +
                "&loja=" +
                loja,
            success: function(modalFerias) {
                $('.modal-content').empty().html(modalFerias);
                criandoHtmlmensagemCarregamento("ocultar");
            }
        });
    })
    $('#modalFerias').on('click', '.fa-trash', function() {
        criandoHtmlmensagemCarregamento("exibir");
        let opcaoSelecionada = '';
        var $tr = $(this).closest('tr');
        // add trim
        var funcionarioFerias = $tr.find('td.matricula').text().trim();
        var NomefuncionarioFerias = $tr.find('td.funcionario').text().trim();

        var Departamento = $('#departamento').val();

        var horarioEntradaFunc = $tr.find('td.horarioEntradaFunc').text().trim();
        var horarioSaidaFunc = $tr.find('td.horarioSaidaFunc').text().trim();
        var horarioIntervaloFunc = $tr.find('td.horarioIntervaloFunc').text().trim();

        var dataInicialFerias = $tr.find('td.dataInicialFerias').text().trim();
        var dataFinalFerias = $tr.find('td.dataFinalFerias').text().trim();
        InsereNoBanco(
            opcaoSelecionada, dataInicialFerias,
            dataFinalFerias, funcionarioFerias, NomefuncionarioFerias, horarioEntradaFunc, horarioSaidaFunc,
            horarioIntervaloFunc, Departamento,
            function() {
                let loja = $('#lojaDaPessoaLogada').val();
                let Departamento = $('#departamento').val();
                $.ajax({
                    url: "modal/modalCancelarFerias.php",
                    method: 'POST',
                    data: 'Departamento=' +
                        Departamento +
                        "&loja=" +
                        loja,
                    success: function(modalFerias) {
                        $('.modal-content').empty().html(modalFerias);
                        criandoHtmlmensagemCarregamento("ocultar");
                        Toasty("Sucesso", "Férias do colaborador: " + NomefuncionarioFerias + " foram canceladas", "#00a550")

                    }
                });
            }
        );
    });

    function InsereNoBanco(opcaoSelecionadaPARAMETRO, dataInicialFeriasPARAMETRO, dataFinalFeriasPARAMETRO, funcionarioFeriasPARAMETRO, NomefuncionarioFeriasPARAMETRO, horarioEntradaFuncPARAMETRO, horarioSaidaFuncPARAMETRO,
        horarioIntervaloFuncPARAMETRO, DepartamentoPARAMETRO, sucessoCallback) {
        var opcaoSelecionada = opcaoSelecionadaPARAMETRO
        var dataInicialFerias = dataInicialFeriasPARAMETRO;
        var dataFinalFerias = dataFinalFeriasPARAMETRO;
        var funcionarioFerias = funcionarioFeriasPARAMETRO;
        var NomefuncionarioFerias = NomefuncionarioFeriasPARAMETRO;
        var horarioEntradaFunc = horarioEntradaFuncPARAMETRO;
        var horarioSaidaFunc = horarioSaidaFuncPARAMETRO;
        var horarioIntervaloFunc = horarioIntervaloFuncPARAMETRO;
        var Departamento = DepartamentoPARAMETRO;
        var remocaoDeFeriasProgramadas = ''
        var loja = $('#lojaDaPessoaLogada').val();
        var cargo = $("#cargo").val();
        var usuarioLogado = $('#usuarioLogado').val();
        let diaInicial = parseInt(dataInicialFerias.split('-')[2]); // Obtém o dia
        let mesInicial = parseInt(dataInicialFerias.split('-')[1]).toString().padStart(2, '0');
        let anoInicial = parseInt(dataInicialFerias.split('-')[0]); // Obtém o ano
        let diaFinal = parseInt(dataFinalFerias.split('-')[2]); // Obtém o dia
        let mesFinal = parseInt(dataFinalFerias.split('-')[1]).toString().padStart(2, '0');
        let anoFinal = parseInt(dataFinalFerias.split('-')[0]); // Obtém o ano
        let mesAnoFinal = anoFinal + "-" + mesFinal;
        let mesAnoInicial = anoInicial + "-" + mesInicial;
        let numeroDiaDaSemanaArrayParaInserirFerias = [];
        let numeroDiaDaSemanaArrayInsereNosDiasFaltantesDoProximoMes = [];

        let programaFerias = 'sim'
        let ultimoDiaDoMesInicial = ultimoDiaDoMes(anoInicial, mesInicial);
        let DataFinalDoMesInicial = mesAnoInicial + "-" + ultimoDiaDoMesInicial;


        let primeiroDiaDoMesFinal = mesAnoFinal + "-01";
        console.log("dataInicialFerias: " + dataInicialFerias);
        console.log("dataFinalFerias: " + dataFinalFerias);
        // console.log("DataFinalDoMesInicial: " + DataFinalDoMesInicial);
        // console.log("primeiroDiaDoMesFinal: " + primeiroDiaDoMesFinal);
        // Convertendo as strings em objetos de data
        // Convertendo as strings em objetos de data
        let dataInicialFeriasObj = new Date(
            parseInt(dataInicialFerias.substring(0, 4)), // ano
            parseInt(dataInicialFerias.substring(5, 7)) - 1, // mês (começa de 0)
            parseInt(dataInicialFerias.substring(8, 10)) // dia
        );
        let dataFinalFeriasObj = new Date(
            parseInt(dataFinalFerias.substring(0, 4)), // ano
            parseInt(dataFinalFerias.substring(5, 7)) - 1, // mês (começa de 0)
            parseInt(dataFinalFerias.substring(8, 10)) // dia
        );
        let DiaFinalDoMesInicialObj = new Date(
            parseInt(DataFinalDoMesInicial.substring(0, 4)), // ano
            parseInt(DataFinalDoMesInicial.substring(5, 7)) - 1, // mês (começa de 0)
            parseInt(DataFinalDoMesInicial.substring(8, 10)) // dia
        );
        let primeiroDiaDoMesFinalObj = new Date(
            parseInt(primeiroDiaDoMesFinal.substring(0, 4)), // ano
            parseInt(primeiroDiaDoMesFinal.substring(5, 7)) - 1, // mês (começa de 0)
            parseInt(primeiroDiaDoMesFinal.substring(8, 10)) // dia
        );

        console.log("dataInicialFerias OBJ: " + dataInicialFeriasObj);
        console.log("dataFinalFerias OBJ: " + dataFinalFeriasObj);
        // console.log("DiaFinalDoMesInicial OBJ: " + DiaFinalDoMesInicialObj);
        // console.log("primeiroDiaDoMesFinal OBJ: " + primeiroDiaDoMesFinalObj);
        if (opcaoSelecionada == '') {
            dataInicialFerias = '';
            dataFinalFerias = '';
            remocaoDeFeriasProgramadas = 'sim';
        }


        //se a data final for maior que o ultimo dia do mes ou seja aqui tratamos de casos que iniciam em um mes e terminam em outro
        if (dataFinalFeriasObj > DiaFinalDoMesInicialObj) {
            for (let i = diaInicial; i <= ultimoDiaDoMesInicial; i++) {
                let aux = i < 10 ? "0" + i : i.toString();
                numeroDiaDaSemanaArrayParaInserirFerias.push('"' + aux + '"');
                // console.log("Dia inserido : " + numeroDiaDaSemanaArrayParaInserirFerias);
            }

            $.ajax({
                url: "Config/insertEUpdate_EscalaMensal.php",
                method: 'get',
                data: 'numeroDiaDaSemana=' +
                    numeroDiaDaSemanaArrayParaInserirFerias +
                    "&opcaoSelecionada=" +
                    opcaoSelecionada +
                    "&funcionario=" +
                    NomefuncionarioFerias +
                    "&mesAtual=" +
                    mesAnoInicial +
                    "&mesPesquisa=" +
                    mesAnoInicial +
                    "&usuarioLogado=" +
                    usuarioLogado +
                    "&matriculaFunc=" +
                    funcionarioFerias +
                    "&loja=" +
                    loja +
                    "&horarioEntradaFunc=" +
                    horarioEntradaFunc +
                    "&horarioSaidaFunc=" +
                    horarioSaidaFunc +
                    "&horarioIntervaloFunc=" +
                    horarioIntervaloFunc +
                    "&cargoFunc=" +
                    cargo +
                    "&departamentoFunc=" +
                    Departamento +
                    "&dataInicialFerias=" +
                    dataInicialFerias +
                    "&dataFinalFerias=" +
                    dataFinalFerias +
                    "&remocaoDeFeriasProgramadas=" +
                    remocaoDeFeriasProgramadas +
                    "&programaFerias=" +
                    programaFerias,
                success: function(retorno) {
                    criandoHtmlmensagemCarregamento("ocultar");
                }
            });

            if (primeiroDiaDoMesFinalObj <= dataFinalFeriasObj) {
                //se dia 01 do mes inicial for menor ou igual a data final das ferias

                for (let i = "1"; i <= diaFinal; i++) {
                    let aux = i < 10 ? "0" + i : i.toString();
                    numeroDiaDaSemanaArrayInsereNosDiasFaltantesDoProximoMes.push('"' + aux + '"');
                    // console.log("Dia inserido final : " + numeroDiaDaSemanaArrayInsereNosDiasFaltantesDoProximoMes);
                }
                $.ajax({
                    url: "Config/insertEUpdate_EscalaMensal.php",
                    method: 'get',
                    data: 'numeroDiaDaSemana=' +
                        numeroDiaDaSemanaArrayInsereNosDiasFaltantesDoProximoMes +
                        "&opcaoSelecionada=" +
                        opcaoSelecionada +
                        "&funcionario=" +
                        NomefuncionarioFerias +
                        "&mesAtual=" +
                        mesAnoFinal +
                        "&mesPesquisa=" +
                        mesAnoFinal +
                        "&usuarioLogado=" +
                        usuarioLogado +
                        "&matriculaFunc=" +
                        funcionarioFerias +
                        "&loja=" +
                        loja +
                        "&horarioEntradaFunc=" +
                        horarioEntradaFunc +
                        "&horarioSaidaFunc=" +
                        horarioSaidaFunc +
                        "&horarioIntervaloFunc=" +
                        horarioIntervaloFunc +
                        "&cargoFunc=" +
                        cargo +
                        "&departamentoFunc=" +
                        Departamento +
                        "&dataInicialFerias=" +
                        dataInicialFerias +
                        "&dataFinalFerias=" +
                        dataFinalFerias +
                        "&remocaoDeFeriasProgramadas=" +
                        remocaoDeFeriasProgramadas +
                        "&programaFerias=" +
                        programaFerias,
                    success: function(retorno) {
                        criandoHtmlmensagemCarregamento("ocultar");
                    }
                });
            }
            $.ajax({
                url: "config/pesquisar_escalaMensal.php",
                method: 'POST',
                data: 'mesPesquisa=' +
                    mesAnoInicial +
                    "&loja=" +
                    loja +
                    "&usuarioLogado=" +
                    usuarioLogado,
                success: function(mes_Pesquisado) {
                    $('.atualizaTabela').empty().html(mes_Pesquisado);
                    criandoHtmlmensagemCarregamento("ocultar");
                }
            });
        } else {
            for (let i = diaInicial; i <= diaFinal; i++) {
                let aux = i < 10 ? "0" + i : i.toString();
                numeroDiaDaSemanaArrayParaInserirFerias.push('"' + aux + '"');
                // console.log("Dia inserido : " + numeroDiaDaSemanaArrayParaInserirFerias);
            }
            $.ajax({
                url: "Config/insertEUpdate_EscalaMensal.php",
                method: 'get',
                data: 'numeroDiaDaSemana=' +
                    numeroDiaDaSemanaArrayParaInserirFerias +
                    "&opcaoSelecionada=" +
                    opcaoSelecionada +
                    "&funcionario=" +
                    NomefuncionarioFerias +
                    "&mesAtual=" +
                    mesAnoInicial +
                    "&mesPesquisa=" +
                    mesAnoInicial +
                    "&usuarioLogado=" +
                    usuarioLogado +
                    "&matriculaFunc=" +
                    funcionarioFerias +
                    "&loja=" +
                    loja +
                    "&horarioEntradaFunc=" +
                    horarioEntradaFunc +
                    "&horarioSaidaFunc=" +
                    horarioSaidaFunc +
                    "&horarioIntervaloFunc=" +
                    horarioIntervaloFunc +
                    "&cargoFunc=" +
                    cargo +
                    "&departamentoFunc=" +
                    Departamento +
                    "&dataInicialFerias=" +
                    dataInicialFerias +
                    "&dataFinalFerias=" +
                    dataFinalFerias +
                    "&remocaoDeFeriasProgramadas=" +
                    remocaoDeFeriasProgramadas +
                    "&programaFerias=" +
                    programaFerias,
                success: function(retorno) {
                    criandoHtmlmensagemCarregamento("ocultar");
                }
            });
            $.ajax({
                url: "config/pesquisar_escalaMensal.php",
                method: 'POST',
                data: 'mesPesquisa=' +
                    mesAnoInicial +
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
        sucessoCallback();
    }
</script>