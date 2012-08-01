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
                             'name' => 'perioddate',
                             'range' => 'peroid',
                             'language' => 'zh-CN',
                             'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'minDate' => '-20D',
                                'maxDate' => 0,
                             )
                 ));
                 $filter1 = "<input type='text' name='start_date' id='start_date' value='{$model->startDate}' class='peroid' size='1' />";
                 $filter2 = "<input type='text' name='end_date' id='end_date' value='{$model->endDate}' class='peroid' size='1' />";
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $model->search(),
                            'filter' => $model,
                            'columns' => array(
                                   array(
                                       'name' => 'id',
                                       'header' => $model->getAttributeLabel('id'),
                                       'type' => 'raw',
                                       'value' => '$data["id"]',
                                       'filter' => false
                                   ),                                  
                                   array(
                                       'name' => 'user_name',
                                       'header' => $model->getAttributeLabel('user_name'),
                                       'type' => 'raw',
                                       'value' => '$data["user_name"]',
                                       'filter' =>  CHtml::textField('EmailRecord[user_name]', $model->user_name, array('size' => 1))
                                   ),                                  
                                   array(
                                       'name' => 'past_email',
                                       'header' => $model->getAttributeLabel('past_email'),
                                       'type' => 'raw',
                                       'value' => '$data["past_email"]',
                                       'filter' => false
                                   ),
                                   array(
                                       'name' => 'email',
                                       'header' => $model->getAttributeLabel('email'),
                                       'type' => 'raw',
                                       'value' => '$data["email"]',
                                       'filter' => $filter1
                                   ),
                                   array(
                                       'name' => 'time',                                   
                                       'header' => '修改时间 ',
                                       'type' => 'raw',
                                       'value' => 'date("Y-m-d H:i",$data["time"])',
                                   	   'filter' => $filter2
                                   ),
                                   array(
                                       'header' => '操作员',
                                       'type' => 'raw',
                                       'value' => 'Admin::getName($data["admin_id"])',
                                       'filter' => CHtml::dropDownList('EmailRecord[admin_id]', $model->admin_id, Admin::items(), array('id' => false, 'prompt' => ''))
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