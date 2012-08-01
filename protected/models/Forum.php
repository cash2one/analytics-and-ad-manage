<?php
class Forum extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

		public function tableName()
	{
		return 'forum';
	}
	
	public function rules()
	{
		return array(
			array('name, sort_type_id', 'required'),
			array('sort_type_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('id, name, sort_type_id', 'safe', 'on'=>'search'),
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
			'name' => '名称',
			'sort_type_id' => '排序规则',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sort_type_id',$this->sort_type_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}	
	
	public static function dropDownData()
	{
	    $db = Yii::app()->db;
	    $dependency = new CDbCacheDependency('SELECT COUNT(id) FROM forum');
	    $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM forum");
	    $data = array();
	    $res = $req->queryAll();
	    if($res)
	    {
	        foreach($res as $row)
	        {
	            $data[$row['id']] = $row['name'];
	        }
	    }
	    return $data;
	}
	
	public static function getForum()
	{
	    $sql = "SELECT * FROM forum";
	    $data = Yii::app()->db->createCommand($sql)->queryAll();
	    return $data;
	}
	
	public static function updateForum($id, $sort_type_id)
	{
	    $sql = "UPDATE forum SET sort_type_id = {$sort_type_id} WHERE id = {$id}";
	    return Yii::app()->db->createCommand($sql)->execute();
	}
}