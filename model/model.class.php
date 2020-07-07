<?php

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/user.class.php';

// Bazna klasa za sve ostale model klase
class Model {

    // Konstruktor koji prima mapu i iz nje kreira varijable
    // Npr za ['id_user' = 0] napravit će $id_user = 0 
    public function __Construct( $data ) {
        foreach( $data as $key => $value) {
            $this->$key = $value;
        }
    }

    // Magic get metoda
    public function __get( $property ) {
        if( property_exists( $this, $property ) ) {
            return $this->$property;
        }
    }

    // Magic set metoda
    public function __set( $property, $value ) {
        $this->$property = $value;

        return $this;
    } 

    // Dohvaća iz baze sve retke i radi pripadnu klasu
    static public function all() {
        $class_list = [];

        // Dohvati bazu i pripremi statement
        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM ' . static::$table );
        $st->execute();

        // Ako nije uspio nikoga pronaći
        if( $st->rowCount() === 0 ) {
            return null;
        }

        // Za svaki dohvaceni redak napravi klasu
        while( $row = $st->fetch() ) {
            $class_name = get_called_class();
            $class_list[] = new $class_name( $row );
        }

        return $class_list;
    }

    // Dohvaća iz baze redak s zadanim id
    static public function find( $id ) {
        $class_name = get_called_class();
        $table_name = $class_name::$table;

        // Dohvati bazu i pripremi statement
        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM ' . $table_name . ' WHERE id =  :id' );
        $st->execute( array( 'id' => $id ) );

        // Ako nije uspio nikoga pronaći
        if( $st->rowCount() === 0 ) { 
            return null;
        }

        return new $class_name( $st->fetch() );
    }

    // Dohvaca iz baze redak gdje je zadacni stupac jednak value vrijednosti
    static public function where( $column_name, $value ) {
        $class_name = get_called_class();
        $table_name = $class_name::$table;

        // Dohvati bazu i pripremi statement
        $db = DB::getConnection();
        try
        {
            $st = $db->prepare( 'SELECT * FROM ' . $table_name . ' WHERE ' . $column_name . ' = :variable' );
            $st->execute( array( 'variable' => $value ) );
        }
        catch( PDOException $e ) { exit( "PDO error [where " . $class_name . "]: " . $e->getMessage() ); }

        // Ako nije uspio nikoga pronaći
        if( $st->rowCount() === 0 ) {
            return null;
        }

        return new $class_name($st->fetch());
    }

    // Npr. za knjigu dohvaca osobu koja ju je posudila
    public function belongsTo( $class_name, $variable_name ) {
        $table_name = $class_name::$table;
        $column_name = explode('_', $variable_name)[0];

        // Dohvati bazu i pripremi statement
        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM ' . $table_name . ' WHERE ' . $column_name . ' =  :id' );
        $st->execute( array( 'id' => $this->$variable_name ) );

        // Ako nije uspio nikoga pronaći
        if($st->rowCount() === 0)
        {
            return null;
        }

        return new $class_name($st->fetch());
    }

    // Npr. osoba ima mnogo posuđenih knjiga
    public function hasMany($class_name, $column_name)
    {
        $table_name = $class_name::$table;
        $variable_name = explode('_', $column_name)[0];

        // Dohvati bazu i pripremi statement
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM ' . $table_name . ' WHERE ' . $column_name . ' =  :id');
        $st->execute(array('id' => $this->$variable_name));

        // Ako nije uspio nikoga pronaći
        if($st->rowCount() === 0)
        {
            return null;
        }

        // Za svaki dohvaceni redak napravi klasu
        $class_list = [];
        while($row = $st->fetch())
        {
            $class_list[] = new $class_name($row);
        }

        return $class_list;
    }
    
}

?>