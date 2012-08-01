<?php
class miningCommand extends CConsoleCommand
{

    public function actionIndex($month=null)
    {
           if($month==null)
           {
            $from=strtotime(date('Y-m-d 00:00:00'));
            $from=strtotime('-1 day',$from);
            $to=strtotime('+1 day',$from);
           }
           else
           {
            $from=strtotime(date('Y-m-01 00:00:00',strtotime($month)));
            $to=strtotime('+1 month',$from);
           }

           $this->dumpGame();
           $this->dumpServer();
           $this->dumpMedia();
           $this->dumpMethod();
           $this->_cleanUp();

           for($i=$from;$i<$to;$i+=86400)
           {
              echo "Dump from ".date("Y-m-d H:i:s",$i).' to '. date('Y-m-d H:i:s',$i+86399)."\n";
              $this->dumpRegister($i,$i+86399);
              $this->dumpDeposite($i,$i+86399);
              //$this->dumpChangeSvr($i,$i+86399);
              $this->dumpVisit($i,$i+86399);
              //$this->dumpUserInfo($i,$i+86399);
           }
    }

    public function dumpMethod()
    {
        $methods=Yii::app()->dbSlaver->createCommand("SELECT * FROM payment_method")->queryAll();
        if($methods)
        {
            echo "Dump Payment Method\n";
            $sql='';
            foreach($methods as $data)
            {
              $sql.="INSERT INTO bdap_payment_method(`id`,`name`,`is_valid`) VALUES('{$data['id']}','{$data['gateway']}',1);";
            }

            if($sql)
            {
              $dbMining=Yii::app()->dbMining;
              $dbMining->createCommand("DELETE  FROM bdap_payment_method")->execute();
              $dbMining->createCommand($sql)->execute();
            }
        }
    }

    public function dumpGame()
    {
        $games=Yii::app()->dbSlaver->createCommand("SELECT * FROM game")->queryAll();
        if($games)
        {
            echo "Dump Games\n";
            $sql='';
            foreach($games as $data)
            {
              $sql.="INSERT INTO bdap_game(`game_name`,`game_id`,`is_valid`) VALUES('{$data['name']}','{$data['id']}',{$data['enable']});";
            }

            if($sql)
            {
              $dbMining=Yii::app()->dbMining;
              $dbMining->createCommand("DELETE  FROM bdap_game")->execute();
              $dbMining->createCommand($sql)->execute();
            }
        }

    }

    public function dumpServer()
    {
        $serveres=Yii::app()->dbSlaver->createCommand("SELECT * FROM server")->queryAll();
        if($serveres)
        {
            echo "Dump Game server\n";
            $sql='';
            foreach($serveres as $data)
            {
              $openTime=date('Y-m-d',$data['open_time']);
              $sql.="INSERT INTO bdap_game_server(`game_id`,`server_name`,`server_id`,`open_time`,`is_valid`) 
                  VALUES('{$data['game_id']}','{$data['name']}','{$data['id']}','{$openTime}',{$data['active']});";
            }
            if($sql)
            {
              $dbMining=Yii::app()->dbMining;
              $dbMining->createCommand("DELETE FROM bdap_game_server")->execute(); 
              $dbMining->createCommand($sql)->execute();
            }
        }
    }

    public function dumpMedia()
    {
        $media=Yii::app()->dbSlaver->createCommand("SELECT id,name FROM channel")->queryAll();
        if($media)
        {
            echo "Dump Media\n";
            $sql='';
            foreach($media as $data)
            {
              $sql.="INSERT INTO bdap_media(`media_id`,`media_name`)
                  VALUES('{$data['id']}','{$data['name']}');";
            }
            if($sql)
            {
              $dbMining=Yii::app()->dbMining;
              $dbMining->createCommand("DELETE FROM bdap_media")->execute();
              $dbMining->createCommand($sql)->execute();
            }
        }
    }

