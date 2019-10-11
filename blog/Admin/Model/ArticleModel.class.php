<?php

    #############################
    #   文章管理模型类
    #############################

    namespace Admin\Model;
    use Frame\Libs\BaseModel;


    class ArticleModel extends BaseModel {

        // 受保护的数据库表名称
        protected $table = "article";

        // 获取连表查询的多条数据
        public function fetchAllWithJoin($startrow=0, $pagesize=10, $where="2>1") {
            // 构建连表查询的SQL语句
            $sql = "SELECT article.*, category.classname, user.username FROM {$this->table} ";
            $sql .= "LEFT JOIN category ON article.category_id=category.id ";
            $sql .= "LEFT JOIN user ON article.user_id=user.id ";
            $sql .= "WHERE {$where} ";
            $sql .= "ORDER BY article.orderby ASC, article.id DESC ";
            $sql .= "LIMIT {$startrow},{$pagesize}";
            // 执行SQL语句，返回二维数组
            return $this->pdo->fetchAll($sql);
        }


    }


?>