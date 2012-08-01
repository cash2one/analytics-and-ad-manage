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
               echo "&nbsp;&nbsp;注册开始日期:<input type='text'  value='{$fromDate}' class='period' name='fromDate' />";
               echo "&nbsp;&nbsp;注册截止日期:<input type='text'  value='{$toDate}' class='period' name='toDate' />";
               echo "&nbsp;&nbsp;". CHtml::button('确认选择',array('id'=>'getSelect'));
               echo "&nbsp;&nbsp;".CHtml::button('导出表格',array('id'=>'export'));
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' =>$dataProvider,
                            'filter' => false,
                            'columns' => array(
                               array(
                                   'name' => 'user_name',
                                   'header'=>'用户名',
                                   'type' => 'raw',
                                   'value' => '$data["user_name"]',
                                   'filter' => false
                               ),
                               array(
                                   'name' => 'game_id',
                                   'header'=>'游戏',
                                   'type' => 'raw',
                                   'value' => 'Game::getName($data["game_id"])',
                                   'filter' => false
                               ),
                               array(
                                   'name' => 'server_id',
                                   'header'=>'区服',
                                   'type' => 'raw',
                                   'value' => 'Server::getName($data["server_id"])',
                                   'filter' => false
                               ),
                               array(
                                     'name' => 'channel_id',
                                     'header' =>'注册渠道',
                                     'type' => 'raw',
                                     'filter'=>$serverInput,
                                     'value' => 'Channel::getName($data["channel_id"])'
                                ),
                                array(
                                    'name' => 'reg_time',
                                    'header' =>'注册时间',
                                    'type' => 'date',
                                    'filter'=>$dateInput,
                                ),
                                array(
                                     'name' => 'max_paid',
                                     'header' =>'单笔最高' ,
                                     'type' => 'number',
                                     'filter'=>false,
                                ),
                                array(
                                     'name' => 'avg_paid',
                                     'header' =>'平均每笔充值' ,
                                     'type' => 'number',
                                     'filter'=>false,
                                ),
                                array(
                                     'name' => 'sum_paid',
                                     'header' =>'充值总额' ,
                                     'type' => 'number',
                                     'filter'=>false,
                                ),
                                array(
                                     'name' => 'login_times',
                                     'header' =>'登录次数' ,
                                     'type' => 'number',
                                     'filter'=>false,
                                ),
                                array(
                                     'header' =>'最后登录游戏区服' ,
                                     'type' => 'raw',
                                     'filter'=>false,
                                     'value'=>'Visit::lastGameServer($data["user_id"])'
                                ),
                                array(
                                     'header' =>'最后登录时间' ,
                                     'type' => 'raw',
                                     'filter'=>false,
                                     'value'=>'Visit::lastVisitTime($data["user_id"])'
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
      var url='<?php echo CHtml::normalizeUrl(array('operation/exportVip'))?>';
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
