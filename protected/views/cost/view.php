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
               $columns=array();
               $modeFilter=CHtml::dropDownList('mode',$mode,array(
                           '1'=>'天',
                           '2'=>'周',
                           '3'=>'月'
                           ));
               if($mode==1)
               {
                  $columns=array(
                                array(
                                    'name'=>'time'
                                   ,'header'=>'日期'
                                   ,'type'=>'date'
                                   ,'filter'=>$modeFilter
                                   ,'value'=>'$data["time"]'
                                ),
                          );
               }
               elseif($mode==2)
               {
                   $columns=array(
                                 array(
                                    'name'=>'time'
                                   ,'header'=>'周'
                                   ,'type'=>'raw'
                                   ,'filter'=>$modeFilter
                                   ,'value'=>'$data["time"]'
                                ),
                                array(
                                    'header'=>'开始日期',
                                    'type'=>'date',
                                    'value'=>'$data["open_time"]+518400*($data["time"]-1)'
                                    ),
                                 array(
                                    'header'=>'结束日期',
                                    'type'=>'date',
                                    'value'=>'$data["open_time"]+518400*$data["time"]'
                                    )
                           );
               }
               else
               {
                    $columns=array(
                                 array(
                                    'name'=>'time'
                                   ,'header'=>'月'
                                   ,'type'=>'raw'
                                   ,'filter'=>$modeFilter
                                   ,'value'=>'$data["time"]'
                                ),
                                array(
                                    'header'=>'开始日期',
                                    'type'=>'date',
                                    'value'=>'$data["open_time"]+2505600*($data["time"]-1)'
                                    ),
                                 array(
                                    'header'=>'结束日期',
                                    'type'=>'date',
                                    'value'=>'$data["open_time"]+2505600*$data["time"]'
                                    )
                           );
               }
               $dataColumns=array(
                       array(
                                'name'=>'payment_user'
                               ,'header'=>'充值人数'
                               ,'type'=>'number'
                               ,'filter'=>false
                               ,'value'=>'$data["payment_user"]'
                           ),
                       array(
                               'header'=>'付费率'
                               ,'type'=>'raw'
                               ,'value'=>'Server::getPaymentPercent($data["server_id"],$data["channel_id"],'.$mode.',$data["time"])."%"'
                            ),
                        array(
                               'header'=>'充值金额'
                               ,'type'=>'number'
                               ,'value'=>'$data["payment_increment"]'
                            ),
                       array(
                               'header'=>'分成利润'
                               ,'type'=>'number'
                               ,'value'=>'$data["income"]'
                            ),
                       array(
                               'header'=>'回款率'
                              ,'type'=>'raw'
                              ,'value'=>'Server::getProfit($data["server_id"],$data["channel_id"],$data["income"])."%"'
                               )
                       );
               $columns=array_merge($columns,$dataColumns);
               $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$server->history($channel->id,$mode)
                           ,'filter'=>false
                           ,'columns'=>$columns
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
