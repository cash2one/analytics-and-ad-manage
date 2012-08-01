<?php
class PackageController extends Controller
{
    public $actionTitle='';
    
    public function actionIndex()
    {
        $model = new Package('search');
        if(!empty($_GET['Package']))
        {
            $model->attributes = $_GET['Package'];
        }
        $this->actionTitle = '礼包列表';
        $this->render('index', array('model' => $model));
    }
    
	public function actionCreate()
	{
        $model = new Package();
        if(isset($_POST['Package']))
        {
            $model->attributes = $_POST['Package'];
            if($model->save())
                $this->redirect(array('index'));
        }
        $this->actionTitle = '添加礼包';
        $this->render('create', array('model' => $model));
	}

	public function actionUpdate($id)
	{
        $model = $this->loadModel($id);
        if(isset($_POST['Package']))
        {
            $model->attributes = $_POST['Package'];
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
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function loadModel($id)
	{
		$model=Package::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='package-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
