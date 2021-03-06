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
               <button id='quickBtn'>快捷修正</button>
                <?php
                $this->widget('ext.XJuiDatePicker', array(
                           'name' => 'date',
                           'range'=>'period',
                           'language' =>'zh-CN',
                           'options' => array(
                              'dateFormat' => 'yy-mm-dd',
                              'maxDate' =>0,
                           )
               ));
                $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$model->monthList()
                           ,'filter'=>false
                           ,'columns'=>array(
                                array(
                                   'header'=>'成本'
                                  ,'type'=>'raw'
                                  ,'value'=>'$data["cost"]'
                                   )
                               ,array(
                                    'header'=>'修改点',
                                    'type'=>'boolean',
                                    'value'=>'$data["modify"]'
                                    )
                               ,array(
                                   'header'=>'日期'
                                  ,'type'=>'date'
                                  ,'value'=>'$data["date"]'
                                   )
                               ,array(
                                     'class' => 'CButtonColumn',
                                     'template' => '{change}',
                                     'buttons' => array(
                                           'change' => array(
                                               'label' => '修正',
                                               'url' => '"javascript://". $data["cost"]."//". $data["date"]',
                                               'click' => 'function(){initData($(this).attr("href"), $("#page").val())}',
                                               'imageUrl' => '/images/arrow_switch.png'
                                            ),
                                     ))
                               ))); 
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
 <?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'changed_cost',
    'options' => array(
        'title' => '修正成本',
        'autoOpen' => false
    ),
));
$form = $this->beginWidget('CActiveForm',array(
                'enableAjaxValidation'=>false,
                ));
?>
<div style="font-size:12px;line-height:26px;">
输入修正值:<br />
<?php echo $form->textField($model,'cost',array('id' => 'X_cost_id'))?><br />
<?php echo $form->hiddenField($model,'date',array('id' => 'X_date_id'))?><br />
<input type="button" id="change_submit" value="确认" />
<input type="button" id="change_cancel" value="取消"/>
</div>
<?php
$this->endWidget();
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'quick_add',
    'options' => array(
        'title' => '快速修正',
        'autoOpen' => false,
        'width'=>400,
        'height'=>300,
    ),
));?>
修正开始日期:<input type='text'  value='' class='period' id='from' name='from' /></br>
<br/>
修正截止日期:<input type='text'  value='' class='period' id='to' name='to' /></br>
<br/>
每日成本&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<input id='cost' type='text'  value='' name='cost' /></br>
<br/>
<button id='submitFixture'>提交</button>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>

<script type='text/javascript'>
var channelId=<?php echo $channel->id ?>;
$(document).ready(function(){
 $('#change_submit').click(function(){
      var data={};
      data.cost=$('#X_cost_id').val();
      if(data.cost.length!=0)
      {
       var costDate=$('#X_date_id').val();
        $.post(
                '/channel/updateCost/id/'+channelId+'/date/'+costDate,
                data,
                function(response){
                     $("#changed_cost").dialog("close");
                     location.reload();
                  });
      }
      else
      {
        alert('成本不能为空');
      }
  });

 $('#submitFixture').click(function(){
      var data={};
      data.cost=$('#cost').val();
      data.from=$('#from').val();
      data.to=$('#to').val();
      if(data.cost.length==0)
      {
        alert('成本不能为空');
      }
      else if(data.from.length==0)
      {
        alert('开始时间不能为空');
      }
      else if(data.to.length==0)
      {
        alert('截止时间不能为空');
      }
      else
      {
         $.post(
                '/channel/updateCostQuick/id/'+channelId,
                data,
                function(response){
                     $("#quick_add").dialog("close");
                     location.reload();
        });
      }
 });

 $('#quickBtn').click(function(){
      $("#quick_add").dialog("open");
    });
  $('#change_cancel').click(function(){
      $("#changed_cost").dialog("close");
  });
});

function initData(url)
{
    var _tmp = url.split('\/\/');
    $("#changed_cost").dialog("open");
    $('#X_cost_id').val(_tmp[1]);
    $('#X_date_id').val(_tmp[2]);
}
</script>
