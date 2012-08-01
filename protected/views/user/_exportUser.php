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
                $this->widget('ext.XJuiDatePicker', array(
                             'name' => 'perioddate',
                             'range' => 'peroid',
                             'language' => 'zh-CN',
                             'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'minDate' => '-20D',
                                'maxDate' => 0,
                             )
                 ));
                 $form=$this->beginWidget('CActiveForm',array( 'enableAjaxValidation'=>false));
                 echo "<input type='text' name='user[start_date]' id='start_date' value='{$startDate}' class='peroid' />&nbsp;&nbsp;";
                 echo "<input type='text' name='user[end_date]' id='end_date' value='{$endDate}' class='peroid'  />&nbsp;&nbsp;";
                 echo CHtml::dropDownList('user[game_id]',null, Game::DropDownData(),array('id'=>'X_game_id'));
                 echo CHtml::dropDownList('user[server_id]',null,Server::DropDownData(),array('id'=>'X_server_id','prompt'=>''));
                 echo CHtml::dropDownList('user[channel_id]',null,Channel::dropDownData());
                 echo '&nbsp;&nbsp; <input type="submit" value="导出" />';
                 $this->endWidget();
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
          $.getJSON('/site/dropDownServer',{'game_id':$(this).val()},function(data){
              $.each(data,function(index,value){
                   $('<option></option>').text(value).val(index).appendTo('#X_server_id');
                  });
              })
      });
      $('#X_game_id').trigger('change');
   });
</script>
