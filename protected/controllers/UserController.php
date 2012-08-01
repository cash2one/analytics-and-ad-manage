<?php
class UserController extends Controller
{

    public function actionIndex()
    {
        $model = new User('search');
        $model->startDate = date('Y-m-d', strtotime('-1 month'));
        $model->endDate = date('Y-m-d');
        if(!empty($_GET['User']))
        {
            $model->attributes = $_GET['User'];
            $model->ChannelId = $_GET['User']['channel_id'];
            $model->startDate = $_GET['start_date'] ? $_GET['start_date'] : $model->startDate;
            $model->endDate = $_GET['end_date'] ? $_GET['end_date'] : $model->endDate;
            $model->IP = $_GET['User']['ip'];
            $model->hasEmail = $_GET['User']['has_email'];
            $model->hasCardid = $_GET['User']['has_cardid']; // 防沉迷
        }
        $this->render('index', array('model' => $model));
    }

    public function actionGame($id)
    {
        $model = Visit::model();
        $model->user_id = $id;
        $model->startDate = date('Y-m-d', strtotime('-1 year'));
        $model->endDate = date('Y-m-d');
        if(!empty($_GET['Visit']))
        {
            $model->attributes = $_GET['Visit'];
            $model->startDate = $_GET['start_date'] ? $_GET['start_date'] : $model->startDate;
            $model->endDate = $_GET['end_date'] ? $_GET['end_date'] : $model->endDate;
        }
        $this->render('game', array('model' => $model));
    }

    public function actionInfo($id)
    {
        $model = User::model()->findByPk($id);
        if(empty($model))
        {
            throw new CHttpException(404, "你请求的页面不存在");
        }
        $data['title'] = '用户个人资料';
        $data['gender'] = array(0 => '--', 1 => '男', 2=> '女');
        $this->render('info', array('model' => $model, 'data' => $data));
    }

    public function actionExportUser()
    {
        $data['startDate'] ="2011-09-01";
        $data['endDate']   = date('Y-m-d');
        if(!empty($_POST['user']))
        {
            extract($_POST['user']);
            $from=strtotime($start_date);
            $to=strtotime($end_date);
            $dataProvider=UserServer::userList($server_id,$channel_id,$from,$to);
            $this->widget('ext.EExcelView',array(
                    'dataProvider' => $dataProvider,
                    'columns' => array(
                        array(
                            'name' => 'user_name',
                            'header' => '用户名',
                            'type' => 'raw',
                            ),
                         array(
                            'name' => 'login_times',
                            'header' => '登录次数',
                            'type' => 'raw',
                            )
                     )));
            exit();
        }
        $this->render('_exportUser',$data);
    }

    public function actionChangemail($id)
    {
        $model = User::model()->findByPk($id);
        if(empty($model))
        {
            throw new CHttpException(404, "你请求的页面不存在");
        }
        $er = new EmailRecord();
        if($_POST)
        {
            Yii::import('application.vendors.*');
            include_once('ucenter.php');
            $ucresult = uc_user_edit($model->user_name, '', '', $_POST['email'], 1);
            if(in_array($ucresult, array(-7, 0, 1)))
            {
                $er->user_id = $model->id;
                $er->user_name = $model->user_name;
                $er->past_email = $_POST['past_email'];
                $er->email = $_POST['email'];
                if($er->save())
                {
                    empty($model->salt) ? $model->salt = md5(uniqid()) : '';
                    $model->email = $_POST['email'];
                    if($model->save())
                    {
                       Yii::app()->user->setFlash('successMsg', '修改成功!');
                    } else {
                        Yii::app()->user->setFlash('Error', '修改失败!');
                    }
                } else {
                    Yii::app()->user->setFlash('Error', '修改失败!');
                }
            } else {
                if($ucresult==-8)
                {
                    Yii::app()->user->setFlash('Error','此用户受保护!');
                }
                else
                {
                    Yii::app()->user->setFlash('Error','同步uchome邮箱失败');
                }
           }
        }
        $data['er'] = $er;
        $data['title'] = '修改邮箱';
        $this->render('changemail', array('model' => $model, 'data' => $data));
    }

    public function actionEmailRecord()
    {
        $model = new EmailRecord('search');
        $model->startDate = date('Y-m-d');
        $model->endDate = date('Y-m-d');
        if(!empty($_GET['EmailRecord']))
        {
            $model->attributes = $_GET['EmailRecord'];
            $model->startDate = $_GET['start_date'] ? $_GET['start_date'] : $model->startDate;
            $model->endDate = $_GET['end_date'] ? $_GET['end_date'] : $model->endDate;
        }
        $this->render('emailrecord', array('model' => $model));
    }

