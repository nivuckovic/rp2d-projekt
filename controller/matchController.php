<?php

class MatchController extends BaseController {
    public function index() {
        $this->registry->template->css_name = "match.css";
        $this->registry->template->script_name= "match_index.js";
        $this->registry->template->show('match_index');
    }

    public function matches() {
        $this->registry->template->css_name = "matchevi.css";
        $this->registry->template->script_name= "match_matches.js";
        $this->registry->template->show('match_matches');
    }

    public function allProfiles() {
        $profiles = [];

        $allProfiles = Profile::all();
        for($i = 0; $i < count($allProfiles); $i++) {
            if($allProfiles[$i]->username === $_SESSION['user']->username)
                continue;

            $profiles[] = $allProfiles[$i];
        }

        $message['profiles'] = $profiles;

        sendJSONandExit($message);
    }

    public function filterProfiles() {
        $profiles = [];

        $allProfiles = Profile::all();
        for($i = 0; $i < count($allProfiles); $i++) {
            if($allProfiles[$i]->username === $_SESSION['user']->username)
                continue;

            if($_POST['spol'] != 'oba' && $_POST['spol'] != $allProfiles[$i]->spol)
                continue;

            if($allProfiles[$i]->godine < $_POST['min_godine'] || $allProfiles[$i]->godine > $_POST['max_godine'])
                continue;

            // Grad ....

            $profiles[] = $allProfiles[$i];
        }

        $message['profiles'] = $profiles;

        sendJSONandExit($message);
    }

    public function insertNewMatchRequest() {
        $message = [];

        try {
            $st = $this->registry->db->prepare('SELECT * FROM matches WHERE (username_1 = :username_1) AND (username_2 = :username_2)');
            $st->execute( array( 'username_1' => $_SESSION['user']->username, 'username_2' => $_POST['user_2'] ));

            if( $st->rowCount() !== 0 ) {
                sendJSONandExit($message);
            } 

            $st = $this->registry->db->prepare( 'INSERT INTO matches(username_1, username_2) VALUES (:username_1, :username_2)' );
            $st->execute( array( 'username_1' => $_SESSION['user']->username, 'username_2' => $_POST['user_2'] ));
        }
        catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
    

        sendJSONandExit($message);
    }

    public function getMatches() {
        $messages['matches'] = [];

        $user_1 = $_SESSION['user']->username;

        try {
            $st = $this->registry->db->prepare('SELECT * FROM matches WHERE (username_1 = :username_1)');
            $st->execute( array( 'username_1' => $user_1 ));
        }
        catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

        while($row = $st->fetch()) {
            try {
                $st2 = $this->registry->db->prepare('SELECT * FROM matches WHERE (username_1 = :username_1) AND (username_2 = :username_2)');
                $st2->execute( array( 'username_1' => $row['username_2'], 'username_2' => $user_1 ));
            }
            catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

            while($st2->fetch()) {
                try {
                    $st3 = $this->registry->db->prepare('SELECT * FROM profiles WHERE (username = :username)');
                    $st3->execute( array( 'username' => $row['username_2']));
                }
                catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
            
                $messages['matches'][] = $st3->fetch();
            }
        }           

        sendJSONandExit($messages);
    }

    public function unmatch() {
        try {
            $st = $this->registry->db->prepare('DELETE FROM matches WHERE username_1 = :username_1 AND username_2 = :username_2');
            $st->execute( array( 'username_1' => $_SESSION['user']->username, 'username_2' => $_POST['user_2'] ));

            $st->execute( array( 'username_1' => $_POST['user_2'], 'username_2' => $_SESSION['user']->username ));

            $st = $this->registry->db->prepare('DELETE FROM messages WHERE username_1 = :username_1 AND username_2 = :username_2');
            $st->execute( array( 'username_1' => $_SESSION['user']->username, 'username_2' => $_POST['user_2'] ));

            $st->execute( array( 'username_1' => $_POST['user_2'], 'username_2' => $_SESSION['user']->username ));
        }
        catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

        sendJSONandExit([]);
    }
}

?>