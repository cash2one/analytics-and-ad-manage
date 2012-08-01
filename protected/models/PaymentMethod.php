<?php
class PaymentMethod extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'payment_method';
    }

    public function rules()
    {
        return array(
            array('gateway, media', 'required'),
            array('gateway, media', 'length', 'max'=>64),
            array('id, gateway, media', 'safe', 'on'=>'search'),
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
            'gateway' => 'Gateway',
            'media' => 'Media',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('gateway',$this->gateway,true);
        $criteria->compare('media',$this->media,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
