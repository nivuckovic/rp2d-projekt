<?php

// definiramo globalne konstante za puteve do fileova
define( '__SITE_PATH', realpath( dirname( __FILE__ ) ) );
define( '__SITE_URL', dirname( $_SERVER['PHP_SELF'] ) );

// Inicijaliziramo bazne klase
require_once 'app/init.php';

session_start();

$registry = new Registry();

$registry->db = DB::getConnection();

$registry->router = new Router($registry);
$registry->router->setPath(__SITE_PATH . '/controller');

$registry->template = new Template($registry);

// Funkcija koja cita $_GET['rt'] i ucitava pripadnu stranicu
$registry->router->setDefaultControllerAction('login', 'index');
$registry->router->loader();

?>