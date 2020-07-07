<?php

class RegisterController extends BaseController {
    public function index() {
        $this->registry->template->css_name = "index.css";
        $this->registry->template->script_name= "register_index.js";
        $this->registry->template->show('register_index');
    }

    public function validate()
    {
        $messages = [];

        $messages['register'] = false;

        // Prekratak username
        if( !preg_match( '/^[A-Za-z]{3,10}$/', $_POST['username'] ) )
        {
            $messages['error_message'] = "Korisničko ime treba imati između 3 i 10 slova.";
            sendJSONandExit($messages);
        }

        // Username zauzet
        $user_result = User::where('username', $_POST['username']);

        if(isset($user_result))
        {
            $messages['error_message'] = "Korisnik s unešenim imenom već postoji!";
            sendJSONandExit($messages);
        }

        // Neispravan email
        if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
        {
            $messages['error_message'] = "Neispravan email!";
            sendJSONandExit($messages);
        }

        // Postojeći email
        $email_result = User::where('email', $_POST['email']);

        if(isset($email_result))
        {
            $messages['error_message'] = "Korisnik s unešenim emailom već postoji!";
            sendJSONandExit($messages);
        }

        // Sve je uredu!
     
        // Generiraj niz(ASCII vrijednosti)
        $reg_seq = $this->generateSequence();

        // Dodaj novog neverificiranog korisnika
        $this->insertNewUser($reg_seq);
        
        // OVO PROVJERI NA SERVERU!!!
        $this->sendVerificationMail($reg_seq);

        $messages['register'] = true;

        sendJSONandExit($messages);
    }

    public function activate()
    {
        $activation_code = $_GET['activation'];

        $user = User::where('registration_sequence', $activation_code);

        // Postoji li user s aktivacijskim kodom
        if(isset($user))
        {
            // Je li user vec potvrden
            if($user->has_registered)
            {
                $this->registry->template->message = 'Korisnički račun je vec aktiviran!';
            }
            else
            {
                $this->activateUser($user->id);
                $this->registry->template->message = 'Uspješno ste aktivirali korisnički račun!';
            }
        }
        else
        {
            $this->registry->template->message = 'Korisnik s aktivacijskim kodom ne postoji!';
        }

        $this->registry->template->css_name = "index.css";
        $this->registry->template->script_name= "register_index.js";
        $this->registry->template->show('register_post'); 
    }

    protected function activateUser($id_user)
    {
        $st = $this->registry->db->prepare('UPDATE users SET has_registered = 1 WHERE id = :id_user');
        $st->execute(array('id_user' => $id_user));
    }

    protected function generateSequence()
    {
        $reg_seq = '';
		for( $i = 0; $i < 20; ++$i )
            $reg_seq .= chr( rand(0, 25) + ord( 'a' ) );

        return $reg_seq;
    }

    protected function insertNewUser($reg_seq)
    {
        try
		{
			$st = $this->registry->db->prepare( 'INSERT INTO users(username, password_hash, email, registration_sequence, has_registered) VALUES ' .
				                '(:username, :password_hash, :email, :registration_sequence, 0)' );
			
			$st->execute( array( 'username' => $_POST['username'], 
				                 'password_hash' => password_hash( $_POST['password'], PASSWORD_DEFAULT ), 
				                 'email' => $_POST['email'], 
				                 'registration_sequence'  => $reg_seq ) );
		}
		catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
    }

    protected function sendVerificationMail($reg_seq)
    {
        $to         = $_POST['email'];
        $subject    = 'Registracijski mail';
        $message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
		$message .= 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/index.php?rt=register/activate&activation=' . $reg_seq . "\n";  
        $headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
		            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                    
        $mail_sent = mail($to, $subject, $message, $headers);

        if(!$mail_sent)
            exit( 'Greška: ne mogu poslati mail.' );

    }
}

?>