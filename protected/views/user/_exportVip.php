 <?php
        $dataProvider->pagination=false;
        $this->widget('ext.EExcelView', array(
                            'dataProvider'=>$dataProvider
                           ,'columns'=>array(
                                array(
                                        'name' => 'game_id',
                                        'header' => '游戏',
                                        'type' => 'raw',
                                        'value' => 'Game::getName($data["game_id"])'
                                  ),
                                  array(
                                        'name' => 'server_id',
                                        'header' => '区服',
                                        'type' => 'raw',
                                        'value'=>'Server::getName($data["server_id"])'
                                  ),
                                  array(
                                        'name' => 'user_id',
                                        'header' => '用户ID',
                                        'type' => 'raw',
                                        'value' => '$data["user_id"]'
                                   ),
                                   array(
                                       'name' => 'user_name',
                                       'header' => '用户账号',
                                       'type' => 'raw',
                                       'value' => '$data["user_name"]'
                                   ),
                                   array(
                                       'name' => 'vip_rank',
                                       'header' => 'VIP等级',
                                       'type' => 'raw',
                                       ),
                                   array(
                                       'name' => 'sum_paid',
                                       'header' => '累计金额',
                                       'type' => 'raw',
                                      ),                                                                     
                                   array(
                                       'name' => 'last_paid_time',
                                       'header' => '最后充值时间',
                                       'type' => 'date'
                                   ),
                                   array(
                                        'name' => 'reg_channel',
                                        'header' => '注册渠道',
                                        'type' => 'raw'
                                   ),
                                   array(
                                       'name' => 'e_mail',
                                       'header' => 'Email',
                                       'type' => 'raw'
                                   ),                                                                     
                                   array(
                                       'name' => 'qq',
                                       'header' => 'QQ',
                                       'type' => 'raw'
                                   ),                                                                     
                                   array(
                                       'name' => 'mobile_phone',
                                       'header' => '手机号',
                                       'type' => 'raw'
                                   )
                               )
                            ))
        ?>
