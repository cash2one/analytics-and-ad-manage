<?php
class UserRcdController extends Controller
{
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function actionIndex()
	{
		$model = new UserRcd();
		if(!empty($_GET['UserRcd']))
		{
		    $model->attributes = $_GET['UserRcd'];
		}
		$this->render('index', array('model' => $model));
	}
	
	public function loadModel($id)
	{
		$model = UserRcd::model()->findByPk($id);
		if($model === null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
