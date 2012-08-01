<?php
class SortTypeController extends Controller
{
    public  $actionTitle='';
    
    public function actionIndex()
    {
        $model = new SortType('search');
        if(!empty($_GET['SortType']))
        {
            $model->attributes = $_GET['SortType'];
        }
        $this->actionTitle = '游戏规则列表';
        $this->render('index', array('model' => $model));
    }
      
    public function actionCreate()
    {
        $model = new SortType();
        if(isset($_POST['SortType']))
        {
            $model->attributes = $_POST['SortType'];
            if($model->save())
                $this->redirect(array('index'));
        }
        $this->actionTitle = '添加新规则';
        $this->render('create', array('model' => $model));
    }
    
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if(isset($_POST['SortType']))
        {
            $model->attributes = $_POST['SortType'];
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
    
    public function actionSort($id)
    {
        if(isset($_POST['sort_type_id']) && isset($_POST['sort']))
        {
            if(Sort::updateSort($_POST['sort_type_id'], $_POST['sort']))
            {
                Yii::app()->user->setFlash('successMsg','更新成功!');
            }
        }
        $data['sort'] = Sort::getSort($id);
        $data['sort_type_id'] = $id;
        $this->actionTitle = '游戏排序';
        $this->render('//sort/index', array('data' => $data));
    }
    
    public function actionChoose()
    {
        if(isset($_POST['forum']))
        {
            foreach ($_POST['forum'] as $id => $sort_type_id)
            {
                Forum::updateForum($id, $sort_type_id);
            }
            Yii::app()->user->setFlash('successMsg','更新成功!');
        }
        $data['forum'] = Forum::getForum();
        $this->render('choose', array('data' => $data));
    }
    
    public function loadModel($id)
    {
        $model = SortType::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
