<?php
class MarketController extends Controller
{
    private $_channelId=null;
    protected function beforeAction($action)
    {
        if(in_array($this->id,array('admin','site')) || Yii::app()->getAuthManager()->checkAccess($this->id.ucfirst($action->id),Yii::app()->user->getName()))
        {
          if(array_key_exists('external',Yii::app()->getAuthManager()->getRoles(Yii::app()->user->getName())))
          {
            $admin=Admin::model()->findByPk(Yii::app()->user->getId());
            $adminData=$admin->getData();
            if($adminData && isset($adminData['channelId']))
            {
              $this->_channelId=$adminData['channelId'];
            }
            else
            {
               throw new CHttpException('403','没有专属渠道记录,请联系管理员');
            }
          }
          $cs=Yii::app()->getClientScript();
          $cs->registerCssFile('/css/screen.css');
          $cs->registerCoreScript('jquery');
          $cs->registerScriptFile('/js/global.js',CClientScript::POS_END);
          return true;
        }
        else
        {
          throw new CHttpException('403','没有相应权限');
        }
    }

    public function actionIndex()
    {
        $model = new AdPos('search');
        if(!empty($_GET['AdPos']))
        {
            $model->attributes = $_GET['AdPos'];
            $model->adName = $_GET['AdPos']['ad_name'];
            $model->posName = $_GET['AdPos']['pos_name'];
            $model->gameId = $_GET['AdPos']['game_id'];
            $model->serverId = $_GET['AdPos']['server_id'];
            $model->posId = $_GET['AdPos']['pos_id'];
        }
        $data = array('startDate' => date('Y-m-d'), 'endDate' => date('Y-m-d'));
        if(!empty($_GET['start_date']))
        {
            $data['startDate'] = $_GET['start_date'];
        }
        if(!empty($_GET['end_date']))
        {
            $data['endDate'] = $_GET['end_date'];
        }
        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $this->render('index', array('model' => $model, 'data' => $data));
    }

