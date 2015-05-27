<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'runtimePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'runtime',
    'name'=>'Child Pass',

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
        ),
        'ePdf' => array(
            'class'         => 'ext.yii-pdf.EYiiPdf',
            'params'        => array(
                'mpdf'     => array(
                    'librarySourcePath' => 'application.vendors.mpdf.*',
                    'constants'         => array(
                        //'_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
                    'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                        'mode'              => '', //  This parameter specifies the mode of the new document.
                        'format'            => 'A4', // format A4, A5, ...
                        'default_font_size' => 0, // Sets the default document font size in points (pt)
                        'default_font'      => '', // Sets the default font-family for the new document.
                        'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                        'mgr'               => 15, // margin_right
                        'mgt'               => 16, // margin_top
                        'mgb'               => 16, // margin_bottom
                        'mgh'               => 9, // margin_header
                        'mgf'               => 9, // margin_footer
                        'orientation'       => 'L', // landscape or portrait orientation
                    )
                ),
                'html2pdf' => array(
                    'librarySourcePath' => 'application.vendors.html2pdf.*',
                    'classFile'         => 'html2pdf.class.php', // For adding to Yii::$classMap
                    /*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                        'orientation' => 'P', // landscape or portrait orientation
                        'format'      => 'A4', // format A4, A5, ...
                        'language'    => 'en', // language: fr, en, it ...
                        'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
                        'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
                        'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                    )*/
                )
            ),
        ),
	),
    'params'=>array(
        'adminEmail'=>'support@childpass.local',
        'surveyEmail' => 'michael@mammalfish.com'

        //root folder for our folders and fields relative to webroot folder
    ),
);