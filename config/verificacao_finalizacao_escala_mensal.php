<?php
include "../../base/conexao_martdb.php";
include "../../base/conexao_TotvzOracle.php";
include "php/CRUD_geral.php";

$mesAtual = $_POST['mesAtual'];
$mesPesquisado = $_POST['mesPesquisa'];
$usuarioLogado = $_POST['usuarioLogado'];
$loja = $_POST['loja'];
$Departamento = $_POST['Departamento'];
$status = $_POST['alteraStatusEscala'];
$matriculas = $_POST['matriculas'];

$escalaLiberadaParaFinalizacao = false;
$matriculas_array = explode(',', $matriculas);

$verifica = new Verifica();
$matriculaFuncionarioNaoEscalado = [];
$nomeFuncionariosComEscalaIncompleta = "";
$indiceAUX = 0;
foreach ($matriculas_array as $i => $rowMatricula) {
    $verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga = $verifica->verificaPessoaNaEscalaMensal($oracle, $matriculas_array[$i], $mesPesquisado, $loja, $Departamento);
    if (count($verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga) == 0) {
        $escalaLiberadaParaFinalizacao = false;
        $matriculaFuncionarioNaoEscalado[$indiceAUX] = $rowMatricula;
        $indiceAUX++;
    } else {
        foreach ($verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga as $rowDadosFunc) :
            $escalaLiberadaParaFinalizacao = false;
            if ($rowDadosFunc['VALIDA'] == 'ALERTA') {
                // echo "<br> entrou <br><br><br>";
                $nomeFuncionariosComEscalaIncompleta .= $rowDadosFunc['NOME'] . ", ";
            } else {
                $escalaLiberadaParaFinalizacao = true;
            }
        endforeach;
    }
}
if (!empty($matriculaFuncionarioNaoEscalado)) {
    //  var_dump($matriculaFuncionarioNaoEscalado);
    $nomeFuncionarioNaoEscalado = "";
    //   echo "<br><br><br><br>";
    foreach ($matriculaFuncionarioNaoEscalado as $i => $rowMatriculaFuncNaoEscalado) :
        $verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga = (array)$verifica->verificaNomeDaPessoaComBaseNaMatricula($TotvsOracle, $rowMatriculaFuncNaoEscalado);
        // var_dump($verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga);
        // echo "<br><br><br><br>";
        $nomeFuncionarioNaoEscalado .= $verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga[0]['NOME'] . ", ";
        $indiceAUX++;
    endforeach;
}

if (!empty($matriculaFuncionarioNaoEscalado) && !empty($nomeFuncionariosComEscalaIncompleta)) {
    $escalaLiberadaParaFinalizacao = false;
    // echo $nomeFuncionariosComEscalaIncompleta ."<br>";
    // echo $nomeFuncionarioNaoEscalado ."<br>";
    $array_valor = array(
        'MENSAGEM' => "Impossível finalizar escala, funcionário(s): " . $nomeFuncionariosComEscalaIncompleta . "está um período de 7 dias ou mais sem folgar, " . $nomeFuncionarioNaoEscalado . " não escalado(s).",
        'ESCALALIBERADAPARAFINALIZACAO' => $escalaLiberadaParaFinalizacao
    );
    echo json_encode($array_valor);
    exit; // Encerra a execução após enviar o JSON
} else if (!empty($matriculaFuncionarioNaoEscalado)) {
    $escalaLiberadaParaFinalizacao = false;
    //  var_dump($matriculaFuncionarioNaoEscalado);
    // $nomeFuncionarioNaoEscalado = "";
    //   echo "<br><br><br><br>";
    // foreach ($matriculaFuncionarioNaoEscalado as $i => $rowMatriculaFuncNaoEscalado) :
    //     $verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga = (array)$verifica->verificaNomeDaPessoaComBaseNaMatricula($TotvsOracle, $rowMatriculaFuncNaoEscalado);
    // var_dump($verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga);
    // echo "<br><br><br><br>";
    //     $nomeFuncionarioNaoEscalado .= $verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga[0]['NOME'] . ", ";
    //     $indiceAUX++;
    // endforeach;
    //  echo "<br><br><br><br>";
    //  var_dump($nomeFuncionarioNaoEscalado);
    //  echo "<br><br><br><br>";
    $array_valor = array(
        'MENSAGEM' => "Impossível finalizar escala, funcionário(s): " . $nomeFuncionarioNaoEscalado . " não escalado(s).",
        'ESCALALIBERADAPARAFINALIZACAO' => $escalaLiberadaParaFinalizacao
    );
    echo json_encode($array_valor);
    exit; // Encerra a execução após enviar o JSON
} else if (!empty($nomeFuncionariosComEscalaIncompleta)) {
    // var_dump($nomeFuncionariosComEscalaIncompleta);
    // echo "<br><br><br><br>";
    $escalaLiberadaParaFinalizacao = false;
    $array_valor = array(
        'MENSAGEM' => "Impossível finalizar escala, funcionário(s): " . $nomeFuncionariosComEscalaIncompleta . "está um período de 7 dias ou mais sem folgar.",
        'ESCALALIBERADAPARAFINALIZACAO' => $escalaLiberadaParaFinalizacao
    );
    echo json_encode($array_valor);
    exit; // Encerra a execução após enviar o JSON
} else if ($escalaLiberadaParaFinalizacao) {
    $array_valor = array(
        'MENSAGEM' => "Escala Finalizada com sucesso! Caso precise procure DP ou o RH da sua loja.",
        'ESCALALIBERADAPARAFINALIZACAO' => $escalaLiberadaParaFinalizacao
    );
     echo json_encode($array_valor);
     exit;
}
















































// foreach ($matriculas_array as $i => $rowMatricula) {

//     $verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga = $verifica->verificaPessoaNaEscalaMensal($oracle, $matriculas_array[$i], $mesPesquisado, $loja, $Departamento);
//     // var_dump($verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga);
//     if (count($verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga) == 0) {
//         $escalaLiberadaParaFinalizacao = false;
//         $array_valor = array(
//             'MENSAGEM' => "Impossível finalizar escala, matricula " .  $rowMatricula . " não escalada.",
//             'ESCALALIBERADAPARAFINALIZACAO' => $escalaLiberadaParaFinalizacao
//         );
//         echo json_encode($array_valor);
//         exit; // Encerra a execução após enviar o JSON
//     } else {
//         foreach ($verificaSeAPessoaFoiInseridaESeNaoTemMaisDe7DiasSemFolga as $rowDadosFunc) :
//             if ($rowDadosFunc['VALIDA'] == 'ALERTA') {
//                 $escalaLiberadaParaFinalizacao = false;
//                 $array_valor = array(
//                     'MENSAGEM' => "Impossível finalizar escala, funcionário: " . $rowDadosFunc['NOME'] . " ficou um período de 7 dias ou mais sem folgar.",
//                     'ESCALALIBERADAPARAFINALIZACAO' => $escalaLiberadaParaFinalizacao
//                 );
//                 echo json_encode($array_valor);
//                 exit; // Encerra a execução após enviar o JSON
//             } else {
//                 $escalaLiberadaParaFinalizacao = true;
//             }
//         endforeach;
//     }
// }

// if ($escalaLiberadaParaFinalizacao) {
//     $array_valor = array(
//         'MENSAGEM' => "Todos atenderam.",
//         'ESCALALIBERADAPARAFINALIZACAO' => $escalaLiberadaParaFinalizacao
//     );
//     echo json_encode($array_valor);
// }
