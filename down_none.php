<?php
error_reporting(0);
date_default_timezone_set("Asia/Shanghai"); //设置时区
$namefile=file('namelist.txt');  //打开姓名列表文件
foreach ($namefile as $name) //导入文件中的姓名学号数据
{
  if ($name=="")  //最后一行防空处理
  {
    continue;
  }
  $position_temp=strpos($name," "); //寻找学号与姓名之间用作分隔的空格
  $number_temp=substr($name,0,$position_temp);  //提取学号
  $name_temp=substr($name,$position_temp+1,strlen($name)-$position_temp-3); //-3的目的是除去换行+回车所占的字符数目
  $map[$number_temp]=$name_temp;  //map数组的下标代表学号，内部存储字符串，代表姓名
}
echo "以下为未打卡的同学姓名：(如为空白，则证明都已打卡)<br/>";
echo "<br/>";
$myfile = fopen(date('Y-m-d') .'.txt', "r");
$st=fread($myfile,filesize(date('Y-m-d') .'.txt'));
$st1="@*#&%" . $st; //在文件头部加上不影响的其他字符，以避免strpos搜索无法找到为0，在第一位也为0
for ($i=1;$i<46;$i++)
  if (strpos($st1,$map[$i])<=0) //如果找不到打卡记录，则证明未打卡
    echo $map[$i] . '<br/>';
fclose($myfile);
/*上面是处理未打卡名单的*/
/*下面是处理ip地址重复的*/
echo "<br/>";
echo "<br/>";
echo "以下为ip地址出现重复的记录(如为空白，则证明无记录)<br/>";
echo "注：如两位同学家住比较近并且都使用流量进行打卡，由于都从同一信号基站转发信号，可能会导致IP地址重复<br/>";
echo "所以遇到IP地址重复的同学，建议借助打卡时间(或家庭住址)、直接询问等方式进行综合研判<br/>";
echo "<br/>";
//echo "<br/>";
$ipfile=file(date('Y-m-d') .'.ip');
$i=1; //保证数组从1开始
foreach ($ipfile as $v)
{
  $allinfo[$i++]=$v;  //将文件信息导入数组
}
//print_r($allinfo);
$t=$i-1;   //上面i多加了个1
//按ip地址进行排序(选择排序)
for($i=1;$i<=$t;$i++)
  for($j=$i+1;$j<=$t;$j++)
  {
    if ($allinfo[$i]>$allinfo[$j])
    {
      $temp=$allinfo[$i];
      $allinfo[$i]=$allinfo[$j];
      $allinfo[$j]=$temp;
    }
  }
$flag=false;
$allinfo[$t+1]="just for the stand"; //作为放置数组溢出的占位符
$allinfo[0]="just for the stand"; //作为放置数组溢出的占位符
for($i=1;$i<=$t;$i++)
{
  $head=substr($allinfo[$i-1],0,strpos($allinfo[$i-1]," ")-1);  //读取上一个打卡记录的ip
  $now=substr($allinfo[$i],0,strpos($allinfo[$i]," ")-1); //读取本次打卡记录的ip
  $tail=substr($allinfo[$i+1],0,strpos($allinfo[$i+1]," ")-1);  //读取下次打卡记录的ip
  //以下流程为：当下一个ip与本次ip不一样时，若上一个ip与本次ip相同，则输出本次记录；若上一个ip与本次ip不同，则不输出本次记录
  if($head==$now)
    $flag=true; //若flag=true，则证明上一个ip与本次ip相同
  if($now==$tail)
  {
    echo $allinfo[$i]."<br/>";
  }
  else
  {
    if($flag==true)
    {
      echo $allinfo[$i]."<br/>";
      $flag=false;
    }
  }
}
?>
