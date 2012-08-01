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
                echo Game::getName($model->game_id).':'.$model->name;
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' =>$dataProvider,
                            'filter' => false,
                            'columns' => array(
                               array(
                                   'name' => 'date',
                                   'header'=>'日期',
                                   'type' => 'date',
                                   'value' => '$data["date"]',
                                   'filter' => false
                               ),
                               array(
                                     'header'=>'广告注册',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["ad_register"]'
                                    ),
                                array(
                                     'header'=>'平台注册',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["platform_register"]'
                                    ),
                                array(
                                     'header'=>'转服注册',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["migrate_register"]'
                                    ),
                                array(
                                     'header'=>'滚服注册',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["chg_svr_register"]'
                                    ),
                                array(
                                     'header'=>'登录用户',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["visit_user"]'
                                    ),
                                array(
                                     'header'=>'回访用户',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' =>'UserServer::revisitByServer($data["server_id"],$data["date"],$data["date"]+86399)'
                                    ),
                                array(
                                     'header'=>'回访率',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => '$data["register_visit"]?round(100*(UserServer::revisitByServer($data["server_id"],$data["date"],$data["date"]+86399))/$data["register_visit"],2)."%":"0%"'
                                    ),
                                array(
                                     'header'=>'充值人数',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["payment_user"]'
                                    ),
                                array(
                                     'header'=>'重复充值人数',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["payment_user_repaid"]'
                                    ),
                               array(
                                     'header'=>'新充值人数',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["new_pay_user"]'
                                    ),
                                  array(
                                     'header'=>'充值金额(GM)',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["paid_gm"]'
                                    ),
                                  array(
                                     'header'=>'平台充值',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => '$data["paid"]'
                                    ),
                                array(
                                     'header'=>'ARUP',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => '$data["payment_user"]?round($data["paid"]/$data["payment_user"],2):0'
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
