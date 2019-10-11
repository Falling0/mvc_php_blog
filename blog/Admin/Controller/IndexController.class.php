<?php

    ##########################
    # 首页控制器
    ##########################


    namespace Admin\Controller;
    use Frame\Libs\BaseController;


    // 定义后台首页控制器类
    final class IndexController extends BaseController {

        // 显示首页
        public function index() {
            // 权限验证
            $this->denyAccess();

            $this->smarty->display("index.html");
        }


        // 显示顶部框架
        public function top() {
            // 权限验证
            $this->denyAccess();

            $this->smarty->display("top.html");
        }


        // 显示左侧框架
        public function left() {
            // 权限验证
            $this->denyAccess();

            $this->smarty->display("left.html");
        }


        // 显示中间框架
        public function center() {
            // 权限验证
            $this->denyAccess();

            $this->smarty->display("center.html");
        }


        // 显示主框架
        public function main() {
            // 权限验证
            $this->denyAccess();
            
            $this->smarty->display("main.html");         
        }


    }



?>