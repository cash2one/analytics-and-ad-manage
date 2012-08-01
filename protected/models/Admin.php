<?php
class Admin extends CActiveRecord
{
    public static $navTree=array(
                  0=>array(
                      'id'=>array('site')
                      ,'route'=>array(
                          'site/index'=>'总览'
                         ,'site/stat'=>'开服推广统计'
                         ,'site/analyzeDaily'=>'每日统计'
                          )
                      ,'name'=>'首页'
                      ,'url'=>'/site/index'
                      ),
                  1=>array(
                      'id'=>array('market')
                      ,'route'=>array(
                          'market/index'=>'广告综合'
                          ,'market/click'=>'广告点击统计'
                          ,'market/register'=>'广告注册统计'
                          ,'market/order'=>'广告充值统计'
                          ,'market/paymentUser'=>'广告充值人数统计'
                          ,'market/revisit'=>'广告回访统计'
                          ,'market/distribute'=>'广告充值用户分布'
                          ,'market/snda'=>'SNDA广告用户统计'
                          )
                      ,'name'=>'广告数据统计'
                      ,'url'=>'/market/index'
                      ),
                  2=>array(
                      'id'=>array('operation')
                      ,'route'=>array(
                          'operation/index'=>'游戏服数据统计'
                          ,'operation/game'=>'游戏数据统计'
                          ,'operation/register'=>'注册用户统计'
                          ,'operation/visit'=>'游戏登录统计'
                          ,'operation/revisit'=>'游戏用户回访统计'
                          ,'operation/order'=>'每天充值统计'
                          ,'operation/orderWeekly'=>'每周充值统计'
                          ,'operation/payment'=>'消费能力统计'
                          ,'operation/vip'=>'大户列表'
                          ,'operation/visitChart'=>'登录曲线'
                          )
                      ,'name'=>'游戏数据统计'
                      ,'url'=>'/operation/index'
                      ),
                  3=>array(
                          'id'=>array('cost')
                          ,'route'=>array(
                              'cost/platform'=>'平台成本回款'
                              ,'cost/index'=>'成本分析'
                              ,'cost/weekly'=>'成本周报'
                              ,'cost/channel'=>'渠道成本回款'
                              ,'cost/server'=>'区服成本回款'
                              )
                          ,'name'=>'成本管理'
                          ,'url'=>'/cost/server'
                          ),
                  4=>array(
                          'id'=>array('ad','pos','channel','material')
                          ,'route'=>array(
                              'pos/index'=>'广告位列表'
                              //,'pos/create'=>'添加广告位'
                              ,'ad/index'=>'广告列表'
                              //,'ad/create'=>'添加广告'
                              ,'ad/batchCreate'=>'批量添加广告'
                              ,'material/index'=>'素材列表'
                              //,'material/create'=>'添加素材'
                              ,'channel/index'=>'渠道列表'
                              //,'channel/create'=>'添加渠道'
                              ,'channel/daily'=>'渠道每日'
                              ,'ad/daily'=>'素材每日'
                              )
                          ,'name'=>'广告管理'
                          ,'url'=>'/pos/index'
                          ),
                  5=>array(
                          'id'=>array('game','server', 'gameType', 'sortType', 'sort', 'package')
                          ,'route'=>array(
                              'game/index'=>'游戏列表'
                              //,'game/create'=>'添加游戏'
                              ,'server/index'=>'区服列表'
                              //,'server/create'=>'添加区服'
                              ,'gameType/index'=>'游戏类别列表'
                              //,'gameType/create'=>'添加游戏类别'
                              ,'game/list'=>'-游戏列表-'
                              ,'sortType/index'=>'游戏排序规则'
                              //,'sortType/create'=>'添加游戏排序规则'
                              ,'sortType/choose'=>'规则选择'
                              ,'package/index'=>'礼包列表'
                              )
                          ,'name'=>'游戏管理'
                          ,'url'=>'/game/index'
                          ),
                  6=>array(
                          'id'=>array('user')
                          ,'route'=>array(
                              'user/index'=>'用户列表'
                              ,'user/exportUser'=>'导出用户'
                              ,'user/emailRecord'=>'修改邮箱记录'
                              ,'user/appeal'=>'用户密码申诉'
                              ,'user/vip'=>'VIP用户'
                              )
                          ,'name'=>'用户管理'
                          ,'url'=>'/user/index'
                          ),
                  7=>array(
                          'id'=>array('finance')
                          ,'route'=>array(
                              'finance/index'=>'充值每日统计',
                              'finance/list'=>'充值订单记录',
                              'finance/game'=>'财务游戏对账',
                              'finance/bank'=>'财务银行对账',
                              'finance/income'=>'游戏收入',
                              'finance/ad'=>'广告充值记录',
                              'finance/exportOffline'=>'充值记录离线导出',
                              )
                          ,'name'=>'财务管理'
                          ,'url'=>'/finance/index'
                          ),
                  8=>array(
                          'id'=>array('gameOpenAnn', 'userVote', 'userRcd')
                          ,'route'=>array(
                              'userVote/index'=>'本周真实投票'
                             ,'gameOpenAnn/index'=>'本周开服预告'
                             ,'gameOpenAnn/create'=>'增加开服预告'
                             ,'userVote/list'=>'合服推荐列表'
                             ,'userRcd/index'=>'新游戏推荐列表'
                             )
                          ,'name'=>'投票管理'
                          ,'url'=>'/userVote/index'
                          )
                  );

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'admin';
    }

    public function rules()
    {
        return array(
            array('name,pwd', 'required'),
            array('name', 'length', 'max'=>64),
            array('name', 'unique'),
            array('salt, pwd', 'length', 'max'=>32),
            array('latest_ip, latest_time, login_times, create_id, create_time', 'length', 'max'=>10),
            array('id, name, salt, pwd, latest_ip, latest_time, login_times, create_id, create_time,data', 'safe', 'on'=>'search'),
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
            'name' => '用户名',
            'salt' => '密码盐',
            'pwd' => '密码',
            'latest_ip' => '最后登录ip',
            'latest_time' => '最后登录时间',
            'login_times' => '登录次数',
            'create_id' => '创建者',
            'create_time' => '创建时间',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('salt',$this->salt,true);
        $criteria->compare('pwd',$this->pwd,true);
        $criteria->compare('latest_ip',$this->latest_ip,true);
        $criteria->compare('latest_time',$this->latest_time,true);
        $criteria->compare('login_times',$this->login_times,true);
        $criteria->compare('create_id',$this->create_id,true);
        $criteria->compare('create_time',$this->create_time,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 20,
        )));
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
            {
               $this->salt=md5(time());
               $this->pwd=$this->_hashPwd($this->salt,$this->pwd);
               $this->latest_ip=ip2long(Yii::app()->request->userHostAddress);
               $this->create_id=Yii::app()->user->getId();
               $this->create_time=time();
               $this->setIsNewRecord(FALSE);
               $this->save();
            }
            return true;
        }
    }

    public function setPwd($pwd)
    {
      $this->pwd=$this->_hashPwd($this->salt,$pwd);
      $this->save();
    }

    public function validatePwd($pwd)
    {
      return $this->pwd == $this->_hashPwd($this->salt, $pwd) ? TRUE : FALSE;
    }

    public function assignedOperations()
    {
        $auth=Yii::app()->authManager;
        $operations=array();
        if($auth->getOperations($this->name))
        {
            foreach($auth->getOperations($this->name) as $item)
            {
               $operations[]=$item->name;
            }
        }
        return $operations;
    }

    public function assignedRoles()
    {
        $auth=Yii::app()->authManager;
        $roles=array();
        if($auth->getRoles($this->name))
        {
            foreach($auth->getRoles($this->name) as $item)
            {
               $roles[]=$item->name;
            }
        }
        return $roles;
    }

    private static function _hashPwd($salt,$pwd)
    {
        return md5($salt .$pwd);
    }

    public static function getRoles()
    {
        $db=Yii::app()->db;
        $req=$db->createCommand("SELECT name,description FROM authitem where type=2");
        $res=$req->queryAll();
        $data=array();
        if($res)
        {
           foreach($res as $item)
           {
             $data[$item['name']]=$item['description'];
           }
        }
        return $data;
    }

    public static function updateRole($adminName,$roles)
    {
        $db=Yii::app()->db;
        $req=$db->createCommand("SELECT itemname FROM auth_assignment where userid=:userid");
        $res=$req->queryColumn(array(':userid'=>$adminName));
        if($res)
        {
           $add=array_diff($roles,$res);
           $del=array_diff($res,$roles);
        }
        else
        {
           $add=$roles;
           $del=array();
        }

        $auth=Yii::app()->authManager;
        if($add)
        {
            foreach($add as $item)
            {
              $auth->assign($item,$adminName);
            }
        }
        if($del)
        {
             foreach($del as $item)
            {
              $auth->revoke($item,$adminName);
            }
        }
    }

    public static function getName($adminId)
    {
        $name='';
        if($adminId)
        {
            $name=Yii::app()->db->createCommand("SELECT name FROM admin WHERE id={$adminId}")->queryScalar();
        }
        return $name;
    }

    public static function items()
    {
        $data=array();
        $dependency = new CDbCacheDependency('SELECT count(id) FROM admin');
        $res=Yii::app()->db->cache(1000,$dependency)->createCommand("SELECT id,name FROM admin")->queryAll();
        if($res)
        {
            foreach($res as $item)
            {
                $data[$item['id']]=$item['name'];
            }
        }
        return $data;
    }

    public static function removeAuthItem($name)
    {
        $db=Yii::app()->db;
            $db->createCommand()
                ->delete('authitem_child', 'parent=:name1 OR child=:name2', array(
                            ':name1'=>$name,
                            ':name2'=>$name
                            ));
            $db->createCommand()
                ->delete('auth_assignment', 'itemname=:name', array(
                            ':name'=>$name,
                            ));
        return $db->createCommand()
            ->delete('authitem', 'name=:name', array(
                        ':name'=>$name
                        )) > 0;
    }

    public function setData($data)
    {
        $db=Yii::app()->db;
        $data=serialize($data);
        $db->createCommand("UPDATE admin set data='{$data}' where id={$this->id}")->execute();
    }

    public function getData()
    {
        return unserialize($this->data);
    }

    public function getHomeUrl()
    {
        $auth=Yii::app()->authManager;
        $roles=$auth->getRoles($this->name);
        $nav=array();
        foreach($roles as $role)
        {
            $data=$role->getData();
            $nav+=$data['nav'];
        }
        $adminData=$this->getData();
        if($adminData && isset($adminData['nav']))
        {
            $_ = array();
            foreach(self::$navTree as $k => $v)
            {
                if(isset($nav[$k]) || isset($adminData['nav'][$k]))
                {
                    $_[$k] = isset($nav[$k]) ? $nav[$k] : $adminData['nav'][$k];
                    unset($_[$k]['route']);
                    foreach($v['route'] as $a => $r)
                    {
                        if(isset($nav[$k]['route'][$a]) || isset($adminData['nav'][$k]['route'][$a]))
                            $_[$k]['route'][$a] = $r;
                    }
                }
            }
        }
        $nav = isset($_) ? $_ : $nav;
        ksort($nav);
        $r = current($nav);
        $url = key($r['route']);
        return $url;
    }
}
