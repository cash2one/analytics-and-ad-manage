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
                 $this->widget('ext.XJuiDatePicker', array(
                             'name' => 'date1',
                             'range'=>'period1',
                             'language' =>'zh-CN',
                             'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'maxDate' =>-1,
                             )
                 ));
                $fromDate=date('Y-m-d',$from);
                $toDate=date('Y-m-d',$to);
                $fromDate1=date('Y-m-d',$from1);
                $toDate1=date('Y-m-d',$to1);
               echo "注册开始日期:<input type='text'  value='{$fromDate}' class='period' name='fromDate' />";
               echo "注册截止日期:<input type='text'  value='{$toDate}' class='period' name='toDate' />";
               echo "充值开始日期:<input type='text'  value='{$fromDate1}' class='period1' name='fromDate1' />";
               echo "充值截止日期:<input type='text'  value='{$toDate1}' class='period1' name='toDate1' />";
               echo "&nbsp;&nbsp;<button id='submitFilter'>提交</button>&nbsp;";
               echo CHtml::button('导出表格',array('id'=>'export'));
               $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>Channel::costList($from,$to,$from1,$to1)
                           ,'filter'=>false
                           ,'columns'=>array(
                               array(
                                   'name'=>'channel_id',
                                   'header'=>'{渠道}',
                                   'type'=>'raw',
                                   'filter'=>"<input type='hidden' name='from' value='{$fromDate}' id='from'>",
                                   'value'=>'$data["name"]'
                                   ),
                               array(
                                   'header'=>'每日单价',
                                   'type'=>'number',
                                   'filter'=>"<input type='hidden' name='to' value='{$toDate}' id='to'>",
                                   'value'=>'$data["now_cost"]'
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
                                   'header'=>'{注册数}',
                                   'type'=>'number',
                                   'filter'=>"<input type='hidden' name='from1' value='{$fromDate1}' id='from1'>",
                                   'value'=>'$data["register"]'
                                   ),
                               array(
                                   'name'=>'cpa',
                                   'header'=>'{CPA}',
                                   'type'=>'raw',
                                   'filter'=>"<input type='hidden' name='to1' value='{$toDate1}' id='to1'>",
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
                                   'header'=>'回访率',
                                   'type'=>'raw',
                                   'value'=>'$data["revisitPercent"]."%"'
                                 ),
                               array(
                                   'header'=>'回访成本',
                                   'type'=>'raw',
                                   'value'=>'$data["revisitCost"]'
                                 ),
                               array(
                                   'name'=>'payment',
                                   'header'=>'{充值金额}',
                                   'filter'=>false,
                                   'type'=>'number',
                                   'value'=>'$data["payment"]'
                                 ),
                               array(
                                   'name'=>'income',
                                   'header'=>'{充值利润}',
                                   'type'=>'number',
                                   'filter'=>false,
                                   'value'=>'$data["income"]'
                                 ),
                               array(
                                   'name'=>'profitPercent',
                                   'header'=>'{回款率}',
                                   'type'=>'raw',
                                   'filter'=>false,
                                   'value'=>'$data["profitPercent"]."%"'
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
      var url='<?php echo CHtml::normalizeUrl(array('cost/exportChannel'))?>';
      var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
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
        $('.period1').change(function(){
              if(this.name=='fromDate1')
              {
                 $('#from1').val(this.value);
              }
              else
              {
                 $('#to1').val(this.value);
              }
         });
          $('#submitFilter').click(function(){
               $('#to').trigger('change');
              });
  });
</script>
