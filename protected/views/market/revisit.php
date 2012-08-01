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
                 $filter1 = "<input type='text' name='start_date' id='start_date' value='{$data['startDate']}' class='peroid' size='1' />";
                 $filter2 = "<input type='text' name='end_date' id='end_date' value='{$data['endDate']}' class='peroid' size='6' />";
                 $start_time = strtotime($data['startDate']);
                 $end_time = strtotime($data['endDate']. '23:59:59');
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $model->combinedList(),
                            'filter' => $model,
                            'columns' => array(
                               array(
                                    'header' => '广告位id',
                                    'type' => 'raw',
                                    'value' => 'Pos::getKey($data["id"])',
                                    'filter' => false
                                ),
                                array(
                                    'name' => 'channel_id',
                                    'header' => $model->getAttributeLabel('channel_id'),
                                    'type' => 'raw',
                                    'filter' => Channel::dropDownData(),
                                    'value' => 'Channel::getName($data["channel_id"])'
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => $model->getAttributeLabel('name'),
                                    'type' => 'raw',
                                    'filter' => false
                                ),
                                array(
                                     'header' => '总注册用户数',
                                     'type' => 'raw',
                                     'filter' => '用户注册时间:',
                                     'value' => 'User::countRegisterByPos($data["id"], '. $start_time .', '. $end_time .')'
                                ),
                                array(
                                     'header' => '回访用户数',
                                     'type' => 'raw',
                                     'filter' => $filter1,
                                     'value' => 'Visit::countRevisitByPos($data["id"], '. $start_time .', '. $end_time .')'
                                ),
                                array(
                                     'header' => '回访率',
                                     'type' => 'raw',
                                     'filter' => $filter2,
                                     'value' => 'User::countRegisterByPos($data["id"], '. $start_time .', '. $end_time .') > 0 ? sprintf("%01.2f", (Visit::countRevisitByPos($data["id"], '. $start_time .', '. $end_time .')/User::countRegisterByPos($data["id"], '. $start_time .', '. $end_time .'))*100). "%": 0'
                                ),
                                array(
                                     'header' => '登录1次',
                                     'type' => 'raw',
                                     'filter' => false,
                                     'value' => 'Visit::countRevisitByPos($data["id"], '. $start_time .', '. $end_time .', 1, 1)'
                                    ),
                                array(
                                     'header' => '登录2次',
                                     'type' => 'raw',
                                     'filter' => false,
                                     'value' => 'Visit::countRevisitByPos($data["id"], '. $start_time .', '. $end_time .', 2, 2)'
                                    ),
                                array(
                                     'header' => '登录3次',
                                     'type' => 'raw',
                                     'filter' => false,
                                     'value' => 'Visit::countRevisitByPos($data["id"], '. $start_time .', '. $end_time .', 3, 3)'
                                    ),
                                array(
                                     'header' => '登录4次',
                                     'type' => 'raw',
                                     'filter' => false,
                                     'value' => 'Visit::countRevisitByPos($data["id"], '. $start_time .', '. $end_time .', 4, 4)'
                                    ),
                                array(
                                     'header' => '登录5-10次',
                                     'type' => 'raw',
                                     'filter' => false,
                                     'value' => 'Visit::countRevisitByPos($data["id"], '. $start_time .', '. $end_time .', 5, 10)'
                                    ),
                                array(
                                     'header' => '登录10次以上',
                                     'type' => 'raw',
                                     'filter' => false,
                                     'value' => 'Visit::countRevisitByPos($data["id"], '. $start_time .', '. $end_time .', 11)'
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
