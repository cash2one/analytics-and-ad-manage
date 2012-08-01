<?php
class Ad extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'ad';
    }

    public function rules()
    {
        return array(
                array('name, path, game_id, server_id,admin_id', 'required'),
                array('deleted,type, create_time,admin_id', 'numerical', 'integerOnly' => true),
                array('name', 'length', 'max' => 64), array('path', 'length', 'max' => 256),
                array('game_id, server_id,admin_id', 'length', 'max' => 10),
                array('id, name,admin_id, type, path, game_id, server_id, create_time', 'safe', 'on' => 'search')
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
                'name' => '广告名称',
                'type' => '广告类型',
                'path' => '素材地址',
                'game_id' => '游戏',
                'server_id' => '区服',
                'admin_id'=>'上传者',
                'create_time' => '创建时间'
                );
    }

    protected function beforeValidate()
    {
        if($this->isNewRecord)
        {
            $this->create_time = time();
        }
        return parent::beforeValidate();
    }

    public function search()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('id', $this->id, true);
        $criteria->compare('deleted',0);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('path', $this->path, true);
        $criteria->compare('game_id', $this->game_id);
        $criteria->compare('server_id', $this->server_id);
        $criteria->compare('admin_id', $this->admin_id);
        $criteria->order = 'create_time DESC';

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 20,
                    )));
    }

    public function conversionRate()
    {
        $rate=0;
        if($this->id)
        {
          $db=Yii::app()->db;
          $adPos=$db->createCommand("SELECT id FROM ad_pos where ad_id={$this->id}")->queryColumn();
          if($adPos)
          {
              $registerTimes=$clickTimes=0;
              foreach($adPos as $id)
              {
                $registerTimes+=User::countRegisterByAdPos($id);
                $clickTimes+=Click::countClickByAdPos($id);
              }
              if($clickTimes!=0)
              {
                $rate=$registerTimes/$clickTimes;
              }
              else
              {
                $rate=0;
              }
          }
        }
        return $rate;
    }

    protected function beforeDelete()
    {
        if(parent::beforeDelete())
        {
          $adPos=AdPos::model()->findAllByAttributes(array('ad_id'=>$this->id,'active'=>1));
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
                $result=$this->updateByPk($this->getPrimaryKey(),array('deleted'=>1))>0;
                $this->afterDelete();
                return $result;
            }
            else
                return false;
        }
        else
            throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
    }

    public static function listContent()
    {
        $dir = Yii::app()->params['adContentPath'];
        chdir($dir);
        foreach(glob('*', GLOB_ONLYDIR) as $file)
        {
            $data[$file] = $file;
        }
        return $data;
    }

    public static function listContentDataProvider($keyword=null)
    {
        $dir = Yii::app()->params['adContentPath'];
        $data=array();
        $list=self::listContent();
        $material = Material::getMaterialName();
        $index=1;
        foreach($list as $item)
        {
            if($keyword)
            {
                $regStr=str_replace('*','[^\s]+',$keyword);
            }
            if($keyword && preg_match("/^{$regStr}/",$item))
            {
              $name='';
              $file=$dir.'/'.$item.'/name.txt';
              if(file_exists($file))
              {
                $fp=fopen($file,'r');
                if($fp)
                {
                 $name = fread($fp, filesize($file));
                 fclose($fp);
                 $fp=null;
                }
              }
              preg_match('~^[a-z]+(\d+)([a-z])?_\d+$~i', $item, $match);
              $m_name = $match[1]? $match[1].$match[2]. $material[$match[1].$match[2]] : '';
                  
              $data[]=array('id'=>$index,'name'=>$item,'def'=>$name, 'm_name'=>$m_name);
              $index++;
            }
        }
        return new CArrayDataProvider($data,array(
                      'pagination'=>array(
                         'pageSize'=>50
                         )
                     ));
    }

    public static function dropDownData($server_id = null)
    {
        $db = Yii::app()->db;
        $data = array();
        if($server_id)
        {
            $dependency = new CDbCacheDependency('SELECT count(id) FROM ad WHERE deleted=0');
            $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM ad where server_id=:server_id AND deleted=0");
            $req->bindParam(':server_id', $server_id);
            $res = $req->queryAll();
            if($res)
            {
                foreach($res as $row)
                {
                    $data[$row['id']] = $row['name'];
                }
            }
        }
        return !empty($data) ? $data : array('--');
    }

    public static function items()
    {
        $db = Yii::app()->db;
        $dependency = new CDbCacheDependency('SELECT count(id) FROM ad WHERE deleted=0');
        $req = $db->cache(1000,$dependency)->createCommand("SELECT id,name FROM ad WHERE deleted=0");
        $res = $req->queryAll();
        $data=array();
        if($res)
        {
            foreach($res as $row)
            {
                $data[$row['id']] = $row['name'];
            }
        }
        return $data;
    }

    public static function defMaterial()
    {
        $db=Yii::app()->db;
        $dependency = new CDbCacheDependency('SELECT max(id) FROM material_data');
        return $db->cache(1000,$dependency)->createCommand("SELECT material_id FROM material_data ORDER BY id DESC LIMIT 1")->queryScalar();
    }

    public static function dailyList($from,$to,$material=null)
    {
     $to+=86399;
     if($material==null)
     {
        $material=self::defMaterial();
     }
     if($material)
     {
         $where="where material_id='{$material}' and date>={$from} and date<={$to}";
         $count=Yii::app()->db->createCommand("SELECT `id` FROM material_data {$where} GROUP BY material_id,date")->queryColumn();
         $sql="SELECT id,sum(click) as click,sum(visit) as visit,sum(register) as register,sum(register_visit) as register_visit
             ,sum(normal_visit) as normal_visit,material_id,date FROM material_data {$where} GROUP BY material_id,date ORDER BY date DESC ";
         return new CSqlDataProvider($sql,array(
                     'totalItemCount'=>count($count)
                     ,'pagination'=>array(
                         'pageSize'=>30
                         )
                     ));
     }
    }

    public static function dropDownMaterial()
    {
        $db = Yii::app()->db;
        $data = array();
        $dependency = new CDbCacheDependency('SELECT count(1) FROM material_data');
        $res=$db->cache(1000,$dependency)->createCommand("SELECT distinct(material_id) FROM material_data ORDER BY material_id ASC")->queryColumn();
        $out=array();
        if($res)
        {
          foreach($res as $item)
          {
              $out[$item]=$item;
          }
        }
        return $out;
    }

}
