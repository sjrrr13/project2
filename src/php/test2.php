<?php
header("content-type:text/html;charset=utf8");
$dns = "mysql:host=localhost;dbname=数据库";
$db = new PDO($dns,'root','root',array(PDO::ATTR_PERSISTENT));
$db -> query("set names utf-8");
//查询总条数
$count = $db -> query("SELECT COUNT(*) FROM seven_day") -> fetchColumn();
//当前页
$page = isset($_GET['page']) ? $_GET['page'] : 1;
//每页显示条数
$size = 2;
//总页数（尾页）
$last = ceil($count/$size);
//上一页
$prev_page = $page - 1 < 1 ? 1 : $page - 1;
//下一页
$next_page = $page + 1 > $last ? $last : $page + 1;
//偏移量
$offset = ($page - 1) * $size;
//执行sql语句
$data = $db -> query("select * from seven_day limit $offset, $size");
?>
<div>
    <a href="show.php?page=<?php echo 1?>">首页</a>&nbsp;&nbsp;
    <a href="show.php?page=<?php echo $prev_page?>">上一页</a>&nbsp;&nbsp;
    <a href="show.php?page=<?php echo $next_page?>">下一页</a>&nbsp;&nbsp;
    <a href="show.php?page=<?php echo $last?>">尾页</a>&nbsp;&nbsp;
</div>
