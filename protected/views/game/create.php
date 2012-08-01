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
            <th valign="top"><?php echo $form->labelEx($model,'logo')?>:</th>
            <td><?php echo $form->textfield($model,'logo',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'logo')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'logo'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'unit')?>:</th>
            <td><?php echo $form->textfield($model,'unit',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'unit')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'unit'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'rate')?>:</th>
            <td><?php echo $form->textfield($model,'rate',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'rate')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'rate'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
         <th valign="top"><?php echo $form->labelEx($model,'share')?>:</th>
         <td><?php echo $form->textfield($model,'share',array('class'=>'inp-form'))?></td>
         <td>
         <?php if($form->error($model, 'share')!=''):?>
         <div class="error-left"></div>
         <div class="error-inner"><?php echo $form->error($model, 'share'); ?></div>
         <?php endif;?>
         </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'website')?>:</th>
            <td><?php echo $form->textfield($model,'website',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'website')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'website'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'bbs')?>:</th>
            <td><?php echo $form->textfield($model,'bbs',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'bbs')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'bbs'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'type_id')?>:</th>
            <td><?php echo $form->dropDownList($model,'type_id', GameType::dropDownData())?></td>
            <td>
            <?php if($form->error($model, 'type_id')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'type_id'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'version')?>:</th>
            <td><?php echo $form->textfield($model,'version', array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'version')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'version'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'status')?>:</th>
            <td><?php echo $form->textfield($model,'status',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'status')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'status'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'status_link')?>:</th>
            <td><?php echo $form->textfield($model,'status_link',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'status_link')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'status_link'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'flag')?>:</th>
            <td><?php echo $form->radioButtonList($model,'flag',array(0 => '无',3 => '微端'), array('separator'=>' '))?></td>
            <td>
            <?php if($form->error($model, 'flag')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'flag'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'client')?>:</th>
            <td><?php echo $form->textfield($model,'client',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'client')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'client'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'isnew')?>:</th>
            <td><?php echo $form->checkBox($model,'isnew',array('value' => 1)). ' 是'?></td>
            <td>
            <?php if($form->error($model, 'isnew')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'isnew'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'join_vote')?>:</th>
            <td><?php echo $form->checkBox($model,'join_vote',array('value' => 1)). ' 是'?></td>
            <td>
            <?php if($form->error($model, 'join_vote')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'join_vote'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <!--<tr>
            <th valign="top"><?php echo $form->labelEx($model,'enable')?>:</th>
            <td><?php echo $form->checkBox($model,'enable',array('value' => 1)). ' 是'?></td>
            <td>
            <?php if($form->error($model, 'enable')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'enable'); ?></div>
            <?php endif;?>
            </td>
        </tr>-->
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'vote_base')?>:</th>
            <td><?php echo $form->textfield($model,'vote_base',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'vote_base')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'vote_base'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'vote_rate')?>:</th>
            <td><?php echo $form->textfield($model,'vote_rate',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'vote_rate')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'vote_rate'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'tutorial')?>:</th>
            <td><?php echo $form->textfield($model,'tutorial',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'tutorial')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'tutorial'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'guide')?>:</th>
            <td><?php echo $form->textfield($model,'guide',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'guide')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'guide'); ?></div>
            <?php endif;?>
            </td>
        </tr>
		<tr>
            <th valign="top"><?php echo $form->labelEx($model,'index')?>:</th>
            <td><?php echo $form->textfield($model,'index',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'index')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'index'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'type')?>:</th>
            <td><?php echo $form->radioButtonList($model,'type',array(0 => '平台游戏',1 => '其他游戏'), array('separator'=>' '))?></td>
            <td>
            <?php if($form->error($model, 'type')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'type'); ?></div>
            <?php endif;?>
            </td>
        </tr>
          <tr>
            <th valign="top"><?php echo $form->labelEx($model,'portal')?>:</th>
            <td><?php echo $form->textfield($model,'portal',array('class'=>'inp-form'))?></td>
            <td>
            <?php if($form->error($model, 'portal')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'portal'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'portal_desc')?>:</th>
            <td><?php echo $form->textArea($model,'portal_desc',array('class'=>'form-textarea'))?></td>
            <td>
            <?php if($form->error($model, 'portal_desc')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'portal_desc'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'desc')?>:</th>
            <td><?php echo $form->textArea($model,'desc',array('class'=>'form-textarea'))?></td>
            <td>
            <?php if($form->error($model, 'desc')!=''):?>
            <div class="error-left"></div>
            <div class="error-inner"><?php echo $form->error($model, 'desc'); ?></div>
            <?php endif;?>
            </td>
        </tr>
        <tr>
            <th valign="top"><?php echo $form->labelEx($model,'payad')?>:</th>
            <td><?php echo $form->textArea($model,'payad',array('class'=>'form-textarea'))?></td>
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
