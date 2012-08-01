<?php
class Server extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'server';
    }

    public function rules()
    {
        return array(
                array('name, admin_id,game_id,open_time,promote_end_time,active', 'required')
              , array('index, status, active, open_time,promote_end_time, close_time, create_time, show, flag', 'numerical', 'integerOnly' => true)
              , array('name', 'length', 'max' => 64)
              , array('server_url, entergame_url', 'length', 'max' => 128)
              , array('admin_id,game_id, register_times', 'length', 'max' => 10)
              , array('login_times', 'length', 'max' => 20)
              , array('id, name, index, server_url, admin_id, game_id,login_times, register_times, status, active, open_time,
                  promote_end_time, close_time, create_time', 'safe', 'on' => 'search'));
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
                'id' => 'ID'
               ,'name' => '区服名'
               ,'index' => '顺序'
               ,'server_url' => '区服地址'
               ,'entergame_url' => '进入游戏地址'
               ,'admin_id' => '管理员'
               ,'game_id' => '游戏'
               ,'login_times' => '登录用户数'
               ,'register_times' => '注册用户数'
               ,'status' => '状态'
               ,'active' => '激活'
               ,'show' => '是否在开服预告中显示'
               ,'flag' => '图标(近期开服公告)'
               ,'open_time' => '开服时间'
               ,'promote_end_time' => '推广结束时间'
               ,'close_time' => '闭服时间'
               ,'create_time' => '创建时间');
    }

    public function search()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('index', $this->index);
        $criteria->compare('admin_id', $this->admin_id);
        $criteria->compare('game_id', $this->game_id);
        $criteria->compare('status', $this->status);
        //$criteria->compare('active', 1);
        $dependency=new CDbCacheDependency('SELECT MAX(`id`) FROM server');
        return new CActiveDataProvider($this->cache(1000,$dependency,2),
                array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 20,
                        )));
    }

    protected function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            if($this->isNewRecord)
            {
                $this->create_time=$this->open_time=time();
                $this->status=0;
                $this->active=1;
                $this->admin_id=Yii::app()->user->getId();
            }
            if($this->open_time)
            {
               $this->open_time=strtotime($this->open_time);
            }
            if($this->promote_end_time)
            {
               $this->promote_end_time=strtotime($this->promote_end_time);
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
                $result=$this->updateByPk($this->getPrimaryKey(),array('deleted'=>1,'active'=>0))>0;
                $this->afterDelete();
                return $result;
            }
            else
                return false;
        }
        else
            throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
    }

    public function indexList($server,$from,$to)
    {
        $where = "WHERE t.active=1 AND t.deleted=0 AND m1.enable = 1";
        if(!empty($server))
        {
           $where .= " AND t.id IN ({$server})";
        }
        $dependency=new CDbCacheDependency('SELECT count(`id`) FROM server WHERE active=1 AND deleted=0');
        $sql = "SELECT t.*,m1.name as game_name FROM `server` AS t
              LEFT JOIN `game` as m1 ON m1.id = t.game_id {$where} ORDER BY m1.id,t.id ASC";
        $rawData=Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
        $nowDate=strtotime(date('Y-m-d'));
        foreach($rawData as $k=>$item)
        {
          $rawData[$k]['open_day']=ceil(($nowDate-$item['open_time']+1)/86400);
          $register=UserServer::registerDistributeByServer($item['id']);
          $rawData[$k]['ad_register']=$register['ad_register'];
          $rawData[$k]['normal_register']=$register['platform_register'];
          $rawData[$k]['migrate_register']=$register['migrate_register'];
          $rawData[$k]['chg_svr_register']=$register['chg_svr_register']; 
          //非实时注册数据
          $rawData[$k]['visit_user']=$rawData[$k]['ad_register']+$rawData[$k]['normal_register']+$rawData[$k]['migrate_register'];
          $rawData[$k]['revisit']=UserServer::revisitByServer($item['id']);
          $rawData[$k]['revisit_percent']=$rawData[$k]['visit_user']?round(100*$rawData[$k]['revisit']/$rawData[$k]['visit_user'],2)."%":"0%";

          //新用户回访率
          $rawData[$k]['new_revisit']=UserServer::RevisitByServer($item['id'],null,null,1);
          //老用户回访率
          $rawData[$k]['old_revisit']=UserServer::RevisitByServer($item['id'],null,null,2);

          $timeRegister=User::nbUser($item['id'],null,$to);
          $rawData[$k]['payment_user']=Order::nbUserPaid($item['id'],$from,$to);
          $rawData[$k]['payment_percent']=$timeRegister?round(100*$rawData[$k]['payment_user']/$timeRegister,2)."%":"0%";
          $rawData[$k]['payment_amount']=Order::sumPaid($item['id'],$from,$to);
          $rawData[$k]['arup']=$rawData[$k]['payment_user']?round($rawData[$k]['payment_amount']/$rawData[$k]['payment_user'],2):"0";
          
          
          $rawData[$k]['new_pay_1']=self::getNewPayNum($item['id'],$item['open_time'],$to);
          $rawData[$k]['new_pay_3']=self::getNewPayNum($item['id'],$item['open_time'],$item['open_time']+86400*3);
          
        }
        return new CArrayDataProvider($rawData,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'id','name','game_id','open_time'
                            )
                        )
                   ,'pagination'=>array(
                       'pageSize'=>20
                       )
                    ));
    }

    public function dailyList()
    {
        $count=Yii::app()->db->createCommand("SELECT count(`id`) FROM server_data WHERE server_id={$this->id}")->queryScalar();
        $sql="SELECT * FROM server_data where server_id={$this->id} ORDER BY date DESC";
        return new CSqlDataProvider($sql,array(
                     'totalItemCount'=>$count
                    ,'pagination'=>array(
                        'pageSize'=>20
                        )
                    ));
    }

    public function consumeList($server,$from,$to)
    {
        $where = "WHERE t.active=1 AND t.deleted=0 AND m1.enable = 1";
        if(!empty($server))
        {
           $where .= " AND t.id IN ({$server})";
        }
        $dependency=new CDbCacheDependency('SELECT count(`id`) FROM server WHERE active=1 AND deleted=0');
        $sql = "SELECT t.*,m1.name as game_name FROM `server` AS t
              LEFT JOIN `game` as m1 ON m1.id = t.game_id {$where} ORDER BY m1.id,t.id ASC";
        $rawData=Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
        $nowDate=strtotime(date('Y-m-d'));
        foreach($rawData as $k=>$item)
        {
          $rawData[$k]['payment_user']=Order::nbUserPaid($item['id'],$from,$to);
          $rawData[$k]['payment_repaid']=Order::nbRepaidUser($item["id"],$from,$to);
          $rawData[$k]['payment_increment']=$rawData[$k]['payment_user']-$rawData[$k]['payment_repaid'];
          $rawData[$k]['payment_times']=Order::nbPaid($item['id'],$from,$to);
          $rawData[$k]['payment_amount']=Order::sumPaid($item['id'],$from,$to);
          $rawData[$k]['payment_avg_amount']=$rawData[$k]['payment_user']?round($rawData[$k]['payment_amount']/$rawData[$k]['payment_user'],2):0;
          $rawData[$k]['payment_avg_times']=$rawData[$k]['payment_user']?round($rawData[$k]['payment_times']/$rawData[$k]['payment_user'],2):0;
          $rawData[$k]['vip1']=UserServer::nbVip($item['id'],1);
          $rawData[$k]['vip2']=UserServer::nbVip($item['id'],2);
        }
        return new CArrayDataProvider($rawData,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'id','name','game_id','open_time'
                            )
                        )
                   ,'pagination'=>array(
                       'pageSize'=>20
                       )
                    ));
    }

    public  function combinedList()
    {
        $where = "WHERE t.active=1 AND t.deleted=0 AND m1.enable = 1";
        if(!empty($this->id))
        {
            $where .= " AND t.id={$this->id}";
        }
        if(!empty($this->name))
        {
            $where .= " AND t.name like '{$this->name}%'";
        }
        if(!empty($this->game_id))
        {
            $where .= " AND t.game_id={$this->game_id}";
        }

        $dependency=new CDbCacheDependency('SELECT count(`id`) FROM server WHERE active=1 AND deleted=0');
        $sql = "SELECT t.*,m1.name as game_name FROM `server` AS t
            LEFT JOIN `game` as m1 ON m1.id = t.game_id {$where} ORDER BY m1.id,t.id ASC";
        $rawData=Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
        return new CArrayDataProvider($rawData,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'id','name','game_id','open_time'
                            )
                        )
                    ,'pagination'=>array(
                        'pageSize'=>20
                        )
                    ));
    }

    public  function orderWeekly($server)
    {
        $where = "WHERE t.active=1 AND t.deleted=0 AND m1.enable = 1";
        if(!empty($server))
        {
           $where .= " AND t.id IN ({$server})";
           $count=Yii::app()->db->createCommand("SELECT count(`id`) FROM server WHERE active=1 AND deleted=0 AND id IN ({$server})")->queryScalar();
        }
        else
        {
          $count=Yii::app()->db->createCommand('SELECT count(`id`) FROM server WHERE active=1 AND deleted=0')->queryScalar();
        }
        $sql = "SELECT t.*,m1.name as game_name FROM `server` AS t
            LEFT JOIN `game` as m1 ON m1.id = t.game_id {$where} ORDER BY m1.id,t.id ASC";
        return new CSqlDataProvider($sql,array(
                     'totalItemCount'=>$count
                    ,'pagination'=>array(
                        'pageSize'=>20
                        )
                    ));
    }

    public function history($channel,$mode=1)
    {
        $db=Yii::app()->db;
        $countSql="SELECT count(1) FROM data_daily where channel_id={$channel} AND server_id={$this->id}";
        if($mode==1)
        {
            $addment=' ORDER BY date ASC';
            $countSql.=$addment;
            $count=$db->createCommand($countSql)->queryScalar();
            $sql="select id,server_id,open_time,channel_id,date as time,payment_user,payment_increment,income
                FROM data_daily where channel_id={$channel} AND server_id={$this->id} {$addment} ";
        }
        else if($mode==2)
        {
            $addment=" GROUP BY week ORDER BY week ASC";
            $countSql.=$addment;
            $res=$db->createCommand($countSql)->queryColumn();
            $count=count($res);
            $sql="select id,server_id,open_time,channel_id,week as time,sum(payment_user) as payment_user,sum(payment_increment) as payment_increment
                ,sum(income) as income FROM data_daily where channel_id={$channel} AND server_id={$this->id} {$addment} ";
        }
        else
        {
            $addment=" GROUP BY month ORDER BY month ASC";
            $countSql.=$addment;
            $res=$db->createCommand($countSql)->queryColumn();
            $count=count($res);
            $sql="select id,server_id,open_time,channel_id,month as time,sum(payment_user) as payment_user,sum(payment_increment) as payment_increment
                ,sum(income) as income FROM data_daily where channel_id={$channel} AND server_id={$this->id} {$addment} ";
        }
        return new CSqlDataProvider($sql, array(
                    'totalItemCount' => $count,
                    'pagination'=>array(
                        'pageSize'=>50
                        )
                    ));
    }

    public static function dropDownData($game_id = null)
    {
        $db = Yii::app()->db;
        $data = array();
        if($game_id)
        {
            $dependency=new CDbCacheDependency('SELECT count(`id`) FROM server WHERE active=1 AND deleted=0');
            $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM server where game_id=:game_id");
            $req->bindParam(':game_id', $game_id);
            $res = $req->queryAll();
        }
        else
        {
            $dependency=new CDbCacheDependency('SELECT count(`id`) FROM server WHERE active=1 AND deleted=0');
            $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM server");
            $res = $req->queryAll();
        }
        if($res)
        {
            foreach($res as $row)
            {
                $data[$row['id']] = $row['name'];
            }
        }
        return $data;
    }

    public static function items()
    {
        $db = Yii::app()->db;
        $dependency=new CDbCacheDependency('SELECT COUNT(`id`) FROM server WHERE active=1 AND deleted=0');
        $req = $db->cache(1000,$dependency)->createCommand("SELECT t.id as id,t.name as name,g.name as prefix FROM server as t
                  LEFT JOIN  game as g ON t.game_id=g.id order by t.game_id desc,t.id desc ");
        $res = $req->queryAll();
        $data = array();
        if($res)
        {
            foreach($res as $row)
            {
                $data[$row['id']] = "{$row['prefix']}:{$row['name']}";
            }
        }
        return $data;
    }

    public static function getName($id)
    {
        $name = '--';
        if($id)
        {
            $db = Yii::app()->db;
            $req = $db->createCommand("SELECT name FROM server where id=:id LIMIT 1");
            $name = $req->queryScalar(array(':id' => $id));
        }
        return $name;
   }

    public static function costAnalysis($week=1,$month=null,$server=null,$channel=null)
   {
      $where="where 1 ";
      if($server)
      {
         $where.=" and server_id IN ({$server})";
      }

      if($channel)
      {
         $where.=" and channel_id IN ({$channel})";
      }

      $sql="SELECT id,server_id,game_id,channel_id,open_time,sum(cost) as cost,avg(share_base) as share,sum(register) as register
          ,sum(revisit_increment) as revisit ,sum(payment_user) as payment_user,sum(payment_amount) as payment_amount
          ,sum(income) as income FROM data_daily {$where} group by server_id,channel_id order by game_id desc,server_id";
      $countSql="SELECT id  FROM data_daily {$where} group by server_id,channel_id ";
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
      $currentProfitList=array();
      $channelName=Channel::dropDownData();
      $gameName=Game::dropDownData();
      $serverName=Server::dropDownData();
      if($data)
      {
        foreach($data as $k=>$item)
        {
           $income=self::getIncome($item['server_id'],$item['channel_id'],$week,$month);
           $lifeTimeIncome=$item['income'];
           $cost=$item['cost'];
           $currentProfitPercent=($cost)?round($income*100/$cost,2):0;
           $lifeTimeProfitPercent=($cost)?round($lifeTimeIncome*100/$cost,2):0;
           $cpa=($item["register"]>0)?round($item["cost"]/$item["register"],1):0;
           $data[$k]['share']=round($item['share']/100,1);
           $data[$k]['current_income']=$income;
           $data[$k]['cpa']=$cpa;
           $data[$k]['current_profit_percent']=$currentProfitPercent;
           $data[$k]['lifetime_profit_percent']=$lifeTimeProfitPercent;
           $data[$k]['channel_name']=$channelName[$item['channel_id']];
           $data[$k]['game_name']=$gameName[$item['game_id']];
           $data[$k]['server_name']=$serverName[$item['server_id']];
           $data[$k]['lifetime_income']=$lifeTimeIncome;
           $currentProfitList[]=$currentProfitPercent;
           if($week)
           {
             $data[$k]['mode']=2;
           }
           else
           {
             $data[$k]['mode']=3;
           }
           $data[$k]['week']=$week;
           $data[$k]['month']=$month;
        }
        $currentProfitList=array_unique($currentProfitList);
        $colorCount=count($currentProfitList);
        if($colorCount==1)
        {
            $baseLine=$currentProfitList[0];
        }
        else
        {
            sort($currentProfitList,SORT_NUMERIC);
            $index=floor($colorCount/2);
            $baseLine=$currentProfitList[$index];
        }
        foreach($data as $k=>$item)
        {
            if($item['current_profit_percent']>$baseLine)
            {
                $data[$k]['color']='green';
            }
            else if($item['current_profit_percent']==$baseLine)
            {
                $data[$k]['color']='yellow';
            }
            else
            {
                $data[$k]['color']='red';
            }
        }
      }
      $dataProvider=new CArrayDataProvider($data,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'server_id','game_id','channel_id','cpa','register','current_profit_percent'),
                            'defaultOrder'=>'game_id DESC,server_id DESC'
                            ),
                    'pagination'=>$pages
       ));
      return $dataProvider;
   }

    public static function costWeekly($fromWeek,$toWeek,$server=null,$channel=null)
   {
      $where="where income>0 ";
      if($server)
      {
         $where.=" and server_id IN ({$server})";
      }

      if($channel)
      {
         $where.=" and channel_id IN ({$channel})";
      }
      $sql="SELECT id,server_id,game_id,channel_id,open_time,sum(cost) as cost,sum(register) as register
          ,sum(income) as income FROM data_daily {$where} group by server_id,channel_id order by game_id desc,server_id";
      $countSql="SELECT count(1)  FROM data_daily {$where} group by server_id,channel_id ";
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
      $currentProfitList=array();
      if($data)
      {

        $channelName=Channel::dropDownData();
        $gameName=Game::dropDownData();
        $serverName=Server::dropDownData();
        foreach($data as $k=>$item)
        {
           $lifeTimeIncome=$item['income'];
           $cost=$item['cost'];
           $lifeTimeProfitPercent=($cost)?round($lifeTimeIncome*100/$cost,2):0;
           $data[$k]['lifetime_profit_percent']=$lifeTimeProfitPercent;
           $data[$k]['cost']=$cost;
           $data[$k]['fromweek']=$fromWeek;
           $data[$k]['endweek']=$toWeek;
           $data[$k]['channel_name']=$channelName[$item['channel_id']];
           $data[$k]['game_name']=$gameName[$item['game_id']];
           $data[$k]['server_name']=$serverName[$item['server_id']];
        }
      }
      $dataProvider=new CArrayDataProvider($data,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'server_id','game_id','channel_id','lifetime_profit_percent'),
                            'defaultOrder'=>'game_id DESC,server_id DESC'
                            ),
                    'pagination'=>$pages
       ));
      return $dataProvider;
   }

    public static function costList($from,$to,$server=null,$game=null)
    {
      $where="where 1 ";
      $to+=86399;
      if($server)
      {
         $where.=" and server_id IN ({$server})";
      }

      if($game)
      {
         $where.=" and game_id={$game}";
      }
      $sql="SELECT id,server_id,open_time,game_id,sum(cost) as cost,sum(register) as register,sum(revisit_increment) as revisit
          FROM data_daily {$where} group by server_id order by game_id desc,server_id";
      $countSql="SELECT id  FROM data_daily {$where} group by server_id order by game_id desc,server_id desc ";
      $count=0;
      $countRes=Yii::app()->db->createCommand($countSql)->queryColumn();
      if($countRes)
      {
          $count=count($countRes);
      }
      $res=Yii::app()->db->createCommand("SELECT id,name,promote_end_time FROM server")->queryAll();
      $pages=new CPagination($count);
      $pages->pageSize=50;
      $pages->getCurrentPage(true);
      $offset=$pages->getOffset();
      $data=Yii::app()->db->createCommand($sql)->queryAll();
      if($data)
      {
        $gameName=Game::dropDownData();
        $serverName=Server::dropDownData();
        foreach($data as $k=>$item)
        {
            $register=$item['register'];
            $revisit=$item['revisit'];
            $cost=$item['cost'];
            $cpa=($register)?round($cost/$register,1):0;
            $revisitCost=($revisit)?round($cost/$revisit,1):0;
            $revisitPercent=($register)?round(100*$revisit/$register,2):0;
            $paymentUser=self::getPaymentUser($item['server_id'],null,$from,$to,true);
            $payment=self::getPaymentAmount($item['server_id'],null,$from,$to,true);
            $amount=Order::sumPaid($item['server_id'],$from,$to)-Order::sumTax($item['server_id'],$from,$to);
            $income=(int)Order::sumProfit($item['server_id'],$from,$to);
            $paymentPercent=($register)?round(100*$paymentUser/$register,2):0;
            $profitPercent=($cost)?round(100*$income/$cost,2):0;
            $data[$k]['cost']=(int)$cost;
            $data[$k]['cpa']=$cpa;
            $data[$k]['revisit_percent']=$revisitPercent;
            $data[$k]['revisit_cost']=$revisitCost;
            $data[$k]['payment_percent']=$paymentPercent;
            $data[$k]['payment']=$payment;
            $data[$k]['amount']=$amount;
            $data[$k]['income']=$income;
            $data[$k]['profit_percent']=$profitPercent;
            $data[$k]['game_name']=$gameName[$item['game_id']];
            $data[$k]['server_name']=$serverName[$item['server_id']];
        }
      }
      return new CArrayDataProvider($data,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'server_id','game_id','cost','register','revisit','open_time','cpa','profit_percent'),
                            'defaultOrder'=>'game_id DESC,server_id DESC'
                            ),
                    'pagination'=>$pages
       ));

    }

   public static function getPaymentUser($server,$channel,$week,$month=null,$day=null)
   {
       $where="where server_id={$server}  ";
       if($channel)
       {
         $where.=" and channel_id={$channel}";
       }

       if(!$day)
       {
          if($week)
          {
            $where.=" AND week={$week}";
          }
          if($month)
          {
            $where.=" AND month={$month}";
          }
       }
       else
       {
          $where.=" AND date>={$week} and date<={$month}";
       }

       $sql="SELECT SUM(payment_user) FROM data_daily  {$where}";
       return (int)Yii::app()->db->createCommand($sql)->queryScalar();
   }

   public static function getPaymentAmount($server,$channel,$week=1,$month=null,$day=null)
   {
       $where="where server_id={$server}  ";
       if($channel)
       {
         $where.=" and channel_id={$channel}";
       }
       if(!$day)
       {
          if($week)
          {
            $where.=" AND week={$week}";
          }
          if($month)
          {
            $where.=" AND month={$month}";
          }
       }
       else
       {
          $where.=" AND date>={$week} and date<={$month}";
       }
       $sql="SELECT SUM(payment_increment) FROM data_daily {$where}";
       return (int)Yii::app()->db->createCommand($sql)->queryScalar();
   }

   public static function getLifeTimeCost($server,$channel)
   {
      $sql="SELECT sum(cost) from data_daily where server_id={$server} and channel_id={$channel}";
      return (int) Yii::app()->db->createCommand($sql)->queryScalar();
   }


   public static function getPaymentPercent($server,$channel,$mode,$time)
   {
     $offsetPaymentUser=self::getPaymentUserOffset($server,$channel,$mode,$time);
     $offsetRegister=self::getRegisterOffset($server,$channel,$mode,$time);
     if($offsetRegister)
     {
       return round(100*$offsetPaymentUser/$offsetRegister,2);
     }
     else
     {
       return 0;
     }
   }

   public static function  getIncome($server,$channel,$week=1,$month=null,$day=null)
   {
     $where="where server_id={$server}  ";
     if($channel)
     {
       $where.=" and channel_id={$channel}";
     }

     if(!$day)
     {
        if($week)
        {
          $where.=" AND week={$week}";
        }
        if($month)
        {
          $where.=" AND month={$month}";
        }
     }
     else
     {
        $where.=" AND date>={$week} and date<={$month}";
     }
     $sql="SELECT SUM(income) FROM data_daily {$where}";
     return (int)Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
    public static function getNewPayNum($serverId,$from=null,$to=null)
    {
       $where =" WHERE server_id={$serverId}";
       $where .= $from ? " AND `date` >= {$from}" : '';
       $where .= $to ? " AND `date` < {$to}" : '';
       $sql = "SELECT sum(new_pay_user) FROM server_data {$where}";
       return Yii::app()->db->createCommand($sql)->queryScalar();
    }
   

   public static function  getIncomeOffset($server,$channel,$week=1,$month=null)
   {
     $where="where server_id={$server} and channel_id={$channel} ";
     if($week)
     {
       $where.=" AND week<={$week}";
     }
     if($month)
     {
       $where.=" AND month<={$month}";
     }
     $sql="SELECT SUM(income) FROM data_daily {$where}";
     return (int)Yii::app()->db->createCommand($sql)->queryScalar();
   }

   public static function  getRegisterOffset($server,$channel,$mode,$time)
   {
     $where="where server_id={$server}  ";

     if($channel)
     {
         $where.=" and channel_id={$channel}";
     }
     if($mode==1)
     {
       $where.=" AND date<={$time}";
     }
     elseif($mode==2)
     {

       $where.=" AND week<={$time}";
     }
     else
     {
       $where.=" AND month<={$time}";
     }
     $sql="SELECT SUM(register) FROM data_daily {$where}";
     return (int)Yii::app()->db->createCommand($sql)->queryScalar();
   }

   public static function  getCostOffset($server,$channel,$mode,$time)
   {
     $where="where server_id={$server}  ";
     if($channel)
     {
         $where.=" and channel_id={$channel}";
     }
     if($mode==1)
     {
       $where.=" AND date<={$time}";
     }
     elseif($mode==2)
     {

       $where.=" AND week<={$time}";
     }
     else
     {
       $where.=" AND month<={$time}";
     }
     $sql="SELECT SUM(cost) FROM data_daily {$where}";
     return (int)Yii::app()->db->createCommand($sql)->queryScalar();
   }

   public static function  getPaymentUserOffset($server,$channel,$mode,$time)
   {
     $where="where server_id={$server} and channel_id={$channel} ";
     if($mode==1)
     {
       $where.=" AND date<={$time}";
     }
     elseif($mode==2)
     {

       $where.=" AND week<={$time}";
     }
     else
     {
       $where.=" AND month<={$time}";
     }
     $sql="SELECT SUM(payment_user) FROM data_daily {$where}";
     return (int)Yii::app()->db->createCommand($sql)->queryScalar(); 
   }

   public static function getProfit($server,$channel,$income)
   {
       $cost=self::getLifeTimeCost($server,$channel);
       $profit=0;
       if($cost)
       {
         $profit=round(100*$income/$cost,2);
       }
       return $profit;
   }

   public static function getColorProfit($server,$channel,$offsetWeek,$income,$cost)
   {
       $offsetIncome=self::getIncomeOffset($server,$channel,$offsetWeek);
       $profit=0;
       if($cost)
       {
         $profit=round(100*$income/$cost,2);
       }

       if($offsetIncome>=$cost && $offsetIncome>0 && $cost>0)
       {
          return  "<span style='color:red'>{$profit}%<span>";
       }
       else
       {
          return  "<span>{$profit}%<span>";
       }

   }
}
