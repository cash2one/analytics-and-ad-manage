<?php

/**
 * This is the model class for table "user_rcd".
 *
 * The followings are the available columns in table 'user_rcd':
 * @property integer $id
 * @property string $username
 * @property integer $user_ip
 * @property string $game_name
 * @property string $reason
 * @property string $time
 */
class UserRcd extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserRcd the static model class
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
		return 'user_rcd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, user_ip, game_name, reason, time', 'required'),
			array('user_ip', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>50),
			array('game_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, user_ip, game_name, reason, time', 'safe', 'on'=>'search'),
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
			'username' => '用户名',
			'user_ip' => 'IP',
			'game_name' => '游戏名',
			'reason' => '原因',
			'time' => '时间',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('user_ip',$this->user_ip);
		$criteria->compare('game_name',$this->game_name,true);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		    'sort'=>array(
                'attributes'=>array(
                        'id'
                ),
                'defaultOrder'=>'id DESC'
		    ),
	        'pagination' => array(
	                'pageSize' => 20,
	        )
		));
	}
}