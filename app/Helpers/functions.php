<?php

    /**
     * Remove caracteres que não sejam números
     * Utilizado por CPF, CNPJ e telefone
     */
    function somenteNumeros($campo)
    {
        return preg_replace("/[^0-9]/", "", $campo);         
    }

    /*
    *Aplica máscara no campo telefone para mostrar em relatórios ou listagem
    */
    function mascaraTelefone($mask,$str){
        if (strlen($str) == 0)
            return '';

        $str = str_replace(" ", "", $str);
        
        for($i=0; $i<strlen($str); $i++){
            $mask[strpos($mask,"#")] = $str[$i];
        }

        $mask = str_replace('#', '', $mask);
    
        return $mask;    
    }

    /*
    *Aplica máscara no campo CPF OU CNPJ para mostrar em relatórios ou listagem
    */
    function mascaraCpfCnpj($mask,$str){
        if (strlen($str) == 0)
            return '';

        $str = str_replace(" ", "", $str);
        
        for($i=0; $i<strlen($str); $i++){
            $mask[strpos($mask,"#")] = $str[$i];
        }

        $mask = str_replace('#', '', $mask);
    
        return $mask;    
    }

