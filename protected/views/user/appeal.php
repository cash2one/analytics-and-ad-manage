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
                <?php if(Yii::app()->user->hasFlash('successMsg')):?>
                <!--  start message-green -->
                <div id="message-green">
                <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="green-left"><?php echo Yii::app()->user->getFlash('successMsg');?></a></td>
                    <td class="green-right"><a class="close-green"><img src="/images/table/icon_close_green.gif"   alt="" /></a></td>
                </tr>
                </table>
                </div>
                <!--  end message-green -->
                <?php endif;?>
                <?php if(Yii::app()->user->hasFlash('errorMsg')):?>
                 <!--  start message-red -->
                <div id="message-red">
                <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="red-left"><?php echo Yii::app()->user->getFlash('errorMsg');?></a></td>
                    <td class="red-right"><a class="close-red"><img src="/images/table/icon_close_red.gif"   alt="" /></a></td>
                </tr>
                </table>
                </div>
                <!--  end message-red -->
                <?php endif;?>
                <?php
                $this->widget('ext.XJuiDatePicker', array(
                             'name' => 'perioddate',
                             'range' => 'peroid',
                             'language' => 'zh-CN',
                             'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'maxDate' => 1,
                             )
                 ));
                 $filter1 = "<input type='text' name='start_date' id='start_date' value='{$model->startDate}' class='peroid' size='1' />";
                 $filter2 = "<input type='text' name='end_date' id='end_date' value='{$model->endDate}' class='peroid' size='1' />";
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $model->search(),
                            'filter' => $model,
                            'columns' => array(
                                   array(
                                       'name' => 'time',
                                       'type' => 'raw',
                                       'value'=>'date("Y-m-d H:i",$data["time"])',
                                       'filter' => $filter1 .$filter2
                                   ),
                                   array(
                                       'name'=>'user_name',
                                       'type'=>'text'
                                       ),
                                   array(
                                       'name'=>'contact_email',
                                       'type'=>'text'
                                       ),
                                   array(
                                       'name'=>'contact_qq',
                                       'type'=>'text'
                                       ),
                                   array(
                                       'name'=>'ip',
                                       'type'=>'raw',
                                       'value'=>'long2ip($data["ip"])'
                                       ),
                                   array(
                                       'name'=>'status',
                                       'type'=>'raw',
                                       'value'=>'Appeal::$statusList[$data["status"]]',
                                       'filter'=>Appeal::$statusList
                                        ),
                                   array(
                                       'class' => 'CButtonColumn',
                                       'template' => '{view}{delete}',
                                       'viewButtonUrl'=>'Yii::app()->controller->createUrl("appealview",array("id"=>$data->primaryKey))',
                                       'deleteButtonUrl'=>'Yii::app()->controller->createUrl("appealDelete",array("id"=>$data->primaryKey))'
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
