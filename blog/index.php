<?php

    ###################
    #   前台入口文件
    ###################

    // 常量定义
    define("DS", DIRECTORY_SEPARATOR);          // 动态目录分隔符
    define("ROOT_PATH", getcwd().DS);           // 当前目录
    define("APP_PATH", ROOT_PATH."Home".DS);    // Home目录

    // 包含框架初始化类
    require_once(ROOT_PATH."Frame".DS."Frame.class.php");

    // 调用框架初始化方法
    Frame\Frame::run();


?>