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
        <?php $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$model->getMergeVote()
                           ,'filter'=>$model
                           ,'columns'=>array(
                                array(
                                    'header' => '游戏'
                                   ,'name' => 'game_id'
                                   ,'type' => 'raw'
                                   ,'value' => 'Game::getName($data["game_id"])'
                                   ,'filter' => Game::dropVoteDownData()
                                )
                               ,array(
                                    'header' => '区服'
                                   ,'name' => 'server_id'
                                   ,'type' => 'raw'
                                   ,'value' => 'Server::getName($data["server_id"])'
                                   ,'filter' => CHtml::dropDownList('UserVote[server_id]', $model->server_id, Server::DropDownData($model->game_id),
                                          array('id' => false, 'prompt' => ''))
                                )
                               ,array(
                                    'header' => '原因'
                                   ,'name' => 'reason'
                                   ,'type' => 'raw'
                                   ,'filter'=>false
                                   )
                              ,array(
                                   'header' => '日期'
                                   ,'name' => 'vote_date'
                                   ,'type' => 'datetime'
                                   ,'filter'=>false
                                   )
                              ,array(
                                   'class'=>'CButtonColumn'
                                   ,'template'=>'{view}'
                                   ,'buttons' => array(
                                           'view' => array(
                                               'label' => '查看详细',
                                               'url' => '"/userVote/view/{$data["id"]}"'
                                           )
                                       )
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
