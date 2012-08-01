<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../framework/yiic.php';
if(file_exists('../develope.me'))
{
  $config=dirname(__FILE__).'/config/console.php';
}
else
{
  $config=dirname(__FILE__).'/config/command.php';
}

require_once($yiic);
