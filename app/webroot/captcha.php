<?php
valiCode('verify_code');
   /**
 * 生成验证码图片
 *
 * @param String $word 验证码在session中的变量名称
 */
function valiCode($word='randcode'){
	Header("Content-type: image/jpg");
	$border = 1; //是否要边框 1要:0不要
	$how = 4; //验证码位数
	$w = $how * 15; //图片宽度
	$h = 25; //图片高度
	$fontsize = 32; //字体大小
	$alpha = "abcdefghijkmnpqrstuvwxyz"; //验证码内容1:字母
	$number = "23456789"; //验证码内容2:数字
	$randcode = ""; //验证码字符串初始化
	srand((double)microtime()*1000000); //初始化随机数种子
	$im = imagecreate($w, $h); //创建验证图片
	/*
	* 绘制基本框架
	*/
	$bgcolor = imagecolorallocate($im, 255, 255, 255); //设置背景颜色
	imageFill($im, 0, 0, $bgcolor); //填充背景色
	if($border)
	{
		$black = imagecolorallocate($im, 9, 9, 9); //设置边框颜色
		imagerectangle($im, 0, 0, $w-1, $h-1, $black);//绘制边框
	}
 
	/*
	* 逐位产生随机字符
	*/
	for($i=0; $i<$how; $i++)
	{
		$alpha_or_number = mt_rand(0, 1); //字母还是数字
		$str = $alpha_or_number ? $alpha : $number;
		$which = mt_rand(0, strlen($str)-1); //取哪个字符
		$code = substr($str, $which, 1); //取字符
		$j = !$i ? 4 : $j+15; //绘字符位置
		$color3 = imagecolorAllocate($im, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100)); //字符随即颜色
		imagechar($im, $fontsize, $j, 3, $code, $color3); //绘字符
		$randcode .= $code; //逐位加入验证码字符串
	}
 
	/*
	* 如果需要添加干扰就将注释去掉
	*
	* 以下for()循环为绘背景干扰线代码
	*/
	/* + -------------------------------绘背景干扰线 开始-------------------------------------------- + */
	for($i=0; $i<5; $i++)//绘背景干扰线
	{
		$color1 = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干扰线颜色
		imagearc($im, mt_rand(-5,$w), mt_rand(-5,$h), mt_rand(20,300), mt_rand(20,200), 55, 44, $color1); //干扰线
	}
	/* + -------------------------------绘背景干扰线 结束-------------------------------------- + */
 
	/*
	* 如果需要添加干扰就将注释去掉
	*
	* 以下for()循环为绘背景干扰点代码
	*/
	/* + --------------------------------绘背景干扰点 开始------------------------------------------ + */
 
	for($i=0; $i<$how*40; $i++)//绘背景干扰点
	{
		$color2 = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干扰点颜色
		imageSetPixel($im, mt_rand(0,$w), mt_rand(0,$h), $color2); //干扰点
	}
 
	/* + --------------------------------绘背景干扰点 结束------------------------------------------ + */
 
	//把验证码字符串写入session  方便提交登录信息时检验验证码是否正确  例如：$_POST['randcode'] = $_SESSION['randcode']
	session_start();
	$_SESSION[$word] = $randcode;
	/*绘图结束*/
	imagejpeg($im);
	imagedestroy($im);
	/*绘图结束*/
}