<!-- start content-outer -->
<div id="content-outer">
<!-- start content -->
<div id="content">
<div id="page-heading"><h1><?php echo $title?></h1></div>
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
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr valign="top">
    <td>
    <?php $form=$this->beginWidget('CActiveForm',array(
                'enableAjaxValidation'=>false,
                ))?>
        <!-- start id-form -->
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'name');?>:</th>
            <td><?php echo $form->textfield($model,'name',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'name')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'name'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'pwd');?>:</th>
            <td><?php echo $form->passwordfield($model,'pwd',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'pwd')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'pwd'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><label for='Admin_roles_0' class="required">权限<span class="required">*</span></label>:</th>
            <td>
                <?php echo CHtml::checkBoxList('Admin[roles]',$rolesSelect,Admin::getRoles(),array(
                        'template'=>'{label}{input}',
                        'separator'=>' ',
                        ))
                ?>
            </td>
            <td>
            <?php if(!empty($extraError['roles'])):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $extraError['roles']; ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top">专属渠道:</th>
            <td><?php echo CHtml::dropDownList('Admin[channel_id]',$channel,Channel::dropDownData(),array('class'=>'inp-form','prompt'=>''))?></td>
            <td>
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