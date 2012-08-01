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
                echo "&nbsp;&nbsp;".CHtml::button('导出表格',array('id'=>'export'));
                $this->widget('ext.XJuiDatePicker', array(
                        'name' => 'payTime',
                        'language' => 'zh-CN',
                        'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'minDate' => '-20D',
                                'maxDate' => 0,
                        )
                ));
                $filter = '<input type="text" name="payTime" size="2" id="payTime" value="'. $data['payTime']. '" />';
                  $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $model->vipList(),
                            'filter' => $model,
                            'columns' => array(
                                  array(
                                        'name' => 'game_id',
                                        'header' => $model->getAttributeLabel('game_id'),
                                        'type' => 'raw',
                                        'filter' => Game::dropDownData(),
                                        'value' => 'Game::getName($data["game_id"])'
                                  ),
                                  array(
                                        'name' => 'server_id',
                                        'header' => $model->getAttributeLabel('server_id'),
                                        'type' => 'raw',
                                        'filter' => CHtml::dropDownList('VipUser[server_id]', $model->server_id, Server::DropDownData($model->game_id),
                                                array('id' => false, 'prompt' => '')),
                                        'value'=>'Server::getName($data["server_id"])'
                                  ),
                                  array(
                                        'name' => 'user_id',
                                        'header' => $model->getAttributeLabel('user_id'),
                                        'type' => 'raw',
                                        'filter' => CHtml::textField('VipUser[user_id]', $model->user_id, array('size' => 1)),
                                        'value' => '$data["user_id"]'
                                   ),
                                   array(
                                       'name' => 'user_name',
                                       'header' => $model->getAttributeLabel('user_name'),
                                       'type' => 'raw',
                                       'filter' => CHtml::textField('VipUser[user_name]', $model->user_name, array('size' => 1)),
                                       'value' => '$data["user_name"]'
                                   ),
                                   array(
                                       'name' => 'vip_rank',
                                       'header' => $model->getAttributeLabel('vip_rank'),
                                       'filter' => false,
                                       'type' => 'raw',
                                       ),
                                   array(
                                       'name' => 'sum_paid',
                                       'header' => $model->getAttributeLabel('sum_paid'),
                                       'filter' => false,
                                       'type' => 'raw',
                                      ),                                                                     
                                   array(
                                       'name' => 'last_paid_time',
                                       'header' => $model->getAttributeLabel('last_paid_time'),
                                       'filter' => $filter,
                                       'type' => 'date'
                                   ),
                                   array(
                                        'name' => 'reg_channel',
                                        'header' => $model->getAttributeLabel('reg_channel'),
                                        'filter' => false,
                                        'type' => 'raw'
                                   ),
                                   array(
                                       'name' => 'e_mail',
                                       'header' => $model->getAttributeLabel('e_mail'),
                                       'filter' => false,
                                       'type' => 'raw'
                                   ),                                                                     
                                   array(
                                       'name' => 'qq',
                                       'header' => $model->getAttributeLabel('qq'),
                                       'filter' => false,
                                       'type' => 'raw'
                                   ),                                                                     
                                   array(
                                       'name' => 'mobile_phone',
                                       'header' => $model->getAttributeLabel('mobile_phone'),
                                       'filter' => false,
                                       'type' => 'raw'
                                   ),
                                   array(
                                       'class' => 'CButtonColumn',
                                       'viewButtonOptions' => array('target' => '_blank'),
                                       'viewButtonUrl' =>'Yii::app()->controller->createUrl("finance/list", array("user_name" => $data["user_name"], "game_id" => $data["game_id"], "server_id" => $data["server_id"], "start_date" => date("Y-m-d", strtotime("-1 year"))))',
                                       'template' => '{view}'
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
<script type="text/javascript">
$(document).ready( function() {
    $('body').delegate('#export','click',function(){
      var inputSelector='#yw0 .filters input,#yw0 .filters select';
      var data = $(inputSelector).serialize();
      var url='<?php echo CHtml::normalizeUrl(array('user/exportVip'))?>';
      var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    });
});
</script>