<?php

require_once __DIR__ . '/db.class.php';

seed_table_users();

exit( 0 );

// ------------------------------------------
function seed_table_users()
{
	$db = DB::getConnection();

	// Ubaci neke korisnike unutra
	try
	{
		$st = $db->prepare( 'INSERT INTO users(username, password_hash, email, registration_sequence, has_registered) VALUES (:username, :password, \'a@b.com\', \'abc\', \'1\')' );

		$st->execute( array( 'username' => 'mirko', 'password' => password_hash( 'mirkovasifra', PASSWORD_DEFAULT ) ) );
		$st->execute( array( 'username' => 'slavko', 'password' => password_hash( 'slavkovasifra', PASSWORD_DEFAULT ) ) );
		$st->execute( array( 'username' => 'ana', 'password' => password_hash( 'aninasifra', PASSWORD_DEFAULT ) ) );
		$st->execute( array( 'username' => 'maja', 'password' => password_hash( 'majinasifra', PASSWORD_DEFAULT ) ) );
		$st->execute( array( 'username' => 'pero', 'password' => password_hash( 'perinasifra', PASSWORD_DEFAULT ) ) );
	}
	catch( PDOException $e ) { exit( "PDO error [insert users]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu users.<br />";
}

?>