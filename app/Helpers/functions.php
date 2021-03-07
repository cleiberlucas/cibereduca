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
        '14' => 'Fevereiro',
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
        '13' => 'janeiro',
        '14' => 'fevereiro',
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
  
function dias_feriados($ano = null)
{
    if ($ano === null){
        $ano = intval(date('Y'));
    }

    $pascoa     = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP consulta http://www.php.net/manual/pt_BR/function.easter-date.php
    //echo "pascoa ".date('Y-m-d', $pascoa);
    $dia_pascoa = date('j', $pascoa);
    $mes_pascoa = date('n', $pascoa);
    $ano_pascoa = date('Y', $pascoa);

    $feriados = array(
        // Datas Fixas dos feriados Nacionail Basileiras
        mktime(0, 0, 0, 1,  1,   $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
        mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes - Lei nº 662, de 06/04/49
        mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49
        mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independência - Lei nº 662, de 06/04/49
        mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80
        mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos - Lei nº 662, de 06/04/49
        mktime(0, 0, 0, 11, 15,  $ano), // Proclamação da republica - Lei nº 662, de 06/04/49
        mktime(0, 0, 0, 12, 25,  $ano), // Natal - Lei nº 662, de 06/04/49
        
        //mktime(0, 0, 0, 11, 30,  $ano), // Dia do evangélico em Brasília
        mktime(0, 0, 0, 8, 1,  $ano), // Aniversário de Formosa

        // These days have a date depending on easter
        mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//2ºfeira Carnaval
        mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 46,  $ano_pascoa),//3ºfeira Carnaval	
        mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa  
        mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Pascoa
        mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Christi
    );

    sort($feriados);
            
    return $feriados;
}

/*     
    Autor: Wellington Rodrigues -> U r S o L o U c O     
    MSN: ursolouco@msn.com 
    Função: vencimento_calculado()       
    Argumentos: 1 ($data) Deve ser passado no formato YYYY-mm-dd 
    Utilização: Livre - Mantenha os créditos ao Autor     
    Retorno: Data calculada no formato YYYY-mm-dd 
*/    
function recalcularVencimento($data)
{ 			        
    list($ano, $mes, $dia) = explode("-", $data);   
    $ret = '';        
    
    //vencimento original no sábado - converte p segunda
    if(date("w", mktime(0,0,0, $mes, $dia, $ano)) == "6"){   
        $ret = date("Y-m-d", mktime(0,0,0,$mes, ($dia + 2), $ano));  											
    }       
    //vencimento original no domingo - converte p segunda
    elseif(date("w",mktime(0,0,0, $mes, $dia, $ano)) == "0"){ 
        $ret = date("Y-m-d", mktime(0, 0, 0, $mes, ($dia + 1), $ano)); 							
    }       
    //demais dias mantém
    else{   
        $ret = date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $ano)); 
    }       
    
    $vencimento_tmp = strtotime($ret);
    $ano_tmp = substr($ret, 0,4);
    $mes_tmp = substr($ret, 5,2);
    $dia_tmp = substr($ret, 8,2);   

// print "vencimento tmp ". date('Y-m-d', $vencimento_tmp);
    //dd($vencimento_tmp);

    //verificando se o vencimento é feriado
    foreach(dias_feriados($ano_tmp) as $a)
    {                
        //echo date("Y-m-d",$a).'<br>';						 
        //Verificar se o novo vencimento caiu em feriado	
        //print "<br>Feriado ". date($ano_tmp."-m-d",$a);					
        if ( date("Y-m-d",$vencimento_tmp) == date($ano_tmp."-m-d",$a)){
            // print "Encontrou feriado ". date($ano_tmp."-m-d",$a);
            if(date("w", mktime(0,0,0, $mes_tmp, $dia_tmp, $ano_tmp)) == "5"){  //se o feriado for sexta-feira, altera o vencimento original p segunda
                $dia_tmp = $dia_tmp+3;
                $ret = date("Y-m-d", mktime(0,0,0,$mes_tmp, $dia_tmp, $ano_tmp));  
            }					
            elseif(date("w", mktime(0,0,0, $mes_tmp, $dia_tmp, $ano_tmp)) >= "1" and date("w", mktime(0,0,0, $mes_tmp, $dia_tmp, $ano_tmp)) <= "4"){  //feriado de segunda a quinta
                $dia_tmp++;
                $ret = date("Y-m-d", mktime(0,0,0,$mes_tmp, $dia_tmp, $ano_tmp));  
            }
            
            // break;
        }
        $vencimento_tmp = strtotime($ret);
    }
    
    // $ano_atual = date("Y", time());        
    $vencimento = strtotime($ret); // vencimento nunca � em final de semana
    
    $ano_venc_tmp = date("Y",$vencimento);
    if ( ''.date("Y",$vencimento).date("m",$vencimento).date("d",$vencimento).'' == ''.$ano_venc_tmp."1231"){ // se vencimento = 31/12/anovenc
            
        if(date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) == "0" ) // se 31/12/anotual = domingo
            $ret = $ano_venc_tmp."-12-28"; // vence em 28/12/anoatual (quinta-feira)     PORQUE O �LTIMO DIA �TIL DO ANO N�O TEM EXPEDIENTE BANC�RIO
            
        elseif(date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) == "1") // se 31/12/anotual = segunda
            $ret = $ano_venc_tmp."-12-28"; // vence em 28/12/anoatual (sexta-feira)     PORQUE O �LTIMO DIA �TIL DO ANO N�O TEM EXPEDIENTE BANC�RIO
            
        elseif(date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) >= "2" AND date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) <= "5") // se 31/12/anotual = ter�a A SEXTA
            $ret = $ano_venc_tmp."-12-30"; // vence em 30/12/anoatual (dia anterior)     PORQUE O �LTIMO DIA �TIL DO ANO N�O TEM EXPEDIENTE BANC�RIO
        
        elseif(date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) == "6") // se 31/12/anotual = s�bado
            $ret = $ano_venc_tmp."-12-29"; // vence em 29/12/anoatual (quinta-feira)    PORQUE O �LTIMO DIA �TIL DO ANO N�O TEM EXPEDIENTE BANC�RIO
    }

    /*  $ret_vetor['ano'] = substr($ret, 0, 4);
    $ret_vetor['mes'] = substr($ret, 5, 2);
    $ret_vetor['dia'] = substr($ret, 8, 2);	 */	

    return $ret;
}     
  
function removerAcentos($string) {
    if($string !== mb_convert_encoding(mb_convert_encoding($string, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
        $string = mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string));
    $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $string);
    $string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), ' ', $string);
    $string = preg_replace('/( ){2,}/', '$1', $string);
    $string = strtoupper(trim($string));
    return $string;
}