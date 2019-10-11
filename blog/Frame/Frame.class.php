<?php

    ###################################
    #   初始化入口类
    ###################################


    // 声明命名空间
    namespace Frame;


    // 定义最终的框架初始类
    final class Frame{
        

        // 公共的静态框架初始化方法
        public static function run() {
            self::initConfig();     // 初始化配置数据
            self::initRoute();      // 初始化路由参数
            self::initConst();      // 初始化常量定义
            self::initAutoLoad();   // 初始化类的自动加载
            self::initDispatch();   // 初始化请求分发
        }


        // 私有的静态初始化配置信息
        private static function initConfig() {
            // 开启session会话
            session_start();
            // 定义全局变量存放配置数组信息
            $GLOBALS['config'] = require_once(APP_PATH."Config".DS."config.php");
        }


        // 私有的静态初始化路由参数
        private static function initRoute() {
            // 获取路由参数
            $p = $GLOBALS['config']['default_platform'];  // 平台参数
            $c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller']; // 控制器参数
            $a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action']; // 行为参数
            define("PLAT", $p);         // 平台常量
            define("CONTROLLER", $c);   // 控制器常量
            define("ACTION", $a);       // 行为常量
        }


        // 私有的静态初始化目录常量
        private static function initConst() {
            define("VIEW_PATH", APP_PATH."View".DS.CONTROLLER.DS);   // View目录
        }


        // 私有的静态初始化类的自动加载
        private static function initAutoLoad() {
            // 类的自动加载
            spl_autoload_register(function($className) {
                // 传递过来的类名参数：Home\Controller\StudentController
                // 类文件的真实路径：./Home/Controller/StudentController.class.php
                // 将传递的类名转成真实类文件路径
                $filename = ROOT_PATH.str_replace("\\", DS, $className).".class.php";

                // 如果类文件存在，则包含
                if(file_exists($filename)) {
                    require_once($filename);
                }
            });
        }


        // 私有的静态初始化请求分发（）
        private static function initDispatch() {
            // 创建控制器类的对象：Home\Controller\StudentController
            $controllerClassName = PLAT."\\"."Controller"."\\".CONTROLLER."Controller";
            $controllerObj = new $controllerClassName();

            // 根据用户的不同动作，调用不同的方法
            $action_name = ACTION;
            $controllerObj->$action_name();
        }


    }

?>