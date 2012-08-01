 <?php
  class userCommand extends CConsoleCommand
  {
      public function actionIndex($itemNum=1000)
      {
          $dbLocal=Yii::app()->dbLocal;
          $sql="SELECT id,server_id,create_time,user_id FROM user_server where game_id=0 LIMIT {$itemNum}";
          $res=$dbLocal->createCommand($sql)->queryAll();
          $count=count($res);
          echo "catch:{$count} items\n";
          if($res)
          {
              $i=1;
              $updateSql='';
              foreach($res as $item)
              {
                $origServerSql="SELECT server_id FROM user_server where user_id={$item['user_id']} and create_time<{$item['create_time']} ORDER BY create_time ASC  LIMIT 1";
                $preServerSql="SELECT server_id FROM user_server where user_id={$item['user_id']} and create_time<{$item['create_time']} ORDER BY create_time DESC  LIMIT 1";
                $origServer=$dbLocal->createCommand($origServerSql)->queryScalar();
                if(!$origServer)
                {
                    $origServer=$item['server_id'];
                }

                if($origServer==$item['server_id'])
                {
                    $preServer=$origServer;
                }
                else
                {
                  $preServer=$dbLocal->createCommand($preServerSql)->queryScalar();
                  if(!$preServer)
                  {
                    $preServer=$item['server_id'];
                  }
                }

                if($origServer==$item['server_id'])
                {
                  $gameId=$dbLocal->createCommand("SELECT game_id FROM server where id={$item['server_id']}")->queryScalar();
                  $origGame=$preGame=$gameId;
                }
                else
                {
                  $origGame=$dbLocal->createCommand("SELECT game_id FROM server where id={$origServer}")->queryScalar();
                  $preGame=$dbLocal->createCommand("SELECT game_id FROM server where id={$preServer}")->queryScalar();
                  $gameId=$dbLocal->createCommand("SELECT game_id FROM server where id={$item['server_id']}")->queryScalar();
                }

                if(!$preGame)
                {
                  $preGame=$gameId;
                }
                if(!$origGame)
                {
                   $origGame=$gameId;
                }

                if($gameId)
                {
                $updateSql="UPDATE user_server SET orgi_server={$origServer},pre_server={$preServer},game_id={$gameId},
                    orgi_game={$origGame},pre_game={$preGame} where id='{$item['id']}';";
                }

                if($updateSql)
                {
                  $dbLocal->createCommand($updateSql)->execute();
                  $updateSql='';
                  echo "Has update {$i} user_server record \n";
                }
                $i++;
              }
          }
      }
  }
