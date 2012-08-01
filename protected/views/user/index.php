<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
    </div>
    <!-- end page-heading -->
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
        <!--  start content-table-inner ...................................................................... START -->
        <div id="content-table-inner">
            <!--  start table-content  -->
            <div id="table-content">                
                <?php
                $this->widget('ext.XJuiDatePicker', array(
                             'name' => 'perioddate',
                             'range' => 'peroid',
                             'language' => 'zh-CN',
                             'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'minDate' => '-20D',
                                'maxDate' => 0,
                             )
                 ));
                 $filter1 = "<input type='text' name='start_date' id='start_date' value='{$model->startDate}' class='peroid' size='1' />";
                 $filter2 = "<input type='text' name='end_date' id='end_date' value='{$model->endDate}' class='peroid' size='1' />";
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $model->userList(),
                            'filter' => $model,
                            'columns' => array(
                                   array(
                                       'name' => 'id',
                                       'header' => $model->getAttributeLabel('id'),
                                       'type' => 'raw',
                                       'value' => '$data["id"]',
                                   ),
                                   array(
                                       'header' => '渠道',
                                       'type' => 'raw',
                                       'value' => 'Channel::getName($data["channel_id"])',
                                       'filter' => CHtml::dropDownList('User[channel_id]', $model->ChannelId, array(0 => '全部', -1 => '平台注册 ') + Channel::dropDownData())
                                   ),
                                   array(
                                       'name' => 'user_name',
                                       'header' => $model->getAttributeLabel('user_name'),
                                       'type' => 'raw',
                                       'value' => '$data["user_name"]',
                                       'filter' =>  CHtml::textField('User[user_name]', $model->user_name, array('size' => 1))
                                   ),
                                   array(
                                       'header' => '盛大通行证',
                                       'type' => 'raw',
                                       'value' => '$data["u_name"]',
                                       'filter' =>  CHtml::textField('User[u_name]', $model->u_name, array('size' => 1))
                                   ),
                                   array(
                                       'header' => '帐号类型',
                                       'type' => 'raw',
                                       'value' => '$data["u_type"] == 1 ? "盛大通行证": "2133用户"',
                                   	   'filter' => CHtml::dropDownList('User[u_type]', $model->u_type, array(-1 => '全部', 0 => '2133用户', 1 => '盛大通行证'))
                                   ),
                                   array(
                                       'name' => 'create_time',
                                       'header' => $model->getAttributeLabel('create_time'),
                                       'type' => 'raw',
                                       'value' => 'date("Y-m-d H:i", $data["create_time"])',
                                       'filter' => $filter1. $filter2
                                   ),
                                   array(
                                       'name' => 'ip',
                                       'header' => $model->getAttributeLabel('ip'),
                                       'type' => 'raw',
                                       'value' => 'long2ip($data["ip"])',
                                       'filter' => CHtml::textField('User[ip]', $model->IP, array('size' => 1))
                                   ),
                                   array(
                                       'name' => 'email',
                                       'header' => $model->getAttributeLabel('email'),
                                       'type' => 'raw',
                                       'value' => '$data["email"] == "guest@2144.cn" ? "未填写": $data["email"]',
                                       'filter' => CHtml::dropDownList('User[has_email]', $model->hasEmail, array(0 => '全部', 1 => '已填', 2 => '未填'))
                                   ),
                                   array(                                       
                                       'header' => '身份证',
                                       'type' => 'raw',
                                       'value' => '$data["card_id"]? $data["card_id"]: "未填写"',
                                   	   'filter' => '防沉迷:'
                                   ),
                                   array(
                                       'header' => '真实姓名',
                                       'type' => 'raw',
                                       'value' => '$data["name"]? $data["name"]: "未填写"',
                                       'filter' => CHtml::dropDownList('User[has_cardid]', $model->hasCardid, array(0 => '全部', 1 => '已填', 2 => '未填'))
                                   ),
                                   array(
                                       'name' => 'login_times',
                                       'header' => $model->getAttributeLabel('login_times'),
                                       'type' => 'raw',
                                       'value' => '$data["login_times"]',
                                       'filter' => false
                                   ),
                                   array(
                                       'header' => '最后登录游戏区服',
                                       'type' => 'raw',
                                       'value' => 'Visit::lastGameServer($data["id"])'
                                   ),
                                   array(
                                       'header' => '最后登录时间',
                                       'type' => 'raw',
                                       'value' => 'Visit::lastVisitTime($data["id"])'
                                   ),
                                   array(
                                       'class' => 'CButtonColumn',
                                       'template' => '{viewgame} {viewinfo}',
                              		   'buttons' => array(
                                           'viewgame' => array(
                                               'label' => '查看游戏信息',
                                               'url' => '"/user/game/{$data["id"]}"',
                                               'imageUrl' => '/images/game.png'
                                           ),
                                           'viewinfo' => array(
                                               'label' => '查看个人资料',
                                               'url' => '"/user/info/{$data["id"]}"',
                                               'imageUrl' => '/images/user.png'
                                           )
                                       )
                                   )
                               )
                            )) ?>
            </div>            
            <!--  end content-table  -->
            <div class="clear"></div>
        </div>
        <!--  end content-table-inner ............................................END  -->
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
<!--  end content-outer........................................................END -->
<div class="clear">&nbsp;</div>
