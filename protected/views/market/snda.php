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
                 $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $dataProvider,
                            'columns' => array(
                               array(
                                   'header'=>'Ad-Pos',
                                   'type'=>'raw',
                                   'filter'=>false,
                                   'value'=>'$data["id"]'
                                   ),
                               array(
                                   'name'=>'pos_id',
                                   'header' => '广告位id',
                                   'type' => 'raw',
                               ),
                               array(
                                     'name' => 'channel_id',
                                     'header' =>'渠道',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value' => 'Channel::getName($data["channel_id"])'
                                ),
                                array(
                                    'header' => '广告位名称',
                                    'type' => 'raw',
                                    'filter' =>false,
                                    'value' => 'Pos::getName($data["pos_id"])'
                                ),
                                array(
                                    'name'=>'ad_name',
                                    'header' => '广告版本',
                                    'filter' =>false,
                                    'type' => 'raw'
                                    ),
                                array(
                                     'name' => 'game_id',
                                     'header' => '游戏',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value'=>'$data["server_name"]'
                                ),
                                array(
                                     'name' => 'server_id',
                                     'header' => '区服',
                                     'type' => 'raw',
                                     'filter' =>false,
                                     'value'=>'$data["game_name"]'
                                ),
                                array(
                                    'name' => 'click',
                                    'header' =>'点击',
                                    'type' => 'raw',
                                    'filter' =>false
                                ),
                                array(
                                    'name' => 'register',
                                    'header' =>'注册',
                                    'type' => 'raw',
                                    'filter' =>false
                                ),
                                array(
                                    'name' => 'role',
                                    'header' =>'角色创建',
                                    'type' => 'raw',
                                    'filter' =>false
                                ),
                                array(
                                    'name' => 'role_percent',
                                    'header' =>'角色创建率',
                                    'type' => 'raw',
                                    'filter' =>false
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
