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
        <?php $form=$this->beginWidget('CActiveForm',array(
                'id'=>'R_form',
                'enableAjaxValidation'=>false,
                'action'=>array('user/appealProcess','id'=>$model->id)
                ))?>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr valign="top">
    <td>
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'user_name')?>:</th>
            <td><?php echo $model->user_name?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'game_name')?>:</th>
            <td><?php echo $model->game_name.'  '. $model->server_name?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'nick_name')?>:</th>
            <td><?php echo $model->nick_name?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'email')?>:</th>
            <td><?php echo $model->email?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'reg_time')?>:</th>
            <td><?php echo $model->reg_time?date("Y-m-d H:i:s",$model->reg_time):'';?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'address')?>:</th>
            <td><?php echo $model->address?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'card_id')?>:</th>
            <td><?php echo $model->card_id?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'real_name')?>:</th>
            <td><?php echo $model->real_name?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'gender')?>:</th>
            <td><?php echo $model->gender?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'birthday')?>:</th>
            <td><?php echo $model->birthday?date("Y-m-d H:i:s",$model->birthday):'';?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'pay_amount')?>:</th>
            <td><?php echo $model->pay_amount?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'pay_latest_time')?>:</th>
            <td><?php echo $model->pay_latest_time?date("Y-m-d H:i:s",$model->pay_latest_time):'';?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'pay_latest_method')?>:</th>
            <td><?php echo $model->pay_latest_method?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'pay_latest_game')?>:</th>
            <td><?php echo $model->pay_latest_game?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'contact_email')?>:</th>
            <td><?php echo $model->contact_email?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'contact_qq')?>:</th>
            <td><?php echo $model->contact_qq?></td>
        </tr>
        <tr>
            <th valign="top"><?php echo CHtml::activeLabel($model,'extra')?>:</th>
            <td><?php echo $model->extra?></td>
        </tr>
        <tr>
         <th>处理结果</th>
         <td valign="top">
             <input class='R_radio' type="radio" name="status" value='1' /> 成功后密码自动发送至申诉邮箱<br/>
             <input class='R_radio' type="radio" name="status" value='2' /> 失败后将发送不通过意见至申诉邮箱<br/>
         </td>
         <td id='R_radio_err' style='display:none;'>
            <div class="error-left"></div>
            <div class="error-inner">请选择处理结果</div>
         </td>
        </tr>
        <tr id="R_reject_tr" style="display:none">
        <th>不通过意见</th>
        <td valign="top">
        <textarea id="R_reject" name="reject" class="form-textarea"></textarea>
        </td>
        <td id='R_reject_err' style='display:none;'>
           <div class="error-left"></div>
           <div class="error-inner">失败申诉需要填写不通过意见</div>
        </td>
        </tr>
         <tr>
         <th>&nbsp;</th>
         <td valign="top">
            <input type="submit" value="" class="form-submit" />
         </td>
        </tr>
    </table>
    <?php $this->endWidget()?>
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
jQuery(document).ready(function(){
     jQuery('#R_form').submit(function(){
            var valid=true;
            var radio=$('.R_radio:checked');
            if(radio.length>0)
            {
              if(radio[0].value==2)
              {
                  if($('#R_reject').val()=="")
                  {
                    $('#R_reject_err').show();
                    valid=false;
                  }
              }
            }
            else
            {
              $('#R_radio_err').show();
              valid=false;
            }
            return valid;
         });
     jQuery('.R_radio').change(function(){
             if(this.value==1)
             {
               $('#R_reject_tr').hide();
               $('#R_reject_err').hide();
             }
             else if(this.value==2)
             {
               $('#R_reject_tr').show();
             }
             $('#R_radio_err').hide();
     });
});
</script>
