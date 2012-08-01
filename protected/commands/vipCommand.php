<?php
class vipCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $dbLocal = Yii::app()->dbLocal;
        $sql = "SELECT id,game_id FROM server where `deleted`=0";
        $res = $dbLocal->createCommand($sql)->queryAll();
        if ($res) {
            foreach ($res as $item) {
                $serverId = $item['id'];
                $gameId = $item['game_id'];
                $this->_calculate($dbLocal, $serverId, $gameId);
                echo "Refresh server:{$serverId} vip list\n";
            }
        }
        $this->_statistics($dbLocal);
        echo "Refresh vip_user\n";
    }

    private function _statistics($dbLocal)
    {
        $cleanupSql = "TRUNCATE vip_user";
        $dbLocal->createCommand($cleanupSql)->execute();


        $sql = "select user_id,sum(paid) spaid from `order` GROUP BY user_id having sum(paid)>=1000 order by sum(paid) desc";
        $userList = $dbLocal->createCommand($sql)->queryAll();

        $insertSql = "";
        foreach($userList as $k=>$v) {

            //充值等级
            if($v['spaid'] > 10000) {
                $vip_rank = 10;
            } else {
                $vip_rank = floor($v['spaid']/1000);
            }

            //个人信息
            $sql = "SELECT
                      user.user_name user_name,
                      profile.name real_name,
                      user.email e_mail,
                      profile.qq qq,
                      profile.tel tel,
                      user.create_time reg_time,
                      user.ad_pos_id
                    FROM
                      user
                      LEFT OUTER JOIN profile ON (user.id = profile.user_id)
                    WHERE
                      user.id = '{$v['user_id']}'";
            $userInfo = $dbLocal->createCommand($sql)->queryRow();

            if($userInfo['real_name'] == '朱振华' || $userInfo['real_name'] == '0') //清除垃圾数据
                $userInfo['real_name'] = '';
            if(trim($userInfo['e_mail']) == 'guest@2144.cn') //清除垃圾数据
                $userInfo['e_mail'] = '';
            if(trim($userInfo['qq']) == '0') //清除垃圾数据
                $userInfo['qq'] = '';
            $channel_name = "";
            if($userInfo['ad_pos_id']) {
                $sql = "SELECT
                          channel.name
                        FROM
                          ad_pos
                          INNER JOIN channel ON (ad_pos.channel_id = channel.id)
                        WHERE
                          ad_pos.id = {$userInfo['ad_pos_id']}";
                $channel_name = $dbLocal->createCommand($sql)->queryScalar();
            } else {
                $channel_name = "";
            }

            $sql = "select distinct server_id from `order` where user_id='{$v['user_id']}'";
            $userGames = $dbLocal->createCommand($sql)->queryAll();
            foreach($userGames as $v2) {
                //最后充值时间
                $sql = "select update_time from `order` where user_id='{$v['user_id']}' and server_id='{$v2['server_id']}' order by update_time desc limit 1";
                $last_paid_time = $dbLocal->createCommand($sql)->queryScalar();

                //此游戏充值数量
                $sql = "select sum(paid) spaid from `order` where user_id='{$v['user_id']}' and server_id='{$v2['server_id']}' limit 1";
                $spaid = $dbLocal->createCommand($sql)->queryScalar();

                //此游戏区
                $sql = "select game_id from `order` where user_id='{$v['user_id']}' and server_id='{$v2['server_id']}' limit 1";
                $game_id = $dbLocal->createCommand($sql)->queryScalar();

                $insertSql  .= "INSERT INTO
                                  vip_user(
                                  game_id,
                                  server_id,
                                  user_id,
                                  user_name,
                                  vip_rank,
                                  sum_paid,
                                  last_paid_time,
                                  reg_channel,
                                  real_name,
                                  e_mail,
                                  qq,
                                  mobile_phone,
                                  reg_time)
                                VALUES(

                                  '$game_id',
                                  '{$v2['server_id']}',
                                  '{$v['user_id']}',
                                  '{$userInfo['user_name']}',
                                  '{$vip_rank}',
                                  '$spaid',
                                  '$last_paid_time',
                                  '$channel_name',
                                  '{$userInfo['real_name']}',
                                  '{$userInfo['e_mail']}',
                                  '{$userInfo['qq']}',
                                  '{$userInfo['tel']}',
                                  '{$userInfo['reg_time']}');\n";



            }



            //echo "username:{$userInfo['user_name']},user id:{$v['user_id']},paid:{$v['spaid']}\n";
        }
        if ($insertSql) {
            $dbLocal->createCommand($insertSql)->execute();
            $insertSql = '';
        }
    }


    private function _calculate($dbLocal, $serverId, $gameId)
    {
        $rangeSql1 = "SELECT user_id,ad_pos_id,channel_id,pos_id,register_time,sum(paid) as spaid,avg(paid) as apaid
            ,max(paid) as mpaid FROM `order` where server_id={$serverId} GROUP BY user_id having spaid>=1000 and spaid<5000";
        $rangeSql2 = "SELECT user_id,ad_pos_id,channel_id,pos_id,register_time,sum(paid) as spaid,avg(paid) as apaid
            ,max(paid) as mpaid FROM `order` where server_id={$serverId} GROUP BY user_id having spaid>=5000";
        //1000~5000
        $this->_clean($dbLocal, $serverId);
        $range1 = $dbLocal->createCommand($rangeSql1)->queryAll();
        $this->_writeRecord($dbLocal, $range1, $serverId, $gameId, 1);
        //5000
        $range2 = $dbLocal->createCommand($rangeSql2)->queryAll();
        $this->_writeRecord($dbLocal, $range2, $serverId, $gameId, 2);
    }

    private function _clean($dbLocal, $serverId)
    {
        $cleanupSql = "DELETE FROM `vip_list` where server_id={$serverId}";
        $dbLocal->createCommand($cleanupSql)->execute();
    }

    private function _writeRecord($dbLocal, $list, $serverId, $gameId, $range)
    {
        if ($list) {
            $insertSql = '';
            foreach ($list as $item) {
                $usernameSql = "SELECT user_name FROM user where id='{$item['user_id']}'";
                $username = $dbLocal->createCommand($usernameSql)->queryScalar();
                if ($username) {
                    $serverRegSql = "SELECT create_time,login_times FROM user_server where server_id={$serverId} and user_id={$item['user_id']}";
                    $userServer = $dbLocal->createCommand($serverRegSql)->queryRow();
                    if ($userServer) {
                        $serverReg = $userServer['create_time'];
                        $loginTimes = $userServer['login_times'];
                    } else {
                        $serverReg = $item['register_time'];
                        $loginTimes = 0;
                    }
                    $insertSql .= "INSERT INTO vip_list(`user_id`,`user_name`,`ad_pos_id`,`channel_id`,`pos_id`,`server_id`,`game_id`,`reg_time`,
                        `server_reg_time`,`avg_paid`,`max_paid`,`sum_paid`,`login_times`,`range`) VALUES('{$item['user_id']}','{$username}',
                            {$item['ad_pos_id']},{$item['channel_id']},{$item['pos_id']},{$serverId},{$gameId},{$item['register_time']},
                            {$serverReg},{$item['apaid']},{$item['mpaid']},{$item['spaid']},{$loginTimes},{$range});";
                    echo "user id:{$item['user_id']},username:{$username},paid:{$item['spaid']}\n";
                }
            }
            if ($insertSql) {
                $dbLocal->createCommand($insertSql)->execute();
                $insertSql = '';
            }
        }
    }
}
