<?php

    ##################################
    #   文章管理控制器类
    ##################################
    
    namespace Admin\Controller;
    use Frame\Libs\BaseController;
    use Admin\Model\ArticleModel;
    use Admin\Model\CategoryModel;


    final class ArticleController extends BaseController {


        // 显示首页文章管理视图
        public function index() {
            // 权限验证
            $this->denyAccess();

            // 获取无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList(
                CategoryModel::getInstance()->fetchAll("id ASC")
            );

            // 构建搜索条件
            $where = "2>1";
            if(!empty($_REQUEST['category_id'])) $where .= " AND article.category_id=" . $_REQUEST['category_id'];  
            if(!empty($_REQUEST['keyword'])) $where .= " AND title LIKE '%" .$_REQUEST['keyword'] ."%'";
            
            // 分页参数
            $pagesize = 10;                                          // 每页显示多少条数据
            $page = isset($_GET['page']) ? $_GET['page'] : 1;       // 当前页码
            $startrow = ($page-1) * $pagesize;                      // 开始行号
            $records = ArticleModel::getInstance()->rowCount($where);     // 总记录数
            $params = array('c'=>CONTROLLER, 'a'=>ACTION);          // 附加参数
            if(!empty($_REQUEST['category_id'])) $params['category_id'] = $_REQUEST['category_id'];
            if(!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];

            // 创建分页类对象
            $pageObj = new \Frame\Vendor\Page($records, $pagesize, $page, $params);
            $pageStr = $pageObj->showPage();

            // 获取所有文章的连表查询分页数据
            $articles = ArticleModel::getInstance()->fetchAllWithJoin($startrow, $pagesize, $where);

            // 赋值并显示视图
            $this->smarty->assign(array(
                'categorys' => $categorys,
                'articles' => $articles,
                'pageStr' => $pageStr, 
            ));
            $this->smarty->display("index.html");
        }


        // 显示添加文章视图
        public function add() {
            // 权限验证
            $this->denyAccess();

            // 获取无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList(
                CategoryModel::getInstance()->fetchAll("id ASC")
            );

            $this->smarty->assign("categorys", $categorys);
            $this->smarty->display("add.html");
        }


        // 显示修改文章视图
        public function edit() {
            // 权限验证
            $this->denyAccess();

            $id = $_GET['id'];

            // 获取无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList(
                CategoryModel::getInstance()->fetchAll("id ASC")
            );

            $articles = ArticleModel::getInstance()->fetchOne("id='{$id}'");

            $this->smarty->assign(array(
                'categorys' => $categorys,
                'articles' => $articles,
            ));
            $this->smarty->display("edit.html");
        }


        // 添加新文章
        public function insert() {
            // 权限验证
            $this->denyAccess();

            // 获取表单数据
            $data['user_id'] = $_SESSION['uid'];
            $data['category_id'] = $_POST['category_id'];
            $data['title'] = $_POST['title'];
            $data['orderby'] = $_POST['orderby'];
            $data['top'] = isset($_POST['top']) ? 1 : 0;
            $data['content'] = $_POST['content'];
            $data['addate'] = time();

            // 判断表单是否为空（不传参数默认检查$_POST的全部项）
            if($this->empty_data(array('title' => $_POST['title'], 'content' => $_POST['content']))) {
                $this->jump("提交数据不能为空！", "?c=Article&a=add");
            }

            // 判断是否成功插入数据
            if(ArticleModel::getInstance()->insert($data)) {
                $this->jump("数据添加成功！", "?c=Article");
            } else {
                $this->jump("数据添加失败！", "?c=Article");
            }
        }


        // 删除文章
        public function delete() {
            // 权限验证
            $this->denyAccess();

            $id = $_GET['id'];
            if(ArticleModel::getInstance()->delete($id)) {
                $this->jump("id={$id}的记录删除成功！", "?c=Article");
            } else {
                $this->jump("id={$id}的记录删除失败！", "?c=Article");
            }
        }


        // 修改文章
        public function update() {
            // 权限验证
            $this->denyAccess();

            // 获取表单数据
            $id = $_POST['id'];
            $data['category_id'] = $_POST['category_id'];
            $data['user_id'] = $_SESSION['uid'];
            $data['title'] = $_POST['title'];
            $data['orderby'] = $_POST['orderby'];
            $data['top'] = isset($_POST['top']) ? 1 : 0;
            $data['content'] = $_POST['content'];

            // 判断表单是否为空（不传参数默认检查$_POST的全部项）
            if($this->empty_data(array('title' => $_POST['title'], 'content' => $_POST['content']))) {
                $this->jump("提交数据不能为空！", "?c=Article&a=edit&id=$id");
            }

            // 更新数据
            if(ArticleModel::getInstance()->update($data, $id)) {
                $this->jump("id={$id}的记录修改成功！", "?c=Article");
            } else {
                $this->jump("id={$id}的记录修改失败！", "?c=Article");
            }
        }


    }


?>