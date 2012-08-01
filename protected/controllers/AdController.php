<?php
class AdController extends Controller
{
    private $_model;
    public  $actionTitle='';

    public function actionIndex()
    {
        $model=new Ad('search');
        if(!empty($_GET['Ad']))
        {
            $model->attributes=$_GET['Ad'];
        }
        $this->actionTitle = '广告列表';
        $this->render('index',array('model'=>$model));
    }

    public function actionView($id)
    {
        $model=$this->_loadModel($id);
        if(empty($model->path))
        {
            throw new CHttpException(404, "此广告已过期");
        }
        $this->redirect('/adcontent/'.$model->path);
    }
    public function actionDaily()
    {
        $material=isset($_GET['material'])?$_GET['material']:Ad::defMaterial();
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
        $dataProvider=Ad::dailyList($from,$to,$material);
        $this->render('daily',array('dataProvider'=>$dataProvider,'from'=>$from,'to'=>$to,'material'=>$material));
    }

    public function actionDailyExport()
    {
        $material=isset($_GET['material'])?$_GET['material']:Ad::defMaterial();
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
        $dataProvider=Ad::dailyList($from,$to,$material);
        $dataProvider->pagination=false;
        $this->render('_daily',array('dataProvider'=>$dataProvider,'from'=>$from,'to'=>$to,'material'=>$material));
    }

    public function actionCreate()
    {
        $model=new Ad;
        if(!empty($_POST['Ad']))
        {
            $model->attributes=$_POST['Ad'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','创建成功!');
                $this->redirect(array('index'));
            }
        }
        $this->actionTitle='添加广告';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle));
    }

    public function actionbatchCreate()
    {
        $model=new Ad;
        if(!empty($_POST['Ad']))
        {
            if(isset($_POST['Ad']['key']) && $_POST['Ad']['key'])
            {
              foreach($_POST['Ad']['key'] as $key)
              {
                $ad=new Ad;
                $ad->attributes=$_POST['Ad'];
                $ad->name=$_POST['Ad']['name'][$key];
                $ad->path=$key;
                if($ad->validate())
                {
                  $ad->save();
                }
              }
              Yii::app()->user->setFlash('successMsg','批量创建成功!');
              $this->redirect(array('index'));
            }
        }
        $keyword=null;
        if(isset($_GET['ajax']))
        {
           $keyword=$_GET['Ad']['name'];
           $model->name=$keyword;
        }
        $this->actionTitle='批量添加广告';
        $dataProvider=Ad::listContentDataProvider($keyword);
        $this->render('batchCreate',array('model'=>$model,'dataProvider'=>$dataProvider,'title'=>$this->actionTitle));
    }

     public function actionUpdate($id)
    {
        $model=$this->_loadModel($id);
        if(!empty($_POST['Ad']))
        {
            $model->attributes=$_POST['Ad'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','编辑成功!');
                $this->redirect(array('index'));
            }
        }
        $this->actionTitle='编辑广告';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle));
    }

    public function actionDelete()
    {
        if(isset($_GET['id']))
        {
            $id=$_GET['id'];
        }
        else
        {
            $id=$_POST['id'];
        }

        if(!is_array($id))
        {
         $model=$this->_loadModel($id);
         $model->delete();
        }
        else
        {
          foreach($id as $i)
          {
              $model=Ad::model()->findByPk($i);
              $model->delete();
          }
        }
        Yii::app()->user->setFlash('successMsg','删除成功!');
        $this->redirect(array('index'));
    }

    private function _loadModel($id)
    {
       if($this->_model==null)
       {
         if($id)
         {
             $this->_model=Ad::model()->findByPk($id);
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
