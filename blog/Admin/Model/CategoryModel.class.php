<?php

    #################################################
    #   文章分类数据模型类
    #
    #   @param categoryList $arrs 原始的分类数据
    #   @param categoryList $level 菜单等级
    #   @param categoryList $pid 上层菜单的id值
    #################################################

    namespace Admin\Model;
    use Frame\Libs\BaseModel;


    class CategoryModel extends BaseModel {
        
        // 受保护的数据表名称
        protected $table = "category";


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