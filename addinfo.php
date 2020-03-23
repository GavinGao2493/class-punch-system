<?php
error_reporting(0); //关闭数组溢出弹窗(危)
function getip()  //获取ip地址
{
  if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
  {
    $ip = getenv("HTTP_CLIENT_IP");
  }
  else
    if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
    {
      $ip = getenv("HTTP_X_FORWARDED_FOR");
    }
    else
      if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
      {
        $ip = getenv("REMOTE_ADDR");
      }
      else
        if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        {
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        else
        {
          $ip = "unknown";
        }
  return ($ip);
}
function checkifable(string $inname,string $innumber) //检查是否匹配
{
  $namefile=file('namelist.txt'); //打开姓名列表文件
  foreach ($namefile as $name) //导入文件中的姓名学号数据
  {
    if ($name=="")  //最后一行防空处理
    {
      continue;
    }
    $position_temp=strpos($name," ");
    $number_temp=substr($name,0,$position_temp);
    $name_temp=substr($name,$position_temp+1,strlen($name)-$position_temp-3); //-3的目的是除去换行+回车所占的字符数目
    $map[$number_temp]=$name_temp;  //map数组的下标代表学号，内部存储字符串，代表姓名
  }
  return $map[$innumber]==$inname;  //判断姓名是否相同
}
if (empty($_POST["name"]) || empty($_POST["number"]) || checkifable($_POST["name"],$_POST["number"])==0)
{
  echo "<script>alert('姓名学号不匹配，打卡失败，请检查姓名学号前后是否有多余的空格')</script>";
  header("Refresh:0;url=./index.html"); //跳转回主页
  exit(); //失败自动结束程序运行
}
date_default_timezone_set("Asia/Shanghai"); //设置时区
$st=date('Y-m-d');  //获取当前日期以确定文件名
//下面是在保存打卡记录
$filename=$st . ".txt";
$handle=fopen($filename,"a+");
$str=fwrite($handle,$_POST["name"] . " " . $_POST["number"] . " " . date('Y-m-d H:i:s', time()) . " " . getip() . "\n");  //追加打卡数据
fclose($handle);
$st=date('Y-m-d');  //本句语句可以删除
//下面在保存ip地址
$filename=$st . ".ip";
$handle=fopen($filename,"a+");
$str=fwrite($handle,getip() . " " . $_POST["name"] . " " . date('Y-m-d H:i:s',time()) . "\n");  //追加ip数据
fclose($handle);
echo "<script>alert('打卡成功')</script>";
header("Refresh:0;url=./index.html"); //跳转回主页
?>
