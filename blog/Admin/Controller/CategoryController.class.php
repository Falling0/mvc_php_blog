<?php

    #########################
    #   文章分类控制器
    #########################

    namespace Admin\Controller;
    use Frame\Libs\BaseController;
    use Admin\Model\CategoryModel;


    final class CategoryController extends BaseController {


        // 显示文章分类首页视图
        public function index() {
            // 权限验证
            $this->denyAccess();

            // 获取所有分类的原始数据 
            $categorys = CategoryModel::getInstance()->fetchAll("id ASC");
            // 获取无限级分类数据：将原始分类数据，转成无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList($categorys);
            // 赋值并显示视图
            $this->smarty->assign("categorys", $categorys);
            $this->smarty->display("index.html");
        }


        // 显示添加视图
        public function add() {
            // 权限验证
            $this->denyAccess();

            // 获取所有分类的原始数据 
            $categorys = CategoryModel::getInstance()->fetchAll("id ASC");
            // 获取无限级分类数据：将原始分类数据，转成无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList($categorys);
            // 赋值并显示视图
            $this->smarty->assign("categorys", $categorys);
            $this->smarty->display("add.html");
        }


        // 显示修改视图
        public function edit() {
            // 权限验证
            $this->denyAccess();

            // 获取表单数据
            $id = $_GET['id'];
            $category = CategoryModel::getInstance()->fetchOne("id={$id}");
            $this->smarty->assign("category", $category);

            // 获取所有分类的原始数据 
            $categorys = CategoryModel::getInstance()->fetchAll("id ASC");
            // 获取无限级分类数据：将原始分类数据，转成无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList($categorys);
            // 赋值并显示视图
            $this->smarty->assign("categorys", $categorys);

            $this->smarty->display("edit.html");
        }


        // 添加分类
        public function insert() {
            // 权限验证
            $this->denyAccess();

            // 获取表单数据
            $data['classname'] = $_POST['classname'];
            $data['orderby'] = $_POST['orderby'];
            $data['pid'] = $_POST['pid'];

            // 判断分类是否已经存在
            if(CategoryModel::getInstance()->rowCount("classname='{$data['classname']}'")) {
                $this->jump("该分类已经存在！", "?c=Category");
            }

            if(CategoryModel::getInstance()->insert($data)) {
                $this->jump("数据添加成功！", "?c=Category");
            } else {
                $this->jump("数据添加失败！", "?c=Category");
            }
        }


        // 删除数据
        public function delete() {
            // 权限验证
            $this->denyAccess();

            $id = $_GET['id'];

            // 判断是否子分类
            $categorys = CategoryModel::getInstance()->fetchAll("id ASC");
            if (CategoryModel::getInstance()->categoryList($categorys, 0, $id)) {
                $this->jump("id={$id}的记录下有子分类，不能删除！", "?c=Category");
            }

            // 删除记录
            if(CategoryModel::getInstance()->delete($id)) {
                $this->jump("id={$id}的记录删除成功！", "?c=Category");
            } else {
                $this->jump("id={$id}的记录删除失败！", "?c=Category");
            }
        }


        // 修改数据
        public function update() {
            // 权限验证
            $this->denyAccess();

            // 获取表单数据
            $id = $_POST['id'];
            $data['classname'] = $_POST['classname'];
            $data['orderby'] = $_POST['orderby'];
            $data['pid'] = $_POST['pid'];

            if(CategoryModel::getInstance()->update($data, $id)) {
                $this->jump("id={$id}的记录修改完成！", "?c=Category");
            } else {
                $this->jump("id={$id}的记录修改失败！", "?c=Category");
            }
        }


    }

?>