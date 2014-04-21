<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'runtimePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'runtime',
    'name'=>'PGPF Interns',

	// preloading 'log' component
	'preload'=>array('log', 'EJSUrlManager'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.extensions.CAdvancedArBehavior',
        'application.extensions.uniqueMultiColumnValidator',
        'application.extensions.YiiMailer.YiiMailer',
	),

	'modules'=>array(
	),

	// application components
	'components'=>array(
        'clientScript'=>array(
            'class' => 'application.components.ClientScript',
        ),
        'viewRenderer'=>array(
            'class' => 'ext.yii-jade.CJadeViewRenderer',
            'prepend' => array(''),
        ),
        'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            'loginUrl'=>array('user/login'),
            'class' => 'WebUser',
		),
        'common' => array(
            'class' => 'application.components.CommonFunctions',
        ),
        // uncomment the following to enable URLs in path-format

		'urlManager'=>array(
            'class' => 'UrlManager',
			'urlFormat'=>'path',
            'showScriptName' => false,
			'rules'=>array(
                'gii'=>'gii',
                'gii/<controller:[\w\-]+>'=>'gii/<controller>',
                'gii/<controller:[\w\-]+>/<action:[\w\-]+>'=>'gii/<controller>/<action>',

				'<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:[\w\-]+>/<action:[\w\-]+>/<step:.+>'=>'<controller>/<action>',
				'<controller:[\w\-]+>/<action:[\w\-]+>/<param1:.+>'=>'<controller>/<action>',
				'<controller:[\w\-]+>/<action:[\w\-]+>'=>'<controller>/<action>',

                array('<controller>/get',    'pattern'=>'<controller:[\w\-]+>/<id:\d+>', 'verb'=>'GET'),
                array('<controller>/create', 'pattern'=>'<controller:[\w\-]+>', 'verb'=>'POST'),
                array('<controller>/update', 'pattern'=>'<controller:[\w\-]+>/<id:\d+>', 'verb'=>'PUT'),
                array('<controller>/delete', 'pattern'=>'<controller:[\w\-]+>/<id:\d+>', 'verb'=>'DELETE'),

				'<controller:[\w\-]+>/<id:\d+>'=>'<controller>/view',
            ),
		),
        'EJSUrlManager' => array(
            'class' => 'ext.JSUrlManager.src.EJSUrlManager',
        ),
		'db'=>array(
            //how it was set before
#			'emulatePrepare' => true,
			'charset' => 'utf8',
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
        'authManager' => array(
            'class' => 'PhpAuthManager',
            'defaultRoles' => array('guest'),
        ),
        'simpleImage'=>array(
            'class' => 'application.extensions.simpleImage.CSimpleImage',
        ),
        'imageHelper'=>array(
            'class' => 'application.components.ImageHelper',
        ),
        'iwi' => array(
            'class' => 'application.extensions.iwi.IwiComponent',
            // GD or ImageMagick
            'driver' => 'ImageMagick',
            // ImageMagick setup path
            //'params'=>array('directory'=>'C:/ImageMagick'),
        ),
	),
    'params'=>array(
        'adminEmail'=>'support@pgpfinterns.local',
        //root folder for our folders and fields relative to webroot folder
    ),
);