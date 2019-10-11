<?php

    #####################################
    #   基础控制器类：用于被控制器类继承
    #####################################

    namespace Frame\Libs;
    use Frame\Vendor\Smarty;

    // 定义抽象的基础控制器类
    abstract class BaseController{

        // 受保护的smarty对象属性
        protected $smarty = null;

        // 构造方法
        public function __construct() {
            // 创建Smarty类的对象
            $smarty = new Smarty();
            // 配置samrty的左右定界符
            $smarty->left_delimiter = "<{";
            $smarty->right_delimiter = "}>";
            // 指定新的编译目录
            $smarty->setCompileDir(sys_get_temp_dir().DS.'view'.DS);
            // 指定视图模板目录
            $smarty->setTemplateDir(VIEW_PATH);
            // 给$this->smarty属性赋值
            $this->smarty = $smarty;
        }


        // 用户是否登录验证
        protected function denyAccess() {
             // 判断用户是否存在（登录）
             if(empty($_SESSION['username'])) {
                $this->jump("请先登录！", "?c=User&a=login");
            }           
        }


        // 跳转方法
        protected function jump($message, $url='?', $time=3) {
            echo "<h2>{$message}</h2>";
            header("refresh:{$time};url={$url}");
            die();
        }


        // 判断表单提交数据是否为空
        protected function empty_data($arr=2) {
            $cunt = 0;
            if($arr == 2) {
                foreach($_POST as $key) {
                    if(empty($key || $key2=='<br />')) {
                        return 1;
                    }
                }
            } else {
                foreach($arr as $key2) {
                    if(empty($key2) || $key2=='<br />') {
                        return 1;
                    }
                }
            }
        }


    }

?>