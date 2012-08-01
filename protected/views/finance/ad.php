<div id="content-outer"><!-- start content -->
<div id="content"><!--  start page-heading -->
<div id="page-heading"></div>
<!-- end page-heading -->
<table border="0" width="100%" cellpadding="0" cellspacing="0"
    id="content-table">
    <tr>
        <th rowspan="3" class="sized"><img
            src="/images/shared/side_shadowleft.jpg" width="20" height="300"
            alt="" /></th>
        <th class="topleft"></th>
        <td id="tbl-border-top">&nbsp;</td>
        <th class="topright"></th>
        <th rowspan="3" class="sized"><img
            src="/images/shared/side_shadowright.jpg" width="20" height="300"
            alt="" /></th>
    </tr>
    <tr>
        <td id="tbl-border-left"></td>
        <td><!--  start content-table-inner ...................................................................... START -->
        <div id="content-table-inner"><!--  start table-content  -->
        <div id="table-content">
        <?php
        echo "&nbsp;&nbsp;".CHtml::button('导出表格',array('id'=>'export'));
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
        $dateFilter1="<input type='text' name='start_date' id='start_date' value='{$startDate}' class='peroid' size='8' />";
        $dateFilter2="<input type='text' name='end_date' id='end_date' value='{$endDate}' class='peroid' size='8' />";
        $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$dataProvider
                           ,'filter'=>false
                           ,'columns'=>array(
                                array(
                                    'name'=>'create_time'
                                   ,'type'=>'date'
                                   ,'header'=>'下单时间'
                                   ,'filter'=>$dateFilter1
                                ),
                                array(
                                    'name'=>'user_name'
                                   ,'type'=>'raw'
                                   ,'header'=>'帐号'
                                   ,'filter'=>$dateFilter2
                                    ),
                                 array(
                                    'name'=>'channel_id'
                                   ,'type'=>'raw'
                                   ,'header'=>'用户注册渠道'
                                   ,'filter' => CHtml::dropDownList('channel_id',$channelId, Channel::DropDownData(),
                                          array('id' => false,'prompt' => ''))
                                   ,'value' => 'Channel::getName($data["channel_id"])'
                                ),
                                array(
                                    'name'=>'game_name'
                                   ,'type'=>'raw'
                                   ,'header'=>'游戏'
                                   ,'filter' => CHtml::dropDownList('game_id', $gameId, Game::DropDownData(),
                                          array('id' => false,'prompt' => '')),
                                ),
                                array(
                                    'name'=>'server_name'
                                   ,'type'=>'raw'
                                   ,'header'=>'区服'
                                   ,'filter' => CHtml::dropDownList('server_id',$serverId, Server::DropDownData($gameId),
                                          array('id' => false,'prompt' => '')),
                                ),
                                array(
                                    'name'=>'paid'
                                   ,'type'=>'raw'
                                   ,'header'=>'充值金额'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'register_time'
                                   ,'type'=>'date'
                                   ,'header'=>'注册时间'
                                   ,'filter'=>false
                                ),
                                array(
                                    'header'=>'登录次数'
                                   ,'type'=>'number'
                                   ,'filter'=>false
                                   ,'value'=>'UserServer::nbLoginTimes($data["user_id"],$data["server_id"])'
                                ),
                                array(
                                    'header'=>'充值次数'
                                   ,'type'=>'number'
                                   ,'filter'=>false
                                   ,'value'=>'Order::nbUserOrder($data["user_id"],null,$data["server_id"])'
                                ),
                                array(
                                    'header'=>'充值总额'
                                   ,'type'=>'raw'
                                   ,'filter'=>false
                                   ,'value'=>'Order::sumUserOrder($data["user_id"],null,$data["server_id"])'
                                ),
                                 array(
                                    'header'=>'平均充值'
                                   ,'type'=>'raw'
                                   ,'filter'=>false
                                   ,'value'=>'round(Order::avgUserOrder($data["user_id"],null,$data["server_id"]),2)'
                                ),
                                array(
                                   'class'=>'CButtonColumn'
                                  ,'viewButtonOptions'=>array('target'=>'_blank')
                                  ,'viewButtonUrl' =>'Yii::app()->controller->createUrl("user/info",array("id"=>$data["user_id"]))'
                                  ,'template'=>'{view}'
                                 )
                               )
                            ))
        ?>
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
<script type="text/javascript">
$(document).ready( function() {
    $('body').delegate('#export','click',function(){
      var inputSelector='#yw0 .filters input,#yw0 .filters select';
      var data = $(inputSelector).serialize();
      var url='<?php echo CHtml::normalizeUrl(array('finance/exportAd'))?>';
      var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    });
});
</script>