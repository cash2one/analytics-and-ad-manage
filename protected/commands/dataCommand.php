<?php
class dataCommand extends CConsoleCommand
{
      public $arr_channel = array();
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
         $lastday=strtotime(date('Y-m-d'))-86400;
         if($day)
         {
           $lastday=strtotime(date('Y-m-d',strtotime($day)));
         }
         $dbLocal=Yii::app()->dbLocal;
         $serverList=$dbLocal->createCommand("SELECT id,game_id,open_time FROM server where deleted=0 and active=1 and
                 open_time<={$lastday}")->queryAll();
         if($serverList)
         {
           foreach($serverList as $server)
           {//one server
              $where=" where server_id={$server['id']}";
              $sql=" select t.id,t.channel_id FROM ad_pos as t LEFT JOIN ad ON t.ad_id=ad.id {$where} ";
              $lifeTimePromote=$dbLocal->createCommand($sql)->queryAll();
              if($lifeTimePromote)
              {
                  $promoteChannel=array();
                  //ad pos 以channel规组
                  foreach($lifeTimePromote as $item)
                  {
                     $promoteChannel[$item['channel_id']][]=$item['id'];
                  }
                  $this->_calculate($promoteChannel,$server,$lastday);
              }

           }//end a server
         }
      }

      private function _calculate($promoteArr,$server,$date)
      {
          $dbLocal=Yii::app()->dbLocal;
          foreach($promoteArr as $channel=>$lifeTimeArr)
          {
            $detectedSql="SELECT count(1) FROM data_daily where server_id={$server['id']} and channel_id={$channel} and date='{$date}'";
            if(!$dbLocal->createCommand($detectedSql)->queryScalar())
            {
              $in=implode(',',$lifeTimeArr);
              $sql = "SELECT SUM(register_times) FROM user_stat WHERE ad_pos_id IN ({$in}) and `date`={$date}";
                    $register=(int)$dbLocal->createCommand($sql)->queryScalar();

              $sql = "SELECT SUM(register_times) FROM user_stat WHERE channel_id={$channel} and `date`={$date} and ad_pos_id>34";
              $channelRegister=(int) $dbLocal->createCommand($sql)->queryScalar();

              $sql = "SELECT SUM(register_times) FROM user_stat WHERE channel_id={$channel} and `date`<={$date} and ad_pos_id IN({$in})";
              $lifeTimeRegister=(int) $dbLocal->createCommand($sql)->queryScalar();



              echo "渠道{$channel}服{$server['id']},该服该渠道总注册{$lifeTimeRegister},当天该服该渠道注册:{$register},当天该渠道注册:{$channelRegister}\n";
              $costBase=$this->_getCost($channel,$date);
              $pay_type = $this->_getPayType($channel);

              $cost=0;
              $cpa=0;
              if ($pay_type == 2) {
              		if($costBase &&  $register)
		              {
		                $cost=round($costBase*$register,4);
		              }
		              $cpa = $costBase;
              } else if ($pay_type == 3) {
          			$sql = "SELECT sum(click_times) FROM click_stat where ad_pos_id in ({$in}) and `date`={$date}";
              		$click = (int) $dbLocal->createCommand($sql)->queryScalar();
              		if($costBase &&  $click)
		              {
		                $cost=round($costBase*$click/1000,4);
		              }
		              $cpa = $costBase;
              } else {
              		if($costBase &&  $channelRegister && $register)
		              {
		                $cost=round($costBase*$register/$channelRegister,4);
		              }
		              if($cost && $register)
		              {
		                $cpa=round($cost/$register,4);
		              }
              }


              echo "渠道总成本{$costBase},该次推广成本:{$cost},cpa:{$cpa}\n";

              $from=$date;
              $to=$date+86400;
              $sql="SELECT count(1) FROM user_server where ad_pos_id IN ({$in}) and server_id={$server['id']}
                   AND lively_time>={$from} and lively_time<{$to}";
              $revisitIncrement=(int)$dbLocal->createCommand($sql)->queryScalar();

              $sql="SELECT count(1) FROM user_server where ad_pos_id IN ({$in}) and server_id={$server['id']}
                    AND lively_time<{$to} and lively_time>0";
              $revisitAmount=(int)$dbLocal->createCommand($sql)->queryScalar();
              $revisitPercent=0;

              if($revisitAmount && $lifeTimeRegister)
              {
                $revisitPercent=round($revisitAmount/$lifeTimeRegister,4);
              }
              echo "回访新增：{$revisitIncrement},该渠道该服总回访数:{$revisitAmount},截止当天回访率:{$revisitPercent}\n";

              $sql="SELECT count(distinct user_id) FROM `order` where ad_pos_id IN ({$in})
                  and update_time>={$from} and update_time<{$to} and server_id={$server['id']}";
              $paymentPerson=(int)$dbLocal->createCommand($sql)->queryScalar();

              $sql="SELECT count(distinct user_id) FROM `order` where ad_pos_id IN ({$in})
                   and update_time>={$server['open_time']} and update_time<{$to} and server_id={$server['id']}";
              $lifetimePaymentPerson=(int)$dbLocal->createCommand($sql)->queryScalar();

              $sql="SELECT sum(paid-payment_tax) as paid FROM `order` where ad_pos_id IN ({$in})
                   and update_time>={$server['open_time']} and update_time<{$to} and server_id={$server['id']}";
              $paymentAmount=(int)$dbLocal->createCommand($sql)->queryScalar();

              $sql="SELECT sum(paid-payment_tax) as paid FROM `order` where ad_pos_id IN ({$in})
                   and update_time>={$from} and update_time<{$to} and server_id={$server['id']}";
              $paymentIncrement=(int)$dbLocal->createCommand($sql)->queryScalar();

              $paymentPercent=0;
              if($lifetimePaymentPerson && $lifeTimeRegister)
              {
                $paymentPercent=round($lifetimePaymentPerson/$lifeTimeRegister,8);
              }
              $shareBase=$this->_getShare($server['game_id'],$date);
              $sql="SELECT sum(profit) as paid FROM `order` where ad_pos_id IN ({$in})
                   and update_time>={$from} and update_time<{$to} and server_id={$server['id']}";
              $income=(int)$dbLocal->createCommand($sql)->queryScalar();

              $profitPercent=0;
              if($cost && $income)
              {
                $profitPercent=round($income/$cost,4);
              }
              echo "{该服该渠道}当天充值人数：{$paymentPerson},截止充值人数:{$lifetimePaymentPerson},截止充值数:{$paymentAmount}\n,当天新增充值:{$paymentIncrement},截止充值比率:{$paymentPercent},当天分成利润:{$income},当天回款率{$profitPercent} \n";
              $openDate=strtotime(date('Y-m-d',$server['open_time']));
              $week=ceil((($date-$openDate+1)/86400)/7);
              $month=ceil((($date-$openDate+1)/86400)/30);
              $fopenDate=date('Y-m-d',$openDate);
              $fdate=date('Y-m-d',$date);
              echo "开服日期:{$fopenDate},导入日期{$fdate},第{$week}周,第{$month}月\n";
              if($register || $revisitIncrement || $paymentPerson || $income)
              {
               $insertSql="INSERT INTO data_daily(`server_id`,`game_id`,`channel_id`,`open_time`,`cost`,`register`,`channel_register`
                  ,`lifetime_register`,`cpa`,`revisit_amount`,`revisit_increment`,`revisit_percent`,`payment_user`
                  ,`lifetime_payment_user`,`payment_percent`,`payment_amount`,`payment_increment`,`income`,`profit_percent`,`share_base`
                  ,`cost_base`,`date`,`week`,`month`) VALUES('{$server['id']}','{$server['game_id']}','{$channel}'
                  ,'{$server['open_time']}','{$cost}','{$register}','{$channelRegister}','{$lifeTimeRegister}','{$cpa}','{$revisitAmount}'
                  ,'{$revisitIncrement}','{$revisitPercent}','{$paymentPerson}','{$lifetimePaymentPerson}','{$paymentPercent}'
                  ,'{$paymentAmount}','{$paymentIncrement}','{$income}','{$profitPercent}','{$shareBase}','{$costBase}','{$date}'
                  ,'{$week}','{$month}')";
               $dbLocal->createCommand($insertSql)->execute();
              }
              else
              {
                  "NULL data ,throw it \n";
              }
            }
            else
            {
                echo "Has been generated!\n";
            }
          }
      }

    private function _getCost($channelId,$date)
    {
        $cost=0;
        if($channelId)
        {
          $dbLocal=Yii::app()->dbLocal;
          $where=" WHERE channel_id={$channelId} and date<={$date}";
          $cost=$dbLocal->createCommand("SELECT cost FROM channel_cost {$where} ORDER BY date DESC LIMIT 1")->queryScalar();
        }
        return $cost;
   }
    private function _getPayType($channelId)
    {
        $type=0;
        if ($channelId) {

	    	if(count($this->arr_channel))  {
	        	$type = $this->arr_channel[$channelId];

	        } else {
	        	$dbLocal=Yii::app()->dbLocal;
	        	$arr = $dbLocal->createCommand("SELECT id,pay_type FROM channel")->queryAll();


				if ($arr) {
	        		foreach ($arr as $re) {
	        			$this->arr_channel[$re['id']] = $re['pay_type'];
	        		}
				}

	        	$type = $this->arr_channel[$channelId];
	        }

        }

        return $type;
   }

   private function _getShare($gameId,$date)
   {
        $share=0;
        if($gameId)
        {
          $dbLocal=Yii::app()->dbLocal;
          $where=" WHERE game_id={$gameId} and date<={$date}";
          $cost=$dbLocal->createCommand("SELECT share FROM game_share {$where} ORDER BY date DESC LIMIT 1")->queryScalar();
        }
        return $cost;
   }
}
