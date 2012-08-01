<?php
class User extends CActiveRecord
{
    public $ChannelId, $IP, $startDate, $endDate, $hasEmail, $hasCardid;
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return array(
            array('user_name, pwd, salt, ad_pos_id, email, ip, login_times, pay_times, create_time', 'required'),
            array('create_time', 'numerical', 'integerOnly'=>true),
            array('user_name, email', 'length', 'max'=>64),
            array('pwd, salt', 'length', 'max'=>32),
            array('ad_pos_id, login_times, pay_times', 'length', 'max'=>10),
            array('id, user_name, pwd, salt, ad_pos_id, email, ip, login_times, pay_times, create_time, u_type, u_id, u_name', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
            'profile' => array(self::HAS_ONE, 'Profile', 'user_id')
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => '用户ID',
            'user_name' => '用户名',
            'pwd' => 'Pwd',
            'salt' => 'Salt',
            'ad_pos_id' => 'Ad Pos',
            'email' => 'Email',
            'ip' => '注册IP',
            'login_times' => '登录次数',
            'pay_times' => 'Pay Times',
            'create_time' => '注册时间',
            'u_type' => '用户类型',
            'u_id' => '第三方用户id',
            'u_name' => '第三方用户名'
        );
    }

    public function search()
    {

        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('user_name',$this->user_name,true);
        $criteria->compare('pwd',$this->pwd,true);
        $criteria->compare('salt',$this->salt,true);
        $criteria->compare('ad_pos_id',$this->ad_pos_id,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('login_times',$this->login_times,true);
        $criteria->compare('pay_times',$this->pay_times,true);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('u_type',$this->u_type,true);
        $criteria->compare('u_id',$this->u_id,true);
        $criteria->compare('u_name',$this->u_name,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function userList()
    {
        $where = ' WHERE 1';
        if(!empty($this->id))
        {
            $where .= ' AND u.id = "'. $this->id. '"';
        }
        if(!empty($this->user_name))
        {
            $where .= ' AND u.user_name = "'. $this->user_name. '"';
        }
        if(!empty($this->u_name))
        {
            $where .= ' AND u.u_name = "'. $this->u_name. '"';
        }
        if(!empty($this->ChannelId))
        {
            $where .= $this->ChannelId == -1 ? ' AND u.ad_pos_id = 0' : ' AND ap.channel_id = '. $this->ChannelId;
        }
        if(!empty($this->startDate))
        {
            $where .= ' AND u.create_time >= '. strtotime($this->startDate);
        }
        if(!empty($this->endDate))
        {
            $where .= ' AND u.create_time <= '. strtotime($this->endDate. '23:59:59');
        }
        if(!empty($this->ip))
        {
            $where .= ' AND u.ip = '. ip2long($this->IP);
        }
        if(!empty($this->hasEmail))
        {
            $where .= $this->hasEmail == 1 ? ' AND u.email != "" AND u.email != "guest@2144.cn"' : ' AND (u.email = "" OR u.email = "guest@2144.cn")';
        }
        if(!empty($this->hasCardid))
        {
            $where .= $this->hasCardid == 1 ? ' AND p.card_id != "" AND p.name != ""' : ' AND (p.card_id = "" OR p.name = "" OR p.card_id IS NULL OR p.name IS NULL)';
        }
        
        if(isset($this->u_type) && $this->u_type != -1)
        {
            $where .= ' AND u.u_type = '. $this->u_type;
        }
        
        $count = Yii::app()->db->createCommand("SELECT COUNT(1) FROM user u LEFT JOIN profile p ON u.id = p.user_id LEFT JOIN ad_pos ap ON u.ad_pos_id = ap.id {$where}")->queryScalar();
        $sql = "SELECT u.id, u.user_name, u.ad_pos_id, u.create_time, u.ip, u.email, u.u_type, u.u_id, u.u_name, ap.channel_id, p.card_id, p.name, u.login_times
                FROM user u
                LEFT JOIN profile p
                ON u.id = p.user_id
                LEFT JOIN ad_pos ap
                ON u.ad_pos_id = ap.id
                {$where}";
        //echo $sql;
        return new CSqlDataProvider($sql,array(
            'totalItemCount' => $count,
            'sort' => array(
                'attributes' => array(
                    'u.id', 'u.create_time'
                ),
                'defaultOrder'=>'u.create_time DESC'
            )
           ,'pagination'=>array(
               'pageSize'=>20
               )
            ));
    }

    static public function  vipList($server,$from,$to)
    {
        $where = "WHERE 1";
        if(!empty($server))
        {
           $where .= " AND server_id IN ({$server})";
        }
        if(!empty($from))
        {
           $where .= " AND reg_time>={$from}";
        }

        if(!empty($to))
        {
           $where .= " AND reg_time<={$to}";
        }

        $count=Yii::app()->db->createCommand("SELECT count(`id`) FROM vip_list {$where}")->queryScalar();
        $sql="SELECT * FROM vip_list {$where}";
        return new CSqlDataProvider($sql,array(
                     'totalItemCount'=>$count
                    ,'sort'=>array(
                        'attributes'=>array(
                            'user_name','server_id','game_id','channel_id','reg_time','max_paid','avg_paid','sum_paid','login_times'),
                            'defaultOrder'=>'game_id DESC,server_id DESC'
                        )
                    ,'pagination'=>array(
                        'pageSize'=>20
                        )
                    ));
    }

    static public function nbChannel($serverId=null,$from=null,$to=null)
    {
        return self::_nbAnalytics('where ad_pos_id>34 ',$serverId,$from,$to);
    }

    static public function nbPlatform($serverId=null,$from=null,$to=null)
    {
        return self::_nbAnalytics('where ad_pos_id=0 ',$serverId,$from,$to);
    }

    static public function nbCompressAd($serverId=null,$from=null,$to=null)
    {
        return self::_nbAnalytics('where ad_pos_id>0 and ad_pos_id<=34 ',$serverId,$from,$to);
    }

    static public function nbUser($serverId=null,$from=null,$to=null,$loginTimes=null)
    {
        return self::_nbAnalytics('where 1 ',$serverId,$from,$to,$loginTimes);
    }

    static private function _nbAnalytics($where,$serverId,$from,$to)
    {
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        if($from)
        {
          $where.=" AND create_time>=$from";
        }
        if($to)
        {
          $where.=" AND create_time<$to";
        }
        return Yii::app()->db->createCommand("SELECT count(1) FROM user_server {$where}")->queryScalar();
    }

    static public function loyalUser($serverId,$from,$to=null)
    {
        $where=" WHERE login_times>=$from";
        $where.=" AND server_id={$serverId}";
        if($to)
        {
           $where.=" AND login_times<={$to}";
        }
        return Yii::app()->db->createCommand("SELECT count(*) FROM user_server {$where}")->queryScalar();
    }

    public static function countRegisterByAdPos($ad_pos_id = null, $from = null, $to = null,$type = 'date')
    {
        $return=0;
        if($type == 'hour' AND $to >= strtotime(date("Y-m-d H:00:00")))
        {
          $where = $ad_pos_id ?"WHERE ad_pos_id = {$ad_pos_id}":"WHERE ad_pos_id>34";
          $where .= $from ? " AND `create_time` >= {$from}" : '';
          $where .= $to ? " AND `create_time` <= {$to}" : '';
          $sql = "SELECT COUNT(1) FROM user {$where}";
          $return = Yii::app()->db->createCommand($sql)->queryScalar();
        }
        else
        {
          $where = $ad_pos_id ?"WHERE ad_pos_id = {$ad_pos_id}":"WHERE ad_pos_id>34";
          $where .= $from ? " AND `{$type}` >= {$from}" : '';
          $where .= $to ? " AND `{$type}` < {$to}" : '';
          $dependency=new CDbCacheDependency("SELECT count(1) FROM user_stat {$where}");
          $sql = "SELECT SUM(register_times) FROM user_stat {$where}";
          $return = Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
        }
        return $return ? $return : 0;
    }

    public static function countRegisterByPos($pos_id, $from = null, $to = null,$type='hour')
    {
        $return=0;
        if($type == 'hour' AND $to >= strtotime(date("Y-m-d H:00:00")))
        {
          $where = "WHERE  ad_pos_id IN (select id from ad_pos where pos_id={$pos_id} and
              bind_time<{$to} AND (debind_time=0 OR debind_time>{$from})) ";
          $where .= $from ? " AND `create_time` >= {$from}" : '';
          $where .= $to ? " AND `create_time` <= {$to}" : '';
          $sql = "SELECT COUNT(1) FROM user {$where}";
          $return = Yii::app()->db->createCommand($sql)->queryScalar();
        }
        else
        {
          $where = "WHERE pos_id = {$pos_id}";
          $where .= $from ? " AND `{$type}` >= {$from}" : '';
          $where .= $to ? " AND `{$type}` < {$to}" : '';
          $dependency=new CDbCacheDependency("SELECT count(1) FROM user_stat {$where}");
          $sql = "SELECT SUM(register_times) FROM user_stat {$where}";
          $return = Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
        }
        return $return ? $return : 0;
    }

    public static function maxRegister($pos_id)
    {
        $sql = "SELECT SUM(register_times) AS count, `date` FROM user_stat WHERE pos_id = {$pos_id} GROUP BY `date`";
        $_tmp = Yii::app()->db->createCommand($sql)->queryAll();
        $max = array();
        if(is_array($_tmp) && count($_tmp) > 0)
        {
            $max = max($_tmp);
        }

        $return = $max ? $max['count']. ' -- '. date('Y/m/d', $max['date']) : '--';
        return $return;
    }

    public static function getAddress($ip)
    {
        Yii::import('application.extensions.ip.*');
        $address = new IpLocation();
        $result = $address->getaddress($ip);
        $addr = '未知地址';
        if($result['area1'] && $result['area2'])
            $addr =  iconv('GBK', 'UTF-8', $result['area1']). ' '. iconv('GBK', 'UTF-8', $result['area2']);

       return $addr;
    }

    public static function batchRegisterByAdPos($adPosArr,$from,$to)
    {
          $in=implode(',',$adPosArr);
          $where = "WHERE ad_pos_id IN ({$in}) and ad_pos_id>34";
          $where .= $from ? " AND `date` >= {$from}" : '';
          $where .= $to ? " AND `date` <= {$to}" : '';
          $sql = "SELECT SUM(register_times) FROM user_stat {$where}";
          return (int) Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public static function batchRegisterByPos($posArr,$from,$to)
    {
          $in=implode(',',$posArr);
          $where = "WHERE pos_id IN ({$in}) ";
          $where .= $from ? " AND `date` >= {$from}" : '';
          $where .= $to ? " AND `date` <= {$to}" : '';
          $sql = "SELECT SUM(register_times) FROM user_stat {$where}";
          return (int) Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public static function registerByChannel($channel,$from,$to)
    {
      $where = "WHERE channel_id={$channel} ";
      $where .= $from ? " AND `date` >= {$from}" : '';
      $where .= $to ? " AND `date` <= {$to}" : '';
      $sql = "SELECT SUM(register_times) FROM user_stat {$where}";
      return (int) Yii::app()->db->createCommand($sql)->queryScalar(); 
    }

    public static function countRoleCreateByAdPos($adPosId,$utype,$from=null,$to=null)
    {
      $where  ="where ad_pos_id={$adPosId} and role_create=1 and u_type={$utype}";
      $where .= $from ? " AND `create_time` >= {$from}" : '';
      $where .= $to ? " AND `create_time` <= {$to}" : '';
      $sql = "SELECT count(1) FROM user {$where}";
      return (int) Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public static function registerByChannelType($type,$from,$to)
    {
      $return=0;
      $db=Yii::app()->db;
      if($to>= strtotime(date("Y-m-d H:00:00")))
      {
        $poses=$db->createCommand("select pos.id FROM pos,channel where pos.channel_id=channel.id and channel.type={$type}")->queryColumn();
        foreach($poses as $pos)
        {
            $return+=self::countRegisterByPos($pos,$from,$to);
        }
      }
      else
      {
          $channels=$db->createCommand("SELECT id FROM channel where type={$type}")->queryColumn();
          foreach($channels as $channel)
          {
             $where = "WHERE channel_id={$channel} ";
             $where .= $from ? " AND `hour` >= {$from}" : '';
             $where .= $to ? " AND `hour` < {$to}" : '';
             $sql = "SELECT SUM(register_times) FROM user_stat {$where}";
             $return+=(int)$db->createCommand($sql)->queryScalar(); 
          }
      }
      return $return;
    }

}
