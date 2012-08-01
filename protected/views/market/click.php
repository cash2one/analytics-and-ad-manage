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
                        'value' => 'Pos::getKey($data["id"])',
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
                    ),
                    array(
                        'header' => '峰值',
                        'type' => 'raw',
                        'filter' => false,
                        'value' => 'Click::maxClick($data["id"])'
                    ));
                $dateColumn = array();
                $baseTime = strtotime($baseDate);
                $begin_time = $baseTime-86400*7;
                $end_time=$baseTime+86400;
                $dateColumn[] = array(
                    'header' => date('m-d', $begin_time). '至'. date('m-d', $baseTime). '总数',
                    'type' => 'raw',
                    'filter' => false,
                    'value' => 'Click::countClickByPos($data["id"],' . $begin_time . ',' . $end_time . ')'
                );
                for($i = 7;$i >= 0;$i--)
                {
                    $filter = false;
                    if($i == 0)
                    {
                        $filter = "<input type='text' name='baseDate' id='baseDate' value='{$baseDate}' size='10' />";
                    }
                    $time = $baseTime-86400*$i;
                    $nextTime = $baseTime-(86400*($i-1));
                    $date = date('m-d', $time);
                    $dateColumn[] = array(
                        'header' => $date,
                        'type' => 'number',
                        'filter' => $filter,
                        'value' => 'Click::countClickByPos($data["id"],' . $time . ',' . $nextTime . ')'
                    );
                }
                $columns = array_merge($columns, $dateColumn);
                $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $model->combinedList(),
                        'filter' => $model,
                        'columns' => $columns
                ))?>
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
