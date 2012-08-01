 <?php
class GameController extends Controller
{
    private $_model;
    public  $actionTitle='';


    public function actionIndex()
    {
       $model=new Game('search');
       if(!empty($_GET['Game']))
       {
           $model->attributes=$_GET['Game'];
       }
       $this->actionTitle='游戏列表';
       $this->render('index',array('model'=>$model));
    }

    public function actionCreate()
    {
        $model=new Game;
        if(!empty($_POST['Game']))
        {
            $model->attributes=$_POST['Game'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','创建成功!');
                $this->redirect(array('index'));
            }
        }
        $this->actionTitle='添加新游戏';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle));
    }

    public function actionView($id)
    {
        $game=$this->_loadModel($id);
        if($game)
        {
          $model=new GameShare;
          $model->game_id=$id;
          $model->monthList();
          $this->actionTitle='游戏分成列表';
          $this->render('view',array('model'=>$model,'game'=>$game,'title'=>$this->actionTitle));
        }
    }

    public function actionUpdate($id)
    {
        $model=$this->_loadModel($id);
        if(!empty($_POST['Game']))
        {
            $model->attributes=$_POST['Game'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','编辑成功!');
                $this->redirect(array('index'));
            }
        }
        $this->actionTitle='编辑游戏';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle));
    }

    public function actionUpdateShare($id,$date)
    {
        if($date>=GameShare::lastModify($id))
        {
            GameShare::updatNowShare($id,$_POST['share']);
        }

        $model=GameShare::model()->findByAttributes(array('game_id'=>$id,'date'=>$date));
        if(!$model)
        {
            $model=new GameShare;
            $model->game_id=$id;
            $model->date=$date;
        }
        $model->share=$_POST['share'];
        $model->save();
    }

    public function actionUpdateShareQuick($id)
    {
        $game=$this->_loadModel($id);
        $from=$_POST['from'];
        $to=$_POST['to'];
        $share=$_POST['share'];
        GameShare::batchUpdate($id,$from,$to,$share);
    }

    public function actionDelete($id)
    {
        $model=$this->_loadModel($id);
        $model->delete();
        Yii::app()->user->setFlash('successMsg','删除成功!');
        $this->redirect(array('index'));
    }
        
    public function actionList()
    {
        $model=new Game('search');
        if(!empty($_GET['Game']))
        {
            $model->attributes=$_GET['Game'];
        }
        $this->render('list',array('model'=>$model));
    }
    
    public function actionEdit($id)
    {
        $model=$this->_loadModel($id);
        if(!empty($_POST['Game']))
        {
            $model->attributes=$_POST['Game'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg','编辑成功!');
                $this->redirect(array('list'));
            }
        }
        $this->actionTitle='编辑游戏';
        $this->render('add',array('model'=>$model,'title'=>$this->actionTitle));
    }

    private function _loadModel($id)
    {
       if($this->_model==null)
       {
         if($id)
         {
             $this->_model=Game::model()->findByPk($id);
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
