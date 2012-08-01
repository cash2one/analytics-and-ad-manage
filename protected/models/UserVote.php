<?php

/**
 * This is the model class for table "user_vote".
 *
 * The followings are the available columns in table 'user_vote':
 * @property integer $id
 * @property string $username
 * @property string $user_ip
 * @property integer $game_id
 * @property integer $server_id
 * @property integer $vote_type
 * @property string $reason
 * @property string $vote_date
 */
class UserVote extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserVote the static model class
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
		return 'user_vote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, user_ip, game_id, server_id, reason, vote_date', 'required'),
			array('game_id, server_id, vote_type', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>20),
			array('user_ip, vote_date', 'length', 'max'=>11),
			array('reason', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, user_ip, game_id, server_id, vote_type, reason, vote_date', 'safe', 'on'=>'search'),
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
			'username' => '姓名',
			'user_ip' => '用户IP',
			'game_id' => '游戏',
			'server_id' => '区服',
			'vote_type' => '类型',
			'reason' => '原因',
			'vote_date' => '日期',
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
		$criteria->compare('user_ip',$this->user_ip,true);
		$criteria->compare('game_id',$this->game_id);
		$criteria->compare('server_id',$this->server_id);
		$criteria->compare('vote_type',$this->vote_type);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('vote_date',$this->vote_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getWeekVote($gameid)
	{
	    $start = strtotime('-'.(date('N')-1). ' days', strtotime(date("Y-m-d")));
	    $end = time();
	    $sql = "SELECT COUNT(1) FROM user_vote WHERE game_id = {$gameid} AND vote_type = 1 AND vote_date >= {$start} AND vote_date < {$end}";
	    return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
    public function getMergeVote()
    {
        $where = 'vote_type = 2';
        if($this->game_id)
        {
            $where .= " AND game_id = {$this->game_id}";
        }
        if($this->server_id)
        {
            $where .= " AND server_id = {$this->server_id}";
        }
        $count = Yii::app()->db->createCommand("SELECT COUNT(1) FROM user_vote WHERE {$where}")->queryScalar();
        $sql = "SELECT * FROM user_vote WHERE {$where}";
        return new CSqlDataProvider($sql,array(
                'totalItemCount'=>$count
                ,'sort'=>array(
                        'attributes'=>array(
                                'id'
                        ),
                        'defaultOrder'=>'id DESC'
                )
                ,'pagination'=>array(
                        'pageSize'=>20
                )
        ));
    }
}