    public function dumpChangeSvr($from=null,$to=null)
    {
        $changeSql="SELECT t.id,user_name,server_id,game_id,pre_server,pre_game,t.lively_time FROM user_server as t
            LEFT JOIN user on t.user_id=user.id
            where pre_server<>server_id and game_id=pre_game and t.login_times>1 ";
        $changeSql.=$from?'and t.create_time>='.$from:'';
        $changeSql.=$to?' and t.create_time<='.$to:'';
        $changes=Yii::app()->dbSlaver->createCommand($changeSql)->queryAll();
        if($changes)
        {
            echo "Dump server change\n";
            $dbMining=Yii::app()->dbMining;
            $sql="";
            $index=1;
            $total=count($changes);
            foreach($changes as $data)
            {
                $createTime=date('Y-m-d H:i:s',$data['lively_time']);
                $sql.="INSERT INTO bdap_change_svr(`id`,`pt_id`,`src_game_id`,`src_svr_id`,`dst_game_id`,`dst_svr_id`,`date`)
                    VALUES('{$data['id']}','{$data['user_name']}','{$data['pre_game']}','{$data['pre_server']}','{$data['game_id']}'
                            ,'{$data['server_id']}','{$createTime}');";
                if($index%500==0 OR $index==$total)
                {
                  $dbMining->createCommand($sql)->execute();
                  $sql='';
                }
                $index++;
            }
        }
    }

