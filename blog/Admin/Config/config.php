<?php

    // 返回配置数组
    return array(
        // 数据库配置信息
        'db_type' => 'mysql',           // 数据库类型
        'db_host' => 'localhost',       // 主机地址
        'db_port' => '3306',             // 端口
        'db_user' => 'root',            // 账户
        'db_pass' => '',            // 密码
        'db_name' => 'blog',            // 数据库名称
        'charset' => 'utf8',            // 字符集

        // 默认的路由配置
        'default_platform' => 'Admin',       // 平台参数
        'default_controller' => 'Index',    // 控制器参数
        'default_action' => 'Index',        // 行为参数
    )

?>