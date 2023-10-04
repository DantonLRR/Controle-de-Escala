<?php
include "../../base/Conexao_teste.php";
$nomeFunc = $_GET['nomeSelecionado'];

$sql="select * from HorariosFuncControleDeEscala a 
where a.nome ='$nomeFunc'";

$parse=ociparse($oracle,$sql);
oci_execute($parse);

    while (($row= oci_fetch_assoc($parse))!=false) {
        $array_valor = array(
            'matricula' =>$row['MATRICULA'],
            'horaEntrada' => $row['HORAENTRADA'],
            'horaSaida' => $row['HORASAIDA'],
            'horaIntervalo' => $row['HORAINTERVALO']
        );
    
        echo json_encode($array_valor);
    }


?>