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
                 $filter1 = "<input type='text' name='start_date' id='start_date' value='{$data['startDate']}' class='peroid' size='8' />";
                 $filter2 = "<input type='text' name='end_date' id='end_date' value='{$data['endDate']}' class='peroid' size='8' />";
                 $start_time = strtotime($data['startDate']);
                 $end_time = strtotime($data['endDate']. '23:59:59');
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $model->combinedList($start_time,$end_time),
                            'filter' => $model,
                            'columns' => array(
                               array(
                                   'header'=>'Ad-Pos',
                                   'type'=>'raw',
                                   'filter'=>false,
                                   'value'=>'$data["ad_pos_id"]'
                                   ),
                               array(
                                   'header' => '广告位id',
                                   'type' => 'raw',
                                   'filter' => CHtml::textField('AdPos[pos_id]', $model->posId, array('size' => 1)),
                                   'value' => 'Pos::getKey($data["id"])',
                               ),
                               array(
                                     'name' => 'channel_id',
                                     'header' => $model->getAttributeLabel('channel_id'),
                                     'type' => 'raw',
                                     'filter' => Channel::dropDownData(),
                                     'value' => 'Channel::getName($data["channel_id"])'
                                ),
                                array(
                                    'header' => '广告位名称',
                                    'type' => 'raw',
                                    'filter' => CHtml::textField('AdPos[pos_name]', $model->posName, array('size' => 1)),
                                    'value' => '$data["pos_name"]'
                                ),
                                array(
                                    'header' => '广告版本',
                                    'type' => 'raw',
                                    'filter' => CHtml::textField('AdPos[ad_name]', $model->adName, array('size' => 1)), 'value' => '$data["ad_name"]'),
                                array(
                                     'name' => 'game_id',
                                     'header' => '游戏',
                                     'type' => 'raw',
                                     'filter' => CHtml::dropDownList('AdPos[game_id]', $model->gameId, Game::DropDownData(),
                                          array('id' => false, 'prompt' => '')),
                                     'value' => 'Game::getName($data["game_id"])'
                                ),
                                array(
                                     'name' => 'server_id',
                                     'header' => '区服',
                                     'type' => 'raw',
                                     'filter' => CHtml::dropDownList('AdPos[server_id]', $model->serverId, Server::DropDownData($model->gameId),
                                          array('id' => false, 'prompt' => '')),
                                     'value'=>'Server::getName($data["server_id"])'
                                ),
                                array(
                                    'name' => 'click_times',
                                    'header' => $model->getAttributeLabel('click_times'),
                                    'type' => 'raw',
                                    'filter' => '用户注册时间 :',
                                    'value' => '$data["click_times"]'
                                ),
                                array(
                                    'name' => 'register_times',
                                    'header' => $model->getAttributeLabel('register_times'),
                                    'type' => 'raw',
                                    'filter' => $filter1,
                                    'value' => 'User::countRegisterByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')'
                                ),
                                array(
                                    'header' => '注册率',
                                    'type' => 'raw',
                                    'filter' => $filter2,
                                    'value' => 'Click::countClickByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .') > 0 ? sprintf("%01.2f", (User::countRegisterByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')/Click::countClickByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .'))*100). "%": 0'
                                ),
                                array(
                                    'header' => '回访用户数',
                                    'type' => 'raw',
                                    'filter' => false,
                                    'value' => 'Visit::countRevisitByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')'
                                ),
                                array(
                                    'header' => '用户回访率',
                                    'type' => 'raw',
                                    'filter' => false,
                                    'value' => 'User::countRegisterByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .') > 0 ? sprintf("%01.2f", (Visit::countRevisitByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')/User::countRegisterByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .'))*100). "%": 0'
                                ),
                                array(
                                    'header' => '充值总额',
                                    'type' => 'raw',
                                    'filter' => false,
                                    'value' => 'Order::countPayByAdPos($data["ad_pos_id"], '. $start_time .', '. $end_time .')'
                                )
                               )
                            )) ?>
            </div>
            <?php echo CHtml::htmlButton('导出报表',array('id'=>'export'))?>
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
 <script type="text/javascript">
 jQuery(document).ready(function(){
    $('#export').click(function(){
       var inputSelector='#yw0 .filters input,#yw0 .filters select';
       var data = $(inputSelector).serialize();
       var url='<?php echo CHtml::normalizeUrl(array('market/export'))?>';
       var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    })
 })
</script>
