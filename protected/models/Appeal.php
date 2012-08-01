<?php
class Appeal extends CActiveRecord
{
    public $startDate, $endDate;
    public static $statusList=array(0=>'未处理',1=>'申诉成功',2=>'申诉拒绝',3=>'已删除');
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'appeal';
    }

    public function rules()
    {
        return array(
            array('status, user_name,game_name,pay_amount,contact_email', 'required'),
            array('status, birthday, time', 'numerical', 'integerOnly'=>true),
            array('user_name, email, reg_game, real_name, contact_email', 'length', 'max'=>64),
            array('nick_name, game_name, server_name, address, pay_latest_method, pay_latest_game', 'length', 'max'=>128),
            array('reject_message','length','max'=>'512'),
            array('gender', 'length', 'max'=>4),
            array('card_id', 'length', 'max'=>18),
            array('reg_time, pay_amount, pay_latest_time, ip', 'length', 'max'=>10),
            array('contact_qq', 'length', 'max'=>16),
            array('id, status, reject_message,user_name, nick_name, game_name, server_name, email, reg_game, real_name, address, gender, birthday, card_id, reg_time, pay_amount, pay_latest_time, pay_latest_method, pay_latest_game, extra, contact_email, contact_qq, ip, time', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'status' => '状态',
            'user_name' => '账户',
            'nick_name' => '角色名称',
            'game_name' => '经常玩的游戏',
            'server_name' => '区服',
            'email' => '注册邮箱',
            'reg_game' => '注册游戏',
            'real_name' => '真实姓名',
            'address' => '注册地址',
            'gender' => '性别',
            'birthday' => '生日',
            'card_id' => '身份证',
            'reg_time' => '注册时间',
            'pay_amount' => '最后一笔充值金额',
            'pay_latest_time' => '最后充值时间',
            'pay_latest_method' => '最后充值方式',
            'pay_latest_game' => '最后充值游戏',
            'extra' => '补充资料',
            'contact_email' => '联系邮箱',
            'contact_qq' => '联系QQ',
            'ip' => 'Ip',
            'time' => '申诉时间',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('user_name',$this->user_name,true);
        $criteria->compare('contact_email',$this->contact_email,true);
        $criteria->compare('time','>='.strtotime($this->startDate));
        $criteria->compare('time','<'.strtotime($this->endDate));
        $criteria->compare('status',$this->status);
        $criteria->order=" time DESC";
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array('pageSize'=>20)
            ));
    }
}
