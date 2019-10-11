<?php

    ##########################
    #   友情链接控制器
    ##########################

    namespace Admin\Controller;
    use Frame\Libs\BaseController;
    use Admin\Model\LinksModel;


    // 定义友情链控制器接类
    final class LinksController extends BaseController {

        // 显示连接列表视图
        public function index() {
            // 权限验证
            $this->denyAccess();

            // 获取链接数据
            $links = LinksModel::getInstance()->fetchAll();
            $this->smarty->assign("links", $links);
            $this->smarty->display('index.html');
        }


        // 显示添加连接视图
        public function add() {
            // 权限验证
            $this->denyAccess();

            $this->smarty->display('add.html');
        }        


        // 显示修改页面视图
        public function edit() {
            // 权限验证
            $this->denyAccess();

            // 获取id号
            $id = $_GET['id'];
            $link = LinksModel::getInstance()->fetchOne("id={$id}");
            $this->smarty->assign("link", $link);
            $this->smarty->display('edit.html');
        }


        // 添加友情链接
        public function insert() {
            // 权限验证
            $this->denyAccess();

            // 获取表单数据
            $data['domain'] = $_POST['domain'];
            $data['url'] = $_POST['url'];
            $data['orderby'] = $_POST['orderby'];

            // 判断是否已有
            if(LinksModel::getInstance()->rowCount("domain='{$data['domain']}' or url='{$data['url']}'")) {
                $this->jump("该友链已经存在！", "?c=Links");
            }

            if(LinksModel::getInstance()->insert($data)) {
                $this->jump("友链：{$data['domain']}，添加成功！", "?c=Links");
            } else {
                $this->jump("友链：{$data['domain']}，添加失败！", "?c=Links");
            }
        }


        // 删除友链
        public function delete() {
            // 权限验证
            $this->denyAccess();

            // 获取要删除的id
            $id = $_GET['id'];
            if(LinksModel::getInstance()->delete($id)) {
                $this->jump("id={$id}的记录删除成功！", "?c=Links");
            } else {
                $this->jump("id={$id}的记录删除失败！", "?c=Links");
            }
        }


        // 修改友链
        public function update() {
            // 权限验证
            $this->denyAccess();

            // 获取表单数据
            $id = $_POST['id'];
            $data['domain'] = $_POST['domain'];
            $data['url'] = $_POST['url'];
            $data['orderby'] = $_POST['orderby'];

            if(LinksModel::getInstance()->update($data, $id)) {
                $this->jump("id={$id}的记录修改成功！", "?c=Links");
            } else {
                $this->jump("id={$id}的记录修改失败！", "?c=Links");
            }
        }

    }


?>