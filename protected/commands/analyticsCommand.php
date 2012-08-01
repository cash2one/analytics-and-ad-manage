<?php
class analyticsCommand extends CConsoleCommand
  {
      public function actionIndex()
      {
         $time=time();
         $dbLocal=Yii::app()->dbLocal;
         $this->_analyticsUser($dbLocal,$time);
         $this->_analyticsClick($dbLocal,$time);
         $this->_analyticsVisit($dbLocal,$time);
         $this->_cleanupClick($dbLocal,$time);
         $this->_dumpVisit($dbLocal,$time,10000);
      }

      public function actionDumpVisit($itemNum)
      {
         $dbLocal=Yii::app()->dbLocal;
         $time=time();
         $this->_dumpVisit($dbLocal,$time,$itemNum);
      }

      public function actionRegVisit($from)
      {
         $hour=strtotime($from);
         $dbLocal=Yii::app()->dbLocal;
         $dectedSql="SELECT id,server_id,hour FROM visit_stat WHERE hour>={$hour} and uv_visit_times>0";
         $res=$dbLocal->createCommand($dectedSql)->queryAll();
         if($res)
         {
             $updateSql='';
             $i=1;
             foreach($res as $item)
             {
              $serverId=$item['server_id'];
              $from=$item['hour'];
              $to=$item['hour']+3600;
              $id=$item['id'];
              $uvRegVisitSql="SELECT count(id) FROM user_server where server_id={$serverId} AND create_time BETWEEN {$from} AND {$to}";
              $uvRegVisitCount=$dbLocal->createCommand($uvRegVisitSql)->queryScalar();
              $updateSql.="UPDATE visit_stat set uv_register_visit={$uvRegVisitCount} where id={$id};";
              if($i%200==0)
              {
                $dbLocal->createCommand($updateSql)->execute();
                echo "Update {$i}  visit record item\n";
                $updateSql='';
              }
              $i++;
             }
         }
      }

      public function actionPoint($strTime)
      {
         $dbLocal=Yii::app()->dbLocal;
         $time=strtotime($strTime);
         if($time>time())
         {
             echo "InValid time\n ";
         }
         else
         {
           $this->_analyticsUser($dbLocal,$time);
           $this->_analyticsClick($dbLocal,$time);
           $this->_analyticsVisit($dbLocal,$time);
         }
      }

      public function actionRange($from,$to=null)
      {
           $fromTime=strtotime($from);
           $now=time();
           if($to==null)
           {
             $toTime=$now;
           }
           else
           {
             $toTime=strtotime($to);
           }

           if(($toTime-$fromTime)<3600 || $toTime<=$now)
           {
               $dbLocal=Yii::app()->dbLocal;
               for($i=$fromTime;$i<=$toTime;$i+=3600)
               {
                  $this->_analyticsUser($dbLocal,$i);
                  $this->_analyticsClick($dbLocal,$i);
                  $this->_analyticsVisit($dbLocal,$i);
               }
           }
           else
           {
             echo "InValid time\n ";
           }
      }

      private function _analyticsUser($dbLocal,$time)
      {
         //精确到小时的时间戳,上一个小时
         $hour=strtotime(date("Y-m-d H:00:00",$time))-3600;
         $strHour=date("Y-m-d H:i:s",$hour);
         //精确到天的时间戳
         $date=strtotime(date("Y-m-d",$hour));
         $from=$hour;
         $to=$hour+3600;
         $sql="SELECT ad_pos_id FROM `user` where ad_pos_id<>0 and create_time >={$from}  AND create_time<{$to} group by ad_pos_id";
         $res=$dbLocal->createCommand($sql)->queryColumn();
         $i=0;
         if($res)
         {
           $insertSql='';
           foreach($res as $id)
           {
             $dectedSql="SELECT id FROM user_stat WHERE ad_pos_id={$id} AND hour={$hour}";
             if(!$dbLocal->createCommand($dectedSql)->queryRow())
             {
              $res=$dbLocal->createCommand("SELECT pos_id,channel_id FROM `ad_pos` where id={$id}")->queryRow();
              $posId=$res['pos_id'];
              $channelId=$res['channel_id'];
              $userSql="SELECT count(1) FROM user where ad_pos_id={$id} AND create_time >={$from} AND create_time<{$to}";
              $userCount=$dbLocal->createCommand($userSql)->queryScalar();
              $insertSql.="INSERT INTO user_stat (`ad_pos_id`,`pos_id`,`channel_id`,`register_times`,`hour`,`date`)
                 VALUES('{$id}','{$posId}','{$channelId}','{$userCount}','{$hour}','{$date}');";
              $i++;
             }
           }
           if($insertSql)
           {
             $dbLocal->createCommand($insertSql)->execute();
             echo "Generate {$i}  user analytics record item for {$strHour}\n";
           }
           else
           {
             echo "Has been generate for user {$strHour}\n";
           }
         }
         else
         {
             echo "Needn't generate static for user {$strHour}\n";
         }
      }

       private function _analyticsClick($dbLocal,$time)
      {
         //精确到小时的时间戳,上一个小时
         $hour=strtotime(date("Y-m-d H:00:00",$time))-3600;
         $strHour=date("Y-m-d H:i:s",$hour);
         //精确到天的时间戳
         $date=strtotime(date("Y-m-d",$hour));
         $from=$hour;
         $to=$hour+3600;
         $sql="SELECT ad_pos_id FROM `click` where time >={$from}  AND time<{$to} group by ad_pos_id";
         $res=$dbLocal->createCommand($sql)->queryColumn();
         $i=0;
         if($res)
         {
           $insertSql='';
           foreach($res as $id)
           {
             $dectedSql="SELECT id FROM click_stat WHERE ad_pos_id={$id} AND hour={$hour}";
             if(!$dbLocal->createCommand($dectedSql)->queryRow())
             {
              $posId=$dbLocal->createCommand("SELECT pos_id FROM `ad_pos` where id={$id}")->queryScalar();
              $clickSql="SELECT count(1) FROM click where ad_pos_id={$id} AND time >={$from}  AND time<{$to}";
              $clickCount=$dbLocal->createCommand($clickSql)->queryScalar();
              $uvClickSql="SELECT count(DISTINCT ip) FROM click where ad_pos_id={$id} AND time >={$from}  AND time<{$to}";
              $uvClickCount=$dbLocal->createCommand($uvClickSql)->queryScalar();
              $insertSql.="INSERT INTO click_stat (`ad_pos_id`,`pos_id`,`click_times`,`uv_click_times`,`hour`,`date`)
                 VALUES('{$id}','{$posId}','{$clickCount}','{$uvClickCount}','{$hour}','{$date}');";
              $i++;
             }
           }
           if($insertSql)
           {
             $dbLocal->createCommand($insertSql)->execute();
             echo "Generate {$i}  click analytics record item for {$strHour}\n";
           }
           else
           {
             echo "Has been generate for click {$strHour}\n";
           }
         }
         else
         {
             echo "Needn't generate static for click {$strHour}\n";
         }
      }

      private function _analyticsVisit($dbLocal,$time)
      {

         //精确到小时的时间戳,上一个小时
         $hour=strtotime(date("Y-m-d H:00:00",$time))-3600;
         $strHour=date("Y-m-d H:i:s",$hour);
         //精确到天的时间戳
         $date=strtotime(date("Y-m-d",$hour));
         $from=$hour;
         $to=$hour+3600;
         $sql="SELECT id,game_id FROM server where `deleted`=0";
         $res=$dbLocal->createCommand($sql)->queryAll();
         $i=0;
         if($res)
         {
           $insertSql='';
           foreach($res as $item)
           {
             $serverId=$item['id'];
             $gameId=$item['game_id'];
             $dectedSql="SELECT id FROM visit_stat WHERE server_id={$serverId} AND hour={$hour}";
             if(!$dbLocal->createCommand($dectedSql)->queryRow())
             {
              $visitSql="SELECT count(1) FROM visit where server_id={$serverId} AND time BETWEEN {$from} AND {$to}";
              $visitCount=$dbLocal->createCommand($visitSql)->queryScalar();
              $uvVisitSql="SELECT count(DISTINCT user_id) FROM visit where server_id={$serverId} AND time BETWEEN {$from} AND {$to}";
              $uvVisitCount=$dbLocal->createCommand($uvVisitSql)->queryScalar();
              $uvRegVisitSql="SELECT count(id) FROM user_server where server_id={$serverId} AND create_time BETWEEN {$from} AND {$to}";
              $uvRegVisitCount=$dbLocal->createCommand($uvRegVisitSql)->queryScalar();
              $insertSql.="INSERT INTO visit_stat (`game_id`,`server_id`,`visit_times`,`uv_visit_times`,`uv_register_visit`,`hour`,`date`)
                 VALUES('{$gameId}','{$serverId}','{$visitCount}','{$uvVisitCount}','{$uvRegVisitCount}','{$hour}','{$date}');";
              $i++;
            }
           }
           if($insertSql)
           {
             $dbLocal->createCommand($insertSql)->execute();
             echo "Generate {$i}  visit analytics record item for {$strHour}\n";
           }
           else
           {
             echo "Has been generate for visit {$strHour}\n";
           }
         }
      }

      private function _cleanupClick($dbLocal,$time)
      {
          $deadLine=$time-432000;//five days ago
          $search="SELECT count(1) FROM click WHERE time<{$deadLine};";
          $count=$dbLocal->createCommand($search)->queryScalar();
          $date=date("Y-m-d H:i:s",$deadLine);
          if($count>0)
          {
            echo "Cleaup {$count} click record item before {$date}\n";
            $dbLocal->createCommand("DELETE FROM click WHERE time<{$deadLine};")->execute();
          }
          else
          {
            echo "Needn't cleaup click record before {$date}\n";
          }
      }

      private function _dumpVisit($dbLocal,$time,$itemNum=null)
      {
          $dbVisit=Yii::app()->dbVisit;
          $deadLine=$time-604800;//seven days ago
          $search="SELECT count(1) FROM visit WHERE time<{$deadLine};";
          $count=$dbLocal->createCommand($search)->queryScalar();
          if($itemNum)
          {
            $count=$itemNum;
          }
          $date=date("Y-m-d H:i:s",$deadLine);
          if($count>0)
          {
            $itemNum=500;
            $minId=0;
            for($i=1;$i<$count;$i+=500)
            {
             $sql="SELECT * FROM visit where time<{$deadLine} and id>{$minId} order by id ASC LIMIT {$itemNum}";
             $res=$dbLocal->createCommand($sql)->queryAll();
             $insertSql='';
             if($res)
             {
               foreach($res as $item)
               {
                   $db='visit_'.substr(md5($item['user_id']),0,1);
                   $minId=$item['id'];
                   $insertSql.="INSERT INTO `{$db}` (`g_id`,`game_id`,`server_id`,`user_id`,`ad_pos_id`,`ip`,`time`) VALUES
                       ('{$item['id']}','{$item['game_id']}','{$item['server_id']}','{$item['user_id']}','{$item['ad_pos_id']}','{$item['ip']}'
                       ,'{$item['time']}');";
               }
               if($insertSql)
               {
                  $dbVisit->createCommand($insertSql)->execute();
                  $insertSql='';
                  $deleteSql="DELETE FROM visit where  time<{$deadLine} and id<={$minId}";
                  $dbLocal->createCommand($deleteSql)->execute();
               }
             }
             echo "dump 500 record\n";
            }
            $dbLocal->createCommand($deleteSql)->execute();
          }
          else
          {
            echo "Needn't dump visit record  before {$date}\n";
          }
      }
  }
?>
