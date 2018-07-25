<?php
return array(
	//'配置项'=>'配置值'
//    'URL_MODEL' => '2',
    // 加载扩展配置文件
    'LOAD_EXT_CONFIG' => 'database,siteinfo',
    'TMPL_PARSE_STRING' => array(
        '__ADMIN__' => __ROOT__. '/Public/Admin/',
        '__WUJI__' => __ROOT__. '/Public/Wuji/',
        '__VIEW__' => __ROOT__ . 'Application/' . MODULE_NAME .'/View',
        '__JQUERY__'    => __ROOT__ . '/Public/Admin/jquery',
        '__IMG__'    => __ROOT__ . '/Public/Admin/img',
        '__CSS__'    => __ROOT__ . '/Public/Admin/css',
        '__JS__'     => __ROOT__ . '/Public/Admin/js',
        '__LOCAL__' => __ROOT__. '/Application/Public/Local/Juejin/',
    ),
);