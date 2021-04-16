<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
require_once '/usr/share/php/Fedora/Autoloader'.'/autoload.php';

\Fedora\Autoloader\Autoload::addClassMap(
    array(
        'cas_authenticationexception' => '/AuthenticationException.php',
                'cas_client' => '/Client.php',
                'cas_cookiejar' => '/CookieJar.php',
                'cas_exception' => '/Exception.php',
                'cas_gracefullterminationexception' => '/GracefullTerminationException.php',
                'cas_invalidargumentexception' => '/InvalidArgumentException.php',
                'cas_languages_catalan' => '/Languages/Catalan.php',
                'cas_languages_chinesesimplified' => '/Languages/ChineseSimplified.php',
                'cas_languages_english' => '/Languages/English.php',
                'cas_languages_french' => '/Languages/French.php',
                'cas_languages_galego' => '/Languages/Galego.php',
                'cas_languages_german' => '/Languages/German.php',
                'cas_languages_greek' => '/Languages/Greek.php',
                'cas_languages_japanese' => '/Languages/Japanese.php',
                'cas_languages_languageinterface' => '/Languages/LanguageInterface.php',
                'cas_languages_portuguese' => '/Languages/Portuguese.php',
                'cas_languages_spanish' => '/Languages/Spanish.php',
                'cas_outofsequencebeforeauthenticationcallexception' => '/OutOfSequenceBeforeAuthenticationCallException.php',
                'cas_outofsequencebeforeclientexception' => '/OutOfSequenceBeforeClientException.php',
                'cas_outofsequencebeforeproxyexception' => '/OutOfSequenceBeforeProxyException.php',
                'cas_outofsequenceexception' => '/OutOfSequenceException.php',
                'cas_pgtstorage_abstractstorage' => '/PGTStorage/AbstractStorage.php',
                'cas_pgtstorage_db' => '/PGTStorage/Db.php',
                'cas_pgtstorage_file' => '/PGTStorage/File.php',
                'cas_proxiedservice' => '/ProxiedService.php',
                'cas_proxiedservice_abstract' => '/ProxiedService/Abstract.php',
                'cas_proxiedservice_exception' => '/ProxiedService/Exception.php',
                'cas_proxiedservice_http' => '/ProxiedService/Http.php',
                'cas_proxiedservice_http_abstract' => '/ProxiedService/Http/Abstract.php',
                'cas_proxiedservice_http_get' => '/ProxiedService/Http/Get.php',
                'cas_proxiedservice_http_post' => '/ProxiedService/Http/Post.php',
                'cas_proxiedservice_imap' => '/ProxiedService/Imap.php',
                'cas_proxiedservice_testable' => '/ProxiedService/Testable.php',
                'cas_proxychain' => '/ProxyChain.php',
                'cas_proxychain_allowedlist' => '/ProxyChain/AllowedList.php',
                'cas_proxychain_any' => '/ProxyChain/Any.php',
                'cas_proxychain_interface' => '/ProxyChain/Interface.php',
                'cas_proxychain_trusted' => '/ProxyChain/Trusted.php',
                'cas_proxyticketexception' => '/ProxyTicketException.php',
                'cas_request_abstractrequest' => '/Request/AbstractRequest.php',
                'cas_request_curlmultirequest' => '/Request/CurlMultiRequest.php',
                'cas_request_curlrequest' => '/Request/CurlRequest.php',
                'cas_request_exception' => '/Request/Exception.php',
                'cas_request_multirequestinterface' => '/Request/MultiRequestInterface.php',
                'cas_request_requestinterface' => '/Request/RequestInterface.php',
                'cas_session_phpsession' => '/Session/PhpSession.php',
                'cas_typemismatchexception' => '/TypeMismatchException.php',
                'phpcas' => '/../CAS.php',
    ),
    __DIR__
);
// @codeCoverageIgnoreEnd
\Fedora\Autoloader\Dependencies::required([
    '/usr/share/php/Psr/Log/autoload.php',
]);
