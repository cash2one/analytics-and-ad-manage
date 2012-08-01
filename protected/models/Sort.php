<?php
class Sort extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'sort';
	}
	
	public function rules()
	{
		return array(
			array('sort_type_id, game_id, weight', 'required'),
			array('sort_type_id, game_id, weight', 'numerical', 'integerOnly'=>true),
			array('id, sort_type_id, game_id, weight', 'safe', 'on'=>'search'),
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
			'sort_type_id' => '规则类型ID',
			'game_id' => '游戏ID',
			'weight' => '权重',
		);
	}

	public function search()
	{
	    
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('sort_type_id',$this->sort_type_id);
		$criteria->compare('game_id',$this->game_id);
		$criteria->compare('weight',$this->weight);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getSort($sort_type_id)
	{
	    $sql = "SELECT id, name FROM game WHERE enable=1 AND deleted=0";
	    $games = Yii::app()->db->createCommand($sql)->queryAll();
	    
	    $sql = "SELECT game_id, weight FROM sort WHERE sort_type_id = {$sort_type_id}";
	    $_sorts = Yii::app()->db->createCommand($sql)->queryAll();
	    $sorts = $weight = array();
	    if($_sorts)
	    {
    	    foreach ($_sorts as $sort)
    	    {
    	        $sorts[$sort['game_id']] = $sort['weight'];
    	    }
	    }
	    foreach ($games as $k => $game)
	    {
	        $_weight = isset($sorts[$game['id']]) ? $sorts[$game['id']] : $game['id']* 10;
	        $weight[] = $_weight;
	        $games[$k]['weight'] = $_weight;
	    }
	    
	    array_multisort($weight, SORT_ASC, SORT_NUMERIC, $games);
	    return $games;
	}
	
	public static function updateSort($sort_type_id, $sort)
	{
	    $sort_type_id = intval($sort_type_id);
	    $_ = array();
	    foreach ($sort as $game_id => $weight)
	    {
	        $_[] = "({$sort_type_id}, {$game_id}, {$weight})";
	    }
	    $sql = "REPLACE INTO sort(sort_type_id, game_id, weight) VALUES". implode(',', $_);
	    return Yii::app()->db->createCommand($sql)->execute();
	}
}