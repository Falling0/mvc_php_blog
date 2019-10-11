<?php

    ##############################
    #   前台首页控制器类
    ##############################

    // 声明命名空间：空间名要与所在目录路径一致
    namespace Home\Controller;
    use Frame\Libs\BaseController;
    use Home\Model\LinksModel;
    use Home\Model\CategoryModel;
    use Home\Model\ArticleModel;

    
    // 定义首页控制器类
    class IndexController extends BaseController {


        // 显示首页视图
        public function index() {

            // 获取友情链接数据
            $links = LinksModel::getInstance()->fetchAll();
            
            // 获取无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList(
                CategoryModel::getInstance()->fetchAllWithJoin()
            );

            // 获取文章按月份归档数据
            $months = ArticleModel::getInstance()->fetchAllWithCount();

            // 构建搜索的条件
            $where = "2>1";
            if(!empty($_GET['category_id'])) $where .= " AND category_id=" . $_GET['category_id'];
            if(!empty($_REQUEST['title'])) $where .= " AND title LIKE '%" . $_REQUEST['title'] . "%'";
            // if(!empty($_GET['yearmonth'])) $where .= "";

            // 分页参数
            $pagesize = 8;                                          // 每页显示数
            $page = isset($_GET['page']) ? $_GET['page'] : 1;     // 当前页码
            $startrow = ($page - 1) * $pagesize;                    // 开始行号
            $records = ArticleModel::getInstance()->rowCount($where);     // 总记录数
            $params = array('c' => CONTROLLER, 'a' => ACTION);      // 附加参数

            if(!empty($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
            if(!empty($_REQUEST['title'])) $params['title'] = $_REQUEST['title'];
            // if(!empty($_GET['yearmonth'])) $params['yearmonth'] = $_GET['yearmonth'];

            // 创建分页类对象
            $pageObj = new \Frame\Vendor\Page($records, $pagesize, $page, $params);
            $pageStr = $pageObj->showPage();

            // 获取文章连表查询的数据
            $articles = ArticleModel::getInstance()->fetchAllWithJoin($startrow, $pagesize, $where);

            $this->smarty->assign(array(
                'links' => $links,
                'categorys' => $categorys,
                'months' => $months,
                'articles' => $articles,
                'pageStr' => $pageStr,
            ));
            $this->smarty->display("index.html");
        }


        // 显示列表页
        public function showList() {

            // 获取友情链接数据
            $links = LinksModel::getInstance()->fetchAll();
            
            // 获取无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList(
                CategoryModel::getInstance()->fetchAllWithJoin()
            );

            // 获取文章按月份归档数据
            $months = ArticleModel::getInstance()->fetchAllWithCount();

            // 构建搜索的条件
            $where = "2>1";
            if(!empty($_GET['category_id'])) $where .= " AND category_id=" . $_GET['category_id'];
            if(!empty($_REQUEST['title'])) $where .= " AND title LIKE '%" . $_REQUEST['title'] . "%'";
            // if(!empty($_GET['yearmonth'])) $where .= "";

            // 分页参数
            $pagesize = 30;                                          // 每页显示数
            $page = isset($_GET['page']) ? $_GET['page'] : 1;     // 当前页码
            $startrow = ($page - 1) * $pagesize;                    // 开始行号
            $records = ArticleModel::getInstance()->rowCount($where);     // 总记录数
            $params = array('c' => CONTROLLER, 'a' => ACTION);      // 附加参数

            if(!empty($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
            if(!empty($_REQUEST['title'])) $params['title'] = $_REQUEST['title'];
            // if(!empty($_GET['yearmonth'])) $params['yearmonth'] = $_GET['yearmonth'];

            // 创建分页类对象
            $pageObj = new \Frame\Vendor\Page($records, $pagesize, $page, $params);
            $pageStr = $pageObj->showPage();

            // 获取文章连表查询的数据
            $articles = ArticleModel::getInstance()->fetchAllWithJoin($startrow, $pagesize, $where);

            $this->smarty->assign(array(
                'links' => $links,
                'categorys' => $categorys,
                'months' => $months,
                'articles' => $articles,
                'pageStr' => $pageStr,
            ));
            $this->smarty->display("list.html");
        }


        // 显示详细内容
        public function content() {

            // 获取无限级分类数据
            $categorys = CategoryModel::getInstance()->categoryList(
                CategoryModel::getInstance()->fetchAllWithJoin()
            );

            $id = $_GET['id'];

            // 更新文章阅读数
            ArticleModel::getInstance()->updateRead($id);

            // 根据id获取连表查询的文章数据
            $article = ArticleModel::getInstance()->fetchOneWithJoin($id);

            // 获取当前id的前一条和后一条数据
            $prevNext[] = ArticleModel::getInstance()->fetchOne("id<$id", "id DESC");
            $prevNext[] = ArticleModel::getInstance()->fetchOne("id>$id", "id ASC");

            $this->smarty->assign(array(
                'categorys' => $categorys,
                'article' => $article,
                'prevNext' => $prevNext,
            ));
            $this->smarty->display("content.html");
        }


        // 点赞
        public function praise() {
            
            $id = $_GET['id'];
            $ip = $_SERVER['REMOTE_ADDR'];

            if(empty($_SESSION['praise'][$id])) {
                // 更新点赞数
                ArticleModel::getInstance()->updatePraise($id);
                $_SESSION['praise'][$id] = $id;
                $this->jump("", "?c=Index&a=content&id=$id", 0);
            } else {
                $this->jump("每个文章只能点赞一次", "?c=Index&a=content&id=$id");
            }
        }


    }

?>