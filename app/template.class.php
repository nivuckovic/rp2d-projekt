<?php

// Template se brine za view, on prikazuje stranicu i sadrži inicijalizirane 
// varijable koje treba prikazati na stranici
class Template {
    // Mapa u koju spremamo varijable
    private $variables = array();

    public function __construct() { }

    // Magic set funkcija
    public function __set( $property, $value ) {
        $this->variables[$property] = $value;

        return $this;
    }

    // Magic get funkcija
    public function __get( $property ) {
        return $this->variables[$property];
    }

    // Funkcija za prikazati stranicu iz view foldera, npr. show( login_index )
    public function show( $name ) {
        $path = __SITE_PATH . '/view/' . $name . '.php';

        if( !file_exists( $path ) ) {
            throw new Exception('Stranica ne postoji: ' . $name);
            return false;
        }

        // Za svaki par (key, value) u $variables stvori varijablu s imenom $key
        // i vrijednosti $value npr. ako imamo $variables =['message' => 'ovo je poruka']
        // onda ćemo dobiti $message = 'ovo je poruka' varijablu u npr. login_index.php
        foreach( $this->variables as $key => $value ) {
            $$key = $value;
        }

        require $path;
    }
}

?>