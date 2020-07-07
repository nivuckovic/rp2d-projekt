<?php

class ChatController extends BaseController {
    public function index() {
        $this->registry->template->css_name = "chat.css";
        $this->registry->template->script_name= "chat_index.js";
        $this->registry->template->show('chat_index');
    }

    public function getMessages() {
        $messages['user_1'] = [];
        $messages['user_2'] = [];

        try {
            $st = $this->registry->db->prepare('SELECT * FROM messages WHERE (username_1 = :username_1) AND (username_2 = :username_2)');
            $st->execute( array( 'username_1' => $_SESSION['user']->username, 'username_2' => $_POST['user_2'] ));

            while($row = $st->fetch()) {
                $messages['user_1'][] = $row;
            }

            $st->execute( array( 'username_1' => $_POST['user_2'], 'username_2' => $_SESSION['user']->username ));

            while($row = $st->fetch()) {
                $messages['user_2'][] = $row;
            }
        }
        catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

        sendJSONandExit($messages);
    }

    public function sendMessage() {
        try {
            $st = $this->registry->db->prepare("INSERT INTO messages(username_1, username_2, poruka) VALUES (:username_1, :username_2, :poruka)");
            $st->execute(array( 'username_1' => $_SESSION['user']->username, 'username_2' => $_POST['user_2'], 'poruka' => $_POST['poruka'] ));
        }
        catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

        sendJSONandExit([]);
    }
}

?>