<?php
class ChangePasswordForm extends CFormModel
{
    public $pwd, $npwd, $vpwd;
    private $_identity;

	public function rules()
	{
		return array(
			array('pwd, npwd, vpwd', 'required'),
			array('pwd, npwd, vpwd', 'length', 'max' => 12,'min' => 6),
			array('pwd, npwd, vpwd', 'verifyPassword'),
			array('npwd', 'compare', 'compareAttribute' => 'vpwd')
		);
	}
	

	public function attributeLabels()
	{
		return array(
		    'pwd' => '原密码',
			'npwd' => '新密码',
			'vpwd' => '确认密码'
		);
	}
	

	public function verifyPassword($attribute, $params)
	{
	    if(!$this->hasErrors())
		{
		    // 验证原密码
		    $this->_identity = new UserIdentity(Yii::app()->user->getName(), $this->pwd);
            $this->_identity->authenticate();
            switch($this->_identity->errorCode)
            {
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError('pwd', '原密码不正确！');
                    break;
                default:
                    break;
            }
		}
	}
}
?>