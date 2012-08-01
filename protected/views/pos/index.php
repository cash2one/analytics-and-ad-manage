<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
    <h1 style="display:inline"><?php echo $data['title'];?></h1><h1 style="float:right;"><a href="<?php echo $this->createUrl('pos/create');?>">添加广告位</a></h1>
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
                    <td class="green-left"><?php echo Yii::app()->user->getFlash('successMsg');?></td>
                    <td class="green-right"><a class="close-green"><img src="/images/table/icon_close_green.gif"   alt="" /></a></td>
                </tr>
                </table>
                </div>
                <!--  end message-green -->
                <?php endif;?>
                <?php
                $this->widget('ext.XJuiDatePicker',array(
                        'name'=>'bind_time'
                        ,'language'=>'zh-CN'
                        ,'mode'=>'datetime'
                        ,'options'=>array(
                                'dateFormat'=>'yy-mm-dd',
                                'minDate'=>0,
                                'maxDate'=>14,
                        ),
                ));
                $pageInput="<input type='hidden' name='page' id='page' value='{$data['page']}'>";
                $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $model->combinedList(),
                            'filter' => $model,
                            'columns' => array(
                               array(
                                    'class' => 'CCheckBoxColumn',
                                    'checkBoxHtmlOptions' => array('name' => 'pos_id[]'),
                                    'selectableRows' => 2,
                                    'id' => 'id'
                               ),
                               array(
                                   'name' => 'id',
                                   'header' => '广告位id',
                                   'type' => 'raw',
                                   'value' => '$data["id"]',
                                   'filter' => false
                               ),
                               array(
                                     'name' => 'channel_id',
                                     'header' => $model->getAttributeLabel('channel_id'),
                                     'type' => 'raw',
                                     'filter' => Channel::dropDownData(),
                                     'value' => 'Channel::getName($data["channel_id"])'
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => $model->getAttributeLabel('name'),
                                    'type' => 'raw',
                                    'filter' => CHtml::textField('Pos[name]', $model->name, array('size' => 1))
                                ),
                                array(
                                     'name' => 'game_id',
                                     'header' => '游戏',
                                     'type' => 'raw',
                                     'filter' => CHtml::dropDownList('Pos[game_id]', $model->gameId, Game::DropDownData(),
                                          array('id' => false,'prompt' => '')),
                                     'value' => 'Game::getName($data["game_id"])'
                                ),
                                array(
                                     'name' => 'server_id',
                                     'header' => '区服',
                                     'type' => 'raw',
                                     'filter' => CHtml::dropDownList('Pos[server_id]', $model->serverId, Server::DropDownData($model->gameId),
                                          array('id' => false, 'prompt' => '')),
                                     'value'=>'Server::getName($data["server_id"])'
                                ),
                                array(
                                     'type' => 'raw',
                                     'header' => '注册素材版本',
                                     'value' => '$data["ad_name"]',
                                     'filter' => CHtml::textField('Pos[ad_name]', $model->adName, array('size' => 1))
                                ),
                                array(
                                     'name' => 'type',
                                     'header' => $model->getAttributeLabel('type'),
                                     'value' => 'Pos::$TYPE[$data["type"]]',
                                     'type' => 'raw',
                                     'filter' => Pos::$TYPE
                                ),
                                array(
                                     'header' => '显示尺寸',
                                     'type' => 'raw',
                                     'value' => '($data["width"] && $data["height"]) ? $data["width"]. "*". $data["height"] : "--"',
                                     'filter' => false
                                ),
                                array(
                                     'header' => '昨日点击',
                                     'type' => 'raw',
                                     'filter' => false,
                                     'value' => 'Pos::lastdayClick($data["id"])'
                                ),
                                 array(
                                     'header' => '广告链接',
                                     'type' => 'raw',
                                     'value' => '(!$data["ad_name"])?"未绑定广告":CHtml::link("www.90hao.com/tuiguang/?pos_id=".$data["id"],"http://www.90hao.com/tuiguang/?pos_id=".$data["id"],
                                     	array("target"=>"_blank")
                                     )',

                                ),
                                array(
                                     'name' => 'cost',
                                     'header' => $model->getAttributeLabel('cost'),
                                     'type' => 'raw',
                                     'filter' =>$pageInput
                               ),
                               array(
                                     'class' => 'CButtonColumn',
                                     'updateButtonUrl' => 'Yii::app()->controller->createUrl("update",array("id"=>$data["id"]))',
                                     'deleteButtonUrl' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data["id"]))',
                                     'template' => '{copy}{update}{change}{slot}{disable}{enable}{delete}',
                                     'buttons' => array(
                                           'change' => array(
                                               'label' => '更新广告',
                                               'url' => '"javascript://". $data["id"]. "//". $data["game_id"]. "//". $data["server_id"]. "//". $data["ad_id"]',
                                               'click' => 'function(){initData($(this).attr("href"), $("#page").val())}',
                                               'imageUrl' => '/images/arrow_switch.png'
                                            ),
                                            'copy' => array(
                                                'label' => '复制链接',
                                                'url' => '"javascript://"', 
                                                'click' => 'function(){copyLink($(this).parent().prev().prev().find("a").attr("href"))}',
                                                'imageUrl' => '/images/copy.png'
                                            ),
                                            'slot' => array(
                                                 'label' => '广告位排期',
                                                 'url' => '"/pos/slot/". $data["id"]',
                                                 'imageUrl' => '/images/slot.png',
                                                 'options'=>array('target' => '_blank')
                                             ),
                                            'disable' => array(
                                                'label' => '关闭',
                                                'url' => '"javascript://". $data["id"]', 
                                                'click' => 'function(){updateStatus($(this).attr("href"), $("#page").val(), "close")}',
                                                'imageUrl' => '/images/cancel.png',
                                                'visible' => '$data["enable"] == 1'                                                
                                            ),
                                            'enable' => array(
                                                'label' => '开启',
                                                'url' => '"javascript://". $data["id"]', 
                                                'click' => 'function(){updateStatus($(this).attr("href"), $("#page").val(), "open")}',
                                                'imageUrl' => '/images/start.png',
                                                'visible' => '$data["enable"] == 0'
                                            )
                                      ),
                                      'htmlOptions' => array('style' => 'width:80px;')
                                   )
                               )
                            )) ?>
            </div>
            <!--  end content-table  -->
            <div class="clear"></div>
            <div><input id="change_batch" type="button" value="批量更换素材" /></div>
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

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'changed_ad',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => '更换广告',
        'autoOpen' => false
    ),
));
$form = $this->beginWidget('CActiveForm',array(
                'enableAjaxValidation'=>false,
                ));
