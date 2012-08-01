<?php
class migrateCommand extends CConsoleCommand
{
    public function actionIndex($itemNum=3000)
    {
        $dbLocal=Yii::app()->dbLocal;
        $sql="SELECT * FROM user_server WHERE create_time=0 LIMIT {$itemNum}";
        $res=$dbLocal->createCommand($sql)->queryAll();
        $count=count($res);
        echo "Catch {$count} item\n";
        if($res)
        {
          $i=1;
          $updateSql='';
          foreach($res as $item)
          {
              $visitSql="SELECT time FROM visit where user_id={$item['user_id']} and server_id={$item['server_id']} order by time ASC LIMIT 1";
              $time=$dbLocal->createCommand($visitSql)->queryScalar();
              if(!$time)
              {
                echo "Miss {$i}:can't find #1 visit create_time\n";
                $time=time();
              }
              else
              {
                echo "Pass {$i}:find #1 visit create_time {$time}\n";
              }
              $updateSql.="UPDATE user_server set create_time={$time} where user_id={$item['user_id']} and server_id={$item['server_id']}; ";
              if($i%500==0 OR $i==$count)
              {
                  $dbLocal->createCommand($updateSql)->execute();
                  $updateSql='';
              }
              $i++;
          }
        }
    }
}
?>
