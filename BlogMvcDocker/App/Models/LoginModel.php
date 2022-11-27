<?php

namespace App\Models;

use System\Model;

class LoginModel extends Model
{
     /**
     * Nom de la table
     *
     * @var string
     */
    protected $table = 'users';

     /**
     *
     * @var mixed
     */
    private $user;

    /**
    *
    * @var \stdClass
    */

    /**
    *
    * @param string $email
    * @param string $password
    * @return bool
    */
    public function isValidLogin($email, $password)
    {
        $user = $this->where('email=?' , $email)->fetch($this->table);

        if (! $user) {
            return false;
        }

        $this->user = $user;

        return password_verify($password, $user->password);
    }

    /**
    *
    * @return \stdClass
    */
    public function user()
    {
        return $this->user;
    }

    /**
    *
    * @return bool
    */
    public function isLogged()
    {
        if ($this->cookie->has('login')) {
            $code = $this->cookie->get('login');
            //$code = ''; // just for now
        } elseif ($this->session->has('login')) {
            $code = $this->session->get('login');
        } else {
            $code = '';
        }

        $user = $this->where('code=?' , $code)->fetch($this->table);

        if (! $user) {
            return false;
        }

        $this->user = $user;

        return true;
    }
}