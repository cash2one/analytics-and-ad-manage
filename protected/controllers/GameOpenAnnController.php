<?php
class GameOpenAnnController extends Controller
{
    public function actionIndex()
    {
       $model=new GameOpenAnn('search');
       if(!empty($_GET['GameOpenAnn']))
       {
           $model->attributes=$_GET['GameOpenAnn'];
           var_dump($_GET['GameOpenAnn']);
           var_dump($model->getAttributes('status'));
       }
        $this->render('index',array('model'=>$model));
    }
    
	public function actionCreate()
	{
		$model=new GameOpenAnn;

		if(isset($_POST['GameOpenAnn']))
		{
			$model->attributes=$_POST['GameOpenAnn'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['GameOpenAnn']))
		{
			$model->attributes=$_POST['GameOpenAnn'];
			if($model->save())
				$this->redirect(array('index'));
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

			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function loadModel($id)
	{
		$model=GameOpenAnn::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
