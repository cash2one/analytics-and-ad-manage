 <?php
        $dataProvider->pagination=false;
        $this->widget('ext.EExcelView', array(
                            'dataProvider'=>$dataProvider
                           ,'columns'=>array(
                                array(
                                    'name'=>'name'
                                   ,'type'=>'raw'
                                   ,'header'=>'游戏'
                                ),
                                array(
                                    'name'=>'paid_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'平台充值金额'
                                ),
                                array(
                                    'name'=>'yeepay_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'易宝充值'
                                ),
                                array(
                                    'name'=>'99bill_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱充值'
                                ),
                                array(
                                    'name'=>'99szx_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱神州行'
                                ),
                                array(
                                    'name'=>'yeepay_fee'
                                   ,'type'=>'raw'
                                   ,'header'=>'易宝手续费'
                                ),
                                array(
                                    'name'=>'99bill_fee'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱手续费'
                                ),
                                 array(
                                    'name'=>'99szx_fee'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱神州行手续费'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'yeepay_income'
                                   ,'type'=>'raw'
                                   ,'header'=>'易宝收入'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'99bill_income'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱收入'
                                   ,'filter'=>false
                                ),
                                array(
                                    'name'=>'99szx_income'
                                   ,'type'=>'raw'
                                   ,'header'=>'快钱神州行收入'
                                ),
                                array(
                                    'name'=>'income_sum'
                                   ,'type'=>'raw'
                                   ,'header'=>'总收入'
                                )
                               )
                            ))
        ?>