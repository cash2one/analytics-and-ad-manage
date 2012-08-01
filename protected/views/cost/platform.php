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
               echo "注册开始日期:<input type='text'  value='{$fromDate}' class='period' name='fromDate' />";
               echo "&nbsp;&nbsp;注册截止日期:<input type='text'  value='{$toDate}' class='period' name='toDate' />";
               echo "&nbsp;&nbsp;<button id='submitFilter'>提交</button>";
               $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>Channel::costPlatform($from,$to)
                           ,'filter'=>false
                           ,'columns'=>array(
                               array(
                                   'name'=>'id',
                                   'header'=>'广告来源',
                                   'type'=>'raw',
                                   'filter'=>"<input type='hidden' name='from' value='{$fromDate}' id='from'>",
                                   'value'=>'$data["name"]'
                                   ),
                               array(
                                   'header'=>'注册数',
                                   'type'=>'number',
                                   'filter'=>"<input type='hidden' name='to' value='{$toDate}' id='to'>",
                                   'value'=>'$data["register"]'
                                   ),
                               array(
                                   'header'=>'注册成本',
                                   'type'=>'raw',
                                   'value'=>'$data["cpa"]'
                                   ),
                               array(
                                   'header'=>'广告费用',
                                   'type'=>'number',
                                   'value'=>'$data["cost"]'
                                   ),
                               array(
                                   'header'=>'注册用户充值',
                                   'type'=>'number',
                                   'value'=>'$data["payment"]'
                                   ),
                               array(
                                   'header'=>'分成后利润',
                                   'type'=>'number',
                                   'value'=>'$data["income"]'
                                   ),
                               array(
                                   'header'=>'回款率',
                                   'type'=>'raw',
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
        $('#submitFilter').click(function(){
               $('#to').trigger('change');
              });
  });
</script>
