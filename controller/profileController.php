<?php

class ProfileController extends BaseController {

public function index() {
    $this->registry->template->css_name = "profile.css";
    $this->registry->template->script_name= "profile_index.js";
    $this->registry->template->show('profile_index');
}

public function create() {
    $this->registry->template->css_name = "create.css";
    $this->registry->template->script_name= "profile_create.js";
    $this->registry->template->show('profile_create');
}

public function saveChanges() {
    $message['succes'] = true;

    if($_SESSION['user']->profile_created)
            $this->deleteCreatedProfile();

    $this->insertNewUser();
    $this->setProfileCreated();

    $message['location'] = "index.php?rt=profile/index";

    sendJSONandExit($message);
}

protected function deleteCreatedProfile() {
    try {
        $st = $this->registry->db->prepare( 'DELETE FROM profiles WHERE username = :username' );
        
        $st->execute( array( 'username' => $_SESSION['user']->username ));
    }
    catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
}

protected function insertNewUser() {
    try {
        $st = $this->registry->db->prepare( 'INSERT INTO profiles(username, ime, prezime, godine, profilna_url, spol, lokacija, o_meni) VALUES ' .
                            '(:username, :ime, :prezime, :godine, :profilna_url, :spol, :lokacija, :o_meni)' );
        
        $st->execute( array( 'username' => $_SESSION['user']->username, 
                            'ime' => $_POST['ime'], 
                            'prezime' => $_POST['prezime'], 
                            'godine'  => $_POST['godine'],
                            'profilna_url' => $_POST['slika'],
                            'spol' => $_POST['spol'],
                            'lokacija' => $_POST['lokacija'],
                            'o_meni' => $_POST['o_meni']
                        ));
    }
    catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
}

protected function setProfileCreated() {
    try {
        $st = $this->registry->db->prepare( 'UPDATE users SET profile_created = "1" WHERE username = :username' );
        
        $st->execute( array( 'username' => $_SESSION['user']->username ));
    }
    catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

    $_SESSION['user']->profile_created = 1;
}

public function getProfile() {
    $message['profile'] = $_SESSION['user']->profile()[0];

    sendJSONandExit($message);
}

public function profileCreated() {
    $message['success'] = intval($_SESSION['user']->profile_created);

    $profiles = $_SESSION['user']->profile();

    if(isset($profiles))
        $message['user'] = $profiles[0];

    sendJSONandExit($message);
}

}

?>