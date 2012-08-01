<?php

/**
 * This is the model class for table "material".
 *
 * The followings are the available columns in table 'material':
 * @property string $id
 * @property integer $mid
 * @property string $name
 */
class Material extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Material the static model class
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
		return 'material';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mid, name', 'required'),
			array('mid', 'match', 'pattern'=>'/^[0-9]{1,3}[a-z]{0,2}$/'),
			array('name', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, mid, name', 'safe', 'on'=>'search'),
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
			'mid' => '素材ID',
			'name' => '素材名',
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
		$criteria->compare('mid',$this->mid);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria
	        ,'sort'=>array(
	                'attributes'=>array(
	                        'mid','name'
	                ),
	                'defaultOrder'=>'cast(mid as unsigned) DESC'
	        )
	        ,'pagination'=>array(
	                'pageSize'=>20
	        )
	        
		));
	}
	
	public static function getMaterialName()
	{
	    $sql = "SELECT * FROM material";
	    $_ = Yii::app()->db->createCommand($sql)->queryAll();
	    $result = array();
	    if(is_array($_) && count($_) > 0)
	    {
	        foreach ($_ as $v)
	        {
	            $result[$v['mid']] = $v['name'];
	        }
	    }
	    return $result;
	}
}