<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the p-rovided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        if (strpos($this->username, '@')) {
            $attr = array('email' => $this->username);
        } else {
            $attr = array('username' => $this->username);
        }
        $record = User::model()->findByAttributes($attr);

        if ($record === null) {
            $this->errorCode    = self::ERROR_USERNAME_INVALID;
            $this->errorMessage = "Incorrect username.";
        } else if ($record->password !== User::hashPassword($this->password)) {
            $this->errorCode    = self::ERROR_PASSWORD_INVALID;
            $this->errorMessage = "Incorrect password.";
        } else {
            $this->_id = $record->id;
            $this->setState('title', $record->last_name . ' ' . $record->first_name);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}