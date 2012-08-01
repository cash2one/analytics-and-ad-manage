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
                 <?php  $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$model->search()
                           ,'filter'=>$model
                           ,'columns'=>array(
                                array(
                                    'name'=>'name'
                                   ,'type'=>'raw'
                                )
                                ,array(
                                     'name'=>'latest_ip'
                                    ,'type'=>'raw'
                                    ,'filter'=>false
                                    ,'value'=>'long2ip($data->latest_ip)'
                                   )
                                ,array(
                                     'name'=>'latest_time'
                                    ,'type'=>'raw'
                                    ,'filter'=>false
                                    ,'value'=>'$data->latest_time?date("Y-m-d H:i:s",$data->latest_time):"--"'
                                    )
                               ,array(
                                     'name'=>'login_times'
                                    ,'type'=>'number'
                                    ,'filter'=>false
                                   )
                               ,array(
                                     'name'=>'create_time'
                                    ,'type'=>'raw'
                                    ,'filter'=>false
                                    ,'value'=>'$data->create_time?date("Y-m-d H:i:s",$data->create_time):"--"'
                               )
                               ,array(
                                   'class'=>'CButtonColumn',
                                   'updateButtonUrl' => 'Yii::app()->controller->createUrl("assign",array("id"=>$data["id"]))',
                                   'template'=>'{update}'
                                )
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
