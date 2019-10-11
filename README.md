# mvc_php_blog
主要学习MVC的思想，用原生的PHP来实现一个简单的MVC框架，完成blog。

<br>

### 程序结构

<pre>

----index.php         前台入口  
----admin.php         后台入口  
----Frame/            框架核心类文件  
--------Libs/         自定义公共类库  
--------Vendor/       第三方类  
----Home/             前台应用目录  
--------Config/       前台配置目录  
--------Controller/   控制器  
--------Model/        模型  
--------View/         视图  
----Admin/            后台应用目录  
--------Config/       后台配置目录  
--------Controller/   控制器  
--------Model/        模型  
--------View/         视图  
----Public/           静态文件目录  
--------Home/         前台静态文件目录  
------------Css/      css目录  
------------Images/   图片目录  
------------Js/       js目录  
--------Admin/        后台静态文件目录  
------------Css/      css目录  
------------Images/   图片目录  
------------Js/       js目录

</pre>

<br>

### 展示

![演示动态图](https://github.com/Falling0/mvc_php_blog/blob/master/demo_img/demo.gif)

首页
![](https://github.com/Falling0/mvc_php_blog/blob/master/demo_img/list.png)

详细页
![](https://github.com/Falling0/mvc_php_blog/blob/master/demo_img/xiangxi.png)

后台文章列表
![](https://github.com/Falling0/mvc_php_blog/blob/master/demo_img/text.png)

添加文章
![](https://github.com/Falling0/mvc_php_blog/blob/master/demo_img/content.png)
