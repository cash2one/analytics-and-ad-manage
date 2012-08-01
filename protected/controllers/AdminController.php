<?php
class AdminController extends Controller
{
    private $_model;
    public  $actionTitle='';

     public function filters()
    {
       return  array('accessControl');
    }

    public function accessRules()
    {
      return array(
              array('allow','expression'=>'in_array(Yii::app()->user->getId(), array(1, 4, 49))'),
              array('allow','actions'=>array('update'),'users'=>array('@')),
              array('deny','users'=>array('*'),'message'=>'权限不足')
              );
    }

    public function actionIndex()
    {
        $model=new Admin('search');
        if(!empty($_GET['Admin']))
        {
            $model->attributes=$_GET['Admin'];
        }

        $this->render('index',array('model'=>$model));
    }

    public function actionAssign($id)
    {
        $model = new AssignForm();
        $auth=Yii::app()->authManager;
        $admin=Admin::model()->findByPk($id);
        if(!$admin)
        {
          throw new CHttpException('404','访问的页面不存在');
        }
        $model->roles=$admin->assignedRoles();
        $model->items=$admin->assignedOperations();
        $model->userid=$id;
        if(!empty($_POST['AssignForm']))
        {
            $oldRoles=$model->roles;
            $oldItems=$model->items;
            $model->attributes=$_POST['AssignForm'];
            if($model->validate())
            {
                $admin->setData($model->assign($oldRoles,$oldItems,$admin->name));
                Yii::app()->user->setFlash('successMsg','配置权限成功!');
                $this->redirect(array('index'));
            }
        }
        $dropDownDataItems=array();
        $dropDownDataRoles=array();

        if($items=$auth->getAuthItems(2))
        {
          foreach($items as $k=>$item)
          {
              $dropDownDataRoles[$k]=$item->description;
          }
        }

        if($items=$auth->getAuthItems(0))
        {
          foreach($items as $k=>$item)
          {
              $dropDownDataItems[$k]=$item->description;
          }
        }
        $this->actionTitle = '配置权限';
        $this->render('assign', array('model' => $model
                    ,'dropDownDataItems'=>$dropDownDataItems
                    ,'dropDownDataRoles'=>$dropDownDataRoles
                    ,'title' => $this->actionTitle
                    ));
    }

    public function actionRoleList()
    {
        $auth=Yii::app()->authManager;
        $data=array();
        if($roles=$auth->getAuthItems(2))
        {
            foreach($roles as $k=>$role)
            {
                $children='';
                if($role->children)
                {
                   foreach($role->children as $item)
                   {
                     $children.=$item->description.',';
                   }
                   $children=rtrim($children,',');
                }
                $data[]=array(
                         'id'=>$k
                        ,'name'=>$role->name
                        ,'description'=>$role->description
                        ,'children'=>$children
                    );
            }
        }
        $dataProvider=new CArrayDataProvider($data,array(
                    'pagination'=>false
        ));
        $this->render('roleList',array('dataProvider'=>$dataProvider));
    }

    public function actionUpdateRole($name)
    {
        $model = new RoleForm();
        $auth=Yii::app()->authManager;
        $role=$auth->getAUthItem($name);
        if(!$role)
        {
          throw new CHttpException('404','访问的页面不存在');
        }
        $model->name=$role->name;
        $model->description=$role->description;
        $items=array();
        if($role->children)
        {
            foreach($role->children as $operation)
            {
              $items[]=$operation->name;
            }
        }
        $model->items=$items;
        if(!empty($_POST['RoleForm']))
        {
            $oldItems=$model->items;
            $model->attributes=$_POST['RoleForm'];
            if($model->validate())
            {
                $model->assign($role,$oldItems);
                Yii::app()->user->setFlash('successMsg','编辑角色成功!');
                $this->redirect(array('roleList'));
            }
        }
        $dropDownData=array();
        if($items=$auth->getAuthItems(0))
        {
          foreach($items as $k=>$item)
          {
              $dropDownData[$k]=$item->description;
          }
        }
        $this->actionTitle = '编辑角色';
        $this->render('role', array('model' => $model,'dropDownData'=>$dropDownData,'title' => $this->actionTitle));
    }

    public function actionDeleteRole($name)
    {
        $auth=Yii::app()->authManager;
        $role=$auth->getAuthItem($name);
        if(!$role)
        {
          throw new CHttpException('404','访问的页面不存在');
        }
        if(Admin::removeAuthItem($role->name))
        {
           Yii::app()->user->setFlash('successMsg','删除角色成功!');
           $this->redirect(array('roleList'));
        }
    }

