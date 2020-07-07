<?php

// Ovo će nam biti ko neki međuspremnik pomoću kojeg će se varijable izmjenjivat između npr. controllera i view
class Registry {
    // Mapa u koju ce se spremiti varijable koje kreiramo u controlleru
    private $variables = array();

    // Magic set funkcija
    public function __set( $property, $value ) {
        $this->variables[$property] = $value;
        
        return $this;
    }

    //Magic get funkcija
    public function __get( $property ) {
        return $this->variables[$property];
    }
}

?>