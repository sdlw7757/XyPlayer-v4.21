<?php 
!defined('APP_PATH') AND define('APP_PATH', rtrim(str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')), '/') . '/');
if(!file_exists(APP_PATH.'save/config.php')){ exit("input error!");}
include APP_PATH.'include/class.main.php';include APP_PATH.'save/config.php';
$action=filter_input(INPUT_GET, 'action');session_start();
switch ($action){ 
   case  "validate":  //验证码
      include 'validate.php';		
      exit;
   case  "logout":    //退出登录  
     
      if(!empty($_SESSION['hashstr'])){unset($_SESSION['hashstr']);unset($_SESSION['username']);}  
      if (filter_has_var(INPUT_COOKIE, 'login_token')){setcookie('login_token', '', time() - 3600*24, "/"); }  // 删除Cookie
      ShowMsg('注销登录成功！',"./");
      exit;
      
   default : //登录 
       if(!filter_has_var(INPUT_POST, 'username') || !filter_has_var(INPUT_POST, 'password')){ ShowMsg('用户和密码没填写完整!','-1'); exit(); } 
       
       // 验证码验证
       $validate = filter_input(INPUT_POST, 'validate');
       $session_validate = isset($_SESSION['authnum_session']) ? $_SESSION['authnum_session'] : '';
       if(empty($validate) || empty($session_validate) || strtolower($validate)!==strtolower($session_validate)){
           ShowMsg('验证码错误!','-1'); exit(); 
       }
       
       // 获取用户名密码
       $username = trim(htmlspecialchars(filter_input(INPUT_POST, 'username')));  
       $password = trim(filter_input(INPUT_POST, 'password')); 
       
       // 强制使用固定的用户名密码
       $user = "admin";
       $pass = "63468756";
       
       // 登录验证
       if($username==$user&&$password==$pass)
       {     //登录成功  
              $hashstr=md5($username.$password);       //构造session安全码
              setcookie('login_token', '1', time() + 3600*24, "/"); // 设置有效期24小时
              $_SESSION['hashstr']=$hashstr; $_SESSION['username']=$username;
	          ShowMsg('成功登录，正在转向管理管理主页！',"./");	
	          exit();
       }else{  
              ShowMsg('用户名或密码错误！',"-1");
	     exit();
       }
          
}




 