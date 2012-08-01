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
               $selectButton=CHtml::button('确认选择',array('id'=>'getSelect'));
               $exportButton=CHtml::button('导出表格',array('id'=>'export'));
               $dateFilter=CHtml::dropDownList('to',$to
                       ,array('7'=>'第一周内'
                              ,'14'=>'第二周内'
                              ,'21'=>'第三周内'
                              ,'28'=>'第四周内'
                              ,'35'=>'第五周内'
                              ,'42'=>'第六周内'
                              ,'49'=>'第七周内'
                              ,'56'=>'第八周内'
                              ,'63'=>'第九周内'
                              ,'30'=>'第一月内'
                              ,'60'=> '第二月内'
                              ,'90'=> '第三月内'
                              ,'120'=>'第四月内'
                              ,'150'=>'第五月内'
                              ,'180'=>'第六月内'
                           ));
               $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$dataProvider
                           ,'filter'=>false
                           ,'columns'=>array(
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
                                    ,'filter'=>$selectButton
                                    ,'value'=>'$data["open_time"]'
                                    ),
                                array(
                                      'header'=>'游戏分成'
                                      ,'type'=>'raw'
                                      ,'value'=>'$data["share"]'
                                    ),
                                array(
                                      'header'=>'广告成本'
                                      ,'type'=>'number'
                                      ,'value'=>'$data["cost"]'
                                     ),
                                array(
                                       'name'=>'register'
                                      ,'header'=>'{注册数}'
                                      ,'type'=>'number'
                                      ,'filter'=>false
                                      ,'value'=>'$data["register"]'
                                    ),
                                array(
                                       'name'=>'cpa'
                                      ,'header'=>'{CPA}'
                                      ,'type'=>'raw'
                                      ,'filter'=>false
                                      ,'value'=>'$data["cpa"]'
                                    ),
                                array(
                                      'header'=>'回访人数'
                                      ,'type'=>'number'
                                      ,'value'=>'$data["revisit"]'
                                    ),
                                array(
                                      'header'=>'回访率'
                                      ,'type'=>'raw'
                                      ,'value'=>'($data["register"]>0)?round($data["revisit"]*100/$data["register"],2)."%":"0%"'
                                    ),
                                array(
                                      'header'=>'回访成本'
                                      ,'type'=>'raw'
                                      ,'value'=>'($data["revisit"]>0)?round($data["cost"]/$data["revisit"],1):0'
                                    ),
                                array(
                                      'header'=>'开服天数'
                                      ,'type'=>'number'
                                      ,'value'=>'ceil(( (time()-$data["open_time"])/86400))'
                                    ),
                                array(
                                      'header'=>'总利润'
                                      ,'type'=>'number'
                                      ,'value'=>'$data["lifetime_income"]'
                                    ),
                                array(
                                      'header'=>'总回款率'
                                      ,'type'=>'raw'
                                      ,'value'=>'$data["lifetime_profit_percent"]."%"'
                                    ),
                                array(
                                      'header'=>'充值人数'
                                      ,'type'=>'raw'
                                      ,'filter'=>$dateFilter
                                      ,'value'=>'Server::getPaymentUser($data["server_id"],$data["channel_id"],$data["week"],$data["month"])'
                                    ),
                                array(
                                      'header'=>'付费率'
                                      ,'type'=>'raw'
                                      ,'value'=>'Server::getPaymentPercent($data["server_id"],$data["channel_id"],$data["mode"],$data["week"]?$data["week"]:$data["month"])."%"'
                                    ),
                                array(
                                      'header'=>'充值金额'
                                      ,'type'=>'number'
                                      ,'value'=>'Server::getPaymentAmount($data["server_id"],$data["channel_id"],$data["week"],$data["month"])'
                                    ),
                                array(
                                      'header'=>'分成利润'
                                      ,'type'=>'number'
                                      ,'value'=>'$data["current_income"]'
                                    ),
                               array(
                                      'name'=>'current_profit_percent'
                                     ,'header'=>'{回款率}'
                                     ,'type'=>'raw'
                                     ,'filter'=>$exportButton
                                     ,'value'=>'"<span style=\'color:".$data["color"]."\'>".$data["current_profit_percent"]."%"."</span>"'
                                    ),
                                array(
                                     'class' => 'CButtonColumn',
                                     'viewButtonUrl' => 'Yii::app()->controller->createUrl("view",array("id"=>$data["channel_id"]
                                             ,"serverId"=>$data["server_id"]))',
                                     'viewButtonOptions'=>array('class'=>'view','target'=>'_blank'),
                                     'template' => '{view}'
                                    )
                                )
                ));
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
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<div class="clear">&nbsp;</div>
<script type="text/javascript">
$(document).ready( function() {
    $('body').delegate('#export','click',function(){
       var inputSelector='#yw0 .filters input,#yw0 .filters select';
       var data = $(inputSelector).serialize();
       var url='<?php echo CHtml::normalizeUrl(array('cost/exportIndex'))?>';
       var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    })
        jQuery('#server_id,#channel_id').multiselect();
        jQuery('body').delegate('#serverBtn','click',function(){
            $("#select_server").dialog("open");
            });
        jQuery('body').delegate('#channelBtn','click',function(){
            $("#select_channel").dialog("open");
            });
        jQuery('body').delegate('#getSelect','click',function(){
            if($("#server_id option").length==$("#server_id option:checked").length)
            {
             $("#server").val('')
            }
            else
            {
             $("#server").val($("#server_id").val());
            }

            if($("#channel_id option").length==$("#channel_id option:checked").length)
            {
             $("#channel").val('')
            }
            else
            {
             $("#channel").val($("#channel_id").val())
            }
            $('#server').trigger('change');
        });
        });
</script>
