<?php
class dados
{
    public function buscandoPermissaoMP($oracle)
    {
        $lista = array();
        $query = "SELECT * FROM WEB_NIVELPERMISSAOMP c
        order by c.descricao asc
         ";

        // echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function buscandoCargosDisponiveis($oracle)
    {
        $lista = array();
        $query = "SELECT DISTINCT PFUNCAO.NOME AS CARGO
        FROM PFUNC
        INNER JOIN PPESSOA ON PFUNC.CODPESSOA = PPESSOA.CODIGO
        INNER JOIN PFRATEIOFIXO ON PFUNC.CODCOLIGADA = PFRATEIOFIXO.CODCOLIGADA AND PFUNC.CHAPA = PFRATEIOFIXO.CHAPA
        INNER JOIN PCCUSTO ON PFRATEIOFIXO.CODCOLIGADA = PCCUSTO.CODCOLIGADA AND PFRATEIOFIXO.CODCCUSTO = PCCUSTO.CODCCUSTO
        INNER JOIN PFUNCAO ON PFUNC.CODCOLIGADA = PFUNCAO.CODCOLIGADA AND PFUNC.CODFUNCAO = PFUNCAO.CODIGO
        INNER JOIN PSECAO ON PFUNC.CODCOLIGADA = PSECAO.CODCOLIGADA AND PFUNC.CODSECAO = PSECAO.CODIGO
        LEFT JOIN (
            SELECT PFHSTFCO.CODCOLIGADA, PFHSTFCO.CHAPA, MAX(PFHSTFCO.DTMUDANCA) AS DATAMUDANCA
            FROM PFHSTFCO
            WHERE PFHSTFCO.CODCOLIGADA = 1
            GROUP BY PFHSTFCO.CODCOLIGADA, PFHSTFCO.CHAPA
        ) FUNCAO ON PFUNC.CODCOLIGADA = FUNCAO.CODCOLIGADA AND PFUNC.CHAPA = FUNCAO.CHAPA
        WHERE PFUNC.CODCOLIGADA = 1
        -- AND PFUNC.CODSITUACAO <> 'D'
        -- AND PFUNC.CHAPA = 106511
        AND PSECAO.Codigo NOT IN ('003', '003.101', '003.106', '003.150', '003.200', '003.201', '003.999')
        order by CARGO";

        // echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function buscandoUsuariosPermitidos($oracle, $nivel)
    {
       $lista = array();
        $query = "select C.ID,
        C.USUARIO,
        C.SEQUSUARIO,
        C.CPF,
        C.NIVEL,
        C.STATUS,
        C.USUARIOINCLUSAO 
        from weboficial.WEB_PERMISSAOUSUARIOMP c
       where  c.nivel = '$nivel'
       ORDER BY C.USUARIO";
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function buscandoUsuarios($oracle)
    {
        $lista = array();
        $query = "select distinct a.codusuario, A.SEQUSUARIO, a.nome, a.nivel, a.nroempresa, A.LOGINID
        from CONSINCO.ge_usuario a
       WHERE A.NIVEL != 0
       and a.loginid is not null
         and TRIM(a.sequsuario) not in
             (select TRIM(C.SEQUSUARIO)
                from WEB_PERMISSAOUSUARIOMP c
              )
       order by a.nome";

        // echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function buscandoCargosComRisco($oracle)
    {
        $lista = array();
        $query = "SELECT DISTINCT A.CARGO, A.ID FROM WEB_CARGORISCO A
        WHERE A.STATUS = 'A'
        ORDER BY A.CARGO";

        // echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function buscandoCargosInativos($oracle)
    {
        $lista = array();
        $query = "SELECT DISTINCT A.CARGO, A.ID FROM web_CARGOSINATIVOS A
        WHERE A.STATUS = 'A'
        ORDER BY A.CARGO";

        // echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function TiposMovimentacao($oracle)
    {
        $lista = array();
        $query = "SELECT * FROM WEB_TIPODEMOVIMENTACAO A";

        // echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
    public function buscandoTipoMovimentacao($oracle, $status)
    {
        $lista = array();
        $query = "SELECT * FROM WEB_TIPODEMOVIMENTACAO A
        WHERE A.STATUS = '$status'";

        // echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
}
class Verificacao
{
    public function VerificandoRiscoMP($oracle, $CargoProposta, $tipoDeMovimentacao)
    {
        $lista = array();
        $query = "select b.web_tipodemovimentacao
        from web_tipodemovimentacao b
        where TRIM(b.ID) = TRIM('$tipoDeMovimentacao')
         AND B.STATUS = 'R'
        union
        SELECT A.CARGO
        FROM WEB_CARGORISCO A
        WHERE A. STATUS = 'A'
        AND TRIM(A.CARGO) = TRIM('$CargoProposta')";

        // echo  $query;

        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        while ($row = oci_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
}
class aprovacaoMp
{
    public function AprovacaoRemuneracao($oracle, $REMUNERACAO_STATUS, $RECRUTAMENTO_STATUS, $QSMS_STATUS, $TREINAMENTO_STATUS, $usuario, $ID)
    {
        $query = "UPDATE WEB_MOVIMENTACAOPESSOAL
        SET REMUNERACAO_STATUS = '$REMUNERACAO_STATUS',
            RECRUTAMENTO_STATUS = '$RECRUTAMENTO_STATUS',
            QSMS_STATUS = '$QSMS_STATUS',
            TREINAMENTO_STATUS = '$TREINAMENTO_STATUS',
            REMUNERACAO_ASSINATURA = '$usuario',
            DTAHORA_REMUNERACAO = SYSDATE
        WHERE SEQMP = $ID";

        // Executar a atualização
        $resultado = oci_parse($oracle, $query);
        oci_execute($resultado);

        // Verificar se a atualização foi bem-sucedida
        if ($resultado) {
            // A atualização foi bem-sucedida
            echo "Atualização realizada com sucesso!";
        } else {
            // A atualização falhou
            echo "Erro ao atualizar os dados!";
        }
    }
}
