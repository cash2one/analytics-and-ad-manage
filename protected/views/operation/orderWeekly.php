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
                 $serverInput=CHtml::hiddenField('server',$server,array('id'=>'server'));
                 $weekInput=CHtml::hiddenField('from',$from,array('id'=>'from'));
                 $weekInput.=CHtml::hiddenField('to',$to,array('id'=>'to'));
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
               echo "&nbsp;&nbsp;充值开始周:";
               echo CHtml::dropDownList('fromWeek',$from,array(
                       '1'=>'第一周'
                      ,'2'=>'第二周'
                      ,'3'=>'第三周'
                      ,'4'=>'第四周'
                      ,'5'=>'第五周'
                      ,'6'=>'第六周'
                      ,'7'=>'第七周')
                      ,array('class'=>'period'));
               echo "&nbsp;&nbsp;充值截至周:";
               echo CHtml::dropDownList('toWeek',$to,array(
                      '8'=>'第八周'
                     ,'9'=>'第九周'
                     ,'10'=>'第十周'
                     ,'11'=>'第十一周'
                     ,'12'=>'第十二周'
                     ,'13'=>'第十三周'
                     ,'14'=>'第十四周')
                     ,array('class'=>'period'));
               echo "&nbsp;&nbsp;". CHtml::button('确认选择',array('id'=>'getSelect'));
               echo "&nbsp;&nbsp;".CHtml::button('导出表格',array('id'=>'export'));
               $columns=array(
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
                                    'filter'=>$weekInput,
                                ),
                                array(
                                     'name' => 'open_time',
                                     'header' =>'开服时间' ,
                                     'type' => 'date',
                                     'filter'=>false,
                                ),
                                array(
                                     'header'=>'总充值',
                                     'type'=>'number',
                                     'filter'=>false,
                                     'value'=>'Order::sumPaid($data["id"])'
                                ),
                               );
                 for($i=$from;$i<=$to;$i++)
                 {
                     $week[]=array(
                             'header'=>"第{$i}周充值",
                             'type'=>'number',
                             'filter'=>false,
                             'value'=>'Order::sumPaidByWeek($data["id"],$data["open_time"],'.$i.')'
                             );
                 }
                 $columns=array_merge($columns,$week);
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' =>$dataProvider,
                            'filter' => false,
                            'columns' =>$columns
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
      var url='<?php echo CHtml::normalizeUrl(array('operation/exportOrderWeekly'))?>';
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
              if(this.name=='fromWeek')
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
