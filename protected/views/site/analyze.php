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
                 $this->widget('ext.XJuiDatePicker',array(
                             'name'=>'date'
                            ,'language'=>'zh-CN'
                            ,'options'=>array(
                                'dateFormat'=>'yy-mm-dd',
                                ),
                            ));
                $filter = "<input type='text' name='date' value='{$date}' id='date' size='1' />";
                $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' =>$model->dailyList($date),
                            'filter' => $model,
                            'columns' => array(
                               array(
                                   'header'=>'时间段',
                                   'type' => 'raw',
                                   'value' => '$data["hourFrom"]. "-". $data["hour"]',
                                   'filter' =>$filter
                               ),
                               array(
                                    'name'=>'pos_id',
                                    'header'=>'广告位id',
                                    'type'=>'raw',
                                   ),
                               array(
                                     'header' =>'渠道',
                                     'type' => 'raw',
                                     'filter' =>CHtml::dropDownList(
                                         'AdPos[channel_id]'
                                         ,$model->channel_id,Channel::dropDownData()
                                         ),
                                     'value' => 'Channel::getName($data["channel_id"])'
                               ),
                               array(
                                   'header' => '广告位',
                                   'type' => 'raw',
                                   'value' => 'Pos::getName($data["pos_id"])'
                               ),
                               array(
                                     'name'=>'ad_id',
                                     'header' =>'广告',
                                     'type' => 'raw',
                                     'filter'=>Ad::items(),
                                     'value' => '$data["ad_name"]'
                                ),
                                array(
                                     'header' =>'游戏',
                                     'type' => 'raw',
                                     'filter' =>CHtml::dropDownList(
                                         'AdPos[game_id]'
                                         ,$model->gameId,Game::dropDownData()
                                         ,array('prompt'=>'')),
                                     'value' => 'Game::getName($data["game_id"])'
                                ),
                                array(
                                     'header' =>'区服',
                                     'type' => 'raw',
                                     'filter' =>CHtml::dropDownList(
                                         'AdPos[server_id]'
                                         ,$model->serverId,Server::dropDownData($model->gameId)
                                         ,array('prompt'=>'')),
                                     'value' => 'Server::getName($data["server_id"])'
                                ),
                                array(
                                     'header' => '点击数',
                                     'type' => 'number',
                                     'value' => 'Click::countClickByAdPos($data["id"],$data["from"],$data["to"],"hour")'
                                ),
                                 array(
                                 'header' => '注册率',
                                 'type' => 'raw',
                                 'value' => 'sprintf("%01.2f",User::countRegisterByAdPos($data["id"],$data["from"],$data["to"],"hour")/Click::countClickByAdPos($data["id"],$data["from"],$data["to"],"hour") * 100). "%"'
                                 ),
                                array(
                                 'header' => '注册人数',
                                 'type' => 'number',
                                 'value' => 'User::countRegisterByAdPos($data["id"],$data["from"],$data["to"],"hour")'
                                ),
                                array(
                                 'header' => '昨日注册人数',
                                 'type' => 'number',
                                 'value' => 'User::countRegisterByPos($data["pos_id"],$data["from"]-86400,$data["to"]-86400,"hour")'
                                ),
                                 array(
                                 'header' => '同比增长率',
                                 'type' => 'raw',
                                 'value' => 'User::countRegisterByPos($data["pos_id"],$data["from"]-86400,$data["to"]-86400,"hour")?sprintf("%01.2f",(User::countRegisterByAdPos($data["id"],$data["from"],$data["to"],"hour")/User::countRegisterByPos($data["pos_id"],$data["from"]-86400,$data["to"]-86400,"hour")-1)*100). "%":0'
                                 ),
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
       var url='<?php echo CHtml::normalizeUrl(array('site/analyzeExport'))?>';
       var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    })
 })
</script>
