<?php
class serverCommand extends CConsoleCommand
{
     public function actionRange($from,$to=null)
      {
         $lastday=strtotime(date('Y-m-d'))-86400;
         $fromTime=strtotime(date('Y-m-d',strtotime($from)));
         if(!$to)
         {
            $toTime=$lastday;
         }
         else
         {
            $toTime=strtotime(date('Y-m-d',strtotime($to)));
         }
         if($toTime>$lastday)
         {
             $toTime=$lastday;
         }
         for($i=$fromTime;$i<=$toTime;$i+=86400)
         {
             $this->actionIndex(date('Y-m-d',$i));
         }
      }

    public function actionIndex($day=null)
    {
        $dbLocal=Yii::app()->dbLocal;
        $lastday=strtotime(date('Y-m-d'))-86400;
        if($day)
        {
            $lastday=strtotime(date('Y-m-d',strtotime($day)));
        }
        $date=$lastday;
        $strLastday=date('Y-m-d',$lastday);
        $from=$date;
        $to=$date+86400;
        $sql="SELECT id,game_id,open_time FROM server where `deleted`=0  and open_time<={$lastday}";
        $res=$dbLocal->createCommand($sql)->queryAll();
        $i=0;
        if($res)
        {
            $i=1;
            $insertSql='';
            foreach($res as $item)
            {
                $serverId=$item['id'];
                $gameId=$item['game_id'];
                $openTime=$item['open_time'];
                $dectedSql="SELECT id FROM server_data WHERE server_id={$serverId} AND date={$date}";
                if(!$dbLocal->createCommand($dectedSql)->queryRow())
                {
                    $insertSql=$this->_calculate($dbLocal,$serverId,$gameId,$openTime,$from,$to);
                    if($insertSql)
                    {
                        $dbLocal->createCommand($insertSql)->execute();
                        $insertSql='';
                        echo "Generate server:{$serverId} data record item on {$strLastday}\n";
                    }
                    else
                    {
                        echo "Ignore NULL data\n";
                    }
                }
                else
                {
                		echo "Has been generate this record\n";
                }
                $i++;
            }
        }
    }

    private function _calculate($dbLocal,$serverId,$gameId,$openTime,$from,$to)
    {
        $insertSql='';
        $adRegister=$dbLocal->createCommand("SELECT count(1) FROM user_server where server_id='{$serverId}' and 
                create_time>={$from} and create_time<{$to} and orgi_server=server_id and ad_pos_id>0")->queryScalar();
        $platformRegister=$dbLocal->createCommand("SELECT count(1) FROM user_server where server_id='{$serverId}' and 
                create_time>={$from} and create_time<{$to} and orgi_server=server_id and ad_pos_id=0")->queryScalar();
        $migrateRegister=$dbLocal->createCommand("SELECT count(1) FROM user_server where server_id='{$serverId}' and 
                create_time>={$from} and create_time<{$to} and orgi_server<>server_id")->queryScalar();
        $chgSvrRegister=$dbLocal->createCommand("SELECT
													  COUNT(distinct user_server1.user_id) AS FIELD_1
													FROM
													  user_server user_server1
													  INNER JOIN user_server ON (user_server.user_id = user_server1.user_id)
													WHERE
													  (user_server.create_time >= {$from}) AND
													  (user_server.create_time < {$to}) AND
													  (user_server.server_id = '{$serverId}') AND
													  (user_server1.game_id = '$gameId') AND
													  (user_server1.server_id <> '{$serverId}') AND
													  (user_server1.create_time < {$from})
													")->queryScalar();
        $visitUser=(int)$dbLocal->createCommand("SELECT sum(uv_visit_times) FROM visit_stat where server_id='{$serverId}' and 
                date={$from}")->queryScalar();
        $registerVisit=$migrateRegister+$platformRegister+$adRegister;
        if($registerVisit>$visitUser)
        {
            $visitUser=$registerVisit;
        }
        $paymentUser=$dbLocal->createCommand("SELECT count(distinct user_id) FROM `order` where 
                update_time>={$from} and update_time<{$to} and server_id={$serverId}")->queryScalar();
        $repaidPaymentUser=$dbLocal->createCommand("SELECT count(distinct user_id) FROM `order` where 
                update_time<{$from} and server_id={$serverId} and user_id IN (SELECT distinct user_id 
                    FROM `order` where update_time>={$from} and update_time<{$to} and server_id={$serverId})")->queryScalar();
        $incrementPaymentUser=$paymentUser-$repaidPaymentUser;
        $paid=$dbLocal->createCommand("SELECT sum(paid) as paid,sum(paid-payment_tax) as paid_gm FROM `order` where
                update_time>={$from} and update_time<{$to} and server_id={$serverId}")->queryRow();
        $paidSum=$paid['paid'];
        $paidGm=$paid['paid_gm'];
        if(!$paidSum)
        {
            $paidSum=0;
        }
        if(!$paidGm)
        {
            $paidGm=0;
        }
        
        //zhousongjie add 新付费用户
        $newPayNum = 0;
        $payUserList=$dbLocal->createCommand("SELECT distinct user_id  FROM `order` ta WHERE `server_id`={$serverId} and update_time>={$from} and  update_time<{$to}")->queryAll();
        if($payUserList)
		{
			foreach($payUserList as $user_id) {//索引有异常，所以拆开执行
				$t_pay_time = $dbLocal->createCommand("SELECT count(1)  FROM `order` ta WHERE `user_id` = {$user_id['user_id']} and update_time<{$from}")->queryScalar();
				if ($t_pay_time == 0) {
					$newPayNum++;
				}
			}
		}
        
        
        
        $date=$from;
        $openDate=strtotime(date('Y-m-d 00:00',$openTime));
        $week=ceil((($date-$openDate+1)/86400)/7);
        $month=ceil((($date-$openDate+1)/86400)/30);
        if($adRegister || $platformRegister || $migrateRegister || $visitUser || $paidSum || $newPayNum || $chgSvrRegister)
        {
            $insertSql="INSERT INTO server_data(`server_id`,`game_id`,`ad_register`,`platform_register`,`migrate_register`,`chg_svr_register`,`visit_user`,`register_visit`,`payment_user`,`new_pay_user`,`payment_user_repaid`,`payment_user_increment`,`paid_gm`,`paid`,`date`,`week`,`month`) VALUES(
            {$serverId},{$gameId},{$adRegister},{$platformRegister},{$migrateRegister},{$chgSvrRegister},{$visitUser},{$registerVisit},{$paymentUser},{$newPayNum},{$repaidPaymentUser},{$incrementPaymentUser},{$paidGm},{$paidSum},{$from},{$week},{$month});";
        }
        return $insertSql;
    }

}