    public function actionExport()
    {
        $model = new AdPos('search');
        if(!empty($_GET['AdPos']))
        {
            $model->attributes = $_GET['AdPos'];
            $model->adName = $_GET['AdPos']['ad_name'];
            $model->posName = $_GET['AdPos']['pos_name'];
            $model->gameId = $_GET['AdPos']['game_id'];
            $model->serverId = $_GET['AdPos']['server_id'];
            $model->posId = $_GET['AdPos']['pos_id'];
        }
        $data = array('startDate' => date('Y-m-d'), 'endDate' => date('Y-m-d'));
        if(!empty($_GET['start_date']))
        {
            $data['startDate'] = $_GET['start_date'];
        }
        if(!empty($_GET['end_date']))
        {
            $data['endDate'] = $_GET['end_date'];
        }
        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $start_time = strtotime($data['startDate']);
        $end_time = strtotime($data['endDate']. '23:59:59');
        $dataProvider=$model->combinedList($start_time,$end_time);
        $dataProvider->pagination=false;
        $this->widget('ext.EExcelView',array(
                    'dataProvider' => $dataProvider,
                    'columns' => array(
                        array(
                            'name' => 'id',
                            'header' => '广告位id',
                            'type' => 'raw',
                            'value' => 'Pos::getKey($data["id"])'
                            ),
                        array(
                            'name' => 'channel_id',
                            'header' => $model->getAttributeLabel('channel_id'),
                            'type' => 'raw',
                            'value' => 'Channel::getName($data["channel_id"])'
                            ),
                        array(
                            'header' => '广告位名称',
                            'type' => 'raw',
                            'value' => '$data["pos_name"]'
                            ),
                        array(
                            'header' => '广告版本',
                            'type' => 'raw',
                            'value' => '$data["ad_name"]'
                            ),
                        array(
                                'name' => 'game_id',
                                'header' => '游戏',
                                'type' => 'raw',
                                'value' => 'Game::getName($data["game_id"])'
                             ),
                        array(
                                'name' => 'server_id',
                                'header' => '区服',
                                'type' => 'raw',
                                'value'=>'Server::getName($data["server_id"])'
                             ),
                        array(
                                'name' => 'click_times',
                                'header' => $model->getAttributeLabel('click_times'),
                                'type' => 'raw',
                                'value' => '$data["click_times"]'
                                    ),
                                array(
                                    'name' => 'register_times',
                                    'header' => $model->getAttributeLabel('register_times'),
                                    'type' => 'raw',
                                    'value' => 'User::countRegisterByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')'
                                        ),
                                    array(
                                        'header' => '注册率',
                                        'type' => 'raw',
                                        'value' => 'Click::countClickByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .') > 0 ? sprintf("%01.2f", (User::countRegisterByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')/Click::countClickByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .'))*100). "%": 0'
                                        ),
                                    array(
                                        'header' => '回访用户数',
                                        'type' => 'raw',
                                        'value' => 'Visit::countRevisitByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')'
                                            ),
                                        array(
                                            'header' => '用户回访率',
                                            'type' => 'raw',
                                            'value' => 'User::countRegisterByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .') > 0 ? sprintf("%01.2f", (Visit::countRevisitByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')/User::countRegisterByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .'))*100). "%": 0'
                                            ),
                                        array(
                                            'header' => '充值总额',
                                            'type' => 'raw',
                                            'value' => 'Order::countPayByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')'
                                                )
                                            )
                                            ));
    }

    public function actionExportOrder()
    {
        $model = new Pos('search');
        if(!empty($_GET['Pos']))
        {
            $model->attributes = $_GET['Pos'];
        }
        $baseDate = date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate = $_GET['baseDate'];
        }

        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $dataProvider=$model->combinedList();
        $dataProvider->pagination=false;
        $columns = array(
                array(
                    'name' => 'id',
                    'header' => '广告位id',
                    'type' => 'raw',
                    'value' => '$data["id"]',
                    ),
                array(
                    'name' => 'channel_id',
                    'header' => $model->getAttributeLabel('channel_id'),
                    'type' => 'raw',
                    'value' => 'Channel::getName($data["channel_id"])'
                    ),
                array(
                    'name' => 'name',
                    'header' => $model->getAttributeLabel('name'),
                    'type' => 'raw',
                    )
                );
        $dateColumn = array();
        $baseTime = strtotime($baseDate);
        $first_time = strtotime('-6 days', $baseTime);
        $last_time = strtotime('+1 days', $baseTime);
        $monthFrom=strtotime(date('Y-m-01',$baseTime));
        $monthEnd= strtotime('+1 month', $monthFrom)-1;
        $dateColumn[] = array(
                'header' =>date('Y-m', $baseTime). '月总数',
                'type' => 'raw',
                'value' => 'Order::countPayByPos($data["id"],' . $monthFrom . ',' . $monthEnd . ')'
                    );
                for($i = 6; $i >= 0; $i--)
                {
                $next = $i - 1;
                $time = strtotime("-{$i} days", $baseTime);
                $nextTime = strtotime("-{$next} days", $baseTime);
                $date = date('m-d', $time);
                $dateColumn[] = array(
                    'header' => $date,
                    'type' => 'raw',
                    'value' => 'Order::countPayByPos($data["id"],' . $time . ',' . $nextTime . ')'
                        );
                    }
                    $columns = array_merge($columns, $dateColumn);
                    $this->widget('ext.EExcelView',array(
                            'dataProvider' =>$dataProvider,
                            'columns' => $columns
                            ));
                    }
    public function actionExportPaymentUser()
    {
        $model = new Pos('search');
        if(!empty($_GET['Pos']))
        {
            $model->attributes = $_GET['Pos'];
        }
        $baseDate = date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate = $_GET['baseDate'];
        }

        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $dataProvider=$model->combinedList();
        $dataProvider->pagination=false;
        $columns = array(
                array(
                    'name' => 'id',
                    'header' => '广告位id',
                    'type' => 'raw',
                    'value' => '$data["id"]',
                    ),
                array(
                    'name' => 'channel_id',
                    'header' => $model->getAttributeLabel('channel_id'),
                    'type' => 'raw',
                    'value' => 'Channel::getName($data["channel_id"])'
                    ),
                array(
                    'name' => 'name',
                    'header' => $model->getAttributeLabel('name'),
                    'type' => 'raw',
                    )
                );
        $dateColumn = array();
        $baseTime = strtotime($baseDate);
        $first_time = strtotime('-6 days', $baseTime);
        $last_time = strtotime('+1 days', $baseTime);
        $monthFrom=strtotime(date('Y-m-01',$baseTime));
        $monthEnd= strtotime('+1 month', $monthFrom)-1;
        $dateColumn[] = array(
                'header' =>date('Y-m', $baseTime). '月总数',
                'type' => 'raw',
                'value' => 'Order::nbUserPaidByPos($data["id"],' . $monthFrom . ',' . $monthEnd . ')'
                    );
                for($i = 6; $i >= 0; $i--)
                {
                $next = $i - 1;
                $time = strtotime("-{$i} days", $baseTime);
                $nextTime = strtotime("-{$next} days", $baseTime);
                $date = date('m-d', $time);
                $dateColumn[] = array(
                    'header' => $date,
                    'type' => 'raw',
                    'value' => 'Order::nbUserPaidByPos($data["id"],' . $time . ',' . $nextTime . ')'
                        );
                    }
                    $columns = array_merge($columns, $dateColumn);
                    $this->widget('ext.EExcelView',array(
                            'dataProvider' =>$dataProvider,
                            'columns' => $columns
                            ));
    }

    public function actionExportDistribute($id)
    {
       if($this->_channelId)
       {
           $id=$this->_channelId;
       }
       $model=Channel::model()->findByPk($id);
       $type='week';
       if(isset($_GET['type']))
       {
       $type=$_GET['type'];
       }
       if($model)
       {
           $period=Order::getTimePeriodByChannel($model->id);
           //间隔大于一周
           $data=array();
           if($period)
           {
               if($type=='week')
               {
                   $date=getdate($period['from']);
                   $wday=$date['wday'];
                   $firstMonday=strtotime(date('Y-m-d 00:00:00',$period['from']))-86400*($wday-1);

                   $date=getdate($period['to']);
                   $wday=$date['wday'];
                   $wday=($wday==0)?7:$wday;
                   $lastSunday=strtotime(date('Y-m-d 00:00:00',$period['to']))+86400*(7-$wday);
                   $i=$firstMonday;
                   while($i<$lastSunday)
                   {
                       $data[]=array(
                               'name'=>$model->name,
                               'id'=>$model->id,
                               'from'=>$i,
                               'to'=>$i+518400,
                               );
                       $i+=604800;
                   }
               }
               else
               {
                   $i=strtotime(date("Y-m-01",$period['from']));
                   while($i<$period['to'])
                   {
                       $data[]=array(
                               'name'=>$model->name,
                               'id'=>$model->id,
                               'from'=>$i,
                               'to'=>strtotime('+1 month',$i)-86400,
                               );
                       $i=strtotime('+1 month',$i);
                   }
               }
           }
           else
           {
               $data=array();
           }
           $dataProvider=new CArrayDataProvider($data,array());
           $dataProvider->pagination=false;
           $this->widget('ext.EExcelView',array(
                       'dataProvider'=>$dataProvider
                       ,'columns'=>array(
                           array(
                               'header'=>'编号'
                               ,'type'=>'raw'
                               ,'value'=>'$data["id"]'
                               )
                           ,array(
                               'header'=>'渠道'
                               ,'type'=>'raw'
                               ,'value'=>'$data["name"]'
                               )
                           ,array(
                               'header'=>'注册时间段'
                               ,'type'=>'raw'
                               ,'value'=>'date("Y-m-d",$data["from"]). "~".date("Y-m-d",$data["to"])'
                               )
                           ,array(
                               'header'=>'充值额'
                               ,'type'=>'raw'
                               ,'value'=>'Order::countPayByChannel($data["id"],$data["from"],$data["to"]+86399)'
                               )
                           )
                        ));
       }
       else
       {
           throw new CHttpException(404,"你请求的页面不存在");
       }
    }

    public function actionExportServerDistribute($id,$from,$to)
    {
        if($this->_channelId)
        {
            $id=$this->_channelId;
        }
        $data=Order::getServerDistributeByChannel($id,$from,$to);
        $dataProvider=new CArrayDataProvider($data,array(
                    'pagination'=>false
                    ));
        $this->widget('ext.EExcelView',array(
                        'dataProvider'=>$dataProvider
                       ,'columns'=>array(
                           array(
                                 'header'=>'游戏'
                                ,'type'=>'raw'
                                ,'value'=>'$data["game_name"]'
                               ),
                           array(
                                 'header'=>'区服'
                                ,'type'=>'raw'
                                ,'value'=>'$data["server_name"]'
                               ),
                           array(
                                 'header'=>'充值额'
                                ,'type'=>'raw'
                                ,'value'=>'$data["sum"]'
                               )
                           )
                        ));
    }

    public function actionClick()
    {
        $model = new Pos('search');
        if(!empty($_GET['Pos']))
        {
            $model->attributes = $_GET['Pos'];
        }
        $baseDate = date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate = $_GET['baseDate'];
        }

        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $this->render('click', array('model' => $model, 'baseDate' => $baseDate));
    }

    public function actionRegister()
    {
        $model = new Pos('search');
        if(!empty($_GET['Pos']))
        {
            $model->attributes = $_GET['Pos'];
        }
        $baseDate = date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate = $_GET['baseDate'];
        }
        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $this->render('register', array('model' => $model, 'baseDate' => $baseDate));
    }

    public function actionOrder()
    {
        $model = new Pos('search');
        if(!empty($_GET['Pos']))
        {
            $model->attributes = $_GET['Pos'];
        }
        $baseDate = date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate = $_GET['baseDate'];
        }
        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $this->render('order', array('model' => $model, 'baseDate' => $baseDate));
    }

    public function actionPaymentUser()
    {
        $model = new Pos('search');
        if(!empty($_GET['Pos']))
        {
            $model->attributes = $_GET['Pos'];
        }
        $baseDate = date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate = $_GET['baseDate'];
        }
        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $this->render('user', array('model' => $model, 'baseDate' => $baseDate));
    }

    public function actionRevisit()
    {
        $model = new Pos('search');
        if(!empty($_GET['Pos']))
        {
            $model->attributes = $_GET['Pos'];
        }
        $data = array('startDate' => date('Y-m-d', strtotime('-1 day')), 'endDate' => date('Y-m-d',strtotime('-1 day')));
        if(!empty($_GET['start_date']))
        {
            $data['startDate'] = $_GET['start_date'];
        }
        if(!empty($_GET['end_date']))
        {
            $data['endDate'] = $_GET['end_date'];
        }
        if($this->_channelId)
        {
            $model->channel_id=$this->_channelId;
        }
        $this->render('revisit', array('model' => $model, 'data' => $data));
    }

    public function actionDistribute()
    {
        $model=new Channel('search');
        if(!empty($_GET['Channel']))
        {
            $model->attributes=$_GET['Channel'];
        }
        if($this->_channelId)
        {
            $model->id=$this->_channelId;
        }
        $this->render('channel',array('model'=>$model));
    }

    public function actionViewDistribute($id)
    {
        if($this->_channelId)
        {
            $id=$this->_channelId;
        }
        $model=Channel::model()->findByPk($id);
        $type='week';

        if(isset($_GET['type']))
        {
            $type=$_GET['type'];
        }

        if($model)
        {
            $period=Order::getTimePeriodByChannel($model->id);
            $data=array();
            if($period)
            {
                if($type=='week')
                {
                    $date=getdate($period['from']);
                    $wday=$date['wday'];
                    $firstMonday=strtotime(date('Y-m-d 00:00:00',$period['from']))-86400*($wday-1);
                    $date=getdate($period['to']);
                    $wday=$date['wday'];
                    $wday=($wday==0)?7:$wday;
                    $lastSunday=strtotime(date('Y-m-d 00:00:00',$period['to']))+86400*(7-$wday);
                    $i=$firstMonday;
                    while($i<$lastSunday)
                    {
                        $data[]=array(
                                'name'=>$model->name,
                                'id'=>$model->id,
                                'from'=>$i,
                                'to'=>$i+518400,
                                );
                        $i+=604800;
                    }
                }
                else
                {
                    $i=strtotime(date("Y-m-01",$period['from']));
                    while($i<$period['to'])
                    {
                        $data[]=array(
                                'name'=>$model->name,
                                'id'=>$model->id,
                                'from'=>$i,
                                'to'=>strtotime('+1 month',$i)-86400,
                                );
                        $i=strtotime('+1 month',$i);
                    }
                }
            }
            else
            {
                $data=array();
            }
            $dataProvider=new CArrayDataProvider($data,array(
                        'sort'=>array(
                            'attributes'=>array(
                                'id'
                                )
                            )
                        ,'pagination'=>array(
                            'pageSize'=>20
                            )
                        ));
            $this->render('distribute',array('dataProvider'=>$dataProvider,'model'=>$model,'type'=>$type));
        }
        else
        {
            throw new CHttpException(404,"你请求的页面不存在");
        }
    }

    public function actionViewServerDistribute($id,$from,$to)
    {
        if($this->_channelId)
        {
            $id=$this->_channelId;
        }
        $data=Order::getServerDistributeByChannel($id,$from,$to);
        $dataProvider=new CArrayDataProvider($data,array(
                    'pagination'=>array(
                        'pageSize'=>20
                        )
                    ));
        $this->render('distributeServer',array('dataProvider'=>$dataProvider,'id'=>$id,'from'=>$from,'to'=>$to));
    }

    public function actionSnda()
    {
       $dataProvider=AdPos::getSndaAdpos();
       $this->render('snda',array('dataProvider'=>$dataProvider));
    }
}
