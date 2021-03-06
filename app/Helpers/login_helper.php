<?php

require_once(APPPATH . 'ThirdParty/CAS.php');
error_reporting(E_ALL & ~E_NOTICE);

//phpCAS::setLogger('/var/log/phpCAS.log');
//phpCAS::setVerbose(true);
//phpCAS::client(CAS_VERSION_2_0,'portail.cevif.univ-paris13.fr',443,'/cas/',true);
phpCAS::client(CAS_VERSION_2_0, 'cas.univ-paris13.fr', 443, '/cas/', false);
phpCAS::setCasServerCACert('/etc/pki/ca-trust/univ-paris13.fr-chain.pem');

//phpCAS::setCasServerCACert("/etc/pki/CA/chaine.pem");

phpCAS::setLang(PHPCAS_LANG_FRENCH);

function authentication(): string
{
    phpCAS::forceAuthentication();
    return phpCAS::getUser();
}

function disconnect()
{
    phpCAS::logout(array('url' => 'https://www-info.iutv.univ-paris13.fr/CalculIP.new'));
    //phpCAS::logoutWithRedirectService('https://www-info.iutv.univ-paris13.fr/CalculIP');
    //phpCAS::logoutWithRedirectService('http://calcul-ip-oursinator-1.c9.io/?logout=success');
}

function isAuthenticated() {
    return phpCAS::isAuthenticated();
}

function authRequired()
{
    if (!(phpCAS::isAuthenticated())) {
        //redirect()->to("https://perdu.com");
        header("Location: https://perdu.com");
        die('Get away');
    }
}

function isLogin(): bool
{
    return phpCAS::checkAuthentication();
}

function getUser(): string
{
    return phpCAS::getUser();
}

function isAdmin(): bool
{
    return (isLogin() && (getUser() === 'franck.butelle'));
}

function checkConnect()
{
    if (!isset($_SESSION['connect'])) {
        echo '<p><strong>Merci de vous connecter d\'abord...</strong></p>';
        //redirect()->to("https://www-info.iutv.univ-paris13.fr/CalculIP.new/?login=");
        header("Location: https://www-info.iutv.univ-paris13.fr/CalculIP.new/?login=");
        exit (0);
    }
}