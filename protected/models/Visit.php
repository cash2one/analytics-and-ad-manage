<?php
class Visit extends CActiveRecord
{
    public $startDate, $endDate;
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'visit';
    }

    public function rules()
    {
        return array(
            array('game_id, server_id, user_id, ip, time', 'required'),
            array('game_id, server_id, user_id, time', 'numerical', 'integerOnly'=>true),
            array('ip', 'length', 'max'=>10),
            array('game_id, server_id, user_id, ip, time', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
        );
    }

    public function attributeLabels()
    {
        return array(
            'game_id' => '登录游戏',
            'server_id' => '游戏服',
            'user_id' => 'User',
            'ip' => '登录IP地址',
            'time' => '登录时间',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('game_id',$this->game_id);
        $criteria->compare('server_id',$this->server_id);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('ip',$this->ip,true);
        $criteria->compare('time',$this->time);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function userGameList()
    {
        $where = 'WHERE user_id = '. $this->user_id;
        if(!empty($this->game_id))
        {
            $where .= ' AND game_id = '. $this->game_id;
        }
        if(!empty($this->server_id))
        {
            $where .= ' AND server_id = '. $this->server_id;
        }
        if(!empty($this->startDate))
        {
            $where .= ' AND time >= '. strtotime($this->startDate);
        }
        if(!empty($this->endDate))
        {
            $where .= ' AND time <= '. strtotime($this->endDate. '23:59:59');
        }
        $count = Yii::app()->db->createCommand("SELECT COUNT(1) FROM visit {$where}")->queryScalar();
        $sql = "SELECT * FROM visit {$where}";
        return new CSqlDataProvider($sql, array(
            'keyField' => 'game_id',
            'totalItemCount' => $count,
            'sort' => array(
                'attributes' => array(
                    'id', 'time'
                ),
                'defaultOrder'=>'time DESC'
            ),
            'pagination'=>array(
               'pageSize'=>20
            )
        ));
    }

    static public function nbVisit($serverId,$from=null,$to=null)
    {
        $where="WHERE 1 ";
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        if($from)
        {
          $where.=" AND time>=$from";
        }
        if($to)
        {
          $where.=" AND time<$to";
        }
        return (int)Yii::app()->db->createCommand("SELECT count(*) FROM visit {$where}")->queryScalar();
    }


    public static function countRevisitByAdPos($ad_pos_id, $from = null, $to = null)
    {
        //ad pos 天然确定了某一游戏服，所以这里不需要 distinct
        $where = "WHERE ad_pos_id = {$ad_pos_id} AND login_times>1 ";
        $where .= $from ? " AND register_time >= {$from}" : '';
        $where .= $to ? " AND register_time <= {$to}" : '';
        $dependency = new CDbCacheDependency('SELECT count(1) FROM user_server ' . $where);
        $sql = "SELECT count(1) FROM user_server {$where}";
        return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
    }

    public static function countRevisitByPos($pos_id, $from = null, $to = null, $start = 2, $end = null)
    {
        $where = "WHERE pos_id = {$pos_id} AND login_times>={$start}";
        $where .= $end ? " AND login_times <= {$end}" : '';
        $where .= $from ? " AND register_time >= {$from}" : '';
        $where .= $to ? " AND register_time <= {$to}" : '';
        $dependency = new CDbCacheDependency('SELECT count(1) FROM user_server ' . $where);
        $sql = "SELECT count(distinct user_id) FROM user_server {$where}";
        return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
    }

    public static function nbDistinctVisit($serverId = null, $from = null, $to = null,$type='date')
    {
        if($to==null || $to >= strtotime(date("Y-m-d H:00:00")))
        {
          $where="WHERE 1 ";
          if($serverId)
          {
            $where.=" AND server_id = {$serverId}";
          }
          if($from)
          {
            $where.=" AND time >= $from";
          }
          if($to)
          {
            $where.=" AND time < $to";
          }
          $sql="SELECT COUNT(DISTINCT(user_id)) FROM visit {$where}";
          return (int)Yii::app()->db->createCommand($sql)->queryScalar();
        }
        else
        {
          $where="WHERE 1 ";
          if($serverId)
          {
            $where.=" AND server_id = {$serverId}";
          }
          $where .= $from ? " AND `{$type}` >= {$from}" : '';
          $where .= $to ? " AND `{$type}` < {$to}" : '';
          $dependency=new CDbCacheDependency("SELECT count(1) FROM visit_stat {$where}");
          $sql="SELECT sum(uv_visit_times) FROM visit_stat {$where}";
           return (int)Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
        }
    }

    public static function nbRegisterVisit($serverId = null, $from = null, $to = null,$type='date')
    {
          $where="WHERE 1 ";
          if($serverId)
          {
            $where.=" AND server_id = {$serverId}";
          }
          $where .= $from ? " AND `{$type}` >= {$from}" : '';
          $where .= $to ? " AND `{$type}` < {$to}" : '';
          $dependency=new CDbCacheDependency("SELECT count(1) FROM visit_stat {$where}");
          $sql="SELECT sum(uv_register_visit) FROM visit_stat {$where}";
          return (int)Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
    }

    public static function lastGameServer($uid)
    {
        $sql = "SELECT game_id, server_id, max(`time`) as `time` FROM visit WHERE user_id = {$uid}";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        if(!$result)
        {
           $db='visit_'.substr(md5($uid),0,1);
           $sql="SELECT game_id,server_id FROM {$db} where user_id={$uid}";
           $result = Yii::app()->visitDump->createCommand($sql)->queryRow();
        }
        $game = $server = '';
        if($result['game_id'] && $result['server_id'])
        {
            $game = Yii::app()->db->createCommand("SELECT name FROM game WHERE id = {$result['game_id']}")->queryScalar();
            $server = Yii::app()->db->createCommand("SELECT name FROM server WHERE id = {$result['server_id']}")->queryScalar();
        }
        return ($game && $server) ? $game. $server : '未登录';
    }

    public static function lastVisitTime($uid)
    {
        $time = Yii::app()->db->createCommand("SELECT max(`time`) as `time` FROM visit WHERE user_id = {$uid}")->queryScalar();
        if(!$time)
        {
           $db='visit_'.substr(md5($uid),0,1);
           $sql="SELECT max(`time`) FROM {$db} where user_id={$uid}";
           $result = Yii::app()->visitDump->createCommand($sql)->queryScalar();
        }
        return $time ? date('Y-m-d H:i', $time) : '未登录';
    }

}
