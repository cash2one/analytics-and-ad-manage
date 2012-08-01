<?php
if(file_exists(dirname(__FILE__). '/../../develope.me'))
{
	define('UC_CONNECT', 'mysql');          // 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()
                                        // mysql 是直接连接的数据库, 为了效率, 建议采用 mysql
	//数据库相关 (mysql 连接时, 并且没有设置 UC_DBLINK 时, 需要配置以下变量)
	define('UC_DBHOST', '10.240.133.59');   // UCenter 数据库主机
	define('UC_DBUSER', 'root');            // UCenter 数据库用户名
	define('UC_DBPW', '2144testmysql');     // UCenter 数据库密码
	define('UC_DBNAME', 'uc_2133_com');     // UCenter 数据库名称
	define('UC_DBCHARSET', 'utf8');         // UCenter 数据库字符集
	define('UC_DBTABLEPRE', 'uc_2133_com.uc_');         // UCenter 数据库表前缀

	//通信相关
	define('UC_KEY', 'NZ6Ejm4xiSuiCaEBVb8cVUBiWjUiOHdITbkjcgNh0yeQwR0dIZxY6KgtdBPro6kC');               // 与 UCenter 的通信密钥, 要与 UCenter 保持一致
	define('UC_API', 'http://uc.2133.com'); // UCenter 的 URL 地址, 在调用头像时依赖此常量
	define('UC_CHARSET', 'utf8');               // UCenter 的字符集
	define('UC_IP', '10.240.133.59');                   // UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
	define('UC_APPID', 3);                  // 当前应用的 ID
} else {
	define('UC_CONNECT', 'mysql');          // 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()
                                        // mysql 是直接连接的数据库, 为了效率, 建议采用 mysql
	//数据库相关 (mysql 连接时, 并且没有设置 UC_DBLINK 时, 需要配置以下变量)
	define('UC_DBHOST', '10.10.16.10');   // UCenter 数据库主机
	define('UC_DBUSER', 'uc2133comu');            // UCenter 数据库用户名
	define('UC_DBPW', 'D0i3O9aKnP');     // UCenter 数据库密码
	define('UC_DBNAME', 'uc_2133_com');     // UCenter 数据库名称
	define('UC_DBCHARSET', 'utf8');         // UCenter 数据库字符集
	define('UC_DBTABLEPRE', 'uc_2133_com.uc_');         // UCenter 数据库表前缀

	//通信相关
	define('UC_KEY', 'NZ6Ejm4xiSuiCaEBVb8cVUBiWjUiOHdITbkjcgNh0yeQwR0dIZxY6KgtdBPro6kC');               // 与 UCenter 的通信密钥, 要与 UCenter 保持一致
	define('UC_API', 'http://uc.2133.com'); // UCenter 的 URL 地址, 在调用头像时依赖此常量
	define('UC_CHARSET', 'utf8');               // UCenter 的字符集
	define('UC_IP', '61.129.46.73');                   // UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
	define('UC_APPID', 3);                  // 当前应用的 ID
}
include_once dirname(__FILE__).'/uc_client/client.php';