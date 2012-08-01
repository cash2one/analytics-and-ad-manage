<?php
class ServerController extends Controller
{
    private $_model;
    public  $actionTitle='';

    public function actionIndex()
    {
          $model=new Server('search');
          if(!empty($_GET['Server']))
          {
              $model->attributes=$_GET['Server'];
          }
          $this->actionTitle='区服列表';
          $this->render('index',array('model'=>$model));
    }

    public function actionCreate()
    {
        $model=new Server;
        if(!empty($_POST['Server']))
        {
            $model->attributes=$_POST['Server'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','创建成功!');
                $this->redirect(array('index'));
            }
            $model->promote_end_time=date('Y-m-d',$model->promote_end_time);
            $model->open_time=date('Y-m-d',$model->open_time);
        }
        $this->actionTitle='添加区服';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle));
    }

     public function actionUpdate($id)
    {
        $model=$this->_loadModel($id);
        if(!empty($_POST['Server']))
        {
            $model->attributes=$_POST['Server'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','编辑成功!');
                $this->redirect(array('index'));
            }
        }
        $model->promote_end_time=date('Y-m-d',$model->promote_end_time);
        $model->open_time=date('Y-m-d',$model->open_time);
        $this->actionTitle='编辑区服';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle));
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
             $this->_model=Server::model()->findByPk($id);
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
