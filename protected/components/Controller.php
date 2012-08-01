<?php
class Controller extends CController
{
    public $layout='//layouts/column1';
    public $menu=array();
    public $breadcrumbs=array();

    protected function beforeAction($action)
    {
        if(parent::beforeAction($action))
        {
            if(in_array($this->id,array('admin','site')) || Yii::app()->getAuthManager()->checkAccess($this->id.ucfirst($action->id),Yii::app()->user->getName()))
            {
             $cs=Yii::app()->getClientScript();
             $cs->registerCssFile('/css/screen.css');
             $cs->registerCoreScript('jquery');
             $cs->registerScriptFile('/js/global.js',CClientScript::POS_END);
             return true;
            }
            else
            {
              throw new CHttpException('403','没有相应权限');
            }
        }
    }
}
