<?php

    #########################
    #   文章分类模型类
    #########################

    namespace Home\Model;
    use Frame\Libs\BaseModel;


    class CategoryModel extends BaseModel {

        protected $tbale = "category";


        // 获取原始的文章分类连表数据（连接文章数量）
        public function fetchAllWithJoin() {
            // 构建SQL语句
            $sql = "SELECT category.*,count(article.id) as article_count FROM {$this->tbale} ";
            $sql .= "LEFT JOIN article ON category.id=article.category_id ";
            $sql .= "GROUP BY category.id ";
            $sql .= "ORDER BY category.id ASC";
            return $this->pdo->fetchAll($sql);
        }


        // 获取无限级分类数据的方法
        public function categoryList($arrs, $level=0, $pid=0) {
            // 静态变量：一定在方法中定义，第一次调用方法是初始化一次，第二次调用不再初始化
            // 静态变量的值在方法执行完毕不会消失
            static $categorys = array();

            // 循环原始的分类数组
            foreach($arrs as $arr) {
                // 查找下级菜单
                if($arr['pid']==$pid) {
                    $arr['level'] = $level;
                    $categorys[] = $arr;
                    // 递归调用
                    $this->categoryList($arrs, $level+1, $arr['id']);
                }
            }
            // 返回结果
            return $categorys;
        }


    }


?>