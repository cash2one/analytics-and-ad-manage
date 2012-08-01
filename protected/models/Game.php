<?php
class Game extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'game';
    }

    public function rules()
    {
        return array(
                  array('name, unit, rate, enable, share, type_id', 'required')
                , array('enable, type_id, flag, isnew, version, join_vote, vote_base', 'numerical', 'integerOnly' => true)
                , array('rate,share, vote_rate', 'numerical')
                , array('name, logo', 'length', 'max' => 256)
                , array('unit, status', 'length', 'max' => 16)
                , array('portal, website, bbs, status_link, tutorial, guide, index', 'length', 'max' => 128)
                , array('portal_desc', 'length', 'max' => 512)
                , array('desc, payad, client', 'length', 'min' => 0)       
                , array('id, name, logo, unit, rate,share, portal, portal_desc, website, bbs, desc, enable, payad', 'safe', 'on' => 'search')
            );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'name' => '名称',
                'logo' => 'Logo',
                'unit' => '虚拟币',
                'share'=>'分成比例',
                'rate' => '交换比率',
                'portal' => '新手卡地址',
                'portal_desc' => '新手卡介绍',
                'website' => '官网',
                'bbs' => '社区',
                'desc' => '介绍',
                'enable' => '可用',
                'payad' => '充值右侧广告',
                'type_id' => '游戏类别',
                'status' => '状态',
                'status_link' => '链接',
                'flag' => '微端',
                'client' => '微端下载地址',
                'isnew' => '新游戏(热门游戏)',
                'tutorial' => '攻略',
                'guide' => '指南',
                'index' => '进入游戏',
                'version' => '版本号(整数)',
                'join_vote' => '是否在投票中显示',
                'vote_base' => '投票基数',
                'vote_rate' => '投票系数'
           );
    }

    public function search()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('logo', $this->logo, true);
        $criteria->compare('unit', $this->unit, true);
        $criteria->compare('rate', $this->rate);
        $criteria->compare('portal', $this->portal, true);
        $criteria->compare('website', $this->website, true);
        $criteria->compare('bbs', $this->bbs, true);
        //$criteria->compare('enable', 1);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 20,
                    )));
    }

    protected function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            if($this->isNewRecord)
            {
                $this->enable=1;
            }
        }
        return true;
    }

    protected function beforeDelete()
    {
        if(parent::beforeDelete())
        {
          $server=Server::model()->findAllByAttributes(array('game_id'=>$this->id));
          if($server)
          {
              foreach($server as $item)
              {
                  $item->delete();
              }
          }
          return true;
        }
    }

    protected function afterSave()
    {
        if($this->isNewRecord)
        {
           $model=new GameShare;
           $model->game_id=$this->id;
           $model->share=$this->share;
           $model->date=strtotime(date('Y-m-d'));
           $model->save();
        }
        else
        {
           $model=GameShare::model()->findByAttributes(array('game_id'=>$this->id,'date'=>strtotime(date('Y-m-d'))));
           if($model)
           {
             $model->share=$this->share;
           }
           else
           {
             $model=new GameShare;
             $model->game_id=$this->id;
             $model->share=$this->share;
             $model->date=strtotime(date('Y-m-d'));
           }
           $model->save();
        }
        parent::afterSave();
    }

     public function delete()
    {
        if(!$this->getIsNewRecord())
        {
            Yii::trace(get_class($this).'.delete()','system.db.ar.CActiveRecord');
            if($this->beforeDelete())
            {
                $result=$this->updateByPk($this->getPrimaryKey(),array('deleted'=>1,'enable'=>0))>0;
                $this->afterDelete();
                return $result;
            }
            else
                return false;
        }
        else
            throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
    }

    public static function dropDownData()
    {
        $db = Yii::app()->db;
        $dependency = new CDbCacheDependency('SELECT COUNT(id) FROM game WHERE enable=1 AND deleted=0');
        $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM game where enable=1 AND deleted=0");
        $data = array();
        $res = $req->queryAll();
        if($res)
        {
            foreach($res as $row)
            {
                $data[$row['id']] = $row['name'];
            }
        }
        return $data;
    }

    public static function getName($id)
    {
        $name = '--';
        if($id)
        {
            $db = Yii::app()->db;
            $req = $db->createCommand("SELECT name FROM game where id=:id LIMIT 1");
            $name = $req->queryScalar(array(':id' => $id));
        }

        return $name;
    }

    public static function getShare($gameId,$date=null)
    {
        $share=0;
        if($gameId)
        {
          $db=Yii::app()->db;
          if($date)
          {
              $where=" WHERE game_id={$gameId} and date<={$date}";
          }
          else
          {
              $where=" WHERE game_id={$gameId} ";
          }
          $share=$db->createCommand("SELECT share FROM game_share {$where} ORDER BY date DESC LIMIT 1")->queryScalar();
        }
        return $share;
    }

    public function combinedList($game,$from,$to)
    {
        $where = "WHERE enable=1 AND deleted=0 ";
        if(!empty($game))
        {
           $where .= " AND id IN ({$game})";
        }
        $dependency=new CDbCacheDependency('SELECT count(`id`) FROM game WHERE enable=1 AND deleted=0');
        $sql = "SELECT * FROM `game` {$where} ORDER BY id DESC";
        $rawData=Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
        $nowDate=strtotime(date('Y-m-d'));
        foreach($rawData as $k=>$item)
        {

          $register=UserServer::registerDistributeByGame($item['id']);
          $rawData[$k]['ad_register']=$register['ad_register'];
          $rawData[$k]['normal_register']=$register['platform_register'];
          $rawData[$k]['migrate_register']=$register['migrate_register'];
          $rawData[$k]['visit_user']=$rawData[$k]['ad_register']+$rawData[$k]['normal_register']+$rawData[$k]['migrate_register'];
          $rawData[$k]['revisit']=UserServer::revisitByGame($item['id']);
          $rawData[$k]['revisit_percent']=$rawData[$k]['visit_user']?round(100*$rawData[$k]['revisit']/$rawData[$k]['visit_user'],2)."%":"0%";
          $timeRegister=UserServer::nbUserByGame($item['id'],null,$to);
          $rawData[$k]['payment_user']=Order::nbUserPaidByGame($item['id'],$from,$to);
          $rawData[$k]['payment_percent']=$timeRegister?round(100*$rawData[$k]['payment_user']/$timeRegister,2)."%":"0%";
          $rawData[$k]['payment_amount']=Order::sumPaidByGame($item['id'],$from,$to);
          $rawData[$k]['arup']=$rawData[$k]['payment_user']?round($rawData[$k]['payment_amount']/$rawData[$k]['payment_user'],2):"0";
        }
        return new CArrayDataProvider($rawData,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'id','name','game_id','open_time'
                            )
                        )
                   ,'pagination'=>array(
                       'pageSize'=>20
                       )
                    ));
    }    
    
    public static function getVoteGame()
    {
        $count = Yii::app()->db->createCommand("SELECT COUNT(1) FROM game WHERE enable = 1 AND deleted = 0 AND join_vote = 1")->queryScalar();
        $sql = "SELECT * FROM game WHERE enable = 1 AND deleted = 0 AND join_vote = 1";
        return new CSqlDataProvider($sql,array(
                'totalItemCount'=>$count
                ,'sort'=>array(
                        'attributes'=>array(
                                'id'
                        ),
                        'defaultOrder'=>'id DESC'
                )
                ,'pagination'=>array(
                        'pageSize'=>20
                )
        ));
    }
    
    public static function getVoteGameList()
    {
        $db = Yii::app()->db;
        $dependency = new CDbCacheDependency('SELECT COUNT(id) FROM game WHERE enable = 1 AND deleted = 0 AND join_vote = 1');
        $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM game where enable = 1 AND deleted = 0 AND join_vote = 1");
        return $req->queryAll();
    }
    
    public static function dropVoteDownData()
    {
        $db = Yii::app()->db;
        $dependency = new CDbCacheDependency('SELECT COUNT(id) FROM game WHERE enable = 1 AND deleted = 0 AND join_vote = 1');
        $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM game where enable = 1 AND deleted = 0 AND join_vote = 1");
        $data = array();
        $res = $req->queryAll();
        if($res)
        {
            foreach($res as $row)
            {
                $data[$row['id']] = $row['name'];
            }
        }
        return $data;
    }
}
