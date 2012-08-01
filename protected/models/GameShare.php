<?php
class GameShare extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'game_share';
    }

    public function rules()
    {
        return array(
            array('game_id, share, date', 'required'),
            array('date', 'numerical', 'integerOnly'=>true),
            array('game_id, share', 'length', 'max'=>10),
            array('id, game_id, share, date', 'safe', 'on'=>'search'),
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
            'game_id' => 'Game',
            'share' => 'Share',
            'date' => 'Date',
        );
    }

    protected function afterSave()
    {
        $db=Yii::app()->db;
        $preSharePoint=$db->createCommand("SELECT date FROM game_share where date>{$this->date} and game_id={$this->game_id} order by date asc LIMIT 1")->queryScalar();
        if($preSharePoint)
        {
            $dataTimeWhere="and date>={$this->date} and date<{$preSharePoint}";
            $orderTimeWhere="and update_time>={$this->date} and update_time<{$preSharePoint}";
        }
        else
        {
            $dataTimeWhere="and date>={$this->date}";
            $orderTimeWhere="and update_time>={$this->date}";
        }

        $dataDetect="SELECT count(1) FROM data_daily where game_id={$this->game_id} and
            share_base<>{$this->share} {$dataTimeWhere} ";
        $orderDetect="SELECT count(1) FROM `order` where game_id={$this->game_id} and
            share_base<>{$this->share} {$orderTimeWhere} ";

        if($db->createCommand($dataDetect)->queryScalar())
        {
           $updateDataSql="UPDATE data_daily SET share_base={$this->share},income=TRUNCATE(payment_increment*{$this->share}/100,2)
               where game_id={$this->game_id} and share_base<>{$this->share} {$dataTimeWhere}";
           $db->createCommand($updateDataSql)->execute();
        }

        if($db->createCommand($orderDetect)->queryScalar())
        {
           $updateOrderSql="UPDATE `order` SET share_base={$this->share},profit=(paid-payment_tax)*{$this->share}/100
               where game_id={$this->game_id} and share_base<>{$this->share} {$orderTimeWhere}";
           $db->createCommand($updateOrderSql)->execute();
        }
        parent::afterSave();
    }
    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('game_id',$this->game_id,true);
        $criteria->compare('share',$this->share,true);
        $criteria->compare('date',$this->date);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function monthList()
    {
      $date=strtotime(date('Y-m-d'));
      $db=Yii::app()->db;
      $res=$db->createCommand("SELECT * from game_share where game_id={$this->game_id} ORDER BY date ASC")->queryAll();
      $out=array();
      if($res)
      {
         $fromDate=$res[0]['date'];
         $index=1;
         $shareList=array();
         $preShare=0;
         foreach($res as $item)
         {
             $shareList[$item['date']]=$item['share'];
         }
         for($i=$fromDate;$i<=$date;$i+=86400)
         {
             if(isset($shareList[$i]))
             {
               $out[]=array('id'=>$index,'date'=>$i,'share'=>$shareList[$i],'modify'=>true);
               $preShare=$shareList[$i];
             }
             else
             {
               $out[]=array('id'=>$index,'date'=>$i,'share'=>$preShare,'modify'=>false);
             }
             $index++;
         }
         arsort($out);
      }
      return new CArrayDataProvider($out,array( 'pagination'=>array('pageSize'=>30)));
    }

    public  static function batchUpdate($game,$from,$to,$share)
    {
        $from=strtotime($from);
        $to=strtotime($to);
        if($from && $to && ($to-$from>86400) && $share && $game)
        {
            if($to>=self::lastModify($game))
            {
              self::updatNowshare($game,$share);
            }
            $db=Yii::app()->db;
            $fromDate=strtotime(date('Y-m-d',$from));
            $toDate=strtotime(date('Y-m-d',$to));
            $sql="DELETE FROM game_share where date>={$fromDate} and date<={$toDate} and game_id={$game}";
            $db->createCommand($sql)->execute();
            $fromModel=new self;
            $toModel=new self;
            $toModel->game_id=$fromModel->game_id=$game;
            $toModel->share=$fromModel->share=$share;
            $toModel->date=$toDate;
            $fromModel->date=$fromDate;
            //不要移动下面两个save的顺序
            $toModel->save();
            $fromModel->save();
        }
    }

    public static function lastModify($game)
    {
      $sql="SELECT date FROM game_share where game_id={$game} order by date DESC LIMIT 1 ";
      return (int)Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public static function updatNowShare($game,$share)
    {
      $sql="UPDATE game SET share={$share} where id={$game}";
      Yii::app()->db->createCommand($sql)->execute();
    }
}
