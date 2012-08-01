<?php
class ChannelController extends Controller
{
    private $_model;
    public  $actionTitle='';


    public function actionIndex()
    {
        $model=new Channel('search');
	unset($model->pay_type);
        if(!empty($_GET['Channel']))
        {
            $model->attributes=$_GET['Channel'];
        }
        $this->actionTitle = '渠道列表';
        $this->render('index',array('model'=>$model));
    }

    public function actionCreate()
    {
        $model=new Channel;
        if(!empty($_POST['Channel']))
        {
            $model->attributes=$_POST['Channel'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','创建成功!');
                $this->redirect(array('index'));
            }
        }
        $this->actionTitle='添加渠道';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle));
    }

    public function actionView($id)
    {
        $channel=$this->_loadModel($id);
        if($channel)
        {
          $model=new ChannelCost;
          $model->channel_id=$id;
          $this->actionTitle='渠道成本列表';
          $this->render('view',array('model'=>$model,'channel'=>$channel,'title'=>$this->actionTitle));
        }
    }

    public function actionDaily()
    {
        if(isset($_GET['Channel']['id']))
        {
         $channel=$this->_loadModel($_GET['Channel']['id']);
        }
        else
        {
         $channel=$this->_loadModel(Channel::defChannel());
        }
        $from=strtotime('2012-01-01');
        $to=strtotime('-1 Day');
        if(isset($_GET['fromDate']))
        {
          $from=strtotime($_GET['fromDate']);
        }
        if(isset($_GET['toDate']))
        {
          $to=strtotime($_GET['toDate']);
        }
        $dataProvider=$channel->dailyList($from,$to);
        $this->render('daily',array('dataProvider'=>$dataProvider,'model'=>$channel,'from'=>$from,'to'=>$to));
    }

    public function actionDailyExport()
    {
        if(isset($_GET['Channel']['id']))
        {
         $channel=$this->_loadModel($_GET['Channel']['id']);
        }
        else
        {
         $channel=$this->_loadModel(Channel::defChannel());
        }
        $from=strtotime('2012-01-01');
        $to=strtotime('-1 Day');
        if(isset($_GET['fromDate']))
        {
          $from=strtotime($_GET['fromDate']);
        }
        if(isset($_GET['toDate']))
        {
          $to=strtotime($_GET['toDate']);
        }
        $dataProvider=$channel->dailyList($from,$to);
        $dataProvider->pagination=false;
        $this->render('_daily',array('dataProvider'=>$dataProvider,'model'=>$channel,'from'=>$from,'to'=>$to));
    }

    public function actionUpdate($id)
    {
        $model=$this->_loadModel($id);
        if(!empty($_POST['Channel']))
        {
            $model->attributes=$_POST['Channel'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','编辑成功!');
                $this->redirect(array('index'));
            }
        }
        $this->actionTitle='编辑渠道';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle));
    }

    public function actionUpdateCost($id,$date)
    {
        if($date>=ChannelCost::lastModify($id))
        {
            ChannelCost::updatNowCost($id,$_POST['cost']);
        }
        $model=ChannelCost::model()->findByAttributes(array('channel_id'=>$id,'date'=>$date));
        if(!$model)
        {
            $model=new ChannelCost;
            $model->channel_id=$id;
            $model->date=$date;
        }
        $model->cost=$_POST['cost'];
        $model->save();
    }

    public function actionUpdateCostQuick($id)
    {
        $channel=$this->_loadModel($id);
        $from=$_POST['from'];
        $to=$_POST['to'];
        $cost=$_POST['cost'];
        ChannelCost::batchUpdate($id,$from,$to,$cost);
    }

    public function actionDelete($id)
    {
        $model=$this->_loadModel($id);
        $model->delete();
        Yii::app()->user->setFlash('successMsg','删除成功!');
        $this->redirect(array('index'));
    }

    private function _loadModel($id)
    {
       if($this->_model==null)
       {
         if($id)
         {
             $this->_model=Channel::model()->findByPk($id);
         }
         if($this->_model==null)
         {
             throw new CHttpException(404,"你请求的页面不存在");
         }
       }
       return $this->_model;
    }
}
?>
