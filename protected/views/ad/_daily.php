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
                     'name'=>'material_id',
                     'header'=>'素材',
                     'type' => 'text',
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
                         'header'=>'注册率',
                         'type' => 'raw',
                         'value' => '$data["click"]?round($data["register"]*100/$data["click"],2)."%":"0%"'
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
                         'header'=>'3日回访用户',
                         'type' => 'raw',
                         'value' => 'UserServer::revisitByMaterial($data["material_id"],$data["date"],$data["date"]+86399,$data["date"],$data["date"]+86400*3)'
                      ),
                 array(
                         'header'=>'3日回访率',
                         'type' => 'raw',
                         'value' => '$data["register"]?round(UserServer::revisitByMaterial($data["material_id"],$data["date"],$data["date"]+86399,$data["date"],$data["date"]+86400*3)*100/$data["register"],2)."%":"0%"'
                      ),
                 array(
                         'header'=>'7日回访用户',
                         'type' => 'raw',
                         'value' => 'UserServer::revisitByMaterial($data["material_id"],$data["date"],$data["date"]+86399,$data["date"],$data["date"]+86400*7)'
                      ),
                 array(
                         'header'=>'7日回访率',
                         'type' => 'raw',
                         'value' => '$data["register"]?round(UserServer::revisitByMaterial($data["material_id"],$data["date"],$data["date"]+86399,$data["date"],$data["date"]+86400*7)*100/$data["register"],2)."%":"0%"'
                      )
                 )
                 )) ?>
