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
            <th valign="top"><?php echo $model->getAttributeLabel('name')?>:</th>
            <td><?php echo $form->textfield($model,'name',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'name')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'name'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $model->getAttributeLabel('pay_type')?>:</th>
            <td><?php echo $form->dropDownList($model,'pay_type',array('1'=>'包月','2'=>'CPA','3'=>'CPM'))?></td>
            <td>
            <?php if($form->error($model, 'type')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'type'); ?></div>
            <?php endif;?>
            </td>
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
            <th valign="top"><?php echo $model->getAttributeLabel('website')?>:</th>
            <td><?php echo $form->textfield($model,'website',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'website')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'website'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $model->getAttributeLabel('type')?>:</th>
            <td><?php echo $form->dropDownList($model,'type',array('0'=>'外部','1'=>'内部'))?></td>
            <td>
            <?php if($form->error($model, 'type')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'type'); ?></div>
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
