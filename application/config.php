<?php
return array(

    'url_route_on' => true,
    'log'          => array(
        'type'             => 'trace', // 支持 socket trace file
        // 以下为socket类型配置
        'host'             => '111.202.76.133',
        //日志强制记录到配置的client_id
        'force_client_ids' => [],
        //限制允许读取日志的client_id
        'allow_client_ids' => [],
    ),

	//模板相关配置
	'theme_on'   => true,
	'parse_str'  => array(
		'__PUBLIC__'    => './public',
	)
);