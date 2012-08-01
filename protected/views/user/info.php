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
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr valign="top">
    <td>
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
        <tr>
            <th valign="top">用户名:</th>
            <td width="200"><?php echo $model->user_name?></td>
            <td>操作</td>
        </tr>
        <!--
        <tr>
            <th valign="top">帐号类型:</th>
            <td></td>
            <td></td>
        </tr>
         -->
        <tr>
            <th valign="top">注册时间:</th>
            <td><?php echo date('Y-m-d H:i', $model->create_time)?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">注册IP地址:</th>
            <td><?php echo long2ip($model->ip)?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">登录次数:</th>
            <td><?php echo $model->login_times?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">邮箱:</th>
            <td><?php echo ($model->email != '' && $model->email != 'guest@2144.cn') ? $model->email : '未填写'?></td>
            <td><a href="/user/changemail/<?php echo $model->id?>">修改邮箱</a></td>
        </tr>
        <tr>
            <th valign="top">防沉迷姓名:</th>
            <td><?php echo $model->profile ? $model->profile->name : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">防沉迷身份证:</th>
            <td><?php echo $model->profile ? $model->profile->card_id : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">用户头像:</th>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">用户昵称:</th>
            <td><?php echo $model->profile ? $model->profile->nick : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">性别:</th>
            <td><?php echo $model->profile ? $data['gender'][$model->profile->gender] : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">生日:</th>
            <td><?php echo $model->profile ? date('Y-m-d', $model->profile->birthday) : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">受教育程度:</th>
            <td><?php echo $model->profile ? $model->profile->education : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">所在地:</th>
            <td><?php echo $model->profile ? $model->profile->province. $model->profile->city. $model->profile->address : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">职业:</th>
            <td><?php echo $model->profile ? $model->profile->occupation : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">收入:</th>
            <td><?php echo $model->profile ? $model->profile->incoming : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">QQ:</th>
            <td><?php echo $model->profile ? $model->profile->qq : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">个人网站:</th>
            <td><?php echo $model->profile ? $model->profile->homepage : '--';?></td>
            <td></td>
        </tr>
        <tr>
            <th valign="top">联系电话:</th>
            <td><?php echo $model->profile ? $model->profile->tel : '--';?></td>
            <td></td>
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
