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
                 $gameInput=CHtml::hiddenField('game',$game,array('id'=>'game'));
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
               echo '<select name="game_id" id="game_id" multiple=true>';
               if($game)
                {
                   $gameArr=explode(',',$game);
                   foreach(Game::dropDownData() as $v=>$name)
                   {
                       $selected=null;
                       if(in_array($v,$gameArr))
                       {
                           $selected="selected";
                       }
                       echo "<option value='{$v}' selected>{$name}</option>";
                   }
                }
                else
                {
                   foreach(Game::dropDownData() as $v=>$name)
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
                                     'name' => 'name',
                                     'header' =>'游戏',
                                     'type' => 'raw',
                                     'filter'=>$gameInput,
                                     'value' => '$data["name"]'
                                ),
                                array(
                                     'header'=>'广告注册',
                                     'type' => 'number',
                                     'filter'=>$dateInput,
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
      var url='<?php echo CHtml::normalizeUrl(array('operation/exportGame'))?>';
      var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    });
    jQuery('body').delegate('#serverBtn','click',function(){
            $("#select_server").dialog("open");
            });
    jQuery('#game_id').multiselect();
    jQuery('body').delegate('#getSelect','click',function(){
            if($("#game_id option").length==$("#game_id option:checked").length)
            {
             $("#game").val('');
            }
            else
            {
             $("#game").val($("#game_id").val());
            }
            $('#game').trigger('change');
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