?>
<div style="font-size:12px;line-height:26px;">
选择广告位推广的游戏:<br />
<?php echo $form->dropDownList($model_ad, 'game_id', Game::dropDownData(), array('id' => 'X_game_id'))?><br />
选择广告位推广的游戏服:<br />
<?php echo $form->dropDownList($model_ad, 'server_id', Server::dropDownData($model_ad->game_id), array('id' => 'X_server_id'))?><br />
选择广告内容:<br />
<?php echo $form->dropDownList($model_ad, 'name', array(), array('id' => 'X_ad_id'))?><br />
选择广告推广时间<br />
<input type="text"  value="" id="bind_time" name="bind_time" /><br />
<input type="button" id="change_submit" value="确认" />
<input type="button" id="change_cancel" value="取消"/>
</div>
<?php
$this->endWidget();
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<div class="clear">&nbsp;</div>
<script type='text/javascript'>
var page;
var i = {};
$(document).ready(function(){
  $('#X_game_id').change(function(){
      $('#X_server_id').empty();
      $.getJSON('/site/dropDownServer', {'game_id':$(this).val()}, function(data){
          $.each(data,function(index,value){
			   $('<option value="'+index+'">'+value+'</option>').appendTo('#X_server_id');
              });
              if(i.sid)
              {
				  setTimeout(function(){$('#X_server_id option[value='+i.sid+']').attr('selected', 'selected')}, 300);
              }
              setTimeout(function(){
				  $('#X_server_id').trigger('change'); 
			  }, 300); 
          });
  });
  $('#X_server_id').change(function(){
      $('#X_ad_id').empty();
      $.getJSON('/site/dropDownAd', {'server_id':$(this).val()}, function(data){
          $.each(data,function(index,value){
				$('<option value="'+index+'">'+value+'</option>').appendTo('#X_ad_id');
              });
          });
          if(i.aid)
          {
			  setTimeout(function(){$('#X_ad_id option[value='+i.aid+']').attr('selected', 'selected')}, 300);
          }
  });

  $('#change_submit').click(function(){
      $.post(
                 '/pos/change',
                {'pos_id': i.pid, 'game_id': $('#X_game_id').val(), 'server_id': $('#X_server_id').val(), 'ad_id': $('#X_ad_id').val(), 'bind_time': $('#bind_time').val()},
                function(data){
                    $("#changed_ad").dialog("close");
                    if(data ==  1)
                    {
                        location.href = '/pos/index?page='+ page;
                    } else {
                        alert('更改失败！');
                    }
                });
  });
  $('#change_cancel').click(function(){
      $("#changed_ad").dialog("close");
  });

  $('#change_batch').click(function(){
      i.pid = [];
      $('input[name="pos_id[]"]:checked').each(function(){
            i.pid.push($(this).val());
      });
      $('#X_game_id').trigger('change');
      $("#changed_ad").dialog("open");
  });
});

function updateStatus(url, page, status)
{
	var _tmp = url.split('\/\/');	
	var pid = _tmp[1];
	var act = (status == 'open') ? 'enable' : 'disable';
	$.get(
		'/pos/' + act + '/' + pid,
		{},
		function(data){
			if(data == 1)
			{
				location.href = '/pos/index?page='+ page;
			} else {
                alert('更新失败！');
            }
		});
}
function copyLink(text)
{
	if(document.all){
    	window.clipboardData.setData("Text", text);
    	alert('复制成功！');
    } else {
    	alert('您的浏览器不支持此功能，请手动复制！');
    }
}
function initData(url, p)
{
	page = p;
    var _tmp = url.split('\/\/');
    i = {'pid': _tmp[1], 'gid': _tmp[2], 'sid': _tmp[3], 'aid': _tmp[4]};    
    $("#changed_ad").dialog("open");
	setTimeout(function() {
		$('#X_game_id').val(_tmp[2]);
		$('#X_game_id').trigger('change');
	}, 300);
    return false;
}
</script>
