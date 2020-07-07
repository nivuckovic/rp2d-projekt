<?php

class TestController extends BaseController {

    // Svaki kontroler mora imati ovu funkciju!
    public function index() {
        $this->registry->template->css_name = "test.css";
        $this->registry->template->message = "Ovo je test!";
        $this->registry->template->show( 'test_index' );
    }
}

?>