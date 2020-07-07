<?php

// Funkcionalnost routera je izdvojena u zasebnu klasu.
class Router {
    // Spremnik za zajedničku pohranu varijabli
    private $registry;
    
    // Put do controller foldera
    private $path;

    // Klasicne one varijable, pozivat cemo new $controller->$action
    public $controller;
    public $action;

    // Konstruktor koji prima spremnik
    public function __construct( $registry ) {
        $this->registry = $registry;
    }

    # Postavlja put do controllera
    # '/controller/'
    public function setPath( $path ) {
        if( is_dir( $path ) == false ) {
            throw new Exception( 'Pogrešan put do controllera!' );
        }

        $this->path = $path;
    }

    // Poziva dohvaćeno u getControlleru
    public function loader() {
        $this->getController();

        $controllerName = $this->controller . 'Controller';

        // Ako npr. loginController.php ne postoji
        if( !file_exists( $this->path. '/' . $controllerName . '.php' ) ) {
            echo $this->controller;
            die( '404 Not Found' );
        }

        require_once __SITE_PATH . '/controller/' . $controllerName . '.php';

        // Ako u npr. loginController.php postoji klasa LoginController
        if( !class_exists( $controllerName ) ) {
            echo $this->file;
            die( '404 Not Found' );
        }

        $controller = new $controllerName( $this->registry );

        // Ako u npr. LoginController klasi postoji metoda index()
        if( !method_exists( $controller, $this->action ) ) {
            echo $this->file;
            die( '404 Not Found' );
        }
        else {
            $action = $this->action;
        }

        // Pozovi metodu action iz klase kontroler
        $controller->$action();
        exit( 0 );
    }

    public function setDefaultControllerAction($_controller, $_action) {
        $this->controller = $_controller;
        $this->action = $_action;
    }

    // Dohvati ime controllera i funkciju za pozvat
    // index.php?rt=index/login => parts[0] = index, parts[1] = login 
    public function getController() {
        if( !isset( $_GET['rt'] ) ) {
            # $this->controller = 'test';
            # $this->action = 'index';
        }
        else {
            $parts = explode( '/', $_GET['rt'] );

            if ( isset( $parts[0] ) && preg_match( '/^[A-Za-z0-9]+$/', $parts[0] ) ) {
                $this->controller = $parts[0];
            }
            else {
                $this->controller = 'users';
            }

            if ( isset( $parts[1] ) && preg_match( '/^[A-Za-z0-9]+$/', $parts[1] ) ) {
                $this->action = $parts[1];
            }
            else
            {
                $this->action = 'index';
            }
        }
    }
}

?>
