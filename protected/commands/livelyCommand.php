<?php
class livelyCommand extends CConsoleCommand {
    public function actionIndex($itemNum=10000)
    {
        $dbLocal=Yii::app()->dbLocal;
        $dbVisit=Yii::app()->dbVisit;
        $i=1;
        while($i<$itemNum)
        {
            $sql="SELECT user_id,server_id,login_times FROM user_server where login_times>1 and lively_time=0 LIMIT 1000";
            $res=$dbLocal->createCommand($sql)->queryAll();
            if($res)
            {
                $updateSql='';
                foreach($res as $item)
                {
                    $time=0;
                    $loginTimes=$item['login_times'];
                    $db='visit_'.substr(md5($item['user_id']),0,1);
                    $sql="SELECT time from {$db} where server_id={$item['server_id']} AND user_id={$item['user_id']}
                    ORDER BY TIME ASC LIMIT 2";
                    $res=$dbVisit->createCommand($sql)->queryAll($sql);
                    $dumpCount=count($res);
                    if($dumpCount==2)
                    {
                        $time=$res[1]['time'];
                    }
                    else
                    {
                        $sql="SELECT time from visit where server_id={$item['server_id']} AND user_id={$item['user_id']}
                        ORDER BY TIME ASC LIMIT 2";
                        $res=$dbLocal->createCommand($sql)->queryAll($sql);
                        $count=count($res);
                        $countSum=$dumpCount;
                        if($count>2)
                        {
                            $time=$res[$count-1]['time'];
                        }
                        else
                        {
                            $loginTimes=$countSum;
                            echo "None found for user:{$item['user_id']} and server:{$item['server_id']}\n";
                        }
                    }
                    $updateSql.=" UPDATE user_server SET login_times={$loginTimes},lively_time={$time} 
                        where server_id={$item['server_id']} AND user_id={$item['user_id']} ; ";
                    $i++;
                    if($updateSql && $i%200==0)
                    {
                       $dbLocal->createCommand($updateSql)->execute();
                       $updateSql='';
                       echo "补全至:{$i}\n";
                    }
                }
            }
            else
            {
                echo "已经没有未补全活跃点时间记录";
                $i=$itemNum+1;
            }
        }
    }
}
