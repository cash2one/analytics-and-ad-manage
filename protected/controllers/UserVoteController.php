<?php
class UserVoteController extends Controller
{
    public  $actionTitle='';
    
    public function actionIndex()
    {
        $model = new Game();
        $this->render('index', array('model' => $model));
    }
    
    public function actionList()
    {
        $model = new UserVote();
        if(!empty($_GET['UserVote']))
        {
            $model->attributes = $_GET['UserVote'];
        }
        $this->render('list', array('model' => $model));
    }

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
		if(isset($_POST['UserVote']))
		{
			$model->attributes=$_POST['UserVote'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
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
		$model=UserVote::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
