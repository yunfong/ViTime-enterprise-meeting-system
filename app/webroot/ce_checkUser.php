<?php
   header("Content-Type:text/xml;charset=UTF-8");
   $userName=$_GET['userName'];
   $password=$_GET['passwrod'];
   $roomID=$_GET['roomID'];
   $role=$_GET['role'];  //���ϲ�����CeCallMeet��������
   $allRight=true;      //��֤�Ƿ�ͨ��,�Լ������һ������
   
   //���������������Լ�����֤����,��������Լ�����
   
   
   
   //��֤�����������CeCallMeet
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