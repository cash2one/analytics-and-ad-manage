<!-- start content-outer -->
<div id="content-outer">
<!-- start content -->
<div id="content">
<div id="page-heading"><h1><?php echo $this->actionTitle?></h1></div>
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
        <form method="post">
        <!-- start id-form -->
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
        <tr>
            <th style="width:200px;">版块</th>
            <td></td>
            <td>排序规则</td>
        </tr>
        <?php foreach($data['forum'] as $forum) {?>
        <tr>
            <th valign="top"><?php echo $forum['name'];?></th>
            <td></td>
            <td>
            <?php echo CHtml::dropDownList("forum[{$forum['id']}]", $forum['sort_type_id'], SortType::dropDownData())?>
            </td>
        </tr>
        <?php }?>
        <tr>
         <th>&nbsp;</th>
         <td valign="top">
            <input type="submit" value="" class="form-submit" />
            <input type="reset" value="" class="form-reset"  />
         </td>
         <td></td>
        </tr>
    </table>
   </form>
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
