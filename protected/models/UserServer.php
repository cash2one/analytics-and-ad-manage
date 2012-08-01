<?php
class UserServer extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'user_server';
    }

    public function rules()
    {
        return array(
            array('user_id, login_times, ad_pos_id, server_id, orgi_server, pre_server, game_id, orgi_game, pre_game, pos_id, lively_time, register_time, create_time', 'required'),
            array('lively_time, register_time, create_time', 'numerical', 'integerOnly'=>true),
            array('user_id, login_times, ad_pos_id, server_id, orgi_server, pre_server, game_id, orgi_game, pre_game, pos_id', 'length', 'max'=>10),
            array('id, user_id, login_times, ad_pos_id, server_id, orgi_server, pre_server, game_id, orgi_game, pre_game, pos_id, lively_time, register_time, create_time', 'safe', 'on'=>'search'),
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
            'login_times' => 'Login Times',
            'ad_pos_id' => 'Ad Pos',
            'server_id' => 'Server',
            'orgi_server' => 'Orgi Server',
            'pre_server' => 'Pre Server',
            'game_id' => 'Game',
            'orgi_game' => 'Orgi Game',
            'pre_game' => 'Pre Game',
            'pos_id' => 'Pos',
            'lively_time' => 'Lively Time',
            'register_time' => 'Register Time',
            'create_time' => 'Create Time',
        );
    }

    static public function registerDistributeByServer($serverId,$from=null,$to=null)
    {
       $where  =" WHERE server_id={$serverId} ";
       $where .= $from ? " AND `date` >= {$from}" : '';
       $where .= $to ? " AND `date` <= {$to}" : '';
       $sql = "SELECT sum(ad_register) as ad_register,sum(platform_register) as platform_register,sum(migrate_register) as migrate_register ,sum(chg_svr_register) as chg_svr_register
           FROM server_data {$where}";
       return Yii::app()->db->createCommand($sql)->queryRow();
    }

    static public function nbUserByServer($serverId,$from,$to)
    {
        $where="where server_id={$serverId}";
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

    static public function revisitByServer($serverId,$from=null,$to=null,$type=0)
    {
        if($type == 0)
            $where =" WHERE server_id={$serverId} and login_times>1";
        else if($type == 1)
            $where =" WHERE server_id={$serverId} and orgi_server={$serverId} and login_times>1";
        else if($type == 2)
            $where =" WHERE server_id={$serverId} and orgi_server<>{$serverId} and login_times>1";
        $where .= $from ? " AND `create_time` >= {$from}" : '';
        $where .= $to ? " AND `create_time` <= {$to}" : '';
        $sql = "SELECT COUNT(1) FROM user_server {$where}";
        return Yii::app()->db->cache(1000)->createCommand($sql)->queryScalar();
    }

    static public function nbUserByGame($gameId,$from,$to)
    {
        $where="where game_id={$gameId}";
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

     static public function registerDistributeByGame($gameId,$from=null,$to=null)
    {
       $where  =" WHERE game_id={$gameId} ";
       $where .= $from ? " AND `date` >= {$from}" : '';
       $where .= $to ? " AND `date` <= {$to}" : '';
       $sql = "SELECT sum(ad_register) as ad_register,sum(platform_register) as platform_register,sum(migrate_register) as migrate_register 
           FROM server_data {$where}";
       return Yii::app()->db->createCommand($sql)->queryRow();
    }

    static public function revisitByGame($gameId,$from=null,$to=null)
    {
       $where =" WHERE game_id={$gameId} and login_times>1";
       $where .= $from ? " AND `create_time` >= {$from}" : '';
       $where .= $to ? " AND `create_time` <= {$to}" : '';
       $sql = "SELECT COUNT(1) FROM user_server {$where}";
       return Yii::app()->db->createCommand($sql)->queryScalar();
    }

    static public function nbVip($serverId,$range)
    {
       $where =" WHERE server_id={$serverId} AND `range`={$range}";
       $sql = "SELECT COUNT(1) FROM vip_list {$where}";
       return Yii::app()->db->createCommand($sql)->queryScalar();
    }

    static public function nbLoginTimes($userId,$serverId)
    {
        $times=0;
        $model=self::model()->findByAttributes(array('user_id'=>$userId,'server_id'=>$serverId));
        if($model)
        {
            $times=$model->login_times;
        }
        return $times;
    }

    static public function userList($serverId,$channelId,$from,$to)
    {
        $to+=86399;
        $db=Yii::app()->db;
        $poses=$db->createCommand("SELECT id FROM `pos` WHERE channel_id={$channelId}")->queryColumn();
        $data=array();
        $count=0;
        if($poses)
        {
          $inSql=implode(',',$poses);
          $where='where t.user_id=m1.id ';
          $where.=" AND t.pos_id IN ($inSql) and t.register_time>={$from} and t.register_time<={$to}";
          $where.=" AND t.server_id=$serverId";
          $sql="select t.login_times,m1.user_name FROM `user_server` as t,`user` as m1 {$where}";
          $count=$db->createCommand("select count(t.id) FROM `user_server` as t,`user` as m1 {$where}")->queryScalar();
        }
        return new CSqlDataProvider($sql, array(
                   'totalItemCount' => $count,
                   'pagination'=>false,
                   ));

    }

    static public function revisitByChannel($channelId,$from=null,$to=null)
    {
       $poses=Yii::app()->db->createCommand("SELECT id FROM pos where channel_id={$channelId}")->queryColumn();
       $out=0;
       if($poses)
       {
         $posIn=implode(',',$poses);
         $where =" WHERE pos_id IN ($posIn) and login_times>1";
         $where .= $from ? " AND `register_time` >= {$from}" : '';
         $where .= $to ? " AND `register_time` <= {$to}" : '';
         $sql = "SELECT COUNT(distinct user_id) FROM user_server {$where}";
         $out=Yii::app()->db->createCommand($sql)->queryScalar();
       }
       return $out;
    }

    static public function revisitByMaterial($material,$from,$to,$begin,$end)
    {
      $adPoses=Yii::app()->db->createCommand("SELECT ad_pos_id FROM material_data where date>={$from} and date<={$to} 
              and material_id='{$material}'")->queryColumn();
      $out=0;
      if($adPoses)
      {
         $adPosIn=implode(',',$adPoses);
         $where =" WHERE ad_pos_id IN ($adPosIn) and login_times>1";
         $where .= $from ? " AND `register_time` >= {$from}" : '';
         $where .= $to ? " AND `register_time` <= {$to}" : '';
         $where .= $begin ? " AND `lively_time` >= {$begin}" : '';
         $where .= $end ? " AND `lively_time` <= {$end}" : '';
         $sql = "SELECT COUNT(distinct user_id) FROM user_server {$where}";
         $out=Yii::app()->db->createCommand($sql)->queryScalar();
      }
      return $out;
    }
}
