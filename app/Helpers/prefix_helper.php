<?php

function aleaAdIP_long() {
// mt_rand est plus rapide que rand
// evitons le fait de toucher à la classe D ou E.
    $input_adresse_ip = mt_rand(1,223)
        .'.'. mt_rand(0,255)
        .'.'. mt_rand(0,255)
        .'.'. mt_rand(1,254);
    return ip2long($input_adresse_ip);
}

// ********************************************
// deux IP aléatoires mais avec les $cidr premiers bits en commun
// ********************************************
function get2RandIPs_long($cidr): array
{
    $adresse1 = aleaAdIP_long();
    $mask = masque_long($cidr);
    $bit = 1 << (31 - $cidr);
    $adresse2 = ($adresse1 & $mask) + rand(1, $bit-1) ;
    if (($adresse1 & $bit)== 0) {
        $adresse2 |= $bit;
    }
    else {
        $adresse2 &= ~($bit);
    }
    return [ $adresse1, $adresse2 ];
}

// ******************************************
// Fonction de calcul du masque inetaddr pour /$n
// ******************************************
function masque_long($n): int
{
    if ($n <= 0) {
        error_log("CalculIP: masque_long Erreur interne: n<=0 !",0);
        $n=1;
    } else if ($n >= 32) {
        error_log("CalculIP: masque_long Erreur interne: n>=32 !",0);
        $n=31;
    }
    return ((1<<$n)-1) << (32 -$n);
}


// ********************************************
// retourne un tableau d'octets pour représenter une IP, l'octet 0 est celui
// de poids faible
// ********************************************
function long2array($adresse): array
{
    $ret=[];
    for ($i=3 ; $i>=0 ; $i--) {
        $ret[$i]= $adresse % 256;
        $adresse >>= 8;
    }
    ksort($ret,SORT_NUMERIC);
    return($ret);
}