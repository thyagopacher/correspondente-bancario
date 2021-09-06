<?php

    include("../control/mpdf/mpdf.php");
    define('MPDF_PATH', '../control/mpdf/');
    if(isset($paisagem) && $paisagem != NULL && $paisagem != ""){
        $mpdf=new mPDF('utf-8', 'A4-L');
    }else{
        $mpdf = new mPDF();
    }

    $cabecalho .= '<h3 style="margin-left: 30%">'.$nome.'</h3>';    
    $rodape     = '<p style="float: left; color: black;width: 180px;text-align: left; font-size: 12px;">Data: '.date('d/m/Y').'</p>';
    $rodape    .= '<p style="float: right; color: grey;width: 10%;text-align: right;">@ South Negócios</p>';
    $mpdf->WriteHTML('<link rel="stylesheet" href="http://southnegocios.com/visao/recursos/css/tabela.css" type="text/css">');
    $mpdf->WriteHTML($stringCSS.$cabecalho.$_POST["html"].$rodape);
    $mpdf->Output();