<?php

require_once __DIR__ . '\..\database\db.class.php';

$db = DB::getConnection();
try
{
    $st = $db->prepare( 'SELECT * FROM users WHERE username = :variable' );
    $st->execute( array( 'variable' => "ana" ) );
}
catch( PDOException $e ) { exit( "PDO error [where " . $class_name . "]: " . $e->getMessage() ); }

while($row = $st->fetch()) {
    print_r($row);
}

?>