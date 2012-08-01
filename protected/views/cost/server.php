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
               $serverInput=CHtml::hiddenField('server',$server,array('id'=>'server'));
               $fromDate=date('Y-m-d',$from);
               $toDate=date('Y-m-d',$to);
               echo "充值开始日期:<input type='text'  value='{$fromDate}' class='period' name='fromDate' />";
               echo "&nbsp;&nbsp;充值截止日期:<input type='text'  value='{$toDate}' class='period' name='toDate' />";
               echo "&nbsp;&nbsp;<button id='submitFilter'>提交</button>&nbsp;";
               echo CHtml::button('导出表格',array('id'=>'export'));
               $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>Server::costList($from,$to,$server)
                           ,'filter'=>false
                           ,'columns'=>array(
                               array(
                                   'name'=>'game_id',
                                   'header'=>'{游戏}',
                                   'type'=>'raw',
                                   'filter'=>"<input type='hidden' name='from' value='{$fromDate}' id='from'>",
                                   'value'=>'$data["game_name"]'
                                   ),
                               array(
                                   'name'=>'server_id',
                                   'header'=>'{区服}',
                                   'type'=>'raw',
                                   'filter'=>"<input type='hidden' name='to' value='{$toDate}' id='to'>",
                                   'value'=>'$data["server_name"]'
                                   ),
                                array(
                                    'name'=>'open_time',
                                   'header'=>'{开服时间}',
                                   'type'=>'date',
                                   'filter'=>$serverInput,
                                   'value'=>'$data["open_time"]'
                                   ),
                               array(
                                   'name'=>'cost',
                                   'header'=>'{广告成本}',
                                   'type'=>'number',
                                   'filter'=>false,
                                   'value'=>'$data["cost"]'
                                   ),
                               array(
                                   'name'=>'register',
                                   'header'=>'{注册}',
                                   'type'=>'number',
                                   'filter'=>false,
                                   'value'=>'$data["register"]'
                                   ),
                               array(
                                   'name'=>'cpa',
                                   'header'=>'{CPA}',
                                   'type'=>'raw',
                                   'filter'=>false,
                                   'value'=>'$data["cpa"]'
                                   ),
                               array(
                                   'name'=>'revisit',
                                   'header'=>'{回访人数}',
                                   'type'=>'number',
                                   'filter'=>false,
                                   'value'=>'$data["revisit"]'
                                   ),
                               array(
                                   'header'=>'回访成本',
                                   'type'=>'raw',
                                   'value'=>'$data["revisit_cost"]'
                                   ),
                               array(
                                   'header'=>'回访率',
                                   'type'=>'raw',
                                   'value'=>'$data["revisit_percent"]. "%"'
                                   ),
                               array(
                                   'header'=>'付费率',
                                   'type'=>'raw',
                                   'value'=>'$data["payment_percent"]. "%"'
                                   ),
                               array(
                                   'header'=>'广告充值额',
                                   'type'=>'number',
                                   'value'=>'$data["payment"]'
                                   ),
                               array(
                                   'header'=>'游戏充值',
                                   'type'=>'number',
                                   'value'=>'$data["amount"]'
                                    ),
                               array(
                                   'header'=>'分成利润',
                                   'type'=>'number',
                                   'value'=>'$data["income"]'
                                   ),
                               array(
                                   'name'=>'profit_percent',
                                   'header'=>'{回款率}',
                                   'type'=>'raw',
                                   'filter'=>false,
                                   'value'=>'$data["profit_percent"]. "%"'
                                   )
                               )
                )); ?> 
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
<script type='text/javascript'>
  $(document).ready(function(){
    $('body').delegate('#export','click',function(){
      var inputSelector='#yw0 .filters input,#yw0 .filters select';
      var data = $(inputSelector).serialize();
      var url='<?php echo CHtml::normalizeUrl(array('cost/exportServer'))?>';
      var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    });
    $('#server_id').multiselect();

    $('body').delegate('#submitFilter','click',function(){
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
    
      $('.period').change(function(){
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
