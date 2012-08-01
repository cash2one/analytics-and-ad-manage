<?php
class statCommand extends CConsoleCommand
  {
      public function actionIndex()
      {
         $dbLocal=Yii::app()->dbLocal;
         $this->_updateAdPos($dbLocal);
         $this->_updateServer($dbLocal);
      }

      private function _updateAdPos($dbLocal)
      {
         $sql="SELECT id FROM ad_pos WHERE active=1";
         $res=$dbLocal->createCommand($sql)->queryColumn();
         $countAdPos=count($res);
         $beginTime=date("Y-m-d H:i:s");
          if($res)
         {
           echo "There are {$countAdPos} Ad Pos item need to update @{$beginTime}\n";
           $updateSql='';
           foreach($res as $adPosId)
           {
              $clickSql="SELECT sum(click_times) FROM click_stat where ad_pos_id={$adPosId}";
              $clickCount=$dbLocal->createCommand($clickSql)->queryScalar();
              $userSql="SELECT count(1) FROM user where ad_pos_id={$adPosId}";
              $userCount=$dbLocal->createCommand($userSql)->queryScalar();
              echo "AD POS:{$adPosId};click:{$clickCount};register:{$userCount};\n";
              $updateSql.="UPDATE ad_pos SET `click_times`='{$clickCount}',`register_times`='{$userCount}' where id={$adPosId};";
           }
           if($updateSql)
           {
              $endTime=date("Y-m-d H:i:s");
              $dbLocal->createCommand($updateSql)->execute();
              echo "update Ad Pos stat done  @{$endTime}\n";
           }
         }
         else
         {
            echo "None Ad Pos item need to update @{$beginTime}\n";
         }
      }

      private function _updateServer($dbLocal)
      {
         $sql="SELECT id FROM server WHERE active=1";
         $res=$dbLocal->createCommand($sql)->queryColumn();
         $countServer=count($res);
         $beginTime=date("Y-m-d H:i:s");
         if($res)
         {
           echo "There are {$countServer} server item need to update @{$beginTime}\n";
           $updateSql='';
           foreach($res as $serverId)
           {
              $visitSql="SELECT count(1) FROM visit where server_id={$serverId}";
              $visitCount=$dbLocal->createCommand($visitSql)->queryScalar();
              $userSql="SELECT count(1) FROM  user_server where server_id={$serverId}";
              $userCount=$dbLocal->createCommand($userSql)->queryScalar();
              echo "server:{$serverId};visit:{$visitCount};register:{$userCount};\n";
              $updateSql.="UPDATE server SET `login_times`='{$visitCount}',`register_times`='{$userCount}' where id={$serverId};";
           }
           if($serverId)
           {
              $endTime=date("Y-m-d H:i:s");
              $dbLocal->createCommand($updateSql)->execute();
              echo "update Server stat done  @{$endTime}\n";
           }
         }
         else
         {
            echo "None server item need to update @{$beginTime}\n";
         }
      }
  }
