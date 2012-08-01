<?php
return array(
        'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'My Console Application',
        'components'=>array(
            'db'=>array(
                'connectionString' => 'mysql:host=10.241.90.248;dbname=my_2144_cn',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => '2144testmysql',
                'charset' => 'gbk',
                ),
            'dbLocal'=>array(
                'class'=>'CDbConnection',
                'connectionString'=>'mysql:host=10.241.90.248;dbname=2133_admin',
                'emulatePrepare' => TRUE,
                'username'=>'root',
                'password'=>'2144testmysql',
                'charset'=>'utf8',
                ),
             'dbMining'=>array(
                'class'=>'CDbConnection',
                'connectionString'=>'mysql:host=10.241.90.248;dbname=data_mining',
                'emulatePrepare' => TRUE,
                'username'=>'root',
                'password'=>'2144testmysql',
                'charset'=>'utf8',
                ),
              'dbSlaver'=>array(
                'class'=>'CDbConnection',
                'connectionString'=>'mysql:host=10.241.90.248;dbname=2133_admin',
                'emulatePrepare' => TRUE,
                'username'=>'root',
                'password'=>'2144testmysql',
                'charset'=>'utf8',
                ),  
             'dbVisit'=>array(
                'class'=>'CDbConnection',
                'connectionString'=>'mysql:host=10.241.90.248;dbname=visit_dump',
                'emulatePrepare' => TRUE,
                'username'=>'root',
                'password'=>'2144testmysql',
                'charset'=>'utf8',
                ),
             'dbPay'=>array(
                'class'=>'CDbConnection',
                'connectionString'=>'mysql:host=10.241.90.248;dbname=pay_2144_cn',
                'emulatePrepare' => TRUE,
                'username'=>'root',
                'password'=>'2144testmysql',
                'charset'=>'utf8',
                )
            ),
        );
