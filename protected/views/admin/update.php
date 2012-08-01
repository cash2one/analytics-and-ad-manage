<!-- start content-outer -->
<div id="content-outer">
<!-- start content -->
<div id="content">
<div id="page-heading"><h1><?php echo $title;?></h1></div>
  <table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
  <tr>
    <th rowspan="3" class="sized"><img src="/images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
    <th class="topleft"></th>
    <td id="tbl-border-top">&nbsp;</td>
    <th class="topright"></th>
    <th rowspan="3" class="sized"><img src="/images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
  </tr>
 <tr>
    <td id="tbl-border-left"></td>
    <td>
    <!--  start content-table-inner -->
    <div id="content-table-inner">
    <div id="table-content">
    <?php if(Yii::app()->user->hasFlash('successMsg')):?>
    <!--  start message-green -->
    <div id="message-green">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="green-left"><?php echo Yii::app()->user->getFlash('successMsg');?></a></td>
        <td class="green-right"><a class="close-green"><img src="/images/table/icon_close_green.gif"   alt="" /></a></td>
    </tr>
    </table>
    </div>
    <!--  end message-green -->
     <?php endif;?>
     <?php if(Yii::app()->user->hasFlash('errorMsg')):?>
    <!--  start message-red -->    
	<div id="message-red">
    	<table border="0" width="100%" cellpadding="0" cellspacing="0">
        	<tr>
        		<td class="red-left"><?php echo Yii::app()->user->getFlash('errorMsg');?></td>
        		<td class="red-right"><a class="close-red"><img src="/images/table/icon_close_red.gif"   alt="" /></a></td>
        	</tr>
    	</table>
	</div>	
	<!--  end message-red -->
    <?php endif;?>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr valign="top">
    <td>
    <?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'changePassword-form',
                'enableAjaxValidation' => false
                ))?>
        <!-- start id-form -->
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
        <tr>
            <th valign="top"><?php echo $form->LabelEx($model,'name')?>:</th>
            <td><?php echo Yii::app()->user->getName();?></td>
            <td>            
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->LabelEx($model,'pwd')?>:</th>
            <td><?php echo $form->passwordField($model, 'pwd', array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'pwd')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'pwd'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top">新密码:</th>
            <td><?php echo $form->passwordField($model, 'npwd', array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'npwd')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'npwd'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top">确认密码:</th>
            <td><?php echo $form->passwordField($model, 'vpwd', array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'vpwd')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'vpwd'); ?></div>
            <?php endif;?>
            </td>
        </tr>      
        <tr>
         <th>&nbsp;</th>
         <td valign="top">
            <input type="submit" value="" class="form-submit" />
            <input type="reset" value="" class="form-reset"  />
         </td>
         <td></td>
        </tr>
    </table>
    <?php $this->endWidget()?>
    <!-- end id-form  -->    
    </td>
    <td>
</td>
</tr>
<tr>
<td><img src="/images/shared/blank.gif" width="695" height="1" alt="blank" /></td>
<td></td>
</tr>
</table>
<div class="clear"></div>
</div>
<!--  end content-table-inner  -->
</div>
</td>
<td id="tbl-border-right"></td>
</tr>
<tr>
    <th class="sized bottomleft"></th>
    <td id="tbl-border-bottom">&nbsp;</td>
    <th class="sized bottomright"></th>
</tr>
</table>
<div class="clear">&nbsp;</div>
</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->
<div class="clear">&nbsp;</div>

<script type="text/javascript">
$(document).ready(function(){
	$(".close-red").click(function () {
		$("#message-red").fadeOut("slow");
	});
});
</script>