    public function dumpDeposite($from,$to)
    {
        $orderSql="SELECT id,game_id,server_id,total,paid,payment_tax,ideal_money,create_time,update_time 
            FROM `order` where 1 ";
        $orderSql.=$from?'and update_time>='.$from:'';
        $orderSql.=$to?' and update_time<='.$to:'';
        $orders=Yii::app()->dbSlaver->createCommand($orderSql)->queryAll();
        if($orders)
        {
            echo "Dump deposite\n";
            $dbMining=Yii::app()->dbMining;
            $sql="";
            $index=1;
            $total=count($orders);
            foreach($orders as $data)
            {
                $paymentSql="SELECT method_id,user_name FROM payment where order_id={$data['id']}";
                $res= Yii::app()->dbSlaver->createCommand($paymentSql)->queryRow();
                if($res)
                {
                  $userName=$res['user_name'];
                  $methodId=$res['method_id'];
                  $createTime=date('Y-m-d H:i:s',$data['create_time']);
                  $updateTime=date('Y-m-d H:i:s',$data['update_time']);
                  $sellPrice=$data['paid']-$data['payment_tax'];
                  $cardRebate=$data['paid']?$sellPrice/$data['paid']:1;
                  $sql.="INSERT INTO bdap_deposite(`game_id`,`server_id`,`pt_id`,`card_id`,`card_channel`,`card_price`,`sell_price`
                     ,`card_value`,`card_rebate`,`create_time`,`log_time`) VALUES('{$data['game_id']}','{$data['server_id']}','{$userName}'
                     ,'{$data['id']}','{$methodId}','{$data['paid']}','{$sellPrice}' ,'{$data['ideal_money']}',{$cardRebate} 
                     ,'{$createTime}','{$updateTime}');";
                  if($index%100==0 OR $index==$total)
                  {
                    $dbMining->createCommand($sql)->execute();
                    $sql='';
                  }
                }
                $index++;
            }
        }
    }

    public function dumpVisit($from=null,$to=null)
    {
        $dbLocal=Yii::app()->dbSlaver;
        $minTime=$dbLocal->createCommand("SELECT min(time) FROM visit")->queryScalar();
        $dbMining=Yii::app()->dbMining;
        $sql="";
        if($from==null OR strtotime($from)<=$minTime)
        {
           echo "Dump visit\n";
           $dumpDb=array( 'visit_0', 'visit_1', 'visit_2', 'visit_3', 'visit_4', 'visit_5',
                   'visit_6', 'visit_7', 'visit_8', 'visit_9', 'visit_a', 'visit_b', 'visit_c',
                   'visit_d', 'visit_e', 'visit_f');
           foreach($dumpDb as $db)
           {
              $visitSql="SELECT * FROM {$db} where 1 ";
              $visitSql.=$from?'and time>='.$from:'';
              $visitSql.=$to?' and time<='.$to:'';
              $visits=Yii::app()->dbVisit->createCommand($visitSql)->queryAll();
              $this->_addVisit($visits);
           }
        }
        $visitSql="SELECT * FROM visit where 1 ";
        $visitSql.=$from?'and time>='.$from:'';
        $visitSql.=$to?' and time<='.$to:'';
        $visits=$dbLocal->createCommand($visitSql)->queryAll();
        $this->_addVisit($visits);
    }

    private function _addVisit($visits)
    {
      if($visits)
      {
        $sql="";
        $total=count($visits);
        $index=1;
        foreach($visits as  $data)
        {
           $userSql="select u_type,user_name FROM user where id={$data['user_id']}";
           $res=Yii::app()->dbSlaver->createCommand($userSql)->queryRow();
           if($res)
           {

             $time=date('Y-m-d h:i:s',$data['time']);
             $ip=long2ip($data['ip']);
             $userName=$res['user_name'];
             $userType=$res['u_type'];
             $sql.="INSERT INTO bdap_user_login(`pt_id`,`pt_num_id`,`user_type`,`user_ip`,`game_id`
               ,`server_id`,`auth_time`) VALUES('{$userName}','{$data['user_id']}','{$userType}','{$ip}','{$data['game_id']}'
               ,'{$data['server_id']}','{$time}');";
             if($index%500==0 OR $index==$total)
             {
                 Yii::app()->dbMining->createCommand($sql)->execute();
               $sql='';
             }
             $index++;
           }
        }
      }
    }

    public function dumpUserInfo($from,$to)
    {
        $userSql="SELECT * FROM user where create_time>={$from} and create_time<{$to}";
        $users=Yii::app()->dbSlaver->createCommand($userSql)->queryAll();
        if($users)
        {
           $dbMining=Yii::app()->dbMining;
           $sql="";
           $index=1;
           $total=count($users);
           foreach($users as $data)
           {
                if($data['user_name'])
                {
                  $mediaId=0;
                  $sourceId=3;
                  $registerTime=date('Y-m-d H:i:s',$data['create_time']);
                  if($data['ad_pos_id']>0)
                  {
                      $mediaSql="SELECT m1.type,m1.id FROM ad_pos as t LEFT JOIN channel as m1 ON t.channel_id=m1.id where t.id={$data['ad_pos_id']}";
                      $res= Yii::app()->dbSlaver->createCommand($mediaSql)->queryRow();
                      if($res)
                      {
                          $mediaId=$res['id'];
                          $sourceId=1;
                          if($res['type']==1)
                          {
                              $sourceId=2;
                          }
                      }
                  }
                  $loginTimes=$this->_getLoginTimes($data['id'],$to);
                  $depositNum=$this->_getDepositNum($data['id'],$to);
                  $ip=long2ip($data['ip']);
                  list($game,$server)=$this->_lastGameServer($data['id'],$to);
                  $lastLoginTime=date('Y-m-d H:i:s',$this->_lastLoginTime($data['id'],$to));
                  $sql.="INSERT INTO bdap_user_info(`pt_id`,`pt_num_id`,`reg_ip`,`user_type`,`reg_time`,`source_id`
                      ,`login_times`,`deposit_num`,`last_login_time`,`dst_svr_id`,`dst_game_id`,`media_id`) 
                      VALUES('{$data['user_name']}','{$data['id']}','{$ip}',{$data['u_type']},'{$registerTime}'
                      ,{$sourceId},'{$loginTimes}','{$depositNum}','{$lastLoginTime}',{$server},{$game},{$mediaId});";
                   if($index%500==0 OR $index==$total)
                  {
                    $dbMining->createCommand($sql)->execute();
                    $sql='';
                  }
                }
                $index++;
           }
        }
    }

    public function dumpRegister($from=null,$to=null)
    {
        $userSql="SELECT t.server_id,t.game_id,t.create_time,m1.user_name,m1.id,m1.ip,m1.ad_pos_id FROM user_server as t
            LEFT JOIN user as m1 ON m1.id=t.user_id where 1 ";
        $userSql.=$from?'and t.create_time>='.$from:'';
        $userSql.=$to?' and t.create_time<='.$to:'';
        $users=Yii::app()->dbSlaver->createCommand($userSql)->queryAll();
        if($users)
        {
            $dbMining=Yii::app()->dbMining;
            $sql="";
            $index=1;
            $total=count($users);
            foreach($users as $data)
            {
                if($data['user_name'])
                {
                  $mediaId=0;
                  $sourceId=3;
                  $registerTime=date('Y-m-d H:i:s',$data['create_time']);
                  $ip=long2ip($data['ip']);
                  if($data['ad_pos_id']>0)
                  {
                      $mediaSql="SELECT m1.type,m1.id FROM ad_pos as t LEFT JOIN channel as m1 ON t.channel_id=m1.id where t.id={$data['ad_pos_id']}";
                      $res= Yii::app()->dbSlaver->createCommand($mediaSql)->queryRow();
                      if($res)
                      {
                          $mediaId=$res['id'];
                          $sourceId=1;
                          if($res['type']==1)
                          {
                              $sourceId=2;
                          }
                      }
                  }
                  $sql.="INSERT INTO bdap_user_reg(`pt_id`,`pt_num_id`,`user_ip`,`game_id`,`server_id`,`media_id`,`reg_time`,`source_id`)
                    VALUES('{$data['user_name']}','{$data['id']}','{$ip}','{$data['game_id']}','{$data['server_id']}','{$mediaId}'
                            ,'{$registerTime}',{$sourceId});";
                   if($index%500==0 OR $index==$total)
                  {
                    $dbMining->createCommand($sql)->execute();
                    $sql='';
                  }
                }
                $index++;
            }
        }
    }

    private function _cleanUp()
    {
      $dbMining=Yii::app()->dbMining;
      
      $dbMining->createCommand("DELETE FROM bdap_user_login")->execute();
      $dbMining->createCommand("DELETE FROM bdap_deposite")->execute();
      //$dbMining->createCommand("DELETE FROM bdap_change_svr")->execute();
   
      $dbMining->createCommand("DELETE FROM bdap_user_reg")->execute();
      //$dbMining->createCommand("DELETE FROM bdap_user_info")->execute();
    }

    private function _getLoginTimes($uid,$end)
    {
      $where=" where user_id={$uid} AND time<={$end}";
      $visit1=(int)Yii::app()->dbSlaver->createCommand("SELECT count(1) FROM visit {$where}")->queryScalar(); 
      $db='visit_'.substr(md5($uid),0,1);
      $visit2=(int)Yii::app()->dbVisit->createCommand("SELECT count(1) FROM {$db} {$where}")->queryScalar(); 
      return $visit1+$visit2;
    }

    private function _getDepositNum($uid,$end)
    {
      $where=" where user_id={$uid} AND update_time<={$end}";
      return (int)Yii::app()->dbSlaver->createCommand("SELECT sum(paid) FROM `order` {$where}")->queryScalar(); 
    }

    private function _lastLoginTime($uid,$end)
    {
      $where=" where user_id={$uid} AND time<={$end}";
      $time=(int)Yii::app()->dbSlaver->createCommand("SELECT time FROM visit {$where} ORDER BY time DESC  LIMIT 1 ")->queryScalar(); 
      if(!$time)
      {
       $db='visit_'.substr(md5($uid),0,1);
       $time=(int)Yii::app()->dbVisit->createCommand("SELECT time FROM {$db} {$where} ORDER BY time DESC  LIMIT 1 ")->queryScalar(); 
      }
      return $time;
    }
    private function _lastGameServer($uid,$end)
    {
        $sql = "SELECT game_id, server_id FROM user_server WHERE user_id = {$uid} and create_time<={$end} ORDER BY create_time DESC  LIMIT 1 ";
        $result = Yii::app()->dbSlaver->createCommand($sql)->queryRow();
        $game=$server=0;
        if($result)
        {
         $game=$result['game_id'];
         $server=$result['server_id'];
        }
        return array($game,$server);
    }
}
