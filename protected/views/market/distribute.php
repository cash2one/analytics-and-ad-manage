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
                <?php $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$dataProvider
                           ,'filter'=>$model
                           ,'columns'=>array(
                                array(
                                     'header'=>'编号'
                                    ,'type'=>'raw'
                                    ,'filter'=>"<input type='hidden'  name='id' id='channel' value='{$model->id}'>"
                                    ,'value'=>'$data["id"]'
                                )
                               ,array(
                                     'header'=>'渠道'
                                    ,'type'=>'raw'
                                    ,'value'=>'$data["name"]'
                                   )
                               ,array(
                                     'header'=>'注册时间段'
                                    ,'type'=>'raw'
                                    ,'filter'=>CHtml::dropDownList('type',$type,array('week'=>'周','month'=>'月'))
                                    ,'value'=>'date("Y-m-d",$data["from"]). "~".date("Y-m-d",$data["to"])'
                                   )
                               ,array(
                                     'header'=>'充值额'
                                    ,'type'=>'number'
                                    ,'value'=>'Order::countPayByChannel($data["id"],$data["from"],$data["to"]+86399)'
                                   )
                                ,array(
                                   'class'=>'CButtonColumn'
                                  ,'viewButtonUrl'=>'Yii::app()->controller->createUrl("viewServerDistribute",
                                      array("id"=>$data["id"],"from"=>$data["from"],"to"=>$data["to"]+86399))'
                                  ,'template'=>'{view}'
                                   )
                               )
                            )) ?>
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
       var url='<?php echo CHtml::normalizeUrl(array('market/exportDistribute'))?>';
       var redirect = $.param.querystring(url, data);
       window.location.href=redirect;
    })
 })
</script> 
