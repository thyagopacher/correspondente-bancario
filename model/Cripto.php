<?php

/*
 * @author Thyago Henrique Pacher - thyago.pacher@gmail.com
 */

class Cripto {
    
    /**string para codificar da url*/
    function codificar($str) {
        $prfx = array('AFVxaIF', 'Vzc2ddS', 'ZEca3d1', 'aOdhlVq', 'QhdFmVJ', 'VTUaU5U',
            'QRVMuiZ', 'lRZnhnU', 'Hi10dX1', 'GbT9nUV', 'TPnZGZz', 'ZGiZnZG',
            'dodHJe5', 'dGcl0NT', 'Y0NeTZy', 'dGhnlNj', 'azc5lOD', 'BqbWedo',
            'bFmR0Mz', 'Q1MFjNy', 'ZmFMkdm', 'dkaDIF1', 'hrMaTk3', 'aGVFsbG');
        for ($i = 0; $i < 3; $i++) {
            $str = $prfx[array_rand($prfx)] . strrev(base64_encode($str));
        }
        $str = strtr($str, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", "a8rqxPtfiNOlYFGdonMweLCAm0TXERcugBbj79yDVIWsh3Z5vHS46pQzKJ1Uk2");
        return $str;
    }

    /**decodifica url que foi passado no parametro p da url*/
    function decodificar($str) {
        $str = strtr($str, "a8rqxPtfiNOlYFGdonMweLCAm0TXERcugBbj79yDVIWsh3Z5vHS46pQzKJ1Uk2", "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
        for ($i = 0; $i < 3; $i++) {
            $str = base64_decode(strrev(substr($str, 7)));
        }
        return $str;
    }

    function separar_parametros($param) {
        return explode('&', $param);
    }

    function separar_valor($opcao) {
        return explode('=', $opcao);
    }

}
