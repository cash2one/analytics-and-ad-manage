<?php
class Click extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'click';
    }

    public function rules()
    {
        return array(
                array('ad_pos_id,ip', 'required'),
                array('time', 'numerical', 'integerOnly'=>true),
                array('ad_pos_id, ip', 'length', 'max'=>10),
                array('id, ad_pos_id, ip, time', 'safe', 'on'=>'search'),
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
                'ad_pos_id' => 'Ad Pos',
                'ip' => 'Ip',
                'time' => 'Time',
                );
    }

    public function search()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('ad_pos_id',$this->ad_pos_id,true);
        $criteria->compare('ip',$this->ip,true);
        $criteria->compare('time',$this->time);

        return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
                    ));
    }

    public static function countClickByAdPos($ad_pos_id = null, $from = null, $to = null,$type='date')
    {

        $where="WHERE 1";
        $return=0;
        if($type=='hour' AND $to>=strtotime(date("Y-m-d H:00:00")))
        {
            if($ad_pos_id)
            {
                $where .= " AND  ad_pos_id = {$ad_pos_id}";
            }
            $where .= $from ? " AND `time` >= {$from}" : '';
            $where .= $to ? " AND `time` <= {$to}" : '';
            $sql = "SELECT COUNT(*) FROM click {$where}";
            $return = Yii::app()->db->createCommand($sql)->queryScalar();
        }
        else
        {
            if($ad_pos_id)
            {
                $where .= " AND  ad_pos_id = {$ad_pos_id}";
            }
            else
            {
                $where .= " AND  ad_pos_id >34";
            }
            $where .= $from ? " AND `{$type}` >= {$from}" : '';
            $where .= $to ? " AND `{$type}` < {$to}" : '';
            $sql = "SELECT SUM(click_times) FROM click_stat {$where}";
            $dependency=new CDbCacheDependency("SELECT count(1) FROM click_stat {$where}");
            $return = Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
        }

        return (int)$return;
    }

    public static function countUIByAdPos($ad_pos_id, $from = null, $to = null,$type='date')
    {
        $where="WHERE 1";
        $return=0;
        if($type=='hour' AND $to>=strtotime(date("Y-m-d H:00:00")))
        {
            if($ad_pos_id)
            {
                $where .= " AND  ad_pos_id = {$ad_pos_id}";
            }
            $where .= $from ? " AND `time` >= {$from}" : '';
            $where .= $from ? " AND `time` <= {$to}" : '';
            $sql = "SELECT COUNT(DISTINCT ip) FROM click {$where}";
            $return = Yii::app()->db->createCommand($sql)->queryScalar();
        }
        else
        {
            if($ad_pos_id)
            {
                $where .= " AND  ad_pos_id = {$ad_pos_id}";
            }
            $where .= $from ? " AND `{$type}` >= {$from}" : '';
            $where .= $to ? " AND `{$type}` < {$to}" : '';
            $sql = "SELECT SUM(uv_click_times) FROM click_stat {$where}";
            $dependency=new CDbCacheDependency("SELECT count(1) FROM click_stat {$where}");
            $return = Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
        }
        return $return;
    }

    public static function countClickByPos($pos_id, $from = null, $to = null,$type='date')
    {
        $where="WHERE 1";
        if($pos_id)
        {
            $where .= " AND  pos_id = {$pos_id}";
        }
        $where .= $from ? " AND `{$type}` >= {$from}" : '';
        $where .= $to ? " AND `{$type}` < {$to}" : '';
        $dependency=new CDbCacheDependency("SELECT count(1) FROM click_stat {$where}");
        $sql = "SELECT SUM(click_times) FROM click_stat {$where}";
        return (float)Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
    }

    public static function maxClick($pos_id)
    {
        $sql = "SELECT SUM(click_times) AS count, `date` FROM click_stat WHERE pos_id = {$pos_id} GROUP BY `date`";
        $_tmp = Yii::app()->db->createCommand($sql)->queryAll();
        $max = array();
        if(is_array($_tmp) && count($_tmp) > 0)
        {
            $max = max($_tmp);
        }

        $return = $max ? $max['count']. ' -- '. date('Y/m/d', $max['date']) : '--';
        return $return;
    }

}
