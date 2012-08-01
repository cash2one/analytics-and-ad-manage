<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
    <h1 style="display:inline"><?php echo $this->actionTitle?></h1><h1 style="float:right;"><a href="<?php echo $this->createUrl('ad/create');?>">添加广告</a></h1>
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
                <?php 
                $dataProvider=$model->search();
                $this->widget('zii.widgets.grid.CGridView',array(
                            'dataProvider'=>$dataProvider
                           ,'filter'=>$model
                           ,'columns'=>array(
                                array(
                                    'class' => 'CCheckBoxColumn',
                                    'checkBoxHtmlOptions' => array('name' => 'id[]'),
                                    'selectableRows' => 2,
                                    'id' =>'id'
                               ),
                                array(
                                    'name'=>'name'
                                   ,'type'=>'raw'
                                )
                               ,array(
                                     'name'=>'game_id'
                                    ,'type'=>'raw'
                                    ,'filter'=>Game::dropDownData()
                                    ,'value'=>'Game::getName($data->game_id)'
                                   )
                                ,array(
                                     'name'=>'server_id'
                                    ,'type'=>'raw'
                                    ,'filter'=>Server::Items()
                                    ,'value'=>'Server::getName($data->server_id)'
                                   )
                                ,array(
                                     'name'=>'admin_id'
                                    ,'type'=>'raw'
                                    ,'filter'=>Admin::Items()
                                    ,'value'=>'Admin::getName($data->admin_id)'
                                   )
                                /*
                                 ,array(
                                    'header'=>'转化率'
                                    ,'type'=>'raw'
                                    ,'filter'=>false
                                    ,'value'=>'$data->conversionRate()?sprintf("%01.2f",$data->conversionRate()*100)."%":0;'
                                   )
                                */
                                ,array(
                                     'name'=>'create_time'
                                    ,'type'=>'raw'
                                    ,'filter'=>false
                                    ,'value'=>'date("Y-m-d H:i:s",$data->create_time)'
                                    ) 
                                ,array(
                                   'class'=>'CButtonColumn'
                                  ,'viewButtonOptions'=>array('target'=>'_blank')
                                  ,'template'=>'{update}{delete}{view}'
                                 )
                               )
                            ))?>
            </div>
            <!--  end content-table  -->
            <div class="clear"></div>
            <div><input id="delete_batch" type="button" value="批量删除" /></div>
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
<script type='text/javascript'>
$(document).ready(function(){
  $('#delete_batch').click(function(){
      var id=[];
      $('input[name="id[]"]:checked').each(function(){
            id.push(this.value);
          });
      $.post(
               '/ad/delete',
               {'id':id},
               function(data){
                  $('select[name="Ad[server_id]"]').trigger('change');
            });
  });

});
</script>
