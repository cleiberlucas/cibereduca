<?php

/**
 * Remove caracteres que não sejam números
 * Utilizado por CPF, CNPJ e telefone
 */
function somenteNumeros($campo)
{
    return preg_replace('/[^0-9]/', '', $campo);
}

/*
    *Aplica máscara no campo telefone para mostrar em relatórios ou listagem
    */
function mascaraTelefone($mask, $str)
{
    if (strlen($str) == 0)
        return '';

    $str = str_replace(" ", "", $str);

    for ($i = 0; $i < strlen($str); $i++) {
        $mask[strpos($mask, "#")] = $str[$i];
    }

    $mask = str_replace('#', '', $mask);

    return $mask;
}

/*
    *Aplica máscara no campo telefone para mostrar em relatórios ou listagem
    */
function mascaraCEP($mask, $str)
{
    if (strlen($str) == 0)
        return '';

    $str = str_replace(" ", "", $str);

    for ($i = 0; $i < strlen($str); $i++) {
        $mask[strpos($mask, "#")] = $str[$i];
    }

    $mask = str_replace('#', '', $mask);

    return $mask;
}

/*
    *Aplica máscara no campo CPF OU CNPJ para mostrar em relatórios ou listagem
    */
function mascaraCpfCnpj($mask, $str)
{
    if (strlen($str) == 0)
        return '';

    $str = str_replace(" ", "", $str);

    for ($i = 0; $i < strlen($str); $i++) {
        $mask[strpos($mask, "#")] = $str[$i];
    }

    $mask = str_replace('#', '', $mask);

    return $mask;
}

function validateCpf($strCPF)
{
    $soma = 0;
    $resto = 0;
    $soma = 0;
    if ($strCPF == "00000000000")
        return false;

    for ($i = 1; $i <= 9; $i++) {
        //$soma = $soma + intval($strCPF.substring(i - 1, i)) * (11 - i);
        $resto = ($soma * 10) % 11;
    }

    if (($resto == 10) || ($resto == 11))
        $resto = 0;

    /* if ($resto != parseInt($strCPF.substring(9, 10))) 
            return false; */

    $soma = 0;
    for ($i = 1; $i <= 10; $i++) {
        // $soma = $soma + parseInt($strCPF.substring(i - 1, i)) * (12 - i);
        $resto = ($soma * 10) % 11;
    }

    if (($resto == 10) || ($resto == 11))
        $resto = 0;

    /* if ($resto != parseInt($strCPF.substring(10, 11)))
             return false; */

    return true;
}

/**
 * Recebe o número de um mês e retorna o nome
 */
function nomeMes(int $mes)
{
    $nomeMes = array(
        '1' => 'Janeiro',
        '2' => 'Fevereiro',
        '3' => 'Março',
        '4' => 'Abril',
        '5' => 'Maio',
        '6' => 'Junho',
        '7' => 'Julho',
        '8' => 'Agosto',
        '9' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro',
        '13' => 'Janeiro',
    );

    return ($nomeMes[$mes]); 
}

/**
 * Recebe o número de um mês e retorna o nome
 */
function nomeMesMinusculo(int $mes)
{
    $nomeMes = array(
        '1' => 'janeiro',
        '2' => 'fevereiro',
        '3' => 'março',
        '4' => 'abril',
        '5' => 'maio',
        '6' => 'junho',
        '7' => 'julho',
        '8' => 'agosto',
        '9' => 'setembro',
        '10' => 'outubro',
        '11' => 'novembro',
        '12' => 'dezembro',
    );

    return ($nomeMes[$mes]);
}

function verificaIntervaloHora($dateInterval, $startDate, $endDate) {
    $dateInterval = new DateTime($dateInterval);
    $startDate = new DateTime($startDate);
    $endDate = new DateTime($endDate);
  
    $startDate->format('Y-m-d H:i:s.uO'); 
    $endDate->format('Y-m-d H:i:s.uO'); 
  
    return  ($dateInterval->getTimestamp() >= $startDate->getTimestamp() &&
    $dateInterval->getTimestamp() <= $endDate->getTimestamp());  

  } 