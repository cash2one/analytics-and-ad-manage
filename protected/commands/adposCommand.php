<?php
class adposCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $now = time();

        $sqlstr = "select id,ad_id,pos_id,channel_id,bind_time from `ad_pos_slot` where bind_time < $now order by bind_time";

        $dbLocal=Yii::app()->dbLocal;
        $adPosList=$dbLocal->createCommand($sqlstr)->queryAll();

        if($adPosList) {
            foreach($adPosList as $adPos)
            {//one ad pos
                $oldAdPos=$dbLocal->createCommand("select id from ad_pos where pos_id=".$adPos['pos_id']." and active = 1")->queryAll();
                if($oldAdPos) {
                    $updateSql = "UPDATE ad_pos set active=0,debind_time={$adPos['bind_time']} where pos_id=".$adPos['pos_id']." and active=1;";
                    //echo $updateSql."\n";
                    $dbLocal->createCommand($updateSql)->execute();



                }
                $insertSql = "INSERT INTO ad_pos (`ad_id`,`pos_id`,`channel_id`,`bind_time`,`active`) VALUES('{$adPos['ad_id']}','{$adPos['pos_id']}','{$adPos['channel_id']}','{$adPos['bind_time']}',1);";
                //echo $insertSql."\n";
                $dbLocal->createCommand($insertSql)->execute();

                $deleteSql = "delete from `ad_pos_slot` where id=".$adPos['id'];
                //echo $deleteSql."\n";
                $dbLocal->createCommand($deleteSql)->execute();

                echo "Generate {$adPos['pos_id']} ad_pos record item at {$now}\n";

            }
        } else {
            echo "Needn't generate ad_pos record item at {$now}\n";
        }
    }


}
