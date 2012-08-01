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
        <?php
          $form=$this->beginWidget('CActiveForm',array(
                'enableAjaxValidation'=>false,
                ));
          $this->widget('ext.XJuiDatePicker',array(
                             'name'=>'date'
                            ,'language'=>'zh-CN'
                            ,'options'=>array(
                                'dateFormat'=>'yy-mm-dd',
                                ),
                            ));
          echo CHtml::dropDownList('game_id', $gameId, Game::DropDownData(),array('id'=>'X_game_id'));
          echo CHtml::dropDownList('server_id', $serverId,Server::DropDownData($gameId),array('id'=>'X_server_id'));
          echo "<input type='text' name='date' value='{$baseDate}' id='date' />";
          echo CHtml::submitButton('提交');
          $this->endWidget()
       ?>
        <?php
        $from = strtotime($baseDate);
        $to=$from+86400;
        $now=time();

        if($to>$now-7200)
        {
           $to=$now-7200;
        }
        for($i = $from; $i < $to; $i += 3600)
        {
            $hours[]=date('H', $i);
            $end=$i + 3600;
            $visitSum=Visit::nbDistinctVisit($serverId, $i, $end,'hour');
            $visitRegister=Visit::nbRegisterVisit($serverId, $i, $end,'hour');
            $visitOld=$visitSum-$visitRegister;
            $sum[]=$visitSum;
            $register[]=$visitRegister;
            $old[]=$visitOld;
        }
        $this->Widget('ext.highcharts.HighchartsWidget', array(
         'options'=>array(
            'title' => array('text' => '时间'),
            'xAxis' => array(
               'categories' =>$hours
            ),
            'yAxis' => array(
               'title' => array('text' => '数值'),
               'min'=>0
            ),
            'series' => array(
               array('name' => '登录用户总数','data' => $sum),
               array('name' => '新注册登录', 'data' =>$register),
               array('name' => '老用户登录', 'data' => $old),
            ),
            'credits'=>array('enabled'=>false),
            'theme'=>'dark-blue'
         )
        ));
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
<script type='text/javascript'>
$(document).ready(function(){
        $('#X_game_id').change(function(){
            $('#X_server_id').empty();
            $.getJSON('/site/dropDownServer', {'game_id':$(this).val()}, function(data){
                $.each(data,function(index,value){
                    $('<option value="'+index+'">'+value+'</option>').appendTo('#X_server_id');
                    });
                })
            })
        });
</script>
