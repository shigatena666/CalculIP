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
$routes->get('/', 'Menu/Impl/HomeController::index');
$routes->post('/handleScore', 'Menu/Impl/HomeController::handleScore');

//Accept get requests for the profile page.
$routes->get('/Profil', 'Menu/Impl/HomeController::profil');

// Accept GET requests for about us.
$routes->get("/QuiSommesNous", "Menu/Impl/AboutUsController::index");

// Accept GET requests for news.
$routes->get("/News", "Menu/Impl/NewsController::index");

// Accept GET requests for courses.
$routes->get("/Cours", "Menu/Impl/CoursesController::index");

// Accept GET requests for memos.
$routes->get("/Memos", "Menu/Impl/MemosController::index");
$routes->get("/Memos/Analyse", "Menu/Impl/MemosController::frameanalysis");
$routes->get("/Memos/Classe", "Menu/Impl/MemosController::ipclasses");
$routes->get("/Memos/Structure", "Menu/Impl/MemosController::framestructure");
$routes->get("/Memos/CIDR", "Menu/Impl/MemosController::cidrnotation");
$routes->get("/Memos/Routage", "Menu/Impl/MemosController::routingtable");
$routes->get("/Memos/SousReseaux", "Menu/Impl/MemosController::subnetworks");


// EXERCICES - START

//Accept GET requests for the exercise list.
$routes->get("/Exercices", "Menu/Impl/ExercisesController::index");

// Accept GET and POST requests for the exercices.
$routes->match(["get", "post"], "/Exercices/Conversion", "Exercises/Impl/ConversionsController::index");
$routes->match(["get", "post"], "/Exercices/TrouverClasse", "Exercises/Impl/IPClassesController::index");
$routes->match(["get", "post"], "/Exercices/TrouverClasseInverse", "Exercises/Impl/IPClassesReverseController::index");
$routes->match(["get", "post"], "/Exercices/AnalyseTrame", "Exercises/Impl/FrameAnalysisController::index");
$routes->match(["get", "post"], "/Exercices/PrefixeMax", "Exercises/Impl/MaxCommonPrefixController::index");
$routes->match(["get", "post"], "/Exercices/PrefixeMaxDifficile", "Exercises/Impl/MaxCommonPrefixHardController::index");
$routes->match(["get", "post"], "/Exercices/NotationCIDRS2", "Exercises/Impl/CIDRNotationController::index");
$routes->match(["get", "post"], "/Exercices/NotationCIDR", "Exercises/Impl/CIDRNotationHardController::index");
$routes->match(["get", "post"], "/Exercices/Masque", "Exercises/Impl/MaskController::index");
$routes->match(["get", "post"], "/Exercices/SousReseaux", "Exercises/Impl/SubNetworkController::index");

// EXERCICES - END

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
