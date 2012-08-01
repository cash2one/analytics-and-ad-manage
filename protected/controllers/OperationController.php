 <?php
class OperationController extends Controller
{
    private $_model;
    public  $actionTitle='';

    public function actionIndex()
    {
        $model=new Server('search');
        $server=null;
        $from=strtotime('2011-08-01');
        $to=strtotime(date('Y-m-d'))-1;

        if(!empty($_GET['server']))
        {
           $server=$_GET['server'];
        }
        if(!empty($_GET['from']))
        {
           $from=strtotime($_GET['from']);
        }
        if(!empty($_GET['to']))
        {
           $to=strtotime($_GET['to'])+86399;
        }
        if(isset($_GET['ajax']))
        {
          $dataProvider=$model->indexList($server,$from,$to);
          $this->renderPartial('index',array('dataProvider'=>$dataProvider,'server'=>$server,'from'=>$from,'to'=>$to));
        }
        else
        {
          $cs=Yii::app()->getClientScript();
          $cs->registerCoreScript('jquery.ui');
          $cs->registerScriptFile('/js/jquery.multiselect.min.js');
          $cs->registerCssFile('/js/jquery.multiselect.css');
          $dataProvider=$model->indexList($server,$from,$to);
          $this->render('index',array('dataProvider'=>$dataProvider,'server'=>$server,'from'=>$from,'to'=>$to));
        }
    }

    public function actionVip()
    {
        $server=null;
        $from=strtotime('2011-06-01');
        $to=strtotime(date('Y-m-d'))-1;

        if(!empty($_GET['server']))
        {
           $server=$_GET['server'];
        }

        if(!empty($_GET['from']))
        {
           $from=strtotime($_GET['from']);
        }

        if(!empty($_GET['to']))
        {
           $to=strtotime($_GET['to'])+86399;
        }

        if(isset($_GET['ajax']))
        {
          $dataProvider=User::vipList($server,$from,$to);
          $this->renderPartial('vip',array('dataProvider'=>$dataProvider,'server'=>$server,'from'=>$from,'to'=>$to));
        }
        else
        {
          $cs=Yii::app()->getClientScript();
          $cs->registerCoreScript('jquery.ui');
          $cs->registerScriptFile('/js/jquery.multiselect.min.js');
          $cs->registerCssFile('/js/jquery.multiselect.css');
          $dataProvider=User::vipList($server,$from,$to);
          $this->render('vip',array('dataProvider'=>$dataProvider,'server'=>$server,'from'=>$from,'to'=>$to));
        }
    }

    public function actionExportVip()
    {
        $server=null;
        $from=strtotime('2011-06-01');
        $to=strtotime(date('Y-m-d'))-1;
        if(!empty($_GET['server']))
        {
           $server=$_GET['server'];
        }
        if(!empty($_GET['from']))
        {
           $from=strtotime($_GET['from']);
        }
        if(!empty($_GET['to']))
        {
           $to=strtotime($_GET['to'])+86399;
        }
        $dataProvider=User::vipList($server,$from,$to);
        $dataProvider->pagination=false;
        $this->widget('ext.EExcelView', array(
                            'dataProvider' =>$dataProvider,
                            'filter' => false,
                            'columns' => array(
                               array(
                                   'name' => 'user_name',
                                   'header'=>'用户名',
                                   'type' => 'raw',
                                   'value' => '$data["user_name"]',
                               ),
                               array(
                                   'name' => 'game_id',
                                   'header'=>'游戏',
                                   'type' => 'raw',
                                   'value' => 'Game::getName($data["game_id"])',
                               ),
                               array(
                                   'name' => 'server_id',
                                   'header'=>'区服',
                                   'type' => 'raw',
                                   'value' => 'Server::getName($data["server_id"])',
                               ),
                               array(
                                     'name' => 'channel_id',
                                     'header' =>'注册渠道',
                                     'type' => 'raw',
                                     'value' => 'Channel::getName($data["channel_id"])'
                                ),
                                array(
                                    'name' => 'reg_time',
                                    'header' =>'注册时间',
                                    'type' => 'date',
                                ),
                                array(
                                     'name' => 'max_paid',
                                     'header' =>'单笔最高' ,
                                     'type' => 'raw',
                                ),
                                array(
                                     'name' => 'avg_paid',
                                     'header' =>'平均每笔充值' ,
                                     'type' => 'raw',
                                ),
                                array(
                                     'name' => 'sum_paid',
                                     'header' =>'充值总额' ,
                                     'type' => 'raw',
                                ),
                                array(
                                     'name' => 'login_times',
                                     'header' =>'登录次数' ,
                                     'type' => 'raw',
                                ),
                                array(
                                     'header' =>'最后登录游戏区服' ,
                                     'type' => 'raw',
                                     'value'=>'Visit::lastGameServer($data["user_id"])'
                                ),
                                array(
                                     'header' =>'最后登录时间' ,
                                     'type' => 'raw',
                                     'value'=>'Visit::lastVisitTime($data["user_id"])'
                                )
                               )
                            ));
    }
    public function actionView($id)
    {
       $server=Server::model()->findByPk($id);
       if(!$server)
       {
        throw new CHttpException(404,"你请求的页面不存在");
       }
       $dataProvider=$server->dailyList();
       $this->render('view',array('dataProvider'=>$dataProvider,'model'=>$server));
    }

    public function actionExport()
    {
        $model=new Server('search');
        $server=null;
        $from=strtotime('2011-08-01');
        $to=strtotime(date('Y-m-d'))-1;

        if(!empty($_GET['server']))
        {
           $server=$_GET['server'];
        }

        if(!empty($_GET['from']))
        {
           $from=strtotime($_GET['from']);
        }

        if(!empty($_GET['to']))
        {
           $to=strtotime($_GET['to'])+86399;
        }
        $dataProvider=$model->indexList($server,$from,$to);
        $dataProvider->pagination=false;
        $this->widget('ext.EExcelView', array(
                            'dataProvider' =>$dataProvider,
                            'columns' => array(
                               array(
                                   'name' => 'id',
                                   'type' => 'raw',
                                   'value' => '$data["id"]',
                               ),
                                 array(
                                     'name' => 'game_id',
                                     'header' =>'游戏',
                                     'type' => 'raw',
                                     'value' => '$data["game_name"]'
                                ),
                                array(
                                    'name' => 'name',
                                    'header' =>'区服',
                                    'type' => 'raw',
                                ),
                                array(
                                     'name' => 'open_time',
                                     'header' =>'开服时间' ,
                                     'type' => 'date',
                                ),
                                array(
                                     'header' => '开服天数',
                                     'type' => 'raw',
                                     'value' => '$data["open_day"]'
                                ),
                                array(
                                     'header'=>'广告注册',
                                     'type' => 'raw',
                                     'value' => '$data["ad_register"]'
                                    ),
                                array(
                                     'header'=>'平台注册',
                                     'type' => 'raw',
                                     'value' => '$data["normal_register"]'
                                    ),
                                array(
                                     'header'=>'转服注册',
                                     'type' => 'raw',
                                     'value' => '$data["migrate_register"]'
                                    ),
                                array(
                                     'header'=>'登录用户',
                                     'type' => 'raw',
                                     'value' => '$data["visit_user"]'
                                    ),
                                array(
                                     'header'=>'回访用户',
                                     'type' => 'raw',
                                     'value' => '$data["revisit"]'
                                    ),
                                array(
                                     'header'=>'回访率',
                                     'type' => 'raw',
                                     'value' => '$data["revisit_percent"]'
                                    ),
                                array(
                                     'header'=>'充值人数',
                                     'type' => 'raw',
                                     'value' => '$data["payment_user"]'
                                    ),
                                array(
                                     'header'=>'充值率',
                                     'type' => 'raw',
                                     'value' => '$data["payment_percent"]'
                                    ),
                                array(
                                     'header'=>'充值金额',
                                     'type' => 'raw',
                                     'value' => '$data["payment_amount"]'
                                    ),
                                array(
                                     'header'=>'ARUP',
                                     'type' => 'raw',
                                     'value' => '$data["arup"]'
                                     )
                               )
                            ));
    }

    public function actionGame()
    {
        $model=new Game('search');
        $game=null;
        $from=strtotime('2011-08-01');
        $to=strtotime(date('Y-m-d'))-1;
        if(!empty($_GET['game']))
        {
           $game=$_GET['game'];
        }
        if(!empty($_GET['from']))
        {
           $from=strtotime($_GET['from']);
        }
        if(!empty($_GET['to']))
        {
           $to=strtotime($_GET['to'])+86399;
        }
        if(isset($_GET['ajax']))
        {
          $dataProvider=$model->combinedList($game,$from,$to);
          $this->renderPartial('game',array('dataProvider'=>$dataProvider,'game'=>$game,'from'=>$from,'to'=>$to));
        }
        else
        {
          $cs=Yii::app()->getClientScript();
          $cs->registerCoreScript('jquery.ui');
          $cs->registerScriptFile('/js/jquery.multiselect.min.js');
          $cs->registerCssFile('/js/jquery.multiselect.css');
          $dataProvider=$model->combinedList($game,$from,$to);
          $this->render('game',array('dataProvider'=>$dataProvider,'game'=>$game,'from'=>$from,'to'=>$to));
        }
    }

    public function actionExportGame()
    {
        $model=new Game('search');
        $game=null;
        $from=strtotime('2011-08-01');
        $to=strtotime(date('Y-m-d'))-1;
        if(!empty($_GET['game']))
        {
           $game=$_GET['game'];
        }
        if(!empty($_GET['from']))
        {
           $from=strtotime($_GET['from']);
        }
        if(!empty($_GET['to']))
        {
           $to=strtotime($_GET['to'])+86399;
        }
        $dataProvider=$model->combinedList($game,$from,$to);
        $dataProvider->pagination=false;
        $this->widget('ext.EExcelView', array(
                            'dataProvider' =>$dataProvider,
                            'columns' => array(
                               array(
                                   'name' => 'id',
                                   'type' => 'raw',
                                   'value' => '$data["id"]',
                               ),
                                 array(
                                     'name' => 'name',
                                     'header' =>'游戏',
                                     'type' => 'raw',
                                     'value' => '$data["name"]'
                                ),
                                array(
                                     'header'=>'广告注册',
                                     'type' => 'raw',
                                     'value' => '$data["ad_register"]'
                                    ),
                                array(
                                     'header'=>'平台注册',
                                     'type' => 'raw',
                                     'value' => '$data["normal_register"]'
                                    ),
                                array(
                                     'header'=>'转服注册',
                                     'type' => 'raw',
                                     'value' => '$data["migrate_register"]'
                                    ),
                                array(
                                     'header'=>'登录用户',
                                     'type' => 'raw',
                                     'value' => '$data["visit_user"]'
                                    ),
                                array(
                                     'header'=>'回访用户',
                                     'type' => 'raw',
                                     'value' => '$data["revisit"]'
                                    ),
                                array(
                                     'header'=>'回访率',
                                     'type' => 'raw',
                                     'value' => '$data["revisit_percent"]'
                                    ),
                                array(
                                     'header'=>'充值人数',
                                     'type' => 'raw',
                                     'value' => '$data["payment_user"]'
                                    ),
                                array(
                                     'header'=>'充值率',
                                     'type' => 'raw',
                                     'value' => '$data["payment_percent"]'
                                    ),
                                array(
                                     'header'=>'充值金额',
                                     'type' => 'raw',
                                     'value' => '$data["payment_amount"]'
                                    ),
                                array(
                                     'header'=>'ARUP',
                                     'type' => 'raw',
                                     'value' => '$data["arup"]'
                                     )
                               )
                            ));
    }

    public function actionVisit()
    {
        $model=new Server('search');
        if(!empty($_GET['Server']))
        {
            $model->attributes=$_GET['Server'];
        }

        $baseDate=date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate=$_GET['baseDate'];
        }
        $this->render('visit',array('model'=>$model,'baseDate'=>$baseDate));
    }
    
    public function actionExportVisit()
    {
        $model=new Server('search');
        if(!empty($_GET['Server']))
        {
            $model->attributes=$_GET['Server'];
        }
        
        $baseDate=date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate=$_GET['baseDate'];
        }
        $dataProvider=$model->combinedList();
        $dataProvider->pagination=false;
        $columns =array(
                array(
                        'name' => 'id',
                        'type' => 'raw',
                        'value' => '$data["id"]'
                ),
                array(
                        'name' => 'game_id',
                        'header' => '游戏',
                        'type' => 'raw',
                        'value' => 'Game::getName($data["game_id"])'
                ),
                array(
                        'name' => 'name',
                        'header' => $model->getAttributeLabel('name'),
                        'type' => 'raw'
                ),
        );
        $dateColumn=array();
        $baseTime=strtotime($baseDate);
        for($i=7;$i>=0;$i--)
        {
            $filter=false;
            $next=$i-1;
            $time=strtotime("-{$i} days",$baseTime);
            $nextTime=strtotime("-{$next} days",$baseTime);
            $date=date('m-d',$time);
            $dateColumn[]=array(
                    'header' =>$date,
                    'type' => 'number',
                    'value' => 'Visit::nbDistinctVisit($data["id"],'.$time.','.$nextTime.')'
            );
        }
        $columns=array_merge($columns,$dateColumn);
        $this->widget('ext.EExcelView', array(
                'dataProvider' =>$dataProvider,
                'filter' => false,
                'columns' =>$columns
        ));
    }

    public function actionRegister()
    {
        $model=new Server('search');
        if(!empty($_GET['Server']))
        {
            $model->attributes=$_GET['Server'];
        }
        $baseDate=date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate=$_GET['baseDate'];
        }
        $this->render('register',array('model'=>$model,'baseDate'=>$baseDate));
    }
    
    public function actionExportRegister()
    {
        $model=new Server('search');
        if(!empty($_GET['Server']))
        {
            $model->attributes=$_GET['Server'];
        }
        $baseDate=date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate=$_GET['baseDate'];
        }
        $dataProvider=$model->combinedList();
        $dataProvider->pagination=false;
        $columns =array(
                array(
                        'name' => 'id',
                        'type' => 'raw',
                        'value' => '$data["id"]'
                ),
                array(
                        'name' => 'game_id',
                        'header' => ' 游戏',
                        'type' => 'raw',
                        'value' => 'Game::getName($data["game_id"])'
                ),
                array(
                        'name' => 'name',
                        'header' => '区服',
                        'type' => 'raw'
                ),
                array(
                        'header' => '总注册用户数',
                        'type' => 'number',
                        'value'=>'User::nbUser($data["id"])'
                )
        );
        $dateColumn=array();
        $baseTime=strtotime($baseDate);
        for($i=7;$i>=0;$i--)
        {
            $next=$i-1;
            $time=strtotime("-{$i} days",$baseTime);
            $nextTime=strtotime("-{$next} days",$baseTime);
            $date=date('m-d',$time);
            $dateColumn[]=array(
                    'header' =>$date,
                    'type' => 'number',
                    'value' => 'User::nbUser($data["id"],'.$time.','.$nextTime.')'
            );
        }
        $columns=array_merge($columns,$dateColumn);
        $this->widget('ext.EExcelView', array(
                'dataProvider' =>$dataProvider,
                'filter' => false,
                'columns' =>$columns
        ));
    }

    public function actionRevisit()
    {
        $model=new Server('search');
        if(!empty($_GET['Server']))
        {
            $model->attributes=$_GET['Server'];
        }
        $this->render('revisit',array('model'=>$model));
    }

    public function actionOrder()
    {
        $model=new Server('search');
        if(!empty($_GET['Server']))
        {
            $model->attributes=$_GET['Server'];
        }
        $baseDate=date('Y-m-d');
        if(!empty($_GET['baseDate']))
        {
            $baseDate=$_GET['baseDate'];
        }
        $dataProvider=$model->combinedList();
        $data=$dataProvider->getData();
        $temp=array(
                'id'=>''
               ,'game_id'=>''
               ,'name'=>''
                );
        array_unshift($data,$temp);
        $dataProvider->setData($data);
        $this->render('order',array('model'=>$model,'baseDate'=>$baseDate,'dataProvider'=>$dataProvider));
    }

    public function actionOrderWeekly()
    {
        $model=new Server('search');
        $server=null;
        $from=1;
        $to=8;
        if(!empty($_GET['server']))
        {
           $server=$_GET['server'];
        }
        if(!empty($_GET['from']))
        {
           $from=$_GET['from'];
        }

        if(!empty($_GET['to']))
        {
           $to=$_GET['to'];
        }

        if(isset($_GET['ajax']))
        {
          $dataProvider=$model->orderWeekly($server);
          $this->renderPartial('orderWeekly',array('dataProvider'=>$dataProvider,'server'=>$server,'from'=>$from,'to'=>$to));
        }
        else
        {
          $cs=Yii::app()->getClientScript();
          $cs->registerCoreScript('jquery.ui');
          $cs->registerScriptFile('/js/jquery.multiselect.min.js');
          $cs->registerCssFile('/js/jquery.multiselect.css');
          $dataProvider=$model->orderWeekly($server);
          $this->render('orderWeekly',array('dataProvider'=>$dataProvider,'server'=>$server,'from'=>$from,'to'=>$to));
        }
    }

    public function actionExportOrderWeekly()
    {
        $model=new Server('search');
        $server=null;
        $from=1;
        $to=8;
        if(!empty($_GET['server']))
        {
           $server=$_GET['server'];
        }
        if(!empty($_GET['from']))
        {
           $from=$_GET['from'];
        }

        if(!empty($_GET['to']))
        {
           $to=$_GET['to'];
        }
        $dataProvider=$model->orderWeekly($server);
        $dataProvider->pagination=false;
        $columns=array(
                        array(
                            'name' => 'id',
                            'type' => 'raw',
                            'value' => '$data["id"]',
                            'filter' => false
                        ),
                          array(
                              'name' => 'game_id',
                              'header' =>'游戏',
                              'type' => 'raw',
                              'value' => '$data["game_name"]'
                         ),
                         array(
                             'name' => 'name',
                             'header' =>'区服',
                             'type' => 'raw',
                         ),
                         array(
                              'name' => 'open_time',
                              'header' =>'开服时间' ,
                              'type' => 'date',
                         ),
                         array(
                              'header'=>'总充值',
                              'type'=>'raw',
                              'value'=>'Order::sumPaid($data["id"])'
                         ),
                    );
                 for($i=$from;$i<=$to;$i++)
                 {
                     $week[]=array(
                             'header'=>"第{$i}周充值",
                             'type'=>'raw',
                             'value'=>'Order::sumPaidByWeek($data["id"],$data["open_time"],'.$i.')'
                             );
                 }
                 $columns=array_merge($columns,$week);
                 $this->widget('ext.EExcelView', array(
                            'dataProvider' =>$dataProvider,
                            'filter' => false,
                            'columns' =>$columns
                            ));
    }

    public function actionPayment()
    {
        $model=new Server('search');
        $server=null;
        $from=strtotime('2011-08-01');
        $to=strtotime(date('Y-m-d'))-1;

        if(!empty($_GET['server']))
        {
           $server=$_GET['server'];
        }

        if(!empty($_GET['from']))
        {
           $from=strtotime($_GET['from']);
        }

        if(!empty($_GET['to']))
        {
           $to=strtotime($_GET['to'])+86399;
        }

        if(isset($_GET['ajax']))
        {
          $dataProvider=$model->consumeList($server,$from,$to);
          $this->renderPartial('payment',array('dataProvider'=>$dataProvider,'server'=>$server,'from'=>$from,'to'=>$to));
        }
        else
        {
          $cs=Yii::app()->getClientScript();
          $cs->registerCoreScript('jquery.ui');
          $cs->registerScriptFile('/js/jquery.multiselect.min.js');
          $cs->registerCssFile('/js/jquery.multiselect.css');
          $dataProvider=$model->consumeList($server,$from,$to);
          $this->render('payment',array('dataProvider'=>$dataProvider,'server'=>$server,'from'=>$from,'to'=>$to));
        }
    }
    
    public function actionExportPayment()
    {
        $model=new Server('search');
        $server=null;
        $from=strtotime('2011-08-01');
        $to=strtotime(date('Y-m-d'))-1;
        
        if(!empty($_GET['server']))
        {
            $server=$_GET['server'];
        }
        
        if(!empty($_GET['from']))
        {
            $from=strtotime($_GET['from']);
        }
        
        if(!empty($_GET['to']))
        {
            $to=strtotime($_GET['to'])+86399;
        }
        $dataProvider=$model->consumeList($server,$from,$to);
        $dataProvider->pagination=false;
        
        $this->widget('ext.EExcelView', array(
                'dataProvider' =>$dataProvider,
                'filter' => false,
                'columns' =>array(
                   array(
                       'name' => 'id',
                       'type' => 'raw',
                       'value' => '$data["id"]'
                   ),
                    array(
                         'name' => 'game_id',
                         'header' =>'游戏',
                         'type' => 'raw',
                         'value' => '$data["game_name"]'
                    ),
                    array(
                        'name' => 'name',
                        'header' =>'区服',
                        'type' => 'raw'
                    ),
                    array(
                         'header' => '充值金额',
                         'type' => 'number',
                         'value' => '$data["payment_amount"]'
                        ),
                    array(
                         'header' => '充值人数',
                         'type' => 'number',
                         'value' => '$data["payment_user"]'
                        ),
                    array(
                         'header' => '非第一次充值',
                         'type' => 'number',
                         'value' => '$data["payment_repaid"]'
                        ),
                    array(
                         'header' => '第一次充值',
                         'type' => 'number',
                         'value' => '$data["payment_increment"]'
                        ),
                    array(
                         'header' => '人均充值金额',
                         'type' => 'raw',
                         'value' => '$data["payment_avg_amount"]'
                        ),
                    array(
                         'header' => '充值笔数',
                         'type' => 'number',
                         'value' => '$data["payment_times"]'
                        ),
                    array(
                         'header' => '人均充值笔数',
                         'type' => 'raw',
                         'value' => '$data["payment_avg_times"]'
                        ),
                    array(
                         'header' => '1000~5000',
                         'type' => 'number',
                         'value' => '$data["vip1"]'
                        ),
                    array(
                         'header' => '>5000',
                         'type' => 'number',
                         'value' => '$data["vip2"]'
                        ),
                   )
        ));
    }

    public function actionVisitChart()
    {
      $baseDate=date('Y-m-d');
      $server=Server::model()->findBYSql('SELECT * FROM server ORDER by id desc limit 1');
      $serverId=$server->id;
      $gameId=$server->game_id;
      if(isset($_POST['server_id']))
      {
        $serverId=$_POST['server_id'];
      }
      if(isset($_POST['game_id']))
      {
        $gameId=$_POST['game_id'];
      }
      if(isset($_POST['date']))
      {
        $baseDate=$_POST['date'];
      }
      $this->render('chart',array('serverId'=>$serverId,'gameId'=>$gameId,'baseDate'=>$baseDate));
    }
}
?>
