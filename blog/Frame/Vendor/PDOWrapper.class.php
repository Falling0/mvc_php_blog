<?php

    ###########################
    # 获取数据库信息的工具类
    ###########################


    // 声明命名空间
    namespace Frame\Vendor;
    use \PDO;
    use \Exception;


    // 定义最终的PDOWrapper类
    final class PDOWrapper{

        // 数据库配置信息
        private $db_type;       // 数据库类型
        private $db_host;       // 主机地址
        private $db_port;       // 端口
        private $db_user;       // 用户
        private $db_pass;       // 密码
        private $db_name;       // 数据库名
        private $charset;       // 字符集
        private $pdo;           // 数据库对象


        // 公共的构造方法
        public function __construct() {
            $this->db_type = $GLOBALS['config']['db_type'];
            $this->db_host = $GLOBALS['config']['db_host'];
            $this->db_port = $GLOBALS['config']['db_port'];
            $this->db_user = $GLOBALS['config']['db_user'];
            $this->db_pass = $GLOBALS['config']['db_pass'];
            $this->db_name = $GLOBALS['config']['db_name'];
            $this->charset = $GLOBALS['config']['charset'];
            
            $this->createPDO();     // 创建PDO对象
            $this->setErrorMode();  // 设置报错模式
        }


        // 显示错误信息方法
        private function showErrMsg($e, $msg) {
            echo "<h2>$msg</h2>";
            echo "错误编号：".$e->getCode();
            echo "<br>错误行号：".$e->getLine();
            echo "<br>错误文件：".$e->getFile();
            echo "<br>错误信息：".$e->getMessage();
            die();
        }


        // 私有的创建PDO对象的方法
        private function createPDO() {
            try{
                // 构造dsn字符串
                $dsn = "{$this->db_type}:host={$this->db_host};port={$this->db_port};";
                $dsn .= "dbname={$this->db_name};charset={$this->charset};";
                // 创建PDO类的对象
                $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass);
            } catch(Exception $e) {
                $this->showErrMsg($e, "创建PDO对象失败！");
            }
        }


        // 私有的设置PDO报错模式
        private function setErrorMode() {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }


        // 执行SQL语句：insert、update、delete、set等
        public function exec($sql) {
            try{
                return $this->pdo->exec($sql);
            } catch(Exception $e) {
                $this->showErrMsg($e, "执行SQL语句失败！SQL语句有问题！");
            }
        }


        // 获取单行数据
        public function fetchOne($sql) {
            try {
                // 执行SQL语句并返回结果集对象
                $PDOStatement = $this->pdo->query($sql);
                // 返回一条记录
                return $PDOStatement->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $this->showErrMsg($e, "执行SQL语句失败！SQL语句有问题！");
            }
        }


        // 获取多行数据
        public function fetchAll($sql) {
            try {
                // 执行SQL语句并返回结果集对象
                $PDOStatement = $this->pdo->query($sql);
                // 返回多条记录
                return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $this->showErrMsg($e, "执行SQL语句失败！SQL语句有问题！");
            }
        }


        // 获取记录数
        public function rowCount($sql) {
            try {
                // 执行SQL语句并返回结果集对象
                $PDOStatement = $this->pdo->query($sql);
                // 返回记录数
                return $PDOStatement->rowCount();
            } catch (Exception $e) {
                $this->showErrMsg($e, "执行SQL语句失败！SQL语句有问题！");
            }            
        }



    }



?>