<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../../yii/framework/yii.php';
$config=dirname(__FILE__).'/../protected/config/index.php';
$globals=dirname(__FILE__).'/../protected/globals.php';


if( isset($_SERVER['APPLICATION_ENV']) && ($_SERVER['APPLICATION_ENV'] != 'production')) {
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

    // Set environment variable
    $environment = $_SERVER['APPLICATION_ENV'];
} else {
    // Set environment variable
    $environment = 'production';
}


// Include config files
$configMain = require_once( dirname(__FILE__).'/../protected/config/main.php' );
$configServer = require_once( dirname( __FILE__ ) . '/../protected/config/server.'
    . $environment . '.php' );

require_once($yii);

// Run application
$config = CMap::mergeArray( $configMain, $configServer );

Yii::createWebApplication($config)->run();
