<?php

class LoginController extends BaseController {

    public function index() {
        $this->registry->template->css_name = "index.css";
        $this->registry->template->script_name= "login_index.js";
        $this->registry->template->show('login_index');
    }

    public function validateLogin() {
        $message = [];

        $message['login'] = false;

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Ako kucice nisu prazne
        if($username !== '' && $password !== '')
        {
            $user = User::where('username', $username);

            // Postoji li username s unesenim imenom
            if(!isset($user))
            {
                $message['error_message'] = 'Korisnik s unešenim imenom ne postoji!';
                sendJSONandExit($message);
            }

            $password_hash = $user->password_hash;

            // Je li password dobar?
            if( !password_verify( $password, $password_hash ) )
            {
                $message['error_message'] = 'Pogrešan password!';
                sendJSONandExit($message);
            }
            
            // Je li korisnik verificiran
            if(!$user->has_registered)
            {
                $message['error_message'] = 'Niste potvrdili aktivacijski link!';
                sendJSONandExit($message);
            }
        }
        else
        {
            $message['error_message'] = 'Molim vas unesite ime i password!';
            sendJSONandExit($message);
        }

        $_SESSION['user'] = $user;
        
        if($user->profile_created) {
            $message['location'] = "index.php?rt=profile/index";
        }
        else {
            $message['location'] = "index.php?rt=profile/create";
        }

        $message['login'] = True;
        sendJSONandExit($message);
    } 

    public function logout() {
        unset($_SESSION['user']);

        $message = [];
        sendJSONandExit($message);
    }
}

?>