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
                ));
           $this->widget('ext.XJuiDatePicker', array(
                        'name' => 'Server_promote_end_time,#Server_open_time',
                        'language' => 'zh-CN',
                        'options' => array(
                            'dateFormat' => 'yy-mm-dd',
                        )
                ));
      ?>

        <!-- start id-form -->
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'name')?>:</th>
            <td><?php echo $form->textfield($model,'name',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'name')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'name'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'entergame_url')?>:</th>
            <td><?php echo $form->textfield($model,'entergame_url',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'entergame_url')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'entergame_url'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'game_id')?>:</th>
            <td><?php echo $form->dropDownList($model,'game_id',Game::dropDownData())?></td>
            <td>
            <?php if($form->error($model, 'game_id')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'game_id'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
        <th valign="top"><?php echo $form->labelEx($model,'open_time')?>:</th>
            <td><?php echo $form->textfield($model,'open_time',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'open_time')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'open_time'); ?></div> <?php endif;?>
            </td>
        </tr>

        <tr>
        <th valign="top"><?php echo $form->labelEx($model,'promote_end_time')?>:</th>
            <td><?php echo $form->textfield($model,'promote_end_time',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'promote_end_time')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'promote_end_time'); ?></div> <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'status')?>:</th>
            <td><?php echo $form->dropDownList($model,'status',ServerStatus::dropDownData())?></td>
            <td>
            <?php if($form->error($model, 'status')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'status'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'show')?>:</th>
            <td><?php echo $form->checkBox($model,'show',array('value' => 1)). ' 是'?></td>
            <td>
            <?php if($form->error($model, 'show')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'show'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'active')?>:</th>
            <td><?php echo $form->checkBox($model,'active',array('value' => 1)). ' 是'?></td>
            <td>
            <?php if($form->error($model, 'active')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'active'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'flag')?>:</th>
            <td><?php echo $form->radioButtonList($model,'flag',array(0 => '无', 1 => 'new',2 => 'hot'), array('separator'=>' '))?></td>
            <td>
            <?php if($form->error($model, 'flag')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'flag'); ?></div>
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
