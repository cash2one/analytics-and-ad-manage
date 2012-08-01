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
               $channelButton=CHtml::button('选择渠道',array('id'=>'channelBtn')).CHtml::hiddenField('channel',$channel,array('id'=>'channel'));
               $serverButton=CHtml::button('选择区服',array('id'=>'serverBtn')).CHtml::hiddenField('server',$server,array('id'=>'server'));
               $dateButton=CHtml::button('周期',array('id'=>'dateBtn')).CHtml::hiddenField('from',$fromWeek,array('id'=>'from'))
               .CHtml::hiddenField('to',$toWeek,array('id'=>'to'));
               $selectButton=CHtml::button('确认选择',array('id'=>'getSelect'));
               $exportButton=CHtml::button('导出表格',array('id'=>'export'));
               $columns=array(
                                array(
                                    'name'=>'game_id'
                                   ,'header'=>'{游戏}'
                                   ,'type'=>'raw'
                                   ,'filter'=>false
                                   ,'value'=>'$data["game_name"]'
                                ),
                                array(
                                    'name'=>'server_id'
                                   ,'header'=>'{区服}'
                                   ,'type'=>'raw'
                                   ,'filter'=>$serverButton
                                   ,'value'=>'$data["server_name"]'
                                    ),
                                array(
                                    'name'=>'channel_id'
                                   ,'header'=>'{渠道}'
                                   ,'type'=>'raw'
                                   ,'filter'=>$channelButton
                                   ,'value'=>'$data["channel_name"]'
                                    ),
                                array(
                                     'header'=>'开服时间'
                                    ,'type'=>'date'
                                    ,'filter'=>$dateButton
                                    ,'value'=>'$data["open_time"]'
                                    ),
                                array(
                                    'header'=>'开服天数'
                                    ,'type'=>'number'
                                    ,'filter'=>$selectButton
                                    ,'value'=>'ceil(( (time()-$data["open_time"])/86400))'
                                    ),
                                array(
                                      'name'=>'lifetime_profit_percent'
                                      ,'header'=>'{总回款率}'
                                      ,'type'=>'raw'
                                      ,'filter'=>false
                                      ,'value'=>'$data["lifetime_profit_percent"]."%"'
                                    )
                               );
               for($i=$fromWeek;$i<=$toWeek;$i+=7)
               {
                   $no=$i/7;
                   $columns[]=array(
                              'header'=>"第{$no}周"
                             ,'type'=>'raw'
                             ,'value'=>'Server::getColorProfit($data["server_id"], $data["channel_id"],'.$no.
                             ',Server::getIncome($data["server_id"],$data["channel_id"],' .$no.'),$data["cost"])'
                           );
               }
               $lastIndex=count($columns)-1;
               $columns[$lastIndex]['filter']=$exportButton;
               $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$dataProvider
                           ,'filter'=>false
                           ,'columns'=>$columns
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
 <?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'select_server',
    'options' => array(
        'title' => '选取区服',
        'autoOpen' => false,
        'width'=>400,
        'height'=>300,
    ),
));?>
<select name="server_id" id="server_id" multiple=true>
<?php
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
?>
</select>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'select_channel',
    'options' => array(
        'title' => '选取渠道',
        'autoOpen' => false,
        'width'=>400,
        'height'=>300,
    ),
));?>
 <select name="channel_id" id="channel_id" multiple=true>
<?php
if($channel)
{
   $channelArr=explode(',',$channel);
   foreach(Channel::dropDownData() as $v=>$name)
   {
       $selected=null;
       if(in_array($v,$channelArr))
       {
           $selected="selected";
       }
       echo "<option value='{$v}' selected>{$name}</option>";
   }
}
else
{
   foreach(Channel::dropDownData() as $v=>$name)
   {
      echo "<option value='{$v}' selected>{$name}</option>";
   }
}
?>
</select>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
<?php
 $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'select_date',
    'options' => array(
        'title' => '选取区间',
        'autoOpen' => false,
        'width'=>400,
        'height'=>300,
    ),
));
           echo CHtml::dropDownList('fromDrop',$fromWeek
                       ,array('7'=>'第1周'
                              ,'14'=>'第2周'
                              ,'21'=>'第3周'
                              ,'28'=>'第4周'
                              ,'35'=>'第5周'
                              ,'42'=>'第6周'
                              ,'49'=>'第7周'
                           ));
            echo CHtml::dropDownList('toDrop',$toWeek
                       ,array('56'=>'第8周'
                              ,'63'=>'第9周'
                              ,'70'=>'第10周'
                              ,'77'=>'第11周'
                              ,'84'=>'第12周'
                              ,'91'=>'第13周'
                              ,'98'=>'第14周'
                           ));
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<div class="clear">&nbsp;</div>
<script type="text/javascript">
$(document).ready( function() {
    $('body').delegate('#export','click',function(){
      var inputSelector='#yw0 .filters input,#yw0 .filters select';
      var data = $(inputSelector).serialize();
      var url='<?php echo CHtml::normalizeUrl(array('cost/exportWeekly'))?>';
      var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    });
        jQuery('#server_id,#channel_id').multiselect();
        jQuery('body').delegate('#serverBtn','click',function(){
            $("#select_server").dialog("open");
            });
        jQuery('body').delegate('#channelBtn','click',function(){
            $("#select_channel").dialog("open");
            });
        jQuery('body').delegate('#dateBtn','click',function(){
            $("#select_date").dialog("open");
            });
        jQuery('body').delegate('#getSelect','click',function(){
            if($("#server_id option").length==$("#server_id option:checked").length)
            {
             $("#server").val('')
            }
            else
            {
             $("#server").val($("#server_id").val())
            }
            if($("#channel_id option").length==$("#channel_id option:checked").length)
            {
             $("#channel").val('')
            }
            else
            {
             $("#channel").val($("#channel_id").val())
            }
            $('#from').val($('#fromDrop').val());
            $('#to').val($('#toDrop').val());
            $('#server').trigger('change');
        });
        });
</script>
