<?php
class UserIdentity extends CUserIdentity
{
    public $id;
    public function authenticate()
    {
        $admin=Admin::model()->findByAttributes(array('name'=>$this->username));
        if(!$admin)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(! $admin->validatePwd($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->id=$admin->id;
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->id;
    }

}
