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
                                'maxDate' =>0,
                                ),
                            ));
        echo "<input type='text' name='date' value='{$baseDate}' id='date' />";
        echo CHtml::submitButton('提交');
        $this->endWidget();
        $from = strtotime('-6 days', strtotime($baseDate));
        $to = strtotime('+1 days', strtotime($baseDate));
        for($i = $from; $i < $to; $i += 86400)
        {
            $dates[]=date('m-d', $i);
            $end=$i + 86400;
            $clickTimes=Click::countClickByAdPos(null, $i, $end);
            $click[]=$clickTimes;

            $reg=(int)User::countRegisterByAdPos(null, $i,$end);
            $register[]=$reg;

            $visitTimes=Visit::nbDistinctVisit(null, $i, $end);
            $visit[]=$visitTimes;

            $paymentAmount=Order::sumPaid(null, $i, $end);
            $payment[]=$paymentAmount;
            $paymentUser[]=Order::nbUserPaid(null, $i, $end);

            if(strtotime(date('Y-m-d'))>$i)
            {
             $offset=604800;
             $clickCompare=Click::countClickByAdPos(null, $i-$offset, $end-$offset);
             $clickPercent[]=$clickCompare?round($clickTimes/$clickCompare,4):0;
             $regCompare=(int)User::countRegisterByAdPos(null,$i-$offset,$end-$offset);
             $regPercent[]=$regCompare?round($reg/$regCompare,4):0;
             $visitCompare=Visit::nbDistinctVisit(null, $i-$offset, $end-$offset);
             $visitPercent[]=$visitCompare?round($visitTimes/$visitCompare,4):0;
             $paymentCompare=Order::sumPaid(null, $i-$offset, $end-$offset);
             $paymentPercent[]=$paymentCompare?round($paymentAmount/$paymentCompare,4):0;
            }

        }
        $this->Widget('ext.highcharts.HighchartsWidget', array(
         'options'=>array(
            'title' => array('text' => '日期'),
            'xAxis' => array(
               'categories' =>$dates
            ),
            'yAxis' => array(
                array(
               'title' => array('text' => '数值'),
               'min'=>0
               ),
               array(
                   'title'=>array('text'=>'比率'),
                   'opposite'=>'true',
                   )
            ),
            'series' => array(
               array('name' =>'广告点击总数','data' => $click,'type'=>'column','visible'=>false),
               array('name' =>'广告注册总数','data' => $register,'type'=>'column'),
               array('name' =>'游戏登录用户数', 'data' =>$visit,'type'=>'column'),
               array('name' =>'充值总数', 'data' => $payment,'type'=>'column'),
               array('name'=>'环比上周点击','yAxis'=>1,'data'=>$clickPercent,'visible'=>false),
               array('name'=>'环比上周注册','yAxis'=>1,'data'=>$regPercent),
               array('name'=>'环比上周登录','yAxis'=>1,'data'=>$visitPercent),
               array('name'=>'环比上周充值','yAxis'=>1,'data'=>$paymentPercent),
            ),
            'credits'=>array('enabled'=>false),
            'theme'=>'dark-blue'
         )
        ));

        $this->Widget('ext.highcharts.HighchartsWidget', array(
         'options'=>array(
            'title' => array('text' => '日期'),
            'xAxis' => array(
               'categories' =>$dates
            ),
            'yAxis' => array(
                array(
               'title' => array('text' => '数值'),
               'min'=>0
               )
            ),
            'series' => array(
               array('name' =>'充值人数', 'data' => $paymentUser,'type'=>'column'),
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
