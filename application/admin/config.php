<?php
return array(

	'user_administrator'   => 1,

	'auth_config' => array(
        'auth_on'           => true,                      // 认证开关
        'auth_type'         => 1,                         // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'auth_group',        // 用户组数据表名
        'auth_group_access' => 'auth_group_access', // 用户-用户组关系表
        'auth_rule'         => 'auth_rule',         // 权限规则表
        'auth_user'         => 'member'             // 用户信息表
	),

	//模板相关配置
	'theme_on'   => false,
	'parse_str'  => array(
		'__PUBLIC__'    => '/public',
		'__CSS__'       => '/application/admin/static/css',
		'__JS__'       => '/application/admin/static/js',

	),

	'donot_to_check' => array('admin/index/login')
);