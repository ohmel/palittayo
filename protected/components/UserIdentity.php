<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
//    public $username;
//    public $userdet;
//    public $church_id;
    public $user_id;
//    public $user_fullname;
    
    public function authenticate() {

        $model = Users::model()->find("user_name=:user_name and user_password=:user_password", array(':user_name'=>$this->username, ':user_password'=>md5($this->password)));
//        echo $model->user_name;exit;
//        $users = array(
//            // username => password
//            'demo' => 'demo',
//            'admin' => 'admin',
//        );
//        echo "<pre>";
//        print_r($model);
//        echo "</pre>";
//        exit;
        if(!$model){
            throw new CHttpException('503: Forbidden or Login Failed!','Username or Password is invalid or you\'re not registered');
            exit;
        }
        if ($model->user_name !== $this->username)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if ($model->user_password !== md5($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else
            $this->user_id = $model->user_id;
            $this->errorCode = self::ERROR_NONE;
        return !$this->errorCode;
    }
    public function getId(){
        return $this->user_id;
    }
}