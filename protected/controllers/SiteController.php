<?php
class SiteController extends Controller
{
    protected function beforeAction($action)
    {
        $ignoreArray=array('login','error','sucai','dropDownAd','dropDownServer','logout','export','exportView','analyzeExport','hourView');
        if(parent::beforeAction($action))
        {
            if(in_array($action->id,$ignoreArray) || Yii::app()->getAuthManager()->checkAccess($this->id.ucfirst($action->id),Yii::app()->user->getName()))
            {
             $cs=Yii::app()->getClientScript();
             $cs->registerCssFile('/css/screen.css');
             $cs->registerCoreScript('jquery');
             $cs->registerScriptFile('/js/global.js',CClientScript::POS_END);
             return true;
            }
            else
            {
              $this->redirect(array('login'));
            }
        }
    }

    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    public function actionIndex()
    {
        $model=new AdPos();
        $baseDate = date('Y-m-d');
        if(!empty($_POST['date']))
        {
            $baseDate = $_POST['date'];
        }
        $this->render('index',  array('model' => $model, 'baseDate' => $baseDate));
    }

    public function actionStat()
    {
        $model=new AdPos('search');
        if(!empty($_GET['AdPos']))
        {
            $model->channel_id=$_GET['AdPos']['channel_id'];
            $model->ad_id=$_GET['AdPos']['ad_id'];
            $model->gameId = $_GET['AdPos']['game_id'];
            $model->serverId = $_GET['AdPos']['server_id'];
        }
        $date=date('Y-m-d');
        if(!empty($_GET['date']))
        {
            $date=$_GET['date'];
        }
        $data['model']=$model;
        $data['date']=$date;
        $this->render('stat',$data);
    }

    public function actionHourView($from,$to)
    {
        if($to-$from<3600)
        {
          throw new CHttpException(500,"参数不正确");
        }
        $model=new AdPos('search');
        if(!empty($_GET['AdPos']))
        {
            $model->channel_id=$_GET['AdPos']['channel_id'];
            $model->ad_id=$_GET['AdPos']['ad_id'];
            $model->gameId = $_GET['AdPos']['game_id'];
            $model->serverId = $_GET['AdPos']['server_id'];
            $model->pos_id=$_GET['AdPos']['pos_id'];
        }
        $this->render('statView',array('model'=>$model,'from'=>$from,'to'=>$to));
    }

    public function actionAnalyzeDaily()
    {
       $model=new AdPos('search');
       $model->channel_id=Channel::defChannel();
       if(!empty($_GET['AdPos']))
       {
           $model->channel_id=$_GET['AdPos']['channel_id'];
           $model->pos_id = $_GET['AdPos']['pos_id'];
           $model->ad_id=$_GET['AdPos']['ad_id'];
           $model->gameId = $_GET['AdPos']['game_id'];
           $model->serverId = $_GET['AdPos']['server_id'];
       }
       $date=date('Y-m-d');
       if(!empty($_GET['date']))
       {
           $date=$_GET['date'];
       }
       $data['model']=$model;
       $data['date']=$date;
       $this->render('analyze',$data);
    }

    public function actionExport()
    {
        $model=new AdPos('search');
        if(!empty($_GET['AdPos']))
        {
            $model->channel_id=$_GET['AdPos']['channel_id'];
            $model->ad_id=$_GET['AdPos']['ad_id'];
            $model->gameId = $_GET['AdPos']['game_id'];
            $model->serverId = $_GET['AdPos']['server_id'];
        }
        $date=date('Y-m-d');
        if(!empty($_GET['date']))
        {
            $date=$_GET['date'];
        }
        $dataProvider=$model->statHourAnalytics($date);
        $dataProvider->pagination=false;
        $this->widget('ext.EExcelView', array(
        'dataProvider' =>$dataProvider,
        'columns' => array(
           array(
               'header'=>'时间段',
               'type' => 'raw',
               'value' => '$data["hourFrom"]. "-". $data["hour"]',
           ),
           array(
              'header' => '点击数',
              'type' => 'raw',
              'value' => 'Click::countClickByAdPos($data["id"], $data["from"],$data["to"],"hour")'
           ),
           array(
              'header' => '注册人数',
              'type' => 'raw',
              'value' => 'User::countRegisterByAdPos($data["id"],$data["from"],$data["to"],"hour")'
           ),
          )
        ));
    }

    public function actionExportView($from,$to)
    {
        if($to-$from<3600)
        {
          throw new CHttpException(500,"参数不正确");
        }
        $model=new AdPos('search');
        if(!empty($_GET['AdPos']))
        {
            $model->channel_id=$_GET['AdPos']['channel_id'];
            $model->ad_id=$_GET['AdPos']['ad_id'];
            $model->gameId = $_GET['AdPos']['game_id'];
            $model->serverId = $_GET['AdPos']['server_id'];
            $model->pos_id=$_GET['AdPos']['pos_id'];
        }
        $dataProvider=$model->hourDetail($from,$to);
        $dataProvider->pagination=false;
        $this->widget('ext.EExcelView', array(
        'dataProvider' =>$dataProvider,
        'columns' => array(
           array(
                'name'=>'id',
                'header'=>'绑定ID',
                'type'=>'raw',
               ),
           array(
                'name'=>'pos_id',
                'header'=>'广告位id',
                'type'=>'raw',
               ),
           array(
                 'header' =>'渠道',
                 'type' => 'raw',
                 'value' => 'Channel::getName($data["channel_id"])'
           ),
           array(
               'header' => '广告位',
               'type' => 'raw',
               'value' => 'Pos::getName($data["pos_id"])'
           ),
           array(
                 'name'=>'ad_id',
                 'header' =>'广告',
                 'type' => 'raw',
                 'value' => '$data["ad_name"]'
            ),
            array(
                 'header' =>'游戏',
                 'type' => 'raw',
                 'value' => 'Game::getName($data["game_id"])'
            ),
            array(
                 'header' =>'区服',
                 'type' => 'raw',
                 'value' => 'Server::getName($data["server_id"])'
            ),
            array(
                 'header' => '点击数',
                 'type' => 'raw',
                 'value' => '$data["click_times"]'
            ),
            array(
             'header' => '注册人数',
             'type' => 'raw',
             'value' => '$data["register_times"]'
            ),
            array(
             'header' => '注册率',
             'type' => 'raw',
             'value' => '$data["pv"]'
             ),
            array(
             'header' => '昨日点击数',
             'type' => 'raw',
             'value' => '$data["y_click_times"]'
            ),
            array(
             'header' => '昨日注册人数',
             'type' => 'raw',
             'value' => '$data["y_register_times"]'
            ),
            array(
             'header' => '昨日注册率',
             'type' => 'raw',
             'value' => '$data["y_pv"]'
             )
           )
        ));
    }

    public function actionAnalyzeExport()
    {
       $model=new AdPos('search');
       $model->channel_id=Channel::defChannel();
       if(!empty($_GET['AdPos']))
       {
           $model->channel_id=$_GET['AdPos']['channel_id'];
           $model->pos_id = $_GET['AdPos']['pos_id'];
           $model->ad_id=$_GET['AdPos']['ad_id'];
           $model->gameId = $_GET['AdPos']['game_id'];
           $model->serverId = $_GET['AdPos']['server_id'];
       }
       $date=date('Y-m-d');
       if(!empty($_GET['date']))
       {
           $date=$_GET['date'];
       }
       $dataProvider=$model->dailyList($date);
       $dataProvider->pagination=false;
       $this->widget('ext.EExcelView', array(
                   'dataProvider' =>$dataProvider,
                   'columns' => array(
                       array(
                           'header'=>'时间段',
                           'type' => 'raw',
                           'value' => '$data["hourFrom"]. "-". $data["hour"]',
                           ),
                       array(
                           'name'=>'pos_id',
                           'header'=>'广告位id',
                           'type'=>'raw',
                           ),
                       array(
                           'header' =>'渠道',
                           'type' => 'raw',
                           'value' => 'Channel::getName($data["channel_id"])'
                           ),
                       array(
                           'header' => '广告位',
                           'type' => 'raw',
                           'value' => 'Pos::getName($data["pos_id"])'
                           ),
                       array(
                               'name'=>'ad_id',
                               'header' =>'广告',
                               'type' => 'raw',
                               'value' => '$data["ad_name"]'
                            ),
                       array(
                               'header' =>'游戏',
                               'type' => 'raw',
                               'value' => 'Game::getName($data["game_id"])'
                            ),
                       array(
                               'header' =>'区服',
                               'type' => 'raw',
                               'value' => 'Server::getName($data["server_id"])'
                            ),
                       array(
                               'header' => '点击数',
                               'type' => 'raw',
                               'value' => 'Click::countClickByAdPos($data["id"],$data["from"],$data["to"],"hour")'
                            ),
                       array(
                             'header' => '注册率',
                             'type' => 'raw',
                             'value' => 'sprintf("%01.2f",User::countRegisterByAdPos($data["id"],$data["from"],$data["to"],"hour")/Click::countClickByAdPos($data["id"],$data["from"],$data["to"],"hour") * 100). "%"'
                            ),
                       array(
                               'header' => '注册人数',
                               'type' => 'raw',
                               'value' => 'User::countRegisterByAdPos($data["id"],$data["from"],$data["to"],"hour")'
                            ),
                       array(
                               'header' => '昨日注册人数',
                               'type' => 'raw',
                                'value' => 'User::countRegisterByPos($data["pos_id"],$data["from"]-86400,$data["to"]-86400,"hour")'
                            ),
                       array(
                               'header' => '同比增长率',
                               'type' => 'raw',
                               'value' => 'User::countRegisterByPos($data["pos_id"],$data["from"]-86400,$data["to"]-86400,"hour")?sprintf("%01.2f",(User::countRegisterByAdPos($data["id"],$data["from"],$data["to"],"hour")/User::countRegisterByPos($data["pos_id"],$data["from"]-86400,$data["to"]-86400,"hour")-1)*100). "%":0'
                            ),
                       )
                           ));
    }
    public function actionSucai()
    {
        $this->render('sucai');
    }

    public function actionLogin()
    {
        $model=new LoginForm;
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            if($model->validate() && $model->login())
            {
                 $admin=Admin::model()->findByPk(Yii::app()->user->getId());
                 $this->redirect('/'. $admin->getHomeUrl());
            } else {
                Yii::app()->user->setFlash('ErrorMsg', '用户名或者密码错误!');
            }
        }
        $this->renderPartial('//site/login',array('model'=>$model));
    }


    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionDropDownAd($server_id)
    {
        if($server_id)
        {
            die(CJSON::encode(Ad::DropDownData($server_id)));
        }
    }

    public function actionDropDownServer($game_id)
    {
        if($game_id)
        {
          die(CJSON::encode(Server::DropDownData($game_id)));
        }
    }
}
