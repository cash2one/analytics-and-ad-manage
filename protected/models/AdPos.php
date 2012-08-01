<?php
class AdPos extends CActiveRecord
{
    public $posName,$posId, $adName, $gameId, $serverId;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'ad_pos';
    }

    public function rules()
    {
        return array(
            array('ad_id, pos_id, channel_id, bind_time', 'required'),
            array('bind_time, debind_time, active', 'numerical', 'integerOnly'=>true),
            array('ad_id, pos_id, channel_id, click_times, register_times', 'length', 'max'=>10),
            array('ad_id, pos_id, channel_id, click_times, register_times, bind_time, debind_time, active', 'safe', 'on'=>'search'),
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
            'ad_id' => '广告',
            'pos_id' => '广告位',
            'channel_id' => '渠道',
            'click_times' => '点击次数',
            'register_times' => '注册用户',
            'bind_time' => '绑定时间',
            'debind_time' => '解绑时间',
            'active' => '激活',
        );
    }

    public function search()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('ad_id',$this->ad_id);
        $criteria->compare('pos_id',$this->pos_id);
        $criteria->compare('channel_id',$this->channel_id);
        $criteria->compare('click_times',$this->click_times);
        $criteria->compare('register_times',$this->register_times);
        $criteria->compare('active',$this->active);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function debind()
    {
        if(!$this->getIsNewRecord())
        {
            $this->updateByPk($this->getPrimaryKey(),array('active'=>0,'debind_time'=>time()));
        }
        else
          throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
    }

    public function combinedList($from,$to)
    {
        $where = 'WHERE 1';
        if(!empty($this->id))
        {
            $where .= " AND ap.id = {$this->id}";
        }
        if(!empty($this->channel_id))
        {
            $where .= " AND ap.channel_id = {$this->channel_id}";
        }
        if(!empty($this->posName))
        {
            $where .= " AND p.name LIKE '{$this->posName}%'";
        }
        if(!empty($this->posId))
        {
            $where .= " AND p.id = '{$this->posId}'";
        }
        if(!empty($this->gameId))
        {
            $where .= " AND a.game_id = {$this->gameId}";
        }
        if(!empty($this->serverId))
        {
            $where .= " AND a.server_id = {$this->serverId}";
        } else {
            $where .= " AND a.server_id IS NOT NULL";
        }
        if(!empty($this->adName))
        {
            $where .= " AND a.name LIKE '{$this->adName}%'";
        }
        $where.=' AND (ap.click_times>0 or ap.register_times>0) ';
        $condition=$where ." AND ap.bind_time<{$to} AND (ap.debind_time=0 OR ap.debind_time>{$from})";
        /*
        $count = Yii::app()->db->createCommand("SELECT COUNT(*) FROM `pos` as p
              LEFT JOIN `ad_pos` ap ON ap.pos_id = p.id
              LEFT JOIN `ad` a ON a.id = ap.ad_id
              {$condition}")->queryScalar(); */
        $sql = "SELECT p.id as id, p.name as pos_name, p.`key`, p.channel_id, a.name as ad_name, a.game_id, a.server_id, ap.id as ad_pos_id, ap.click_times, ap.register_times
                FROM pos p
                LEFT JOIN ad_pos ap ON p.id = ap.pos_id
                LEFT JOIN ad a ON a.id = ap.ad_id {$condition}";
        $data=Yii::app()->db->createCommand($sql)->queryAll();
        Yii::trace($sql,"调试");
        foreach($data as $k=>$item)
        {
            $clickTimes=Click::countClickByAdPos($item["ad_pos_id"],$from,$to);
            $regTimes=User::countRegisterByAdPos($item["ad_pos_id"],$from,$to);
            if($clickTimes==0 && $regTimes == 0)
            {
              unset($data[$k]);
              continue;
            }
            $data[$k]['click_times']=$clickTimes;
        }
        
        return new CArrayDataProvider($data, array(
                    'sort' => array(
                        'attributes' => array('id'),
                         'defaultOrder'=>'id DESC'
                    ),
                    'pagination'=>array(
                       'pageSize'=>20
                       )
                    ));
        /*
        return new CSqlDataProvider($sql, array(
                    'totalItemCount' => $count,
                    'sort' => array(
                        'attributes' => array(
                            'p.id'),
                         'defaultOrder'=>'p.id DESC'
                    ),
                    'pagination'=>array(
                       'pageSize'=>20
                       )
                    ));
                    */
    }

    public  function statHourAnalytics($date=null)
      {
        if($date==null)
        {
           $date=date('Y-m-d');
        }
        $fromTime=strtotime($date);
        $toTime=strtotime($date)+86400;
        $maxHour=strtotime(date('Y-m-d H:00:00'))+3600;
        if($toTime>$maxHour)
        {
            $toTime=$maxHour;
        }
        $data=array();
        for($i=$toTime;$i>$fromTime;$i-=3600)
        {
          $from=$i-3600;
          $to=$i;
            $data[]=array(
                     'hourFrom'=>date("Y-m-d H:00",$from)
                    ,'hour'=>'-' .date('H:00',$to).'合计'
                    ,'from'=>$from
                    ,'to'=>$to
                    ,'id'=>0
                    );
        }
        return new CArrayDataProvider($data,array(
               'sort'=>array(
                   'attributes'=>array(
                       'hour'
                       )
                   )
                   ,'pagination'=>array(
                       'pageSize'=>24
                       )
        ));
      }
    public function hourDetail($from,$to)
      {
        $where = "WHERE 1";
        $realTime=true;
        if(!empty($this->channel_id))
        {
            $where .= " AND t.channel_id ={$this->channel_id}";
        }
        if(!empty($this->gameId))
        {
           $where .= " AND m1.game_id={$this->gameId}";
        }
        if(!empty($this->serverId))
        {
            $where .= " AND m1.server_id = {$this->serverId}";
        }
        if(!empty($this->ad_id))
        {
            $where .= " AND t.ad_id ={$this->ad_id}";
        }
        if(!empty($this->pos_id))
        {
            $where .= " AND t.pos_id ={$this->pos_id}";
        }
        if($to<strtotime(date('Y-m-d H:00')))
        {
          $condition=$where ." AND t.id IN(SELECT ad_pos_id from click_stat where ad_pos_id>34 and hour='{$from}')";
          $dependency=new CDbCacheDependency("SELECT ad_pos_id from click_stat where ad_pos_id>34 and hour='{$from}'");
        }
        else
        {
          $condition=$where ." AND t.bind_time<{$to} AND (t.debind_time=0 OR t.debind_time>{$from})";
          $dependency=new CDbCacheDependency("SELECT count(`id`) FROM ad_pos where
                  bind_time<{$to} AND (debind_time=0 OR debind_time>{$from})");
        }
        $sql = "SELECT t.*,m1.name as ad_name,m1.server_id,m1.game_id FROM `ad_pos` AS t
               LEFT JOIN `ad` as m1 ON m1.id = t.ad_id {$condition}";
        $data=Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
        $res=array();
        if($data)
        {
            foreach($data as $item)
            {
              $clickTimes=Click::countClickByAdPos($item["id"], $from,$to,"hour");
              if($clickTimes==0)
              {
                 continue;
              }
              $registerTimes=User::countRegisterByAdPos($item["id"],$from,$to,"hour");
              $pv=$registerTimes?sprintf("%01.2f",$registerTimes/$clickTimes*100).'%':0;
              $yFrom=$from-86400;
              $yTo=$to-86400;
              $ydayClickTimes=Click::countClickByAdPos($item["id"],$yFrom,$yTo,"hour");
              $ydayRegisterTimes=User::countRegisterByAdPos($item["id"],$yFrom,$yTo,"hour");
              $ydayPv=$ydayRegisterTimes?sprintf("%01.2f",$ydayRegisterTimes/$ydayClickTimes*100).'%':0;
              $res[]=array(
                      'id'=>$item['id'],
                      'pos_id'=>$item['pos_id'],
                      'channel_id'=>$item['channel_id'],
                      'ad_id'=>$item['ad_id'],
                      'ad_name'=>$item['ad_name'],
                      'game_id'=>$item['game_id'],
                      'server_id'=>$item['server_id'],
                      'click_times'=>$clickTimes,
                      'register_times'=>$registerTimes,
                      'pv'=>$pv,
                      'y_click_times'=>$ydayClickTimes,
                      'y_register_times'=>$ydayRegisterTimes,
                      'y_pv'=>$ydayPv,
                      );
            }
        }
        return new CArrayDataProvider($res,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'channel_id','ad_id','pos_id',
                            ),
                        'defaultOrder'=>array(
                            'channel_id'=>true
                            )
                        )
                   ,'pagination'=>array(
                       'pageSize'=>50
                       )
       ));
      }

    public function dailyList($date=null,$page=1)
      {
          $where = "WHERE 1";
          if(!empty($this->channel_id))
          {
              $where .= " AND t.channel_id ={$this->channel_id}";
          }
          if(!empty($this->gameId))
          {
             $where .= " AND m1.game_id={$this->gameId}";
          }
          if(!empty($this->serverId))
          {
              $where .= " AND m1.server_id = {$this->serverId}";
          }
          if(!empty($this->ad_id))
          {
              $where .= " AND t.ad_id ={$this->ad_id}";
          }
          if(!empty($this->pos_id))
          {
              $where .= " AND t.pos_id ={$this->pos_id}";
          }
          if($date==null)
          {
              $date=date('Y-m-d');
          }
          $fromTime=strtotime($date);
          $toTime=strtotime($date)+86400;
          $maxHour=strtotime(date('Y-m-d H:00:00'))+3600;
          $nowHour=strtotime(date('Y-m-d H:00'));
          if($toTime>$maxHour)
          {
              $toTime=$maxHour;
          }
          $res=array();
          for($i=$toTime;$i>$fromTime;$i-=3600)
          {
              $from=$i-3600;
              $to=$i;
              if($to<$nowHour)
              {
                  $condition=$where." AND EXISTS(select id from `click_stat` where ad_pos_id=t.id AND ad_pos_id>34 and hour='{$from}' LIMIT 1) ";
                  $dependency=new CDbCacheDependency("SELECT ad_pos_id from click_stat where ad_pos_id>34 and hour='{$from}'");
              }
              else
              {
                  $condition=$where ." AND t.bind_time<{$to} AND (t.debind_time=0 OR t.debind_time>{$from})";
                  $condition.=" AND EXISTS(select id from `click` where ad_pos_id=t.id and time>={$from} and time<{$to} LIMIT 1) ";
                  $dependency=new CDbCacheDependency("SELECT count(`id`) FROM ad_pos where
                          bind_time<{$to} AND (debind_time=0 OR debind_time>{$from})");
              }
              $sql = "SELECT t.*,m1.name as ad_name,m1.server_id,m1.game_id FROM `ad_pos` AS t
                LEFT JOIN `ad` as m1 ON m1.id = t.ad_id {$condition}";
              $data=Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
              if($data)
              {
                  foreach($data as $item)
                  {
                      $res[]=array(
                            'hourFrom'=>date("Y-m-d H:00",$from),
                            'hour'=>'-' .date('H:00',$to),
                            'id'=>$item['id'],
                            'pos_id'=>$item['pos_id'],
                            'channel_id'=>$item['channel_id'],
                            'ad_id'=>$item['ad_id'],
                            'ad_name'=>$item['ad_name'],
                            'game_id'=>$item['game_id'],
                            'server_id'=>$item['server_id'],
                            'from'=>$from,
                            'to'=>$to,
                    );
                  }
              }
          }
          return new CArrayDataProvider($res,array(
                      'sort'=>array(
                          'attributes'=>array(
                              'channel_id','ad_id','pos_id'
                              ),
                          'defaultOrder'=>array(
                              'pos_id'=>true
                              )
                          )
                      ,'pagination'=>array(
                          'pageSize'=>50
                          )
                      ));
      }

    public static function getAdPosByRange($from,$to,$channel,$serverId=null,$gameId=null)
    {
      $where="WHERE ((t.debind_time=0 and {$to}>=t.bind_time) OR (t.debind_time>0 and t.bind_time>={$from} and t.bind_time<={$to})) AND t.channel_id={$channel}";
      if($serverId)
      {
         $where.=" AND server_id={$serverId} ";
      }
      if($gameId)
      {
         $where.=" AND game_id={$gameId} ";
      }
      $sql=" select t.id FROM ad_pos as t LEFT JOIN ad ON t.ad_id=ad.id {$where} ";
      return Yii::app()->db->createCommand($sql)->queryColumn();
    }

    public  static function getPosByRange($from,$to,$channel)
      {
        $where="WHERE t.bind_time<{$to} AND (t.debind_time=0 OR t.debind_time>{$from}) AND t.channel_id={$channel}";
        $sql=" select t.pos_id FROM ad_pos as t {$where} ";
        return Yii::app()->db->createCommand($sql)->queryColumn();
      }

    public static  function getSndaAdpos()
    {
        $db=Yii::app()->db;
        $sql="SELECT ad_pos_id FROM user where u_type=1 and ad_pos_id>0 group by ad_pos_id";
        $adPosIds=$db->createCommand($sql)->queryColumn();
        $data=array();
        if($adPosIds)
        {
          $in=implode(',',$adPosIds);
          $sql="SELECT ad_pos.*,ad.name as ad_name,ad.server_id,ad.game_id FROM ad_pos,ad where ad_pos.id IN({$in}) and ad.id=ad_pos.ad_id ";
          $data=$db->createCommand($sql)->queryAll();
          if($data)
          {
             foreach($data as $k=>$item)
             {
               $register=User::countRegisterByAdPos($item['id']);
               $role=User::countRoleCreateByAdPos($item['id'],1);
               $data[$k]['click']=Click::countUIByAdPos($item['id']);
               $data[$k]['register']=$register;
               $data[$k]['role']=$role;
               $data[$k]['role_percent']=$register?round($role*100/$register,2).'%':'0%';
               $data[$k]['game_name']=Game::getName($item['game_id']);
               $data[$k]['server_name']=Server::getName($item['server_id']);
             }
          }
        }
        return new CArrayDataProvider($data,array(
                      'sort'=>array(
                          'attributes'=>array(
                              'id','pos_id','server_id','game_id'
                              ),
                          'defaultOrder'=>array(
                              'pos_id'=>true
                              )
                          )
                      ,'pagination'=>array(
                          'pageSize'=>50
                          )
                      )); 
    }
}
