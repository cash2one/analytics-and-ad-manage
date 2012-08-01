<?php
class orderCommand extends CConsoleCommand
{
    public $paymentTax=array('SNDACARD-NET' => 0.2, 'NETEASE-NET' => 0.2, 'SZX-NET' => 0.1);
    public function actionIndex($itemNum=100)
    {
        $dbLocal=Yii::app()->dbLocal;
        $dbPay=Yii::app()->dbPay;
        $sql="SELECT id,orderid,gamereturndate,username,money,indate,yb,orderid FROM gamepay WHERE paystatus=1 AND export=0 ORDER BY id desc LIMIT {$itemNum}";
        $insertSql='';
        $updateSql='';
        $res=$dbPay->createCommand($sql)->queryAll();
        $i=1;
        $count=count($res);
        echo "Catch {$count} item\n";
        if($res AND $i<$itemNum)
        {
            foreach($res as $item)
            {
                $duplicSql="SELECT id FROM `order` where id={$item['id']} LIMIT 1";
                if(!$dbLocal->createCommand($duplicSql)->queryRow())
                {
                    //$userSql="SELECT id FROM user where user_name='{$item['username']}'";
                    //$userId=$dbLocal->createCommand($userSql)->queryScalar();
                    Yii::import('application.vendors.*');
                    include_once('ucenter.php');
                    list($userId,$username,$email)=uc_get_user($item['username']);
                    if($userId)
                    {
                       $insertSql.=$this->_addRecord($item,$userId,$i);
                       if(strlen($item['orderid'])<18)
                       {
                         $updateSql.="UPDATE gamepay SET export=2 where id={$item['id']};";
                       }
                       else
                       {
                         $updateSql.="UPDATE gamepay SET export=1 where id={$item['id']};";
                       }
                    }
                    else
                    {
                       echo "Miss {$i}:can't find user \n";
                    }

                    if($insertSql)
                    {
                        $dbLocal->createCommand($insertSql)->execute();
                        $insertSql='';
                    }
                }
                else
                {
                    if(strlen($item['orderid'])<18)
                    {
                      $updateSql.="UPDATE gamepay SET export=2 where id={$item['id']};";
                    }
                    else
                    {
                      $updateSql.="UPDATE gamepay SET export=1 where id={$item['id']};";
                    }
                    echo "Miss {$i}:duplicated\n";
                }

                if($updateSql)
                {
                   $dbPay->createCommand($updateSql)->execute();
                   $updateSql='';
                }
                $i++;
            }
        }
    }

    public function actionComplete($itemNum=1000)
    {
        $dbLocal=Yii::app()->dbLocal;
        $dbPay=Yii::app()->dbPay;
        $sql="SELECT id,orderid FROM gamepay WHERE paystatus=1 AND export=0 ORDER BY id ASC LIMIT {$itemNum}";
        $res=$dbPay->createCommand($sql)->queryAll();
        $i=1;
        $count=count($res);
        echo "Catch {$count} item\n";
        if($res)
        {
            $updateSql='';
            foreach($res as $item)
            {
                $id=$item['id'];
                $orderid=$item['orderid'];
                $detectSql="SELECT id FROM `order` where id={$id} LIMIT 1";
                //test order record,no pay item,diff flag
                if(strlen($orderid)<18)
                {
                   $updateSql.="UPDATE gamepay SET export=2 where id={$id};";
                }
                else
                {
                  if($dbLocal->createCommand($detectSql)->queryRow())
                  {
                     $updateSql.="UPDATE gamepay SET export=1 where id={$id};";
                  }
                }
                if(($i%100==0 OR $i==$count) AND $updateSql)
                {
                   $dbPay->createCommand($updateSql)->execute();
                   $updateSql='';
                   echo "Complete {$i}:order export flag\n";
                }
                $i++;
            }
        }
    }

