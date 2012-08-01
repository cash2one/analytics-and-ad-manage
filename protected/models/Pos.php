<?php
class Pos extends CActiveRecord
{
    public static $TYPE = array(1 => 'jpg', 2 => 'gif', 3 => 'png', 4 => 'swf');
    public static $UPLOAD_TYPE = array(1 => '第三方上传', 2 => 'js上传', 3 => '附件上传');
    public static $COST_TYPE=array(1 => '点击数', 2 => '注册数', 3 => '充值分成',4 => '包月');
    public $adName,$gameId,$serverId;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pos';
    }

    public function rules()
    {
        return array(
            array('name, channel_id', 'required'),
            array('type, height, width, upload_type, cost_type', 'numerical', 'integerOnly' => true),
            array('cost', 'numerical'), array('name', 'length', 'max' => 256),
            array('key', 'length', 'max' => 16),
            array('channel_id, upload_limit', 'length', 'max' => 10),
            array('id, name, key, channel_id, type, height, width, upload_limit, upload_type, cost, cost_type', 'safe', 'on' => 'search')
        );
    }

    protected function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            if($this->isNewRecord)
            {
                $this->id = $this->key ? $this->key : null;
                $this->type = $this->type ? $this->type : 1;
                $this->width = $this->width ? $this->width : 0;
                $this->height = $this->height ? $this->height : 0;
                $this->upload_limit = $this->upload_limit ? $this->upload_limit : 0;
                $this->upload_type = $this->upload_type ? $this->upload_type : 1;
                $this->cost = $this->cost ? $this->cost : 0;
                $this->cost_type = $this->cost_type ? $this->cost_type : 1;
                $this->enable = 1;
                $this->deleted = 0;
            }
            return true;
        }
    }

    public function relations()
    {
        return array(
            'channel' => array(self::BELONGS_TO, 'Channel', 'channel_id'),
            'ad' => array(self::MANY_MANY, 'Ad', 'ad_pos(ad_id, pos_id)')
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '广告位名称',
            'key' => '广告位Id',
            'channel_id' => '渠道名称',
            'type' => '格式',
            'height' => '高',
            'width' => '宽',
            'upload_limit' => '大小限制',
            'upload_type' => '广告上传',
            'cost' => '广告位成本',
            'cost_type' => '成本结算方式'
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('id', $this->id);
        $criteria->compare('deleted',0);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('key', $this->key, true);
        $criteria->compare('channel_id', $this->channel_id);
        $criteria->compare('type', $this->type);
        $criteria->compare('height', $this->height);
        $criteria->compare('width', $this->width);
        $criteria->compare('upload_limit', $this->upload_limit, true);
        $criteria->compare('upload_type', $this->upload_type);
        $criteria->compare('cost', $this->cost);
        $criteria->compare('cost_type', $this->cost_type);

        return new CActiveDataProvider($this, array('criteria' => $criteria));
    }

      protected function beforeDelete()
    {
        if(parent::beforeDelete())
        {
          $adPos=AdPos::model()->findAllByAttributes(array('pos_id'=>$this->id,'active'=>1));
          if($adPos)
          {
              foreach($adPos as $item)
              {
                  $item->debind();
              }
          }
          return true;
        }
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

    public function combinedList()
    {
        $where = "WHERE t.deleted=0 ";
        if(!empty($this->id))
        {
           $where .= " AND t.id={$this->id}";
        }
        if(!empty($this->name))
        {
           $where .= " AND t.name like '{$this->name}%'";
        }
        if(!empty($this->channel_id))
        {
           $where .= " AND t.channel_id={$this->channel_id}";
        }
        if(!empty($this->gameId))
        {
           $where .= " AND m2.game_id={$this->gameId}";
        }
        if(!empty($this->serverId))
        {
           $where .= " AND m2.server_id={$this->serverId}";
        }
        if(!empty($this->adName))
        {
           $where .= " AND m2.name like '{$this->adName}%'";
        }
        if(!empty($this->type))
        {
           $where .= " AND t.type={$this->type}";
        }
        if(!empty($this->cost_type))
        {
           $where .= " AND t.cost_type={$this->cost_type}";
        }

        $dependency = new CDbCacheDependency('SELECT COUNT(`id`) FROM pos WHERE enable = 1');

        $count = Yii::app()->db->cache(1000,$dependency,2)->createCommand("SELECT COUNT(*) FROM `pos` as t
              LEFT JOIN `ad_pos` as m1 ON m1.pos_id = t.id AND m1.active = 1
              LEFT JOIN `ad` as m2 ON m2.id = m1.ad_id
              {$where}")->queryScalar();
        $sql = "SELECT t.*, m2.id as ad_id, m2.name as ad_name,m2.game_id,m2.server_id FROM `pos` AS t
              LEFT JOIN `ad_pos` as m1 ON m1.pos_id = t.id AND m1.active = 1
              LEFT JOIN `ad` as m2 ON m2.id = m1.ad_id {$where}";
        return new CSqlDataProvider($sql,array(
                    'totalItemCount'=>$count
                   ,'sort'=>array(
                        'attributes'=>array(
                            'id','name','channel_id','cost','cost_type'
                        ),
                        'defaultOrder'=>'id DESC'
                    )
                   ,'pagination'=>array(
                       'pageSize'=>20
                       )
                    ));
    }

    public static function getKey($pos_id)
    {
        $key = Yii::app()->db->createCommand("SELECT `key` FROM pos WHERE id = {$pos_id}")->queryScalar();
        return $key ? $key : $pos_id;
    }

    public static function getName($pos_id)
    {
         $name = intval($pos_id) ? Yii::app()->db->createCommand("SELECT name FROM pos WHERE id = {$pos_id}")->queryScalar() : '--';
         return $name;
    }

    public static function lastdayClick($pos_id)
    {
         $from=strtotime(date('Y-m-d 00:00'))-86400;
         $to=strtotime(date('Y-m-d 00:00'));
         return Click::countClickByPos($pos_id,$from,$to);
    }


    public static function maxClick($pos_id)
    {
        return Yii::app()->db->createCommand("SELECT max(click_times) FROM ad_pos WHERE pos_id = {$pos_id}")->queryScalar();
    }
}
