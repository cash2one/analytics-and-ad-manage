<?php
 class XWebUser extends CWebUser
{
   protected function afterLogin($fromCookie)
   {
      $admin=Admin::model()->findByPk(Yii::app()->user->getId());
      if($admin)
      {
          $admin->latest_ip=ip2long(Yii::app()->request->userHostAddress);
          $admin->latest_time=time();
          $admin->login_times+=1;
          $admin->save();
      }
   }
}
?>
