<?php
   header("Content-Type:text/xml;charset=UTF-8");
   $userName=$_GET['userName'];
   $password=$_GET['passwrod'];
   $roomID=$_GET['roomID'];
   $role=$_GET['role'];  //以上参数是CeCallMeet传过来的
   $allRight=true;      //验证是否通过,自己定义的一个变量
   
   //在下面你可以添加自己的验证代码,规则可以自己决定
   
   
   
   //验证完后输出结果给CeCallMeet
   if($allRight)
   {
		echo "<Result isUser='true'>";
		echo "</Result>";
	}
	else
	{
		echo "<Result isUser='false'>";
		echo "</Result>";
	}
?>