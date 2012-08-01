<!-- start content-outer -->
<div id="content-outer">
<!-- start content -->
<div id="content">
<div id="page-heading"><h1><?php echo $data['title'];?></h1></div>
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
    <?php if(Yii::app()->user->hasFlash('keyConflict')):?>
    <!--  start message-red -->
    <div id="message-red">
        <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="red-left"><?php echo Yii::app()->user->getFlash('keyConflict');?></td>
                <td class="red-right"><a class="close-red"><img src="/images/table/icon_close_red.gif"   alt="" /></a></td>
            </tr>
        </table>
    </div>
    <!--  end message-red -->
    <?php endif;?>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr valign="top">
    <td>
    <?php $form=$this->beginWidget('CActiveForm',array(
                'enableAjaxValidation'=>false,
                ))?>
        <!-- start id-form -->
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
        <tr>
            <th valign="top"><?php echo $form->LabelEx($model,'name')?>:</th>
            <td><?php echo $form->textfield($model,'name',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'name')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'name'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->LabelEx($model,'channel_id')?>:</th>
            <td><?php echo $form->dropDownList($model, 'channel_id', Channel::dropDownData())?></td>
            <td>
            <?php if($form->error($model, 'channel_id')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'channel_id'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->LabelEx($model,'key')?>:</th>
            <?php if ($this->route == 'pos/create') {?>
            <td>
                <input type="text" value="" maxlength="16" id="Pos_key" name="Pos[key]" class="inp-form">
            </td>
            <td>
                <?php if($form->error($model, 'key')!=''):?>
                <div class="error-left"></div>
                <div class="error-inner"><?php echo $form->error($model, 'key'); ?></div>
                <?php else:?>
                <div class="bubble-left"></div>
                <div class="bubble-inner">id默认由系统自动生成，如有需要请手动填写。</div>
                <div class="bubble-right"></div>
                <?php endif;?>                
            </td>
            <?php } else {?>
            <td><?php echo $model->id; ?></td>
            <td>
            	<!--
            	<div class="bubble-left"></div>
                <div class="bubble-inner"></div>
                <div class="bubble-right"></div>
                -->
            </td>
            <?php } ?> 
        </tr>
        <tr>
            <th valign="top"><?php echo $form->LabelEx($model,'type')?>:</th>
            <td><?php echo $form->radioButtonList($model, 'type', Pos::$TYPE, array('separator' => '&nbsp;'))?></td>
            <td>
            <?php if($form->error($model, 'type')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'type'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top">显示尺寸:</th>
            <td>
                <?php echo $model->getAttributeLabel('width')?>:
                <?php echo $form->textfield($model, 'width', array('class'=>'inp-form'))?>
                <?php echo $model->getAttributeLabel('height')?>:
                <?php echo $form->textfield($model, 'height', array('class'=>'inp-form'))?>
            </td>
            <td>
            <?php if($form->error($model, 'width')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'width'); ?></div>
            <?php endif;?>
             <?php if($form->error($model, 'height')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'height'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $model->getAttributeLabel('upload_limit')?>:</th>
            <td><?php echo $form->textfield($model,'upload_limit',array('class'=>'inp-form'))?> K(1M=1024K)</td>
            <td>
            <?php if($form->error($model, 'upload_limit')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'upload_limit'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $model->getAttributeLabel('upload_type')?>:</th>
            <td><?php echo $form->radioButtonList($model, 'upload_type',Pos::$UPLOAD_TYPE, array('separator' => '&nbsp;'))?></td>
            <td>
            <?php if($form->error($model, 'upload_type')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'upload_type'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $model->getAttributeLabel('cost')?>:</th>
            <td><?php echo $form->radioButtonList($model, 'cost_type',Pos::$COST_TYPE, array('separator' => '&nbsp;'))?></td>
            <?php if($form->error($model, 'cost_type')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'cost_type'); ?></div>
            <?php endif;?>
        </tr>
        <tr>
            <th valign="top"><?php echo $model->getAttributeLabel('cost')?>:</th>
            <td><?php echo $form->textfield($model,'cost',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'cost')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'cost'); ?></div>
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
