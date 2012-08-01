<?php

/**
 * This is the model class for table "game_open_ann".
 *
 * The followings are the available columns in table 'game_open_ann':
 * @property integer $id
 * @property string $game_name
 * @property string $server_name
 * @property string $announcement
 * @property integer $sort
 * @property integer $status
 */
class GameOpenAnn extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return GameOpenAnn the static model class
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
		return 'game_open_ann';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('game_name, server_name, announcement, sort', 'required'),
			array('sort, status', 'numerical', 'integerOnly'=>true),
			array('game_name, server_name', 'length', 'max'=>20),
			array('announcement', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, game_name, server_name, announcement, sort, status', 'safe', 'on'=>'search'),
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
			'game_name' => '游戏',
			'server_name' => '区服名',
			'announcement' => '开启描述',
			'sort' => '排序',
			'status' => '状态',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('game_name',$this->game_name,true);
		$criteria->compare('server_name',$this->server_name,true);
		$criteria->compare('announcement',$this->announcement,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
	        'sort'=>array(
	                'attributes'=>array(
	                   'sort'
	                ),
	                'defaultOrder'=>'sort desc'
	        )
		));
	}
}