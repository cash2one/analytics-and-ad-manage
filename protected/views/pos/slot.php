<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
    <h1><?php echo $data["title"]?></h1>
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
                $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider' => $model->slotList()
                           ,'columns' => array(
                               array(
                                    'name' => 'pos_id'
                                   ,'header' => $model->getAttributeLabel('pos_id')
                                   ,'type' => 'raw'
                               )
                              ,array(
                                   'header' => '广告位名称'
                                   ,'type' => 'raw'
                                   ,'value' => 'Pos::getName($data["pos_id"])'
                               )
                               ,array(
                                   'name' => 'channel_id'
                                  ,'header' => $model->getAttributeLabel('channel_id')
                                  ,'type' => 'raw'
                                  ,'value' => 'Channel::getName($data["channel_id"])'
                               )
                               ,array(
                                   'header' => '游戏'
                                   ,'type' => 'raw'
                                   ,'value' => 'Game::getName($data["game_id"])'
                               )
                               ,array(
                                   'header' => '区服'
                                   ,'type' => 'raw'
                                   ,'value' => 'Server::getName($data["server_id"])'
                               )
                               ,array(
                                       'header' => '注册素材版本'
                                       ,'type' => 'raw'
                                       ,'name' => 'name'
                               )
                               ,array(
                                       'header' => '测试链接'
                                       ,'type' => 'raw'
                                       ,'value' => 'CHtml::link("http://www.90hao.com/adcontent/". $data[path]. "/", "http://www.90hao.com/adcontent/". $data[path]. "/", array("target"=>"_blank"))'
                               )
                               ,array(
                                   'name' => 'bind_time'
                                   ,'header' => '时间'
                                   ,'type' => 'datetime'
                               )
                               ,array(
                                   'class'=>'CButtonColumn'
                                   ,'deleteButtonUrl' => 'Yii::app()->controller->createUrl("slotDelete",array("id"=>$data["id"]))'
                                   ,'template'=>'{delete}'
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