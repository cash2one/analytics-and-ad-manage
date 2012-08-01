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
                            'dataProvider' => $model->combinedList(),
                            'filter' => $model,
                            'columns' => array(
                               array(
                                   'name' => 'id',
                                   'type' => 'raw',
                                   'value' => '$data[\'id\']',
                                   'filter' => false
                               ),
                                array(
                                     'name' => 'game_id',
                                     'header' => $model->getAttributeLabel('game_id'),
                                     'type' => 'raw',
                                     'filter' => Game::dropDownData(),
                                     'value' => 'Game::getName($data[\'game_id\'])'
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => $model->getAttributeLabel('name'),
                                    'type' => 'raw'
                                ),
                                array(
                                     'name' => 'register_times',
                                     'header' => '总注册用户数',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value'=>'User::nbUser($data["id"])'
                                ),
                                array(
                                     'header' => '登录1次',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => 'User::loyalUser($data[\'id\'],1,1)'
                                    ),
                                array(
                                     'header' => '登录2次',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => 'User::loyalUser($data[\'id\'],2,2)'
                                    ),
                                array(
                                     'header' => '登录3次',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => 'User::loyalUser($data[\'id\'],3,3)'
                                    ),
                                array(
                                     'header' => '登录4次',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => 'User::loyalUser($data[\'id\'],4,4)'
                                    ),
                                array(
                                     'header' => '登录5-10次',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => 'User::loyalUser($data[\'id\'],5,10)'
                                    ),
                                array(
                                     'header' => '登录10次以上',
                                     'type' => 'number',
                                     'filter' =>false,
                                     'value' => 'User::loyalUser($data[\'id\'],10)'
                                    ),
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
