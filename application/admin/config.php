<?php
return array(

	//模板相关配置
	'theme_on'   => false,
	'parse_str'  => array(
		'__PUBLIC__'    => '/public',
		'__CSS__'       => '/application/admin/static/css',
		'__JS__'       => '/application/admin/static/js',

	),

	'donot_to_check' => array('admin/index/login')
);