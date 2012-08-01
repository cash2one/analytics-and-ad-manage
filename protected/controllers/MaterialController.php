<?php
class MaterialController extends Controller
{
    public  $actionTitle='';
    
    public function actionIndex()
    {
       $this->actionTitle = '素材列表';
       $model=new Material('search');
       if(!empty($_GET['Material']))
       {
           $model->attributes=$_GET['Material'];
       }
        $this->render('index',array('model'=>$model));
    }
    
	public function actionCreate()
	{
	    $this->actionTitle = '创建素材';
		$model=new Material;

		if(isset($_POST['Material']))
		{
			$model->attributes=$_POST['Material'];
			if($model->save())
				$this->redirect(array('index','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	public function actionUpdate($id)
	{
	    $this->actionTitle = '更新素材';
		$model=$this->loadModel($id);

		if(isset($_POST['Material']))
		{
			$model->attributes=$_POST['Material'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function loadModel($id)
	{
		$model=Material::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='material-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
