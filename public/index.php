<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
include  "../bootstrap.php";

// use Core\Route;

// // Définir les routes ici
// Route::get('/client', 'ClientController@index');
// Route::post('/client', 'ClientController@store');
// Route::post('/ajoutDette', 'ClientController@listAdd');


// // Gérer les requêtes
// Route::handleRequest();


include "../bootstrap.php";

 use Core\Route;
 Route::middlewareGroup('auth', ['auth']);
 Route::middlewareGroup('log', ['log']);
 
 Route::get('/protected', ['controller' => 'ProtectedController', 'method' => 'index'])->middleware(['auth', 'log']);
 Route::post('/login', ['controller' => 'AuthController', 'method' => 'login'])->middleware('log');
 
 Route::get('/protected', ['controller' => 'ProtectedController', 'method' => 'index'])->middleware('auth');
Route::post('/login', ['controller' => 'AuthController', 'method' => 'login'])->middleware(['log']);


Route::get('/list/session', ['controller'=> 'SessionController','method'=>'listSession']);
Route::get('/list/session2', ['controller'=> 'SessionController','method'=>'listSession2']);
Route::post('/session/cancel', ['controller'=> 'SessionController','method'=>'cancel']);
Route::get('/list/cours', ['controller'=> 'SessionController','method'=>'showCourse']);
Route::post('/list/cours', ['controller'=> 'SessionController','method'=>'showCourseP']);
Route::get('/login', ['controller'=> 'SecuriteController','method'=>'showLogin']);
Route::post('/login', ['controller'=> 'SecuriteController','method'=>'login']);
Route::post('/logout', ['controller'=> 'SecuriteController','method'=>'logout']);
Route::get('/etudiant/cours', ['controller'=> 'SessionController','method'=>'showCourseE']);
Route::get('/etudiant/sessions', ['controller'=> 'SessionController','method'=>'showSessionE']);
Route::get('/etudiant/absence', ['controller'=> 'SessionController','method'=>'showAbsence']);
Route::post('/savepresence', ['controller'=> 'SessionController','method'=>'savePresence']);
Route::post('/savejustif', ['controller'=> 'SessionController','method'=>'saveJustif']);

// Gérer les requêtes
Route::handleRequest($config);



// $route->handleRequest();

// Définir les routes ici
// Route::get('/client', ['controller' => 'ClientController', 'method' => 'index']);
// Route::post('/client', ['controller' => 'ClientController', 'method' => 'store']);
// Route::post('/ajoutDette/#id', ['controller' => 'ClientController', 'method' => 'ajoutPanier']);
// Route::get('/dette/list/#id', ['controller' => 'ClientController', 'method' => 'listdette']);
// Route::post('/dette/list/#id', ['controller' => 'DetteController', 'method' => 'filtrePaginate']);

// Route::get('/ajoutDette/#id', ['controller' => 'ClientController', 'method' => 'listAdd']);
// Route::get('/paiement/list/#id', ['controller' => 'DetteController', 'method' => 'listPayments']);
// Route::get('/paiement/pay/#id', ['controller' => 'DetteController', 'method' => 'formpayer']);
// Route::post('/paiement/pay/#id', ['controller' => 'DetteController', 'method' => 'payer']);
// Route::get('/details/article/#id', ['controller' => 'DetteController', 'method' => 'listArticle']);
// Route::post('/dette/register', ['controller' => 'DetteController', 'method' => 'registerDebt']);