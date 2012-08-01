<?php
return CMap::mergeArray(
        require(dirname(__FILE__).'/main.php'),
        array(
            'modules'=>array(
                'gii'=>array(
                    'class'=>'system.gii.GiiModule',
                    'password'=>'public',
                    )
                ),
            'components'=>array(
                'db'=>array(
                    'class'=>'CDbConnection',
                    'connectionString'=>'mysql:host=10.241.90.248;dbname=2133_admin',
                    'emulatePrepare' => TRUE,
                    'username'=>'root',
                    'password'=>'2144testmysql',
                    'charset'=>'utf8',
                    'enableProfiling' => true,
                    'enableParamLogging' => true,
                    ),
                'visitDump'=>array(
                    'class'=>'CDbConnection',
                    'connectionString'=>'mysql:host=10.241.90.248;dbname=visit_dump',
                    'emulatePrepare' => TRUE,
                    'username'=>'root',
                    'password'=>'2144testmysql',
                    'charset'=>'utf8',
                    'enableProfiling' => true,
                    'enableParamLogging' => true,
                    ),
                    'cache'=>array(
                        'class'=>'system.caching.CMemCache',
                         'servers'=>array(
                            array('host'=>'10.241.90.248', 'port'=>11211, 'weight'=>20)
                            )
                        ),
                    'log'=>array(
                        'class'=>'CLogRouter',
                        'routes'=>array(
                            array(
                                'class'=>'CFileLogRoute',
                                'levels'=>'error, warning',
                                ),
                            array(
                                'class' => 'application.extensions.pqp.PQPLogRoute',
                                'categories' => 'application.*, exception.*',
                                ),
                            ),
                        ),
                    ),
                    'params'=>array(
                            'adContentPath'=>dirname(__FILE__).'/../../adcontent',
                            'offlineOrderPath'=>dirname(__FILE__).'/../../files'
                            )
                    )
        );
