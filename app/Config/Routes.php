<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'HomeController::index');

// EXERCICES - START

// Accept GET and POST requests for conversions.
$routes->get("/Exercices/Conversion", "Exercises/ConversionsController::index");
$routes->post("/Exercices/Conversion", "Exercises/ConversionsController::index");

// Accept GET and POST requests for IP classes.
$routes->get("/Exercices/TrouverClasse", "Exercises/IPClassesController::index");
$routes->post("/Exercices/TrouverClasse", "Exercises/IPClassesController::index");

// Accept GET and POST requests for reverse IP classes.
$routes->get("/Exercices/TrouverClasseInverse", "Exercises/IPClassesReverseController::index");
$routes->post("/Exercices/TrouverClasseInverse", "Exercises/IPClassesReverseController::index");

// Accept GET and POST requests for frame analysis.
$routes->get("/Exercices/AnalyseTrame", "Exercises/FrameAnalysisController::index");
$routes->post("/Exercices/AnalyseTrame", "Exercises/FrameAnalysisController::index");

// Accept GET and POST requests for max common prefix.
$routes->get("/Exercices/PrefixeMax", "Exercises/MaxCommonPrefixController::index");
$routes->post("/Exercices/PrefixeMax", "Exercises/MaxCommonPrefixController::index");

// Accept GET and POST requests for max common prefix hard.
$routes->get("/Exercices/PrefixeMaxDifficile", "Exercises/MaxCommonPrefixHardController::index");
$routes->post("/Exercices/PrefixeMaxDifficile", "Exercises/MaxCommonPrefixHardController::index");

// Accept GET and POST requests for CIDR notation.
$routes->get("/Exercices/NotationCIDRS2", "Exercises/CIDRNotationController::index");
$routes->post("/Exercices/NotationCIDRS2", "Exercises/CIDRNotationController::index");

// Accept GET and POST requests for CIDR notation hard.
$routes->get("/Exercices/NotationCIDR", "Exercises/CIDRNotationHardController::index");
$routes->post("/Exercices/NotationCIDR", "Exercises/CIDRNotationHardController::index");

// Accept GET and POST requests for mask.
$routes->get("/Exercices/Masque", "Exercises/MaskController::index");
$routes->post("/Exercices/Masque", "Exercises/MaskController::index");

// Accept GET and POST requests for sub network.
$routes->get("/Exercices/SousReseaux", "Exercises/SubNetworkController::index");
$routes->post("/Exercices/SousReseaux", "Exercises/SubNetworkController::index");

// EXERCICES - END

// Accept GET requests for about us.
$routes->get("/QuiSommesNous", "AboutUsController::index");

// Accept GET requests for news.
$routes->get("/News", "NewsController::index");

// Accept GET requests for memos.
$routes->get("/Memos", "MemosController::index");
$routes->get("/Memos/Analyse", "MemosController::frameanalysis");
$routes->get("/Memos/Classe", "MemosController::ipclasses");
$routes->get("/Memos/Structure", "MemosController::framestructure");
$routes->get("/Memos/CIDR", "MemosController::cidrnotation");
$routes->get("/Memos/Routage", "MemosController::routingtable");
$routes->get("/Memos/SousReseaux", "MemosController::subnetworks");

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
