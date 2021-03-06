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
                $this->widget('ext.XJuiDatePicker', array(
                        'name' => 'baseDate',
                        'language' => 'zh-CN',
                        'options' => array(
                            'dateFormat' => 'yy-mm-dd',
                            'minDate' => '-20D',
                            'maxDate' => 0,
                        )
                ));
                $columns = array(
                    array(
                        'name' => 'id',
                        'header' => '广告位id',
                        'type' => 'raw',
                        'value' => '$data["id"]',
                        'filter' => false
                    ),
                    array(
                        'name' => 'channel_id',
                        'header' => $model->getAttributeLabel('channel_id'),
                        'type' => 'raw',
                        'filter' => Channel::dropDownData(),
                        'value' => 'Channel::getName($data["channel_id"])'
                    ),
                    array(
                        'name' => 'name',
                        'header' => $model->getAttributeLabel('name'),
                        'type' => 'raw',
                        'filter' => false
                    )
                );
                $dateColumn = array();
                $baseTime = strtotime($baseDate);
                $first_time = strtotime('-6 days', $baseTime);
                $last_time = strtotime('+1 days', $baseTime);
                $monthFrom=strtotime(date('Y-m-01',$baseTime));
                $monthEnd= strtotime('+1 month', $monthFrom)-1;
                $dateColumn[] = array(
                    'header' =>date('Y-m', $baseTime). '月总数',
                    'type' => 'raw',
                    'filter' => false,
                    'value' => 'Order::countPayByPos($data["id"],' . $monthFrom . ',' . $monthEnd . ')'
                );
                for($i = 6; $i >= 0; $i--)
                {
                    $filter = false;
                    if($i == 0)
                    {
                        $filter = "<input type='text' name='baseDate' id='baseDate' value='{$baseDate}' size='10' />";
                    }
                    $next = $i - 1;
                    $time = strtotime("-{$i} days", $baseTime);
                    $nextTime = strtotime("-{$next} days", $baseTime);
                    $date = date('m-d', $time);
                    $dateColumn[] = array(
                        'header' => $date,
                        'type' => 'number',
                        'filter' => $filter,
                        'value' => 'Order::countPayByPos($data["id"],' . $time . ',' . $nextTime . ')'
                    );
                }
                $columns = array_merge($columns, $dateColumn);
                $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $model->combinedList(),
                        'filter' => $model,
                        'columns' => $columns
                ))?>
            </div>
            <?php echo CHtml::htmlButton('导出报表',array('id'=>'export'))?>
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
 jQuery(document).ready(function(){
    $('#export').click(function(){
       var inputSelector='#yw0 .filters input,#yw0 .filters select';
       var data = $(inputSelector).serialize();
       var url='<?php echo CHtml::normalizeUrl(array('market/exportOrder'))?>';
       var redirect = $.param.querystring(url, data);
      window.location.href=redirect;
    })
 })
</script>
