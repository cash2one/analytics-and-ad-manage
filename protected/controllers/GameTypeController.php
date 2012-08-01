<?php
class GameTypeController extends Controller
{
    public  $actionTitle='';
    
    public function actionIndex()
    {
        $model = new GameType('search');
        if(!empty($_GET['GameType']))
        {
            $model->attributes = $_GET['GameType'];
        }
        $this->actionTitle = '游戏类别列表';
        $this->render('index', array('model' => $model));
    }
      
    public function actionCreate()
    {
        $model = new GameType();
        if(isset($_POST['GameType']))
        {
            $model->attributes = $_POST['GameType'];
            if($model->save())
                $this->redirect(array('index'));
        }
        $this->actionTitle = '添加新游戏类别';
        $this->render('create', array('model' => $model));
    }
    
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if(isset($_POST['GameType']))
        {
            $model->attributes = $_POST['GameType'];
            if($model->save())
                $this->redirect(array('index'));
        }
        
        $this->render('create', array('model' => $model));
    }
    
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $this->loadModel($id)->delete();
            
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
    
    public function loadModel($id)
    {
        $model = GameType::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
