<?php
class Channel extends CActiveRecord
{
	public static $PAYTYPE = array(1 => '包月', 2 => 'CPA', 3 => 'CPM');
    
	
	public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'channel';
    }

    public function rules()
    {
        return array(
            array('name,cost,type,pay_type', 'required'),
            array('type,deleted,pay_type', 'numerical', 'integerOnly'=>true),
            array('cost', 'match','pattern' => '/^\d{1,10}(\.\d{0,2})?$/','message'=>'{attribute} 整数位最多10位，小数位2位'),
            array('name', 'length', 'max'=>64),

            array('name','unique'),
            array('website', 'length', 'max'=>256),
            array('id, name,deleted,cost,type,pay_type, website', 'safe', 'on'=>'search'),
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
            'name' => '渠道名',
            'cost'=>'单价',
            'type' => '渠道类型',
            'pay_type' => '付费类型',
            'website' => '渠道网址',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('deleted',0);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('pay_type', $this->pay_type);
        $criteria->compare('website',$this->website,true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 20,
                    )));
    }

    protected function afterSave()
    {
        if($this->isNewRecord)
        {
           $model=new ChannelCost;
           $model->channel_id=$this->id;
           $model->cost=$this->cost;
           $model->date=strtotime(date('Y-m-d'));
           $model->save();
        }
        else
        {
           $model=ChannelCost::model()->findByAttributes(array('channel_id'=>$this->id,'date'=>strtotime(date('Y-m-d'))));
           if($model)
           {
             $model->cost=$this->cost;
           }
           else
           {
             $model=new ChannelCost;
             $model->channel_id=$this->id;
             $model->cost=$this->cost;
             $model->date=strtotime(date('Y-m-d'));
           }
           $model->save();
        }
        parent::afterSave();
    }

     protected function beforeDelete()
    {
        if(parent::beforeDelete())
        {
          $pos=Pos::model()->findAllByAttributes(array('channel_id'=>$this->id,'deleted'=>0));
          if($pos)
          {
              foreach($pos as $item)
              {
                  $item->delete();
              }
          }
          return true;
        }
    }

    public function delete()
    {
        if(!$this->getIsNewRecord())
        {
            Yii::trace(get_class($this).'.delete()','system.db.ar.CActiveRecord');
            if($this->beforeDelete())
            {
                $result=$this->updateByPk($this->getPrimaryKey(),array('deleted'=>1))>0;
                $this->afterDelete();
                return $result;
            }
            else
                return false;
        }
        else
            throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
    }

    public static function dropDownData()
    {
        $db=Yii::app()->db;
        $dependency = new CDbCacheDependency('SELECT COUNT(id) FROM channel WHERE deleted = 0');
        $req=$db->cache(1000,$dependency)->createCommand("SELECT id,name FROM channel WHERE deleted = 0");
        $data=array();
        $res=$req->queryAll();
        if($res)
        {
            foreach($res as $row)
            {
                $data[$row['id']]=$row['name'];
            }
        }
        return $data;
    }

    public static function getName($id)
    {
        $name = '平台注册';
        if($id)
        {
            $db = Yii::app()->db;
            $req = $db->createCommand("SELECT name FROM channel where id=:id LIMIT 1");
            $name = $req->queryScalar(array(':id' => $id));
        }
        return $name;
    }

    public static function defChannel()
    {
        $db=Yii::app()->db;
        $dependency = new CDbCacheDependency('SELECT MIN(id) FROM channel WHERE deleted = 0');
        return $db->cache(1000,$dependency)->createCommand("SELECT id FROM channel WHERE deleted = 0")->queryScalar();
    }

    public static function getCost($channelId,$date=null)
    {
        $cost=0;
        if($channelId)
        {
          $db=Yii::app()->db;
          if($date)
          {
              $where=" WHERE channel_id={$channelId} and date<={$date}";
          }
          else
          {
              $where=" WHERE channel_id={$channelId} ";
          }
          $cost=$db->createCommand("SELECT cost FROM channel_cost {$where} ORDER BY date DESC LIMIT 1")->queryScalar();
        }
        return $cost;
   }

   public static function costList($from,$to,$paymentFrom,$paymentTo)
   {
      $paymentTo+=86399;
      $to+=86399;
      $where="where date>={$from} and date<={$to} ";
      $sql="SELECT data_daily.id,channel_id,sum(data_daily.cost) as cost,sum(register) as register,sum(revisit_increment) as revisit
          ,name,channel.cost as now_cost,channel.pay_type FROM data_daily LEFT JOIN channel ON channel.id=data_daily.channel_id
          {$where} group by channel_id order by channel_id";
      $countSql="SELECT id FROM data_daily {$where} group by channel_id";
      $count=0;
      $countRes=Yii::app()->db->createCommand($countSql)->queryColumn();
      if($countRes)
      {
          $count=count($countRes);
      }
      $pages=new CPagination($count);
      $pages->pageSize=50;
      $pages->getCurrentPage(true);
      $offset=$pages->getOffset();
      $data=Yii::app()->db->createCommand($sql)->queryAll();
      if($data)
      {
        foreach($data as $k=>$item)
        {
          $res=self::getIncomePayment($item['channel_id'],$from,$to,$paymentFrom,$paymentTo);
          $income=$res['income'];
          $payment=$res['payment'];
          $cost=$item['cost'];
          $cpa=($item["register"])?round(($item["cost"]/$item["register"]),1):0;
          $revisitPercent=($item["register"])?round((100*$item["revisit"]/$item["register"]),2):0;
          $revisitCost=($item["revisit"])?round(($item["cost"]/$item["revisit"]),2):0;
          $profitPercent=($cost)?round(100*$income/$cost,2):0;
          $data[$k]['cpa']=$cpa;
          $data[$k]['revisitPercent']=$revisitPercent;
          $data[$k]['revisitCost']=$revisitCost;
          $data[$k]['profitPercent']=$profitPercent;
          $data[$k]['income']=$income;
          $data[$k]['payment']=$payment;
        }
      }

      return new CArrayDataProvider($data,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'channel_id','register','cost','cpa','payment','income','profitPercent','revisit'),
                            'defaultOrder'=>'channel_id asc'
                            ),
                    'pagination'=>$pages
       ));
   }

   public static function  costPlatform($from,$to)
   {
      $db=Yii::app()->db;
      $to+=86399;
      $innerChannel=$db->createCommand("SELECT id FROM channel where type=1")->queryColumn();
      $outerChannel=$db->createCommand("SELECT id FROM channel where type=0")->queryColumn();
      $sumColumn=array('id'=>4,'name'=>'总计','register'=>0,'cost'=>0,'cpa'=>0,'income'=>0,'payment'=>0,'profit_percent'=>0,'lifetime_cost'=>0);
      if($innerChannel)
      {
          $in=implode(',',$innerChannel);
          $where="where date>={$from} and date<={$to} ";
          $where.=" AND channel_id IN ({$in})";
          $sql="SELECT sum(cost) as cost,sum(register) as register FROM data_daily {$where}";
          $res=$db->createCommand($sql)->queryRow();
          if(!$res)
          {
              $res=array('cost'=>0,'regsiter'=>$register);
          }

          $where="  where channel_id IN ({$in}) AND register_time>={$from} AND register_time<={$to}";
          $sql = "SELECT SUM(paid-payment_tax) as payment,sum(profit) as income FROM `order` {$where}";
          $paymentRes=$db->createCommand($sql)->queryRow();
          if(!$paymentRes)
          {
              $paymentRes=array('payment'=>0,'income'=>0);
          }
          $cost=$res['cost'];
          $profitPercent=($cost)?round(100*$paymentRes['income']/$cost,2):0;
          $cpa=($res['register'])?round($res['cost']/$res['register'],1):0;
          $sumColumn['register']+=$res['register'];
          $sumColumn['cost']+=$res['cost'];
          $sumColumn['payment']+=$paymentRes['payment'];
          $sumColumn['income']+=$paymentRes['income'];
          $sumColumn['lifetime_cost']+=$cost;
          $data[]=array(
                    'id'=>1,
                    'name'=>'内部',
                    'register'=>$res['register'],
                    'cpa'=>$cpa,
                    'cost'=>$cost,
                    'payment'=>$paymentRes['payment'],
                    'income'=>$paymentRes['income'],
                    'profit_percent'=>$profitPercent
                  );
      }

      if($outerChannel)
      {
          $in=implode(',',$outerChannel);
          $where="where date>={$from} and date<={$to} ";
          $where.=" AND channel_id IN ({$in})";
          $sql="SELECT sum(cost) as cost,sum(register) as register FROM data_daily {$where}";
          $res=$db->createCommand($sql)->queryRow();
          if(!$res)
          {
              $res=array('cost'=>0,'regsiter'=>$register);
          }

          $where="  where channel_id IN ({$in}) AND register_time>={$from} AND register_time<={$to}";
          $sql = "SELECT SUM(paid-payment_tax) as payment,sum(profit) as income FROM `order` {$where}";
          $paymentRes=$db->createCommand($sql)->queryRow();
          if(!$paymentRes)
          {
              $paymentRes=array('payment'=>0,'income'=>0);
          }
          $cost=$res['cost'];
          $profitPercent=($cost)?round(100*$paymentRes['income']/$cost,2):0;
          $cpa=($res['register'])?round($res['cost']/$res['register'],1):0;
          $sumColumn['register']+=$res['register'];
          $sumColumn['cost']+=$res['cost'];
          $sumColumn['payment']+=$paymentRes['payment'];
          $sumColumn['income']+=$paymentRes['income'];
          $sumColumn['lifetime_cost']+=$cost;
          $data[]=array(
                    'id'=>2,
                    'name'=>'外部',
                    'register'=>$res['register'],
                    'cpa'=>$cpa,
                    'cost'=>$cost,
                    'payment'=>$paymentRes['payment'],
                    'income'=>$paymentRes['income'],
                    'profit_percent'=>$profitPercent
                  );
      }
      $sql = "SELECT SUM(paid-payment_tax) as payment,sum(profit) as income FROM `order` where ad_pos_id=0 
            AND register_time>={$from} AND register_time<={$to}";
      $paymentRes=$db->createCommand($sql)->queryRow();
      if(!$paymentRes)
      {
          $paymentRes=array('payment'=>0,'income'=>0);
      }
      $platform=array();
      $platform['id']=3;
      $platform['name']='平台';
      $platform['register']=User::nbPlatform(null,$from,$to);
      $platform['cpa']=0;
      $platform['cost']=0;
      $platform['payment']=$paymentRes['payment'];
      $platform['income']=$paymentRes['income'];
      $platform['profit_percent']=0;
      $data[]=$platform;
      $sumColumn['register']+=$platform['register'];
      $sumColumn['payment']+=$platform['payment'];
      $sumColumn['income']+=$platform['income'];
      $sumColumn['cpa']=($sumColumn['register'])?round($sumColumn['cost']/$sumColumn['register'],1):0;
      $sumColumn['profit_percent']=($sumColumn['lifetime_cost'])?round(100*$sumColumn['income']/$sumColumn['lifetime_cost'],2):0;
      $data[]=$sumColumn;
      return new CArrayDataProvider($data,array(
                    'pagination'=>false
       ));
   }


   static public function getIncomePayment($channel,$regFrom,$regTo,$payFrom,$payTo)
   {
          $db=Yii::app()->db;
          $where=" where channel_id={$channel} AND register_time>={$regFrom} AND register_time<={$regTo} AND
              update_time>={$payFrom} and update_time<={$payTo}";
          $sql="SELECT game_id,profit,paid,payment_tax,update_time FROM `order` {$where}";
          $res=Yii::app()->db->createCommand($sql)->queryAll();
          $payment=$income=0;
          if($res)
          {
           foreach($res as $item)
           {
               $payment+=$item['paid']-$item['payment_tax'];
               $date=strtotime(date('Y-m-d',$item['update_time']));
               $gameId=$item['game_id'];
               $income+=$item['profit'];
           }
          }
         return array('payment'=>$payment,'income'=>$income);
   }

   public function dailyList($from,$to)
   {
     $to+=86399;
     $where="where channel_id={$this->id} and date>={$from} and date<={$to}";
     $count=Yii::app()->db->createCommand("SELECT count(`id`) FROM channel_data {$where} ")->queryScalar();
     $sql="SELECT * FROM channel_data {$where} ORDER BY date DESC";
     return new CSqlDataProvider($sql,array(
                     'totalItemCount'=>$count
                    ,'pagination'=>array(
                        'pageSize'=>30
                        )
                    ));
   }

   static public function listMaterial($channelId,$from,$to,$export=false)
   {
      $sql="SELECT ad_pos_id FROM click_stat,pos where click_stat.date>={$from} and click_stat.date<={$to} and pos.id=click_stat.pos_id and pos.channel_id={$channelId}";
      $adPoses=Yii::app()->db->createCommand($sql)->queryColumn();
      $out='';
      if($adPoses)
      {
        $adIn=implode(',',$adPoses);
        $sql="SELECT distinct (ad.path) from ad_pos,ad where ad_pos.id IN ({$adIn}) and ad.id=ad_pos.ad_id";
        $material=Yii::app()->db->createCommand($sql)->queryColumn();
        if($material)
        {
          foreach($material as $path)
          {
              if($export==false)
              {
                $out.="<a href='http://www.90hao.com/adcontent/{$path}' target='_blank'>{$path}</a><br/>";
              }
              else
              {
                $out.=",{$path}";
              }
          }
          $out=trim($out);
        }
      }
      return $out;
   }

   static public function listServer($channelId,$from,$to,$export=false)
   {
        $sql="SELECT ad_pos_id FROM click_stat,pos where click_stat.date>={$from} and click_stat.date<={$to} and pos.id=click_stat.pos_id and pos.channel_id={$channelId}";
      $adPoses=Yii::app()->db->createCommand($sql)->queryColumn();
      $out='';
      if($adPoses)
      {
        $adIn=implode(',',$adPoses);
        $sql="SELECT distinct ad.server_id from ad_pos,ad where ad_pos.id IN ({$adIn}) and ad.id=ad_pos.ad_id";
        $server=Yii::app()->db->createCommand($sql)->queryColumn();
        if($server)
        {
          foreach($server as $serverId)
          {
              if($serverId)
              {
                $req =Yii::app()->db->createCommand("SELECT server.name as server,game.name as game FROM server,game 
                        where server.id={$serverId} and game.id=server.game_id LIMIT 1");
                $name = $req->queryRow();
                if($export==false)
                {
                  $out.="{$name['game']}::{$name['server']}"."<br/>";
                }
                else
                {
                  $out.=",{$name['game']}::{$name['server']}";
                }
                $out=trim($out);
              }
          }
        }
      }
      return $out;
   }
}
