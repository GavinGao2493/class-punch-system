<?php
  date_default_timezone_set("Asia/Shanghai"); //设置时区
  if(isset($_POST["flag"])) //通过POST传递来避免直接访问此php导致数据误删除
  {
    $st=date('Y-m-d');
    $filename=$st . ".txt";
    $handle=fopen($filename,"w");
    $str=fwrite($handle,"");
    fclose($handle);
    $filename=$st . ".ip";
    $handle=fopen($filename,"w");
    $str=fwrite($handle,"");
    fclose($handle);
    echo "记录删除完成，页面会在2秒内自动跳转";
    header("Refresh:2;url=./check.html");
  }
  else
  {
    echo "<script>alert('不合法调用')</script>";
    header("Refresh:0;url=./control.html");
  }
?>
