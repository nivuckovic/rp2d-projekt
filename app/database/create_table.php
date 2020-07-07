<?php

// Stvaramo tablice u bazi (ako veÄ‡ ne postoje od ranije).
require_once __DIR__ . '/db.class.php';

create_table_users();

exit( 0 );

// --------------------------
function has_table( $tblname )
{
	$db = DB::getConnection();
	
	try
	{
		$st = $db->prepare( 
			'SHOW TABLES LIKE :tblname'
		);

		$st->execute( array( 'tblname' => $tblname ) );
		if( $st->rowCount() > 0 )
			return true;
	}
	catch( PDOException $e ) { exit( "PDO error [show tables]: " . $e->getMessage() ); }

	return false;
}


function create_table_users()
{
	$db = DB::getConnection();

	if( has_table( 'users' ) )
		exit( 'Tablica users vec postoji. Obrisite ju pa probajte ponovno.' );


	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS users (' .
			'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'username varchar(50) NOT NULL,' .
			'password_hash varchar(255) NOT NULL,'.
			'email varchar(50) NOT NULL,' .
			'registration_sequence varchar(20) NOT NULL,' .
			'has_registered int)'
		);

		$st->execute();
	}
    catch( PDOException $e ) { exit( "PDO error [create users]: " . $e->getMessage() ); }

	echo "Napravio tablicu users.<br />";
}


?> 