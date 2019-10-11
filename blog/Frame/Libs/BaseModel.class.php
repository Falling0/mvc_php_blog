<?php

    #################################
    #   基础模型类：用于被模型类继承
    #################################


    // 声明命名空间
    namespace Frame\Libs;
    // 引入PDO类
    use Frame\Vendor\PDOWrapper;
    use \rtrim;


    // 定义抽象的基础类模型类
    abstract class BaseModel{

        // 受保护的PDO对象属性
        protected $pdo = null;


        // 构造方法
        public function __construct() {
            // 创建PDOWrapper类的对象
            $this->pdo = new PDOWrapper();
        }


        // 公共的静态创建不同模型对象的方法
        public static function getInstance() {
            // 获取静态化调用的类名（静态化调用自动获取）
            $modelClassName = get_called_class();
            // 创建指定模型类对象，并返回
            return new $modelClassName();
        }


        // 获取一行数据
        public function fetchOne($where="2>1", $orderby="id ASC") {
            $sql = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderby}";
            return $this->pdo->fetchOne($sql);
        }


        // 获取多行数据
        public function fetchAll($orderby="id DESC") {
            $sql = "SELECT * FROM {$this->table} ORDER BY {$orderby}";
            return $this->pdo->fetchAll($sql);
        }


        // 删除
        public function delete($id) {
            $sql = "DELETE FROM {$this->table} WHERE id={$id}";
            return $this->pdo->exec($sql);
        }


        // 添加
        public function insert($data) {
            // 构建“字段列表”和“值列表”字符串
            $fields = '';
            $values = '';
            foreach($data as $key=>$value) {
                $fields .= "$key,";
                $values .= "'{$value}',";
            }
            // 去除结尾的逗号
            $fields = rtrim($fields, ',');
            $values = rtrim($values, ',');
            // 构建插入的SQL语句
            $sql = "INSERT INTO {$this->table}($fields) VALUES($values)";
            return $this->pdo->exec($sql);
        }


        // 更新记录
        public function update($data, $id) {
            // 构建“字段名=字段值”的字符串
            $str = "";
            foreach($data as $key=>$value) {
                $str .= "{$key}='{$value}',";
            }
            // 去除结尾逗号
            $str = rtrim($str, ',');
            // 构建更新的SQL语句
            $sql = "UPDATE {$this->table} SET {$str} WHERE id={$id}";
            return $this->pdo->exec($sql);
        }        


        // 获取记录数
        public function rowCount($where="2>1") {
            $sql = "SELECT * FROM {$this->table} WHERE {$where}";
            return $this->pdo->rowCount($sql);
        }


    }




?>