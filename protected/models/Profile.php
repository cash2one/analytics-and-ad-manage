<?php
class Profile extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'profile';
    }

    public function rules()
    {
        return array(
            array('name, card_id, user_id, gender', 'required'),
            array('gender, birthday', 'numerical', 'integerOnly'=>true),
            array('name, education, incoming, qq, tel', 'length', 'max'=>16),
            array('card_id', 'length', 'max'=>18),
            array('user_id', 'length', 'max'=>10),
            array('avatar, homepage, address', 'length', 'max'=>128),
            array('occupation', 'length', 'max'=>32),
            array('id, name, nick, card_id, user_id, avatar, gender, birthday, education, occupation, incoming, qq, homepage, province, city, address, tel', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id')
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'nick' => 'Nick',
            'card_id' => 'Card',
            'user_id' => 'User',
            'avatar' => 'Avatar',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
            'education' => 'Education',
            'occupation' => 'Occupation',
            'incoming' => 'Incoming',
            'qq' => 'Qq',
            'homepage' => 'Homepage',
            'province' => 'Province',
            'city' => 'City',
            'address' => 'Address',
            'tel' => 'Tel',
        );
    }

    public function search()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('card_id',$this->card_id,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('avatar',$this->avatar,true);
        $criteria->compare('gender',$this->gender);
        $criteria->compare('birthday',$this->birthday);
        $criteria->compare('education',$this->education,true);
        $criteria->compare('occupation',$this->occupation,true);
        $criteria->compare('incoming',$this->incoming,true);
        $criteria->compare('qq',$this->qq,true);
        $criteria->compare('homepage',$this->homepage,true);
        $criteria->compare('address',$this->address,true);
        $criteria->compare('province',$this->province,true);
        $criteria->compare('city',$this->city,true);
        $criteria->compare('tel',$this->tel,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
