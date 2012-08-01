<?php
class VipUser extends CActiveRecord
{
    public $pay_time;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'vip_user';
	}
	public function rules()
	{
		return array(
			array('user_id, game_id, server_id, user_name, vip_rank, sum_paid, last_paid_time, reg_channel, real_name, e_mail, qq, mobile_phone, reg_time', 'required'),
			array('user_id, game_id, server_id, vip_rank, sum_paid, last_paid_time, reg_time', 'numerical', 'integerOnly'=>true),
			array('user_name, real_name, mobile_phone', 'length', 'max'=>20),
			array('e_mail, reg_channel', 'length', 'max'=>50),
			array('qq', 'length', 'max'=>10),
			array('id, user_id, game_id, user_name, vip_rank, sum_paid, last_paid_time, real_name, e_mail, qq, mobile_phone, reg_time', 'safe', 'on'=>'search'),
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
			'user_id' => '用户ID',
		    'game_id' => '游戏',
		    'server_id' => '区服',
			'user_name' => '用户账号',
			'vip_rank' => 'VIP等级',
			'sum_paid' => '累计金额',
			'last_paid_time' => '最后充值时间',
		    'reg_channel' => '注册渠道',
			'real_name' => '真实姓名',
			'e_mail' => 'Email',
			'qq' => 'QQ',
			'mobile_phone' => '手机号',
			'reg_time' => '注册时间',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('game_id',$this->game_id);
		$criteria->compare('server_id',$this->server_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('vip_rank',$this->vip_rank);
		$criteria->compare('sum_paid',$this->sum_paid);
		$criteria->compare('last_paid_time',$this->last_paid_time);
		$criteria->compare('reg_channel',$this->reg_channel);
		$criteria->compare('real_name',$this->real_name,true);
		$criteria->compare('e_mail',$this->e_mail,true);
		$criteria->compare('qq',$this->qq,true);
		$criteria->compare('mobile_phone',$this->mobile_phone,true);
		$criteria->compare('reg_time',$this->reg_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
	        'pagination' => array(
	                'pageSize' => 20,
	        )
		));
	}
	
	public function vipList()
	{
	    $where = ' WHERE 1';
	    $filed = ' ';
	    if($this->user_id)
	    {
	        $where .= " AND user_id = '{$this->user_id}'";
	    }
	    if($this->user_name)
	    {
	        $where .= " AND user_name = '{$this->user_name}'";
	    }
	    if($this->last_paid_time)
	    {
	        $where .= " AND last_paid_time <= {$this->last_paid_time}";
	    }
	    if($this->game_id)
	    {
	        $filed .= "game_id, ";
	        $where .= " AND game_id = {$this->game_id}";
	        if($this->server_id)
	        {
	            $filed .= "server_id, ";
	            $where .= " AND server_id = {$this->server_id}";
	        }
	    }
	    
        $sql = "SELECT {$filed} user_id, user_name, vip_rank, MAX(last_paid_time) as last_paid_time, SUM(sum_paid) AS sum_paid, reg_channel, e_mail, qq, mobile_phone
                FROM vip_user
                {$where}
                GROUP BY user_id
                ORDER BY sum_paid DESC";
        $count_sql = "SELECT COUNT(DISTINCT(user_id)) FROM vip_user {$where}";
	    
	    $count = Yii::app()->db->createCommand($count_sql)->queryScalar();
	    return new CSqlDataProvider($sql, array(
	            'totalItemCount'=>$count,
	            'pagination'=>array(
	                    'pageSize'=>20
	            )
	    ));
	}
}