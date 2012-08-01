<ddiv id="content-outer"><!-- start content -->
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
        $from= strtotime($startDate);
        $to= strtotime($endDate. '23:59:59');
        $report='';
        foreach(PaymentMethod::model()->findAll('id<>1002') as $method)
        {
           $report .="<h4>{$method->gateway}:". Payment::sumByMethod($method->id,$from,$to)."</h4>";
        }
        $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>Payment::paymentList($from,$to)
                           ,'filter'=>false
                           ,'columns'=>array(
                                array(
                                     'name'=>'game_name'
                                    ,'type'=>'raw'
                                    ,'header'=>'游戏'
                                    ,'filter'=>$dateFilter1
                                   ),
                                array(
                                    'name'=>'name'
                                   ,'type'=>'raw'
                                   ,'header'=>'区服'
                                   ,'filter'=>$dateFilter2
                                ),
                                array(
                                    'name'=>'payment'
                                   ,'type'=>'raw'
                                   ,'header'=>'充值总额'
                                   ,'filter'=>$report
                                ),
                                array(
                                    'name'=>'open_time'
                                   ,'type'=>'raw'
                                   ,'header'=>'开服天数'
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
