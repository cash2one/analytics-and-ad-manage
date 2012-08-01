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
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
        <tr>
            <th valign="top">ID:</th>
            <td><?php echo $model->id;?></td>
        </tr>
        <tr>
            <th valign="top">用户名:</th>
            <td><?php echo $model->username;?></td>
        </tr>
        <tr>
            <th valign="top">游戏:</th>
            <td><?php echo $model->game_name;?></td>
        </tr>
        <tr>
            <th valign="top">原因:</th>
            <td><?php echo $model->reason;?></td>
        </tr>
        <tr>
            <th valign="top">时间:</th>
            <td><?php echo date("Y-m-d H:i:s", $model->time);?></td>
        </tr>
        <tr>
            <th valign="top"></th>
            <td><button onclick="history.back()">返回</button></td>
            <td></td>
        </tr>
    </table>
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