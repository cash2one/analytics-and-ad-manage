<?php
class ChannelCost extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'channel_cost';
    }

    public function rules()
    {
        return array(
            array('channel_id, cost, date', 'required'),
            array('channel_id,date', 'numerical', 'integerOnly'=>true),
            array('cost', 'match','pattern' => '/^\d{1,10}(\.\d{0,2})?$/','message'=>'{attribute} 整数位最多10位，小数位2位'),
            array('id, channel_id, cost, date', 'safe', 'on'=>'search'),
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
            'channel_id' => 'Channel',
            'cost' => 'Cost',
            'date' => 'Date',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('channel_id',$this->channel_id);
        $criteria->compare('cost',$this->cost);
        $criteria->compare('date',$this->date);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

     protected function afterSave()
    {
        $db=Yii::app()->db;
        $preCostPoint=$db->createCommand("SELECT date FROM channel_cost where date>{$this->date} and channel_id={$this->channel_id} order by date asc LIMIT 1")->queryScalar();
        if($preCostPoint)
        {
            $where="where channel_id={$this->channel_id} and cost_base<>{$this->cost} AND  date>={$this->date} and date<{$preCostPoint}";
        }
        else
        {
            $where="where channel_id={$this->channel_id} and cost_base<>{$this->cost} AND date>={$this->date}";
        }
        $dataDetect="SELECT count(1) FROM data_daily {$where}";

        if($db->createCommand($dataDetect)->queryScalar())
        {
           $updateDataSql="UPDATE data_daily SET cost=TRUNCATE({$this->cost}*register/channel_register,2),cost_base={$this->cost} {$where} and channel_register>0 ";
           $db->createCommand($updateDataSql)->execute();
        }

        parent::afterSave();
    }

    public function monthList()
    {
      $date=strtotime(date('Y-m-d'));
      $db=Yii::app()->db;
      $res=$db->createCommand("SELECT * from channel_cost where channel_id={$this->channel_id} ORDER BY date ASC ")->queryAll();
      $out=array();
      if($res)
      {
         $fromDate=$res[0]['date'];
         $index=1;
         $costList=array();
         $preCost=0;
         foreach($res as $item)
         {
             $costList[$item['date']]=$item['cost'];
         }
         for($i=$fromDate;$i<=$date;$i+=86400)
         {
             if(isset($costList[$i]))
             {
               $out[]=array('id'=>$index,'date'=>$i,'cost'=>$costList[$i],'modify'=>true);
               $preCost=$costList[$i];
             }
             else
             {
               $out[]=array('id'=>$index,'date'=>$i,'cost'=>$preCost,'modify'=>false);
             }
             $index++;
         }
         arsort($out);
      }
      return new CArrayDataProvider($out,array( 'pagination'=>array('pageSize'=>30)));
    }

    public  static function batchUpdate($channel,$from,$to,$cost)
    {
        $from=strtotime($from);
        $to=strtotime($to);
        if($from && $to && ($to-$from>86400) && $cost && $channel)
        {
            if($to>=self::lastModify($channel))
            {
              self::updatNowCost($channel,$cost);
            }
            $db=Yii::app()->db;
            $fromDate=strtotime(date('Y-m-d',$from));
            $toDate=strtotime(date('Y-m-d',$to));
            $sql="DELETE FROM channel_cost where date>={$fromDate} and date<={$toDate} and channel_id={$channel}";
            $db->createCommand($sql)->execute();
            $fromModel=new self;
            $toModel=new self;
            $toModel->channel_id=$fromModel->channel_id=$channel;
            $toModel->cost=$fromModel->cost=$cost;
            $toModel->date=$toDate;
            $fromModel->date=$fromDate;
            //不要移动下面两个save的顺序
            $toModel->save();
            $fromModel->save();
        }
    }

    public static function lastModify($channel)
    {
      $sql="SELECT date FROM channel_cost where channel_id={$channel} order by date DESC LIMIT 1 ";
      return (int)Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public static function updatNowCost($channel,$cost)
    {
      $sql="UPDATE channel SET cost={$cost} where id={$channel}";
      Yii::app()->db->createCommand($sql)->execute();
    }
}
