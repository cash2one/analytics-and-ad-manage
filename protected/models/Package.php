<?php
class Package extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Package the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'package';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, game_id, default, weight, link', 'required'),
			array('game_id, default, weight', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('link', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, game_id, default, weight, link', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'game_id' => '游戏',
			'default' => '是否默认',
			'weight' => '权重',
			'link' => '链接',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('game_id',$this->game_id);
		$criteria->compare('default',$this->default);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('link',$this->link,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria
	        ,'sort'=>array(
	                'attributes'=>array(
	                      'id', 'name', 'game_id'
	                ),
	                'defaultOrder'=>'id DESC'
	        )
	        ,'pagination'=>array(
	                'pageSize'=>20
	        )
		));
	}
}