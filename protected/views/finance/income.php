<div id="content-outer"><!-- start content -->
<div id="content"><!--  start page-heading -->
<div id="page-heading"></div>
<!-- end page-heading -->
<table border="0" width="100%" cellpadding="0" cellspacing="0"
    id="content-table">
    <tr>
        <th rowspan="3" class="sized"><img
            src="/images/shared/side_shadowleft.jpg" width="20" height="300"
            alt="" /></th>
        <th class="topleft"></th>
        <td id="tbl-border-top">&nbsp;</td>
        <th class="topright"></th>
        <th rowspan="3" class="sized"><img
            src="/images/shared/side_shadowright.jpg" width="20" height="300"
            alt="" /></th>
    </tr>
    <tr>
        <td id="tbl-border-left"></td>
        <td><!--  start content-table-inner ...................................................................... START -->
        <div id="content-table-inner"><!--  start table-content  -->
        <div id="table-content">
        <?php
           echo "&nbsp;&nbsp;".CHtml::button('导出表格',array('id'=>'export'));
           $this->widget('ext.XJuiDatePicker', array(
                             'name' => 'perioddate',
                             'range' => 'peroid',
                             'language' => 'zh-CN',
                             'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'minDate' => '-20D',
                                'maxDate' => 0,
                             )
                 ));
        $dateFilter1="<input type='text' name='start_date' id='start_date' value='{$startDate}' class='peroid' size='8' />";
        $dateFilter2="<input type='text' name='end_date' id='end_date' value='{$endDate}' class='peroid' size='8' />";
        $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$dataProvider
                           ,'filter'=>false
                           ,'columns'=>array(
                                array(
                                    'name'=>'name'
                                   ,'type'=>'raw'
                                   ,'header'=>'游戏'
                                   ,'filter'=>$dateFilter1
                                ),
                                array(
                                    'name'=>'paid_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'平台充值金额'
                                   ,'filter'=>$dateFilter2
                                ),
                                array(
                                    'name'=>'yeepay_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'易宝充值'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'99bill_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱充值'
                                   ,'filter'=>false
                                ),
                                 array(
                                    'name'=>'99szx_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱神州行'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'yeepay_fee'
                                   ,'type'=>'raw'
                                   ,'header'=>'易宝手续费'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'99bill_fee'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱手续费'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'99szx_fee'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱神州行手续费'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'yeepay_income'
                                   ,'type'=>'raw'
                                   ,'header'=>'易宝收入'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'99bill_income'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱收入'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'99szx_income'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱神州行收入'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'income_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'总收入'
                                   ,'filter'=>false
                                )
                               )
                            ))
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
<script type="text/javascript">
$(document).ready( function() {
    $('body').delegate('#export','click',function(){
      var inputSelector='#yw0 .filters input,#yw0 .filters select';
      var data = $(inputSelector).serialize();
      var url='<?php echo CHtml::normalizeUrl(array('finance/exportIncome'))?>';
      var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    });
});
</script> 