    private function _addRecord($item,$userId,$i)
    {
        $dbPay=Yii::app()->dbPay;
        $dbLocal=Yii::app()->dbLocal;
        $insertSql='';
        $paySql="SELECT * FROM pay
            where paystatus=1 AND orderid={$item['orderid']}";
        $payList=$dbPay->createCommand($paySql)->queryAll();
        if($payList)
        {
            $paidTotal=0;
            $payItemCount=0;
            $serverName='';
            $userPromote=array('ad_pos_id'=>0,'pos_id'=>0,'channel_id'=>0,'register_time'=>0);
            $paymentTax=0;
            foreach($payList as $payItem)
            {
                $duplicPaySql="SELECT id FROM `payment` where id={$payItem['id']} LIMIT 1";
                if(!$dbLocal->createCommand($duplicPaySql)->queryRow())
                {
                    $payReturn=strtotime($payItem['payreturndate']);
                    $serverSql="SELECT name FROM `server` where id='{$payItem['protype']}'";
                    $serverName=$dbLocal->createCommand($serverSql)->queryScalar();
                    $methodSql="SELECT gateway FROM `payment_method` where id='{$payItem['paytype']}'";
                    $methodName=$dbLocal->createCommand($methodSql)->queryScalar();
                    $userPromote=$this->_localUser($userId);
                    $insertSql.="INSERT INTO `payment`(`id`,`user_id`,`order_id`,`method_id`,`txn`,`value`,`status`,`time`,`platform_id`
                        ,`bussiness_id`,`user_name`,`operation_name`,`game_name`,`game_id`,`server_id`,`server_name`,`payment_code`
                        ,`method_name`,`ad_pos_id`,`pos_id`)
                        VALUES('{$payItem['id']}','{$userId}','{$item['id']}','{$payItem['paytype']}','{$payItem['payreturnid']}'
                                ,'{$payItem['money']}',1,'{$payReturn}','{$payItem['payorderid']}','{$payItem['orderid']}'
                                ,'{$payItem['username']}','{$payItem['myusername']}','{$payItem['proname']}','{$payItem['proid']}'
                                ,'{$payItem['protype']}','{$serverName}','{$payItem['frpid']}','{$methodName}',
                                '{$userPromote['ad_pos_id']}','{$userPromote['pos_id']}');";
                    $paidTotal+=$payItem['money'];
                    if(!empty($this->paymentTax[$payItem['frpid']]))
                    {
                       $paymentTax+=$payItem['money']*$this->paymentTax[$payItem['frpid']];
                    }
                    $payItemCount++;
                }
            }
            $orderTime=strtotime($item['indate']);
            $callBackTime=strtotime($item['gamereturndate']);
            $gameSql="SELECT name FROM `game` where id='{$payList[0]['proid']}'";
            $gameName=$dbLocal->createCommand($gameSql)->queryScalar();
            $share=0;
            if(isset($payList[0]['proid']))
            {
              $where=" WHERE game_id={$payList[0]['proid']} and date<={$callBackTime}";
              $share=(int)$dbLocal->createCommand("SELECT share FROM game_share {$where} ORDER BY date DESC LIMIT 1")->queryScalar();
              $sql="SELECT share FROM game_share {$where} ORDER BY date DESC LIMIT 1";
            }
            if($share)
            {
              $profit=($paidTotal-$paymentTax)*$share/100;
            }
            else
            {
              $profit=($paidTotal-$paymentTax);
              $share=100;
            }
            $serverSql="SELECT name FROM `server` where id='{$payItem['protype']}'";
            $serverName=$dbLocal->createCommand($serverSql)->queryScalar();
            $insertSql.="INSERT INTO `order`(`id`,`user_id`,`game_id`,`server_id`,`total`,`paid`,`ideal_money`
                ,`status`,`game_orderid`,`game_name`,`server_name`,`payment_tax`,`profit`,`ad_pos_id`,`channel_id`,`pos_id`
                ,`share_base`,`create_time` ,`update_time`,`register_time`) 
                VALUES ('{$item['id']}','{$userId}','{$payList[0]['proid']}','{$payList[0]['protype']}'
                ,{$item['money']},{$paidTotal},{$item['yb']},1,{$item['orderid']},'{$gameName}','{$serverName}',{$paymentTax},{$profit}
                ,'{$userPromote['ad_pos_id']}',{$userPromote['channel_id']},'{$userPromote['pos_id']}','{$share}',{$orderTime},{$callBackTime},{$userPromote['register_time']});";
            echo "Pass {$i}:order {$item['id']}::{$payItemCount} item payment record\n";
        }
        else
        {
            echo "miss {$i}:order {$item['id']}::can't find item payment record\n";
        }
        return $insertSql;
    }

    private function _localUser($userId)
    {
        $data=array('ad_pos_id'=>0,'pos_id'=>0,'channel_id'=>0,'register_time'=>0);
        $dbLocal=Yii::app()->dbLocal;
        $res=$dbLocal->createCommand("SELECT ad_pos_id,create_time FROM user where id={$userId}")->queryRow();
        $adPosId=$res['ad_pos_id'];
        $registerTime=$res['create_time'];
        if($registerTime)
        {
          $data['register_time']=$registerTime;
        }
        if($adPosId)
        {
            $data['ad_pos_id']=$adPosId;
            $res=$dbLocal->createCommand("SELECT pos_id,channel_id FROM `ad_pos` where id={$adPosId}")->queryRow();
            if($res)
            {
               $data['pos_id']=$res['pos_id'];
               $data['channel_id']=$res['channel_id'];
            }
        }
        return $data;
    }

}
