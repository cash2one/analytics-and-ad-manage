<?php
class channelCommand extends CConsoleCommand
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
        $sql="SELECT id FROM channel where `deleted`=0";
        $res=$dbLocal->createCommand($sql)->queryColumn();
        $i=0;
        if($res)
        {
            $i=1;
            $insertSql='';
            foreach($res as $id)
            {
                $dectedSql="SELECT id FROM channel_data WHERE channel_id={$id} AND date={$date}";
                if(!$dbLocal->createCommand($dectedSql)->queryRow())
                {
                    $insertSql=$this->_calculate($dbLocal,$id,$from,$to);
                    if($insertSql)
                    {
                        $dbLocal->createCommand($insertSql)->execute();
                        $insertSql='';
                        echo "Generate channel:{$id} data record item on {$strLastday}\n";
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
        $where="WHERE t.bind_time<{$to} AND (t.debind_time=0 OR t.debind_time>{$from})";
        $sql="select id,ad_id,pos_id,channel_id FROM ad_pos as t {$where} ";
        $ads=$dbLocal->createCommand($sql)->queryAll();
        if($ads)
        {
          $this->_materialGen($dbLocal,$ads,$from,$to);
        }
    }

    private function _materialGen($dbLocal,$ads,$from,$to)
    {

       foreach($ads as  $adPos)
       {
         $insertSql='';
         $ad=$dbLocal->createCommand("SELECT path,game_id,server_id FROM ad where id={$adPos['ad_id']}")->queryRow();
         if($ad['path'])
         {
           preg_match('/^[a-z]{2,8}([0-9]{1,3})_[0-9]{1,3}$/',$ad['path'],$matches);
           if(isset($matches[1]))
           {
               $adPosId=$adPos['id'];
               $dectedSql="SELECT id FROM material_data WHERE ad_pos_id={$adPosId} AND date={$from}";
               if(!$dbLocal->createCommand($dectedSql)->queryRow())
               {
                   $click=(int)$dbLocal->createCommand("SELECT sum(click_times) FROM click_stat where ad_pos_id={$adPosId} and
                           date>={$from} and date<{$to}")->queryScalar();
                   $register=(int)$dbLocal->createCommand("SELECT sum(register_times) FROM user_stat where ad_pos_id={$adPosId} and
                           date>={$from} and date<{$to}")->queryScalar();
                   $visit=(int)$dbLocal->createCommand("SELECT count(1) FROM visit where ad_pos_id={$adPosId} and time>={$from} and time<{$to}")->queryScalar();
                   $registerVisit=(int)$dbLocal->createCommand("SELECT count(1) FROM visit where ad_pos_id={$adPosId} and is_register=1 and time>={$from} and time<{$to}")->queryScalar();
                   $normalVisit=$visit-$registerVisit;
                   if($click || $register || $visit)
                   {
                       $insertSql="INSERT INTO material_data(`channel_id`,`date`,`click`,`register`,`visit`,`register_visit` ,`normal_visit`
                           ,`ad_pos_id`,`material_id`,`ad_id`,`pos_id`,`game_id`,`server_id`) VALUES( {$adPos['channel_id']},{$from},{$click}
                               ,{$register} ,{$visit},{$registerVisit},{$normalVisit},{$adPosId},{$matches[1]},{$adPos['ad_id']},{$adPos['pos_id']}
                               ,{$ad['game_id']},{$ad['server_id']});";
                       $dbLocal->createCommand($insertSql)->execute();
                       echo "Generate material data {$adPosId}:{$matches[1]}\n";
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
           }
         }
       }
    }

    private function _calculate($dbLocal,$channelId,$from,$to)
    {
        $insertSql='';
        $poses=$dbLocal->createCommand("SELECT id FROM pos where channel_id={$channelId}")->queryColumn();
        if($poses)
        {
            $posIn=implode(',',$poses);
            $click=(int)$dbLocal->createCommand("SELECT sum(click_times) FROM click_stat where pos_id IN ({$posIn}) and
                    date>={$from} and date<{$to}")->queryScalar();
            $register=(int)$dbLocal->createCommand("SELECT sum(register_times) FROM user_stat where pos_id IN ({$posIn}) and
                    date>={$from} and date<{$to}")->queryScalar();
            $visit=(int)$dbLocal->createCommand("SELECT count(1) FROM visit where channel_id={$channelId} and time>={$from} and time<{$to}")->queryScalar();
            $registerVisit=(int)$dbLocal->createCommand("SELECT count(1) FROM visit where channel_id={$channelId} and is_register=1 and time>={$from} and time<{$to}")->queryScalar();
            $normalVisit=$visit-$registerVisit;
            $paymentUser=(int)$dbLocal->createCommand("SELECT count(distinct user_id) FROM `order` where channel_id={$channelId} and create_time>={$from}  and create_time<{$to}")->queryScalar();
            $paymentAmount=(float)$dbLocal->createCommand("SELECT sum(paid) FROM `order` where channel_id={$channelId} and create_time>={$from}  and create_time<{$to}")->queryScalar();
            $insertSql="INSERT INTO channel_data(`channel_id`,`date`,`click`,`register`,`visit`,`register_visit` ,`normal_visit`
                ,`payment_user`,`payment_amount`) VALUES( {$channelId},{$from},{$click},{$register},{$visit},{$registerVisit},
                {$normalVisit},{$paymentUser},{$paymentAmount});";
        }
        return $insertSql;
    }

}