    public function actionAppeal()
    {
        $model=new Appeal;
        $model->status=0;
        $model->startDate = date('Y-m-d',strtotime("-1 month"));
        $model->endDate = date('Y-m-d',strtotime("+1 day"));
        if(!empty($_GET['Appeal']))
        {
          $model->attributes=$_GET['Appeal'];
          $model->startDate = $_GET['start_date'] ? $_GET['start_date'] : $model->startDate;
          $model->endDate = $_GET['end_date'] ? $_GET['end_date'] : $model->endDate;
        }
        $this->render('appeal',array('model'=>$model));
    }

    public function actionAppealView($id)
    {
        if($model=Appeal::model()->findByPk($id))
        {
            $this->render('appealView',array('model'=>$model,'title'=>'用户申诉'));
        }
        else
        {
          throw new CHttpException(404,"你请求的页面不存在");
        }
    }

    public function actionAppealDelete($id)
    {
        if($model=Appeal::model()->findByPk($id))
        {
           $model->status=3;
           $model->update();
           Yii::app()->user->setFlash('successMsg','删除成功!');
           $this->redirect(array('appeal'));
        }
        else
        {
          throw new CHttpException(404,"你请求的页面不存在");
        }

    }

    public function actionAppealProcess($id)
    {
        if($model=Appeal::model()->findByPk($id))
        {
            if(!empty($_POST))
            {
                $username=$model->user_name;
                $email=$model->contact_email;
                if(!empty($_POST['status']) && $_POST['status']==1)
                {
                    $user = User::model()->findByAttributes(array('user_name' => $username));
                    if($user)
                    {
                         Yii::import('application.vendors.*');
                         include_once('ucenter.php');
                         $newpw='';
                         $string='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
                         for($i=0;$i<8;$i++)
                         {
                             $newpw .= $string[rand(0, 61)];
                         }
                         $ucresult = uc_user_edit($username, '', $newpw, '', 1);
                         if($ucresult == 1)
                         {
                            $user->salt = md5(uniqid());
                            $user->pwd = md5(md5($newpw). $user->salt);
                            if($user->save())
                            {
                                $message=new YiiMailMessage;
                                $message->setSubject("密码申诉成功");
                                $message->view='pwd';
                                $message->setBody(array("username"=>$username,'password'=>$newpw),'text/html');
                                $message->addTo($email);
                                $message->from="service@2133.com";
                                Yii::app()->mail->send($message);
                                Yii::app()->user->setFlash('successMsg','处理成功!');
                                $model->status=1;
                                $model->save();                            
                            }
                         } else {                         
                                if($ucresult==-8)
                                {
                                    Yii::app()->user->setFlash('errorMsg','此用户受保护!');
                                }
                                else
                                {
                                    Yii::app()->user->setFlash('errorMsg','同步uchome密码失败');
                                }
                           }
                    } else {
                        Yii::app()->user->setFlash('errorMsg','此账户不存在!');
                    }
                }
                else if(!empty($_POST['status']) && $_POST['status']==2)
                {
                    $message=new YiiMailMessage;
                    $message->setSubject("密码申诉失败");
                    $message->view='reject';
                    $message->setBody(array("reject"=>$_POST['reject'],"username"=>$username),'text/html');
                    $message->addTo($email);
                    $message->from="service@2133.com";
                    Yii::app()->mail->send($message);
                    $model->reject_message=$_POST['reject'];
                    $model->status=2;
                    $model->save();
                    Yii::app()->user->setFlash('successMsg','处理成功!');
                }
            }
            $this->redirect(array('appeal'));
        }
        else
        {
            throw new CHttpException(404,"你请求的页面不存在");
        }
    }
    
    public function actionVip()
    {
        $model = new VipUser('search');
        $data = array();
        if($_GET['payTime'])
        {
            $model->last_paid_time = strtotime($_GET['payTime']);
            $data['payTime'] = $_GET['payTime'];
        }
        if(!empty($_GET['VipUser']))
        {
            $model->attributes = $_GET['VipUser'];
        }
        $this->render('vip', array('model' => $model, 'data' => $data));
    }
    
    public function actionExportVip()
    {
        $model = new VipUser('search');
        $data = array();
        if($_GET['payTime'])
        {
            $model->last_paid_time = strtotime($_GET['payTime']);
            $data['payTime'] = $_GET['payTime'];
        }
        if(!empty($_GET['VipUser']))
        {
            $model->attributes = $_GET['VipUser'];
        }
        $data['dataProvider'] = $model->vipList();
        $this->render('_exportVip',$data);
    }
}
