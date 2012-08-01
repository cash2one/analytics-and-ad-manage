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
                 $fromDate=date('Y-m-d',$from);
                 $toDate=date('Y-m-d',$to);
                 $serverInput=CHtml::hiddenField('server',$server,array('id'=>'server'));
                 $dateInput=CHtml::hiddenField('from',$fromDate,array('id'=>'from'));
                 $dateInput.=CHtml::hiddenField('to',$toDate,array('id'=>'to'));
                 $this->widget('ext.XJuiDatePicker', array(
                             'name' => 'date',
                             'range'=>'period',
                             'language' =>'zh-CN',
                             'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'maxDate' =>-1,
                             )
                 ));
                echo '<select name="server_id" id="server_id" multiple=true>';
                if($server)
                {
                    $serverArr=explode(',',$server);
                    foreach(Server::Items() as $v=>$name)
                    {
                        $selected=null;
                        if(in_array($v,$serverArr))
                        {
                            $selected="selected";
                        }
                        echo "<option value='{$v}' selected>{$name}</option>";
                    }
                 }
                 else
                 {
                    foreach(Server::Items() as $v=>$name)
                    {
                       echo "<option value='{$v}' selected>{$name}</option>";
                    }
                 }
               echo "</select>";
               echo "&nbsp;&nbsp;充值开始日期:<input type='text'  value='{$fromDate}' class='period' name='fromDate' />";
               echo "&nbsp;&nbsp;充值截止日期:<input type='text'  value='{$toDate}' class='period' name='toDate' />";
               echo "&nbsp;&nbsp;". CHtml::button('确认选择',array('id'=>'getSelect'));
               echo "&nbsp;&nbsp;".CHtml::button('导出表格',array('id'=>'export'));
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' =>$dataProvider,
                            'filter' => false,
                            'columns' => array(
                               array(
                                   'name' => 'id',
                                   'type' => 'raw',
                                   'value' => '$data["id"]',
                                   'filter' => false
                               ),
                                 array(
                                     'name' => 'game_id',
                                     'header' =>'游戏',
                                     'type' => 'raw',
                                     'filter'=>$serverInput,
                                     'value' => '$data["game_name"]'
                                ),
                                array(
                                    'name' => 'name',
                                    'header' =>'区服',
                                    'type' => 'raw',
                                    'filter'=>$dateInput,
                                ),
                                array(
                                     'name' => 'open_time',
                                     'header' =>'开服时间' ,
                                     'type' => 'date',
                                     'filter'=>false,
                                ),
                                array(
                                     'header' => '开服天数',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["open_day"]'
                                ),
                                array(
                                     'header'=>'广告注册',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["ad_register"]'
                                    ),
                                array(
                                     'header'=>'平台注册',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["normal_register"]'
                                    ),
                                array(
                                     'header'=>'转服注册',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["migrate_register"]'
                                    ),
                               	array(
                                     'header'=>'滚服注册',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["chg_svr_register"]'
                                    ),
                                array(
                                     'header'=>'登录用户',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["visit_user"]'
                                    ),
                                array(
                                     'header'=>'回访用户',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["revisit"]'
                                    ),
                                array(
                                    'header'=>'新用户回访用户',
                                    'type' => 'number',
                                    'filter' =>false,
                                    'value' => '$data["new_revisit"]'
                                ),
                                array(
                                    'header'=>'老用户回访用户',
                                    'type' => 'number',
                                    'filter' =>false,
                                    'value' => '$data["old_revisit"]'
                                ),
                                array(
                                     'header'=>'回访率',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => '$data["revisit_percent"]'
                                    ),
                                array(
                                     'header'=>'充值人数',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["payment_user"]'
                                    ),
                                array(
                                     'header'=>'总新充值用户',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["new_pay_1"]'
                                    ),
                               array(
                                     'header'=>'3日新充值用户',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["new_pay_3"]'
                                    ),
                                array(
                                     'header'=>'充值率',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => '$data["payment_percent"]'
                                    ),
                                array(
                                     'header'=>'充值金额',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["payment_amount"]'
                                    ),
                                array(
                                     'header'=>'ARUP',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => '$data["arup"]'
                                     ),
                                 array(
                                     'class' => 'CButtonColumn',
                                     'viewButtonUrl' => 'Yii::app()->controller->createUrl("view",array("id"=>$data["id"]))',
                                     'template' => '{view}',
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
      var url='<?php echo CHtml::normalizeUrl(array('operation/export'))?>';
      var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    });
    jQuery('#server_id').multiselect();

    jQuery('body').delegate('#getSelect','click',function(){
            if($("#server_id option").length==$("#server_id option:checked").length)
            {
             $("#server").val('');
            }
            else
            {
             $("#server").val($("#server_id").val());
            }
            $('#server').trigger('change');
        });
    jQuery('.period').change(function(){
              if(this.name=='fromDate')
              {
                 $('#from').val(this.value);
              }
              else
              {
                 $('#to').val(this.value);
              }
        });
    });
</script> 
