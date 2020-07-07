<?php

abstract class BaseController {
    # Zajednički spremnik
    protected $registry;

    public function __construct( $registry )
    {
        $this->registry = $registry;
    }

    // Apstraktna funkcija nasljeđena klasa ju mora overrideat!
    abstract function index();
    
}

?>
