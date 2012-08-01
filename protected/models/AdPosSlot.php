<?php
class AdPosSlot extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'ad_pos_slot';
	}
	public function rules()
	{
		return array(
			array('ad_id, pos_id, channel_id, bind_time', 'required'),
			array('bind_time', 'numerical', 'integerOnly'=>true),
			array('ad_id, pos_id, channel_id', 'length', 'max'=>10),
			array('id, ad_id, pos_id, channel_id, bind_time', 'safe', 'on'=>'search'),
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
			'ad_id' => '广告名称',
			'pos_id' => '广告位ID',
			'channel_id' => '渠道名称',
			'bind_time' => '排期时间',
		);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('ad_id',$this->ad_id,true);
		$criteria->compare('pos_id',$this->pos_id,true);
		$criteria->compare('channel_id',$this->channel_id,true);
		$criteria->compare('bind_time',$this->bind_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
	        'pagination' => array(
	                'pageSize' => 20,
	        )
		));
	}
	
	public function slotList()
	{
	    $db = Yii::app()->db;
	    $data = array();
	    if($this->pos_id)
	    {
	        $dependency = new CDbCacheDependency('SELECT count(`id`) FROM ad_pos_slot');
	        $data = $db->cache(1000,$dependency)->createCommand("SELECT aps.id, aps.ad_id, aps.pos_id, aps.channel_id, aps.bind_time, a.name, a.path, a.game_id, a.server_id FROM ad_pos_slot aps LEFT JOIN ad a ON aps.ad_id = a.id WHERE pos_id = {$this->pos_id}")->queryAll();
	    }
	    return new CArrayDataProvider($data, array(
                'sort' => array(
                    'attributes' => array('bind_time', 'id'),
                     'defaultOrder'=>'bind_time DESC'
                ),
                'pagination'=>array(
                   'pageSize'=>20
                   )
                ));
	}
}