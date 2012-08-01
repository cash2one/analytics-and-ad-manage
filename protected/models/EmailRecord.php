<?php
class EmailRecord extends CActiveRecord
{
    public $startDate, $endDate;
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'email_record';
    }

    public function rules()    
    {
        return array(
            array('user_id, past_email, email, time, admin_id', 'required'),
            array('user_id, time, admin_id', 'numerical', 'integerOnly' => true),
            array('email', 'email'),
            array('id, user_id, user_name, past_email, email, time, admin_id', 'safe', 'on'=>'search')
        );
    }
    
    public function relations()
    {
        return array();
    }
    
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => '用户ID',
            'user_name' => '用户名',
            'past_email' => '修改前邮箱',
            'email' => '修改后邮箱',
            'time' => '修改时间',
            'admin_id' => '管理员ID'            
        );
    }
    
    protected function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            if($this->isNewRecord)
            {
                $this->time = time();
                $this->admin_id=Yii::app()->user->getId();
            }
            return true;
        }
    }
    
    public function search()
    {
        $criteria = new CDbCriteria;
        
        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('user_name', $this->user_name);
        $criteria->compare('past_email', $this->past_email);
        $criteria->compare('email', $this->email);
        $criteria->compare('admin_id', $this->admin_id);
        $criteria->addBetweenCondition('time', strtotime($this->startDate), strtotime($this->endDate. '23:59:59'));

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}