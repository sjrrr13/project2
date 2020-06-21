<?php
//启动会话
session_start();
//删除会话
session_unset();
//结束会话
session_destroy();
echo '<script>alert("您已退出登录")</script>';
$url = "../html/logIn.html";
header("Location: " . $url);
?>