    public function actionRole()
    {
        $model = new RoleForm();
        $auth=Yii::app()->authManager;
        if(!empty($_POST['RoleForm']))
        {
            $model->attributes=$_POST['RoleForm'];
            if($model->validate())
            {
                $role=$auth->createRole($model->name,$model->description);
                foreach($model->items as $items)
                {
                    $role->addChild($items);
                }
                $role->setData($model->genData($role));
                $this->refresh();
            }
        }
        $dropDownData=array();
        if($items=$auth->getAuthItems(0))
        {
          foreach($items as $k=>$item)
          {
              $dropDownData[$k]=$item->description;
          }
        }
        $this->actionTitle = '添加角色';
        $this->render('role', array('model' => $model,'dropDownData'=>$dropDownData,'title' => $this->actionTitle));
    }


    public function actionItemList()
    {
        $auth=Yii::app()->authManager;
        $data=array();
        if($roles=$auth->getAuthItems(0))
        {
            foreach($roles as $k=>$item)
            {
                $data[]=array(
                         'id'=>$k
                        ,'name'=>$item->name
                        ,'description'=>$item->description
                    );
            }
        }
        $dataProvider=new CArrayDataProvider($data,array(
                    'pagination'=>false
        ));
        $this->render('itemList',array('dataProvider'=>$dataProvider));
    }

    public function actionUpdateItem($name)
    {
        $auth=Yii::app()->authManager;
        $model = new OperationForm();
        $item=$auth->getAUthItem($name);
        if(!$item)
        {
          throw new CHttpException('404','访问的页面不存在');
        }
        $model->name=$item->name;
        $model->description=$item->description;
        if(!empty($_POST['OperationForm']))
        {
            $model->attributes=$_POST['OperationForm'];
            if($model->validate())
            {
                $model->assign($item);
                Yii::app()->user->setFlash('successMsg','编辑操作项成功!');
                $this->redirect(array('itemList'));
            }
        }

        $this->actionTitle = '编辑操作项';
        $this->render('operation', array('model' => $model, 'title' => $this->actionTitle)); 
    }

    public function actionDeleteItem($name)
    {
        $auth=Yii::app()->authManager;
        $item=$auth->getAuthItem($name);
        if(!$item)
        {
          throw new CHttpException('404','访问的页面不存在');
        }
        if(Admin::removeAuthItem($item->name))
        {
           Yii::app()->user->setFlash('successMsg','删除操作项成功!');
           $this->redirect(array('itemList'));
        }
    }
    public function actionItem()
    {
        $model = new OperationForm();
        if(!empty($_POST['OperationForm']))
        {
            $model->attributes=$_POST['OperationForm'];
            if($model->validate())
            {
                $auth=Yii::app()->authManager;
                $auth->createOperation($model->name,$model->description);
                $this->refresh();
            }
        }
        $this->actionTitle = '添加操作项';
        $this->render('operation', array('model' => $model, 'title' => $this->actionTitle));
    }

    public function actionCreate()
    {
        $model=new Admin;
        $channel=null;
        $extraError=array();
        $rolesSelect=array();
        if(!empty($_POST['Admin']))
        {
            $model->attributes=$_POST['Admin'];
            if(empty($_POST['Admin']['roles']))
            {
                $extraError['roles']='至少选择一种身份';
                $model->validate();
            }
            else
            {
              $rolesSelect=$_POST['Admin']['roles'];
              if($model->save())
              {
                Admin::updateRole($model->name,$rolesSelect);
                if(in_array('external',$rolesSelect) && $_POST['Admin']['channel_id'])
                {
                   $admin=Admin::model()->findByPk($model->id);
                   if($admin)
                   {
                     $data=array('channelId'=>$_POST['Admin']['channel_id']);
                     $admin->setData($data);
                   }
                }
                Yii::app()->user->setFlash('successMsg','创建成功!');
                $this->redirect(array('index'));
              }
            }
        }
        $this->actionTitle='添加管理员';
        $this->render('create',array('model'=>$model,'title'=>$this->actionTitle,'extraError'=>$extraError
                    ,'rolesSelect'=>$rolesSelect,'channel'=>$channel));
    }

    public function actionUpdate()
    {
        $model = new ChangePasswordForm();
        if(!empty($_POST['ChangePasswordForm']))
        {
            $model->pwd = $_POST['ChangePasswordForm']['pwd'];
            $model->npwd = $_POST['ChangePasswordForm']['npwd'];
            $model->vpwd = $_POST['ChangePasswordForm']['vpwd'];
            if($model->validate())
            {
                $admin = Admin::model()->findByPk(Yii::app()->user->getId());
                $admin->setPwd($_POST['ChangePasswordForm']['npwd']);
                Yii::app()->user->setFlash('successMsg', '密码修改成功!');
                $this->refresh();

            }
        }
        $this->actionTitle = '修改密码';
        $this->render('update', array('model' => $model, 'title' => $this->actionTitle));
    }

}
