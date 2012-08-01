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
                             'name' => 'date',
                             'range'=>'period',
                             'language' =>'zh-CN',
                             'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'maxDate' =>-1,
                             )
                 ));
                 $fromDate=date('Y-m-d',$from);
                 $toDate=date('Y-m-d',$to);
                 $filterFrom="<input type='text'  value='{$fromDate}' class='period' name='fromDate' />";
                 $filterTo="<input type='text'  value='{$toDate}' class='period' name='toDate' />";
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' =>$dataProvider,
                            'filter' => false,
                            'columns' => array(
                               array(
                                   'name' => 'date',
                                   'header'=>'日期',
                                   'type' => 'date',
                                   'value' => '$data["date"]',
                                   'filter' => false
                               ),
                               array(
                                   'header'=>'渠道',
                                   'type' => 'text',
                                   'filter' =>CHtml::activeDropDownList($model, "id", Channel::dropDownData(),array("id" => false, "prompt" => "")),
                                   'value' => '"'.$model->name.'"',
                                   ),
                               array(
                                     'header'=>'点击',
                                     'name' => 'click',
                                     'type' => 'number',
                                     'filter' =>$filterFrom,
                                    ),
                               array(
                                     'header'=>'注册',
                                     'name' => 'register',
                                     'type' => 'number',
                                     'filter' =>$filterTo,
                                    ),
                                array(
                                     'header'=>'登录',
                                     'name' => 'visit',
                                     'type' => 'number',
                                     'filter' =>false,
                                    ),
                                array(
                                     'header'=>'新用户登录',
                                     'name' => 'register_visit',
                                     'type' => 'number',
                                     'filter' =>false,
                                    ),
                                array(
                                     'header'=>'老用户登录',
                                     'name' => 'normal_visit',
                                     'type' => 'number',
                                     'filter' =>false,
                                    ),
                                array(
                                     'header'=>'当天充值人数',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["payment_user"]'
                                    ),
                                 array(
                                     'header'=>'当天充值金额',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["payment_amount"]'
                                    ),
                                 array(
                                     'header'=>'注册回访人数',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => 'UserServer::revisitByChannel($data["channel_id"],$data["date"],$data["date"]+86399)'
                                    ),
                                 array(
                                     'header'=>'注册回访人数',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => '$data["register"]?round(UserServer::revisitByChannel($data["channel_id"],$data["date"],$data["date"]+86399)*100/$data["register"],1)."%":"0%"'
                                      ),
                                 array(
                                     'header'=>'注册总充值人数',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => 'Order::countPayUserByChannel($data["channel_id"],$data["date"],$data["date"]+86399)'
                                      ),
                                 array(
                                     'header'=>'付费率',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => '$data["register"]?round(Order::countPayUserByChannel($data["channel_id"],$data["date"],$data["date"]+86399)*100/$data["register"],1)."%":"0%"'
                                      ),
                                 array(
                                     'header'=>'注册总充值金额',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => 'Order::countPayByChannel($data["channel_id"],$data["date"],$data["date"]+86399)'
                                      ),
                                 array(
                                     'header'=>'广告素材',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => 'Channel::listMaterial($data["channel_id"],$data["date"],$data["date"]+86399)'
                                    ),
                                 array(
                                     'header'=>'开服备注',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => 'Channel::listServer($data["channel_id"],$data["date"],$data["date"]+86399)'
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
       var url='<?php echo CHtml::normalizeUrl(array('channel/dailyExport'))?>';
       var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    })
 })
</script>  
