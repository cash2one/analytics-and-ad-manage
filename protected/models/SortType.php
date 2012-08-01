<?php
class SortType extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'sort_type';
	}

	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>128),
			array('id, name', 'safe', 'on'=>'search'),
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
			'name' => '规则名称',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function dropDownData()
	{
	    $db = Yii::app()->db;
	    $dependency = new CDbCacheDependency('SELECT COUNT(id) FROM sort_type');
	    $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM sort_type");
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
}