 <?php
class validCommand extends CConsoleCommand
{
    public function actionIndex($from=null,$to=null)
    {
        $nowHour=strtotime(date('Y-m-d H:00'));
        $begin=$from?strtotime($from):$nowHour-7200;
        $end=$to?strtotime($to):$nowHour-3600;
        if($begin>$end)list($begin,$end)=array($end,$begin);
        $this->_travelUser($begin,$end);
    }

    function _travelUser($from,$to)
    {
        $strFrom=date('Y-m-d H:i',$from);
        $strTo=date('Y-m-d H:i',$to);
        $db = Yii::app()->dbLocal;
        $sql="SELECT id,user_name FROM user where ad_pos_id>0 and u_type=1 and create_time>={$from} and create_time<={$to} and role_create=0";
        $userList=$db->createCommand($sql)->queryAll();
        if($userList)
        {
          foreach($userList as $user)
          {
            $sql="SELECT server_id FROM user_server where user_id={$user['id']}";
            $server=$db->createCommand($sql)->queryColumn();
            if($server)
            {
              foreach($server as $serverId)
              {
                if($this->_isCreate($serverId,$user['user_name']))
                {
                    $this->_setActive($user['id']);
                    echo "active user {$user['id']}:{$user['user_name']} ON server:{$serverId} \n";
                    break;
                }
              }
            }
          }
        }
        else
        {
          echo "No SNDA user from {$strFrom} to {$strTo} register\n";
        }
    }


    private function _setActive($uid)
    {
        $db = Yii::app()->dbLocal;
        $sql="UPDATE user SET role_create=1 where id={$uid}";
        $db->createCommand($sql)->execute();
    }

    private function _isCreate($serverId,$userName)
    {
       $active=false;
       $time=time();
       $gameType=$this->_getGametype($serverId);
       $token=$this->_getGameCode($gameType,$userName,$time);
       $queryArr=array(
               'gamepaytype'=>$gameType,
               'username'=>$userName,
               'time'=>$time,
               'checkcode'=>$token,
               'gameoptionid'=>3,
               );
       $queryStr=http_build_query($queryArr);
       $url="http://pay.2144.cn/checkgame/gamepay.php";
       $url.='?'.$queryStr;
       $response='';
       $this->_httpGet($response,$url);
       if($response)
       {
         $response=unserialize(urldecode($response));
         if(isset($response['isuser'])&& $response['isuser']==1)
         {
            $active=true;
         }
       }
       return $active;
    }

    private function _getGameCode($gameType,$uid,$time)
    {
       $db = Yii::app()->dbLocal;
       $sql = "SELECT token FROM server_interface where game_type={$gameType}";
       $res = $db->createCommand($sql)->queryScalar();
       $res = $res ? $res :'.*_123';
       $token=substr(md5($res . $uid . $time) , 2 , 8);
       return $token;
    }

    private function _getGametype($serverId)
    {
       $db = Yii::app()->dbLocal;
       $sql = "SELECT game_type FROM server where id={$serverId}";
       return $db->createCommand($sql)->queryScalar();
    }

    private function _httpGet(&$result, $httpUrl, $s = 1 , $t = 1)
    {
        if($t == 0)
        {
            $html = @file_get_contents("$httpUrl");
        }
        else
        {
            $urlarray = parse_url($httpUrl);
            $host = $urlarray['host'];
            $url = $urlarray['path'] . "?" . $urlarray['query'];
            $html = $this->_getsocket($host, 80, $url);
        }
        //过滤掉返回值中的垃圾
        $html = str_replace(' ', '', $html);
        $html = str_replace("\n", '', $html);
        $html = str_replace("\r", '', $html);
        //若为1则返回全部
        if ($s == 1)
        {
            $result = $html;
            return true;
        }
        else//否则拆分后返回
        {
            $vlist  = array();
            $vlist  = split("&", $html);
            foreach ($vlist as $k => $v)
            {
                $rlist  = split('=', $v);
                if (isset($rlist[0]) && isset($rlist[1]) && $rlist[0] != '' && $rlist[1] != '')
                {
                    $result[$rlist[0]] = $rlist[1];
                }
            }
        }
    }

    private function _getsocket($host, $port, $url)
    {
        $fp = @fsockopen ($host, $port, $errno, $errstr, 30);
        if (!$fp)
        {
            return "reason=1&error=" . $errno;
        }
        else
        {
            @fputs ($fp, "GET $url HTTP/1.0\r\nHost:" .$host . "\r\n\r\n");
            $line = '';
            while (!@feof($fp))
            {
                $line .= @fgets ($fp, 128);
            }
            @fclose ($fp);
        }
        $line = @preg_replace("/(.+?)\\r\\n\\r\\n(.+?)/is", "\\2", $line, 1);
        return $line;
    }
}
