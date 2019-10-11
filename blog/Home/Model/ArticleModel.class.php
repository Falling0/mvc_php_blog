<?php

    #############################
    #   文章模型类
    #############################

    namespace Home\Model;
    use Frame\Libs\BaseModel;


    class ArticleModel extends BaseModel {
        
        protected $table = "article";


        // 获取文章按月份归档数据
        public function fetchAllWithCount() {
            $sql = "SELECT date_format(from_unixtime(addate), '%Y年%m月') as yearmonth, ";
            $sql .= "count(id) as article_count FROM {$this->table} ";
            $sql .= "GROUP BY yearmonth ";
            $sql .= "ORDER BY yearmonth DESC";
            return $this->pdo->fetchAll($sql);
        }


        // 获取文章连表查询的数据
        public function fetchAllWithJoin($startrow, $pagesize, $where) {
            $sql = "SELECT article.*, user.name, category.classname FROM {$this->table} ";
            $sql .= "LEFT JOIN user ON article.user_id=user.id ";
            $sql .= "LEFT JOIN category ON article.category_id=category.id ";
            $sql .= "WHERE {$where} ";
            $sql .= "ORDER BY article.id DESC ";
            $sql .= "LIMIT {$startrow},{$pagesize}";
            return $this->pdo->fetchAll($sql);
        }


        // 获取指定id的连表查询的数据
        public function fetchOneWithJoin($id) {
            $sql = "SELECT article.*, user.name, category.classname FROM {$this->table} ";
            $sql .= "LEFT JOIN user ON article.user_id=user.id ";
            $sql .= "LEFT JOIN category ON article.category_id=category.id ";
            $sql .= "WHERE article.id={$id} ";
            return $this->pdo->fetchOne($sql);           
        }


        // 更新文章阅读数
        public function updateRead($id) {
            $sql = "UPDATE {$this->table} SET `read` = `read` + 1 WHERE id={$id}";
            return $this->pdo->exec($sql);
        }


        // 更新点赞数
        public function updatePraise($id) {
            $sql = "UPDATE {$this->table} SET `praise` = `praise` + 1 WHERE id={$id}";
            return $this->pdo->exec($sql);
        }


    }



?>