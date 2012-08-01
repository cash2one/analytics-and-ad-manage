<?php
class LoginForm extends CFormModel
{
    public $name;
    public $password;
    private $_identity;

    public function rules()
    {
        return array(
                array('name, password', 'required'),
                array('password', 'length','max'=>12,'min'=>6),
                array('name', 'length', 'max'=>128),
                array('name,password', 'authenticate'),
                );
    }

    public function attributeLabels()
    {
        return array(
                'name'=>'用户名',
                'password'=>'密码'
                );
    }

    public function authenticate($attribute,$params)
    {
        if(!$this->hasErrors())
        {

            $this->_identity=new UserIdentity($this->name,$this->password);
            $this->_identity->authenticate();

            switch($this->_identity->errorCode)
            {
                case UserIdentity::ERROR_USERNAME_INVALID:
                    $this->addError('name','name is incorrect.');
                    break;
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError('password','Password is incorrect.');
                    break;
                default:
                    break;
            }
        }
    }

    public function login()
    {
        if($this->_identity===null)
        {
            $this->_identity=new UserIdentity($this->name,$this->password);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
        {
            Yii::app()->user->login($this->_identity);
            return true;
        }
        else
            return false;
    }
}
