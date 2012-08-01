<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>2133 管理后台</title>
<link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" title="default" />
</head>
<body id="login-bg">
<!-- Start: login-holder -->
<div id="login-holder">
    <!-- start logo -->
    <div id="logo-login">
    </div>
    <!-- end logo -->
    <div class="clear"></div>
    <!--  start loginbox ................................................................................. -->
    <div id="loginbox">
    <?php $form=$this->beginWidget('CActiveForm', array(
      'id'=>'login-form',
      'enableClientValidation'=>true,
      'clientOptions'=>array(
          'validateOnSubmit'=>true,
       ),
     ));
    ?>
    <!--  start login-inner -->
    <div id="login-inner">
        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $form->label($model,'name'); ?></th>
            <td><?php echo $form->textField($model,'name',array('class'=>'login-inp')); ?></td>
            <td>
            </td>
        </tr>
        <tr>
            <th><?php echo $form->label($model,'password'); ?></th>
            <td><?php echo $form->passwordField($model,'password',array('class'=>'login-inp')); ?></td>
        </tr>
        <tr>
            <th></th>
            <td><?php echo CHtml::submitButton('',array('class'=>'submit-login'))?></td>
        </tr>
        </table>
    </div>
    <!--  end login-inner -->
    <?php $this->endWidget(); ?>
    <div class="clear"></div>
     <?php if(Yii::app()->user->hasFlash('ErrorMsg')){?>
    <div style="text-align:center;color:blue;">
    <?php echo Yii::app()->user->getFlash('ErrorMsg');?>
    </div>
    <?php }?>
    
 </div>
 <!--  end loginbox -->
</div>
<!-- End: login-holder -->
</body>
</html>
