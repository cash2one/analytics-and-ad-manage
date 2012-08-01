<?php
class Order extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'order';
    }

    public function rules()
    {
        return array(
            array('user_id, game_id, server_id, total, paid, ideal_money, status', 'required'),
            array('status, create_time, update_time', 'numerical', 'integerOnly'=>true),
            array('total, paid, ideal_money', 'numerical'),
            array('user_id, game_id, server_id', 'length', 'max'=>10),
            array('id, user_id, game_id, server_id, total, paid, ideal_money, status, create_time, update_time', 'safe', 'on'=>'search'),
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
            'id' => 'ID',
            'user_id' => 'User',
            'game_id' => 'Game',
            'server_id' => 'Server',
            'total' => 'Total',
            'paid' => 'Paid',
            'ideal_money' => 'Ideal Money',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        );
    }

    public function search()
    {

        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('game_id',$this->game_id,true);
        $criteria->compare('server_id',$this->server_id,true);
        $criteria->compare('total',$this->total);
        $criteria->compare('paid',$this->paid);
        $criteria->compare('ideal_money',$this->ideal_money);
        $criteria->compare('status',$this->status);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('update_time',$this->update_time);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    static public function sumPaid($serverId=null,$from=null,$to=null)
    {
        $where='where status=1 ';
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        return (float)Yii::app()->db->createCommand("SELECT SUM(paid) FROM `order` {$where}")->queryScalar();
    }

    static public function sumTax($serverId=null,$from=null,$to=null)
    {
        $where='where status=1 ';
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        return (float)Yii::app()->db->createCommand("SELECT SUM(payment_tax) FROM `order` {$where}")->queryScalar();
    }

    static public function sumIdeal($serverId,$from=null,$to=null)
    {
        $where="where status=1 AND server_id={$serverId}";
        if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        return (float)Yii::app()->db->createCommand("SELECT SUM(ideal_money) FROM `order` {$where}")->queryScalar();
    }

    static public function sumProfit($serverId=null,$from=null,$to=null)
    {
        $where='where status=1 ';
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        return (float)Yii::app()->db->createCommand("SELECT SUM(profit) FROM `order` {$where}")->queryScalar();
    }

    static public function sumPaidByWeek($serverId,$from,$to)
    {
        $where="where status=1 AND server_id={$serverId}";
        $from=strtotime(date("Y-m-d",$from));
        $from=$from+604800*($to-1);
        $to=$from+604800;
        $where.=" AND update_time>=$from and update_time<$to";
        return (float)Yii::app()->db->createCommand("SELECT SUM(paid) FROM `order` {$where}")->queryScalar();
    }

    static public function sumPaidByGame($gameId=null,$from=null,$to=null)
    {
        $where='where status=1 ';
        if($gameId)
        {
          $where.=" AND game_id={$gameId}";
        }
        if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        return (float)Yii::app()->db->createCommand("SELECT SUM(paid) FROM `order` {$where}")->queryScalar();
    }

     static public function nbPaid($serverId=null,$from=null,$to=null)
    {
        $where='where status=1 ';
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        return (float)Yii::app()->db->createCommand("SELECT COUNT(paid) FROM `order` {$where}")->queryScalar();
    }

    static public function nbUserPaid($serverId,$from=null,$to=null)
    {
        $where='where status=1 ';
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
         if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        $sql="SELECT  COUNT(DISTINCT user_id) FROM `order` {$where}";
        return (float)Yii::app()->db->createCommand($sql)->queryScalar();
    }

    static public function nbUserPaidByGame($gameId,$from=null,$to=null)
    {
        $where='where status=1 ';
        if($gameId)
        {
          $where.=" AND game_id={$gameId}";
        }
         if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        $sql="SELECT  COUNT(DISTINCT user_id) FROM `order` {$where}";
        return (float)Yii::app()->db->createCommand($sql)->queryScalar();
    }

    static public function nbUserPaidByPos($posId,$from=null,$to=null)
    {
        $where='where status=1 ';
        if($posId)
        {
          $where.=" AND pos_id={$posId}";
        }
         if($from)
        {
          $where.=" AND update_time>=$from";
        }
        if($to)
        {
          $where.=" AND update_time<$to";
        }
        $sql="SELECT  COUNT(DISTINCT user_id) FROM `order` {$where}";
        return (float)Yii::app()->db->createCommand($sql)->queryScalar();
    }

    static public function nbRepaidUser($serverId,$from,$to)
    {
        return Yii::app()->db->createCommand("SELECT count(distinct user_id) FROM `order` where 
                update_time<{$from} and server_id={$serverId} and user_id IN (SELECT distinct user_id 
                    FROM `order` where update_time>={$from} and update_time<{$to} and server_id={$serverId})")->queryScalar();
    }

    static public function avgUserPaid($serverId,$from=null,$to=null)
    {
        $nbUser=self::nbUserPaid($serverId,$from,$to);
        $sumPaid=self::sumPaid($serverId,$from,$to);
        return (float) ($nbUser?$sumPaid/$nbUser:0);
    }

    /**
     *
     * 根据广告位统计充值
     * @param Integer $pos_id
     * @param Integer $from 统计的起始时间
     * @param Integer $to 统计的结束时间
     */
    public static function countPayByPos($pos_id, $from = null, $to = null)
    {
        $where = "where pos_id={$pos_id} ";
        $where .= $from ? " AND o.update_time >= {$from}" : '';
        $where .= $to ? " AND o.update_time <= {$to}": '';
        $sql = "SELECT SUM(paid)
                FROM `order` o
                {$where} AND o.status = 1";
        $paid = Yii::app()->db->createCommand($sql)->queryScalar();
        return $paid ? $paid : 0;
    }

    /**
     *
     * 根据ad_pos_id统计充值
     * @param Integer $ad_pos_id
     * @param Integer $from 用户注册的起始时间
     * @param Integer $to 用户注册的结束时间
     */
    public static function countPayByAdPos($ad_pos_id, $from = null, $to = null)
    {
        $dependency = new CDbCacheDependency('SELECT count(id) FROM `order`');
        $where = "WHERE ad_pos_id = {$ad_pos_id} AND status=1 ";
        $where .= $from ? " AND register_time >= {$from}" : '';
        $where .= $to ? " AND register_time <= {$to}" : '';
        $sql = "SELECT SUM(paid)
                FROM `order`
                {$where}";
       $paid = Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
       return $paid ? $paid : 0;
    }

    public static function countPayByChannel($channel_id, $from = null, $to = null)
    {
        $dependency = new CDbCacheDependency('SELECT count(id) FROM `order`');
        $where = "WHERE channel_id = {$channel_id} AND status=1 ";
        $where .= $from ? " AND register_time >= {$from}" : '';
        $where .= $to ? " AND register_time <= {$to}" : '';
        $sql = "SELECT SUM(paid)
                FROM `order`
                {$where}";
       $paid = Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
       return $paid ? $paid : 0;
    }

    public static function countPayUserByChannel($channel_id, $from = null, $to = null)
    {
        $dependency = new CDbCacheDependency('SELECT count(id) FROM `order`');
        $where = "WHERE channel_id = {$channel_id} AND status=1 ";
        $where .= $from ? " AND register_time >= {$from}" : '';
        $where .= $to ? " AND register_time <= {$to}" : '';
        $sql = "SELECT count(distinct user_id)
                FROM `order`
                {$where}";
       $paid = Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
       return $paid ? $paid : 0;
    }

    public static function getTimePeriodByChannel($channelId)
    {
        $dependency = new CDbCacheDependency('SELECT count(id) FROM `order`');
        $where = "WHERE channel_id ={$channelId} AND status=1  AND register_time>0 ";
        $fromSql = "SELECT register_time FROM `order` {$where} ORDER BY register_time ASC LIMIT 1";
        $toSql = "SELECT register_time FROM `order` {$where} ORDER BY register_time DESC LIMIT 1";
        $from=Yii::app()->db->cache(1000,$dependency)->createCommand($fromSql)->queryScalar();
        $to=Yii::app()->db->cache(1000,$dependency)->createCommand($toSql)->queryScalar();
        if($from)
        {
          return array('from'=>$from,'to'=>$to);
        }
        else
        {
          return false;
        }
    }

    public static function getServerDistributeByChannel($channelId,$from,$to)
    {
      $dependency = new CDbCacheDependency('SELECT count(id) FROM `order`');
      $where = "WHERE channel_id ={$channelId} AND status=1 ";
      if($from)
      {
          $where.=" AND register_time>={$from}";
      }
      if($to)
      {
          $where.=" AND register_time<={$to}";
      }
      $sql="SELECT id,server_id,server_name,game_id,game_name,sum(paid) as sum FROM `order` {$where} GROUP BY server_id ORDER BY game_id,server_id";
      return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
    }

    public static function nbUserOrder($userId,$gameId=null,$serverId=null)
    {
        $where="where status=1 and user_id={$userId}";
        if($gameId)
        {
          $where.=" AND game_id={$gameId}";
        }
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        $sql="SELECT  COUNT(id) FROM `order` {$where}";
        return (int)Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public static function sumUserOrder($userId,$gameId=null,$serverId=null)
    {
        $where="where status=1 and user_id={$userId}";
        if($gameId)
        {
          $where.=" AND game_id={$gameId}";
        }
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        $sql="SELECT  sum(paid) FROM `order` {$where}";
        return (float)Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public static function avgUserOrder($userId,$gameId=null,$serverId=null)
    {
        $where="where status=1 and user_id={$userId}";
        if($gameId)
        {
          $where.=" AND game_id={$gameId}";
        }
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        $sql="SELECT  avg(paid) FROM `order` {$where}";
        return (float)Yii::app()->db->createCommand($sql)->queryScalar();
    }
}

