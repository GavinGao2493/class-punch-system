<?php
date_default_timezone_set("Asia/Shanghai"); //设置时区
$hisfile=file(date('Y-m-d') .'.txt'); //返回数组的内容
foreach($hisfile as $v)
{
  echo $v.'<br>'; //逐个输出内容
}
?>
