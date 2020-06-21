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

    /**
     * Recebe o número de um mês e retorna o nome
     */
    function nomeMes(int $mes)
    {
        $nomeMes = array (
            '1' => 'Janeiro',
            '2' => 'Fevereiro',
            '3' => 'Março',
            '4' => 'Abril',
            '5' => 'Maio',
            '6' => 'Junho',
            '7' => 'Julho',
            '8' => 'Agosto',
            '9' => 'Setembro',
            '10'=> 'Outubro',
            '11'=> 'Novembro',
            '12'=> 'Dezembro',
        );

        return($nomeMes[$mes]);
    }
