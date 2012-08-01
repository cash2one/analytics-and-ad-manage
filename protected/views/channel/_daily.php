<?php
$this->widget('ext.EExcelView', array(
            'dataProvider' =>$dataProvider,
            'filter' => false,
            'columns' => array(
                array(
                    'name' => 'date',
                    'header'=>'日期',
                    'type' => 'date',
                    'value' => '$data["date"]',
                    ),
                array(
                    'header'=>'渠道',
                    'type' => 'text',
                    'value' => '"'.$model->name.'"',
                    ),
                array(
                    'header'=>'点击',
                    'name' => 'click',
                    'type' => 'raw',
                    ),
                array(
                    'header'=>'注册',
                    'name' => 'register',
                    'type' => 'raw',
                    ),
                array(
                        'header'=>'登录',
                        'name' => 'visit',
                        'type' => 'raw',
                     ),
                array(
                        'header'=>'新用户登录',
                        'name' => 'register_visit',
                        'type' => 'raw',
                     ),
                array(
                        'header'=>'老用户登录',
                        'name' => 'normal_visit',
                        'type' => 'raw',
                     ),
                array(
                        'header'=>'当天充值人数',
                        'type' => 'raw',
                        'value' => '$data["payment_user"]'
                     ),
                array(
                        'header'=>'当天充值金额',
                        'type' => 'raw',
                        'value' => '$data["payment_amount"]'
                     ),
                array(
                        'header'=>'注册回访人数',
                        'type' => 'raw',
                        'value' => 'UserServer::revisitByChannel($data["channel_id"],$data["date"],$data["date"]+86399)'
                     ),
                array(
                        'header'=>'注册回访人数',
                        'type' => 'raw',
                        'value' => '$data["register"]?round(UserServer::revisitByChannel($data["channel_id"],$data["date"],$data["date"]+86399)*100/$data["register"],1)."%":"0%"'
                     ),
                array(
                        'header'=>'注册总充值人数',
                        'type' => 'raw',
                        'value' => 'Order::countPayUserByChannel($data["channel_id"],$data["date"],$data["date"]+86399)'
                     ),
                array(
                        'header'=>'付费率',
                        'type' => 'raw',
                        'value' => '$data["register"]?round(Order::countPayUserByChannel($data["channel_id"],$data["date"],$data["date"]+86399)*100/$data["register"],1)."%":"0%"'
                     ),
                array(
                        'header'=>'注册总充值金额',
                        'type' => 'raw',
                        'value' => 'Order::countPayByChannel($data["channel_id"],$data["date"],$data["date"]+86399)'
                     ),
                array(
                        'header'=>'广告素材',
                        'type' => 'raw',
                        'value' => 'Channel::listMaterial($data["channel_id"],$data["date"],$data["date"]+86399,true)'
                     ),
                array(
                        'header'=>'开服备注',
                        'type' => 'raw',
                        'value' => 'Channel::listServer($data["channel_id"],$data["date"],$data["date"]+86399,true)'
                     ),
                )
                )) ?>
