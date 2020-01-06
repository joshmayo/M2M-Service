<?php
/**
 * Wrapper class for the PHP BCrypt library.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

namespace M2MConnect;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BcryptWrapper
{

    public function __construct()
    {
        $this->log = new Logger('logger');
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'login.log', Logger::INFO));
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'login_error.log', Logger::ERROR));
    }

    public function __destruct(){}

    public function createHashedPassword($string_to_hash)
    {
        $password_to_hash = $string_to_hash;
        $bcrypt_hashed_password = '';

        if (!empty($password_to_hash))
        {
            $options = array('cost' => BCRYPT_COST);
            $bcrypt_hashed_password = password_hash($password_to_hash, BCRYPT_ALGO, $options);
        }
        return $bcrypt_hashed_password;
    }

    public function authenticatePassword($string_to_check, $stored_user_password_hash)
    {
        $this->log->info('User attempting to login.');

        $user_authenticated = false;
        $current_user_password = $string_to_check;

        if (!empty($current_user_password) && !empty($stored_user_password_hash))
        {
            if (password_verify($current_user_password, $stored_user_password_hash))
            {
                $user_authenticated = true;

                $this->log->info('User login successful.');
            }
            else
            {
                $this->log->info('User login failed.');
            }
        }
        return $user_authenticated;
    }
}
