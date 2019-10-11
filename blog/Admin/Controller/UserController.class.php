<?php

    ##########################
    # 用户控制器
    ##########################

    namespace Admin\Controller;
    use Frame\Libs\BaseController;
    use Admin\Model\UserModel;
    use Frame\Vendor\Captcha;


    // 定义后台用户控制器类
    final class UserController extends BaseController {

        // 显示用户列表视图
        public function index() {
            // 权限验证
            $this->denyAccess();
            // 创建模型类对象
            $modelObj = UserModel::getInstance();
            // 获取多行数据
            $users = $modelObj->fetchAll();
            // 向视图赋值，并显示视图
            $this->smarty->assign('users', $users);
            $this->smarty->display('index.html');
        }


        // 登录窗口视图
        public function login() {
            $this->smarty->display("login.html");
        }


        // 登录验证处理
        public function loginCheck() {
            // 获取表单提交值
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $verify = $_POST['verify'];

            // 判断验证码与服务器保存的是否一致
            if(strtolower($verify) != $_SESSION['captcha']) {
                $this->jump("验证码不正确！", "?c=User&a=login");
            }
            
            // 判断用户名和密码，与数据库中的是否一致
            $user = UserModel::getInstance()->fetchOne("username='$username' and password='$password'");
            if(!$user) {
                $this->jump("用户名或密码不正确！", "?c=User&a=login");
            }

            // 判断用户状态是否正常
            if(empty($user['status'])) {
                $this->jump("该账号已停用！", "?c=User&a=login");
            }

            // 更新数据库关于用户的登录数据：最后登录ip、最后登录时间、登录的次数
            $data['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
            $data['last_login_time'] = time();
            $data['login_times'] = $user['login_times'] + 1;
            if(!UserModel::getInstance()->update($data, $user['id'])) {
                $this->jump("用户信息更新失败！", "?c=User&a=login");
            }

            // 保存用户的登录状态
            $_SESSION['uid'] = $user['id'];
            $_SESSION['username'] = $username;

            // 跳转后台首页
            header("location:./admin.php");
        }


        // 验证码
        public function captcha() {
            // 创建验证码类的对象
            $captcha = new Captcha();
            // 将验证码字符串保存到session中
            $_SESSION['captcha'] = $captcha->getCode();
        }


        // 显示添加用户页视图
        public function add() {
            // 权限验证
            $this->denyAccess();
            $this->smarty->display('add.html');
        }


        // 删除指定用户
        public function delete() {
            // 权限验证
            $this->denyAccess();
            $id = $_GET['id'];
            if(UserModel::getInstance()->delete($id)) {
                $this->jump("id={$id}的记录删除成功", "?c=User");
            } else {
                $this->jump("id={$id}的记录删除失败", "?c=User");
            }
        }


        // 插入新用户信息
        public function insert() {
            // 权限验证
            $this->denyAccess();
            // 获取表单数据
            $data['username'] = $_POST['username'];
            $data['password'] = md5($_POST['password']);
            $data['name'] = $_POST['name'];
            $data['tel'] = $_POST['tel'];
            $data['status'] = $_POST['status'];
            $data['role'] = $_POST['role'];
            $data['addate'] = time();

            // 判断用户是否已经存在
            if(UserModel::getInstance()->rowCount("username='{$data['username']}'")) {
                $this->jump("用户{$data['username']}已经被注册了！", "?c=User");
            }

            // 判断两次密码是否一致
            if($data['password'] != md5($_POST['confirmpwd'])) {
                $this->jump("两次输入的密码不一致！", "?c=User");
            }

            // 判断记录是否插入成功
            if(UserModel::getInstance()->insert($data)) {
                $this->jump("用户注册成功！", "?c=User");
            } else {
                $this->jump("用户注册失败！", "?c=User");
            }
        }


        // 显示修改视图
        public function edit() {
            // 权限验证
            $this->denyAccess();
            $id = $_GET['id'];
            $user = UserModel::getInstance()->fetchOne("id={$id}");
            $this->smarty->assign("user", $user);
            $this->smarty->display("edit.html");
        }


        // 修改更新指定用户信息
        public function update() {
            // 权限验证
            $this->denyAccess();
            // 获取表单数据
            $id = $_POST['id'];
            $data['name'] = $_POST['name'];
            $data['tel'] = $_POST['tel'];
            $data['status'] = $_POST['status'];
            $data['role'] = $_POST['role'];

            // 判断密码是否为空
            if(!empty($_POST['password']) && !empty($_POST['confirmpwd'])) {
                // 判断两次密码是否一致
                if($_POST['password'] == $_POST['confirmpwd']) {
                    $data['password'] = md5($_POST['password']);
                }
            }

            if(UserModel::getInstance()->update($data, $id)) {
                $this->jump("id={$id}记录修改成功！", "?c=User");
            } else {
                $this->jump("id={$id}记录修改失败！", "?c=User");
            }
        }


        // 注销登录
        public function logout() {
            // 权限验证
            $this->denyAccess();
            // 删除session数据
            unset($_SESSION['uid']);
            unset($_SESSION['username']);
            // 删除session文件
            session_destroy();
            // 设置当前session对应的cookie数据为过期时间
            setcookie(session_name(), false);
            // 跳转到登录界面
            header("location:./admin.php?c=User&a=login");
        }

    }


?>