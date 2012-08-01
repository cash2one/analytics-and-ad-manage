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
                            'dataProvider' => $model->userGameList(),
                            'filter' => $model,
                            'columns' => array(
                                   array(
                                       'name' => 'time',
                                       'header' => $model->getAttributeLabel('time'),
                                       'type' => 'raw',
                                       'value' => 'date("Y-m-d H:i", $data["time"])',
                                       'filter' => $filter1. $filter2
                                   ),
                                   array(
                                       'name' => 'game_id',
                                       'header' => $model->getAttributeLabel('game_id'),
                                       'type' => 'raw',
                                       'value' => 'Game::getName($data["game_id"])',
                                       'filter' => CHtml::dropDownList('Visit[game_id]', $model->game_id, Game::DropDownData(), array('id' => false, 'prompt' => ''))
                                   ),
                                   array(
                                       'name' => 'server_id',
                                       'header' => $model->getAttributeLabel('server_id'),
                                       'type' => 'raw',
                                       'value' => 'Server::getName($data["server_id"])',
                                       'filter' => CHtml::dropDownList('Visit[server_id]', $model->server_id, Server::DropDownData($model->game_id), array('id' => false, 'prompt' => ''))
                                   ),                                                                     
                                   array(
                                       'name' => 'ip',
                                       'header' => $model->getAttributeLabel('ip'),
                                       'type' => 'raw',
                                       'value' => 'long2ip($data["ip"])',
                                       'filter' => false
                                   ),
                                   array(
                                       'header' => '登录地',
                                       'type' => 'raw',
                                       'value' => '$data["ip"] ? User::getAddress(long2ip($data["ip"])) : "未知地址"'
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