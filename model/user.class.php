<?php

require_once __DIR__ . '/model.class.php';

class User extends Model
{
    static protected $table = 'users';

    public function profile() {
        return $this->hasMany('Profile', 'username');
    }
}

?>