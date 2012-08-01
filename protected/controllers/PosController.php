<?php
class PosController extends Controller
{
    private $_model;
    public function actionIndex()
    {
        $model = new Pos('search');
        if(!empty($_GET['Pos']))
        {
            $model->attributes = $_GET['Pos'];
            $model->adName=$_GET['Pos']['ad_name'];
            $model->gameId=$_GET['Pos']['game_id'];
            $model->serverId=$_GET['Pos']['server_id'];
        }

        $model_ad = new Ad;
        $data['title'] = '广告位列表';
        $data['page'] = isset($_GET['page'])? intval($_GET['page']) : 1;
        $this->render('index', array('model' => $model, 'model_ad' => $model_ad, 'data' => $data));
    }

    public function actionCreate()
    {
        $model = new Pos();
        if(!empty($_POST['Pos']))
        {
            if($_POST['Pos']['key'])
            {
                $sql = "SELECT id FROM pos WHERE `key` = '{$_POST['Pos']['key']}' OR id = '{$_POST['Pos']['key']}'";
                $row = Pos::model()->findBySql($sql);
                if($row)
                {
                    Yii::app()->user->setFlash('keyConflict', '广告位ID重复!');
                    $this->render('create', array('model' => $model, 'data' => $data));
                    exit;
                }
            }
            $model->attributes = $_POST['Pos'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg', '创建成功!');
                $this->redirect(array('index'));
            }
        }

        $data['title'] = '添加广告位';
        $this->render('create', array('model' => $model, 'data' => $data));
    }

    public function actionUpdate($id)
    {
        $model = $this->_loadModel($id);
        $data['title'] = '编辑广告位';
        if(!empty($_POST['Pos']))
        {
            $model->attributes = $_POST['Pos'];
            if($model->save())
            {
                Yii::app()->user->setFlash('successMsg', '编辑成功!');
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array('model' => $model, 'data' => $data));
    }

    public function actionDelete($id)
    {
        $model = $this->_loadModel($id);
        $model->delete();
        Yii::app()->user->setFlash('successMsg', '删除成功!');
        $this->redirect(array('index'));
    }

    public function actionChange()
    {
        $pos_ids = is_array($_POST['pos_id']) ? $_POST['pos_id'] : array($_POST['pos_id']);
        if(empty($pos_ids))
            exit('0');
        
        $bind_time = strtotime($_POST['bind_time']);
        if($bind_time && $bind_time - time() > 300)
        {
            $pos = Pos::model()->findAllBySql('SELECT * FROM pos WHERE id IN('. implode(',', $pos_ids). ')');
            if($pos)
            {
                $channel_ids = array();
                foreach ($pos as $p)
                {
                    $channel_ids[$p->id] = $p->channel_id;
                }
                $model = new AdPosSlot();
                foreach ($pos_ids as $pos_id)
                {
                    $model->setIsNewRecord(true);
                    $model->id = null;
                    $model->ad_id = $_POST['ad_id'];
                    $model->pos_id = $pos_id;
                    $model->channel_id = $channel_ids[$pos_id];
                    $model->bind_time = $bind_time;
                    $model->save();
                }
            }
        } else {
            $mList = AdPos::model()->findAllBySql('SELECT * FROM ad_pos WHERE pos_id IN('. implode(',', $pos_ids). ') AND active = 1');
            if($mList)
            {
                foreach ($mList as $m)
                {
                   $m->active = 0;
                   $m->debind_time = time();
                   $m->save();
                }
            }
    
            $pos = Pos::model()->findAllBySql('SELECT * FROM pos WHERE id IN('. implode(',', $pos_ids). ')');
            if($pos)
            {
                $channel_ids = array();
                foreach ($pos as $p)
                {
                    $channel_ids[$p->id] = $p->channel_id;
                }
                $model = new AdPos();
                foreach ($pos_ids as $pos_id)
                {
                    $model->setIsNewRecord(true);
                    $model->id = null;
                    $model->ad_id = $_POST['ad_id'];
                    $model->pos_id = $pos_id;
                    $model->channel_id = $channel_ids[$pos_id];
                    $model->active = 1;
                    $model->bind_time = time();
                    $model->save();
                }
            }
        }
        exit('1');
    }

    public function actionDisable($id)
    {
        $mList = AdPos::model()->findAllByAttributes(array('pos_id' => $id, 'active' => 1));
        if($mList)
        {
            foreach ($mList as $m)
            {
              $m->debind();
            }
        }

        $model = $this->_loadModel($id);
        $model->enable = 0;
        $model->save();
        Yii::app()->user->setFlash('successMsg', '关闭成功!');        
        exit('1');
    }

    public function actionEnable($id)
    {
        $model = $this->_loadModel($id);
        $model->updateByPk($model->getPrimaryKey(),array('enable'=>1));
        Yii::app()->user->setFlash('successMsg', '开启成功!');        
        exit('1');
    }
    
    public function actionSlot($id)
    {
        $pos = $this->_loadModel($id);
        if($pos)
        {
            $data = array();
            $data["title"] = $pos->id. " 排期";
            $model = new AdPosSlot();
            $model->pos_id = $id;
            $this->render('slot', array('model' => $model, 'data' => $data));
            Yii::app()->end();
        }
        $this->redirect('/pos/index');
    }
    
    public function actionSlotDelete($id)
    {
        $model = AdPosSlot::model()->findByPk($id);
        $pos_id = $model->pos_id;
        $model->delete();
        Yii::app()->user->setFlash('successMsg', '删除成功!');
        $this->redirect("/pos/slot/{$pos_id}");
    }

    private function _loadModel($id)
    {
        if($this->_model == null)
        {
            if($id)
            {
                $this->_model = Pos::model()->findByPk($id);
            }
            if($this->_model == null)
            {
                throw new CHttpException(404, "你请求的页面不存在");
            }
        }
        return $this->_model;
    }
}
