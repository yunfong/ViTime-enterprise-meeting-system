<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh_cn" lang="zh_cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $login_user->username?$login_user->username:'游客'; ?></title>
<script language="javascript">AC_FL_RunContent = 0;</script>
<script src="/js/AC_RunActiveContent.js" language="javascript"></script>

</head>
<body bgcolor="#ffffff" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
	<?php
	if(!empty($connStr))
	{
?>

<script language="javascript">
	if (AC_FL_RunContent == 0) {
		alert("此页需要 AC_RunActiveContent.js");
	} else {
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=1,1,1,0',
			'width', '100%',
			'height', '100%',
			'src', '/preloader?<?php echo $connStr ?>',
			'quality', 'high',
			'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
			'align', 'middle',
			'play', 'true',
			'loop', 'true',
			'scale', 'showall',
			'wmode', 'window',
			'devicefont', 'false',
			'id', 'preloader',
			'bgcolor', '#ffffff',
			'name', 'preloader',
			'menu', 'true',
			'allowFullScreen', 'true',
			'allowScriptAccess','sameDomain',
			'movie', '/preloader?<?php echo $connStr ?>',
			'salign', ''
			); //end AC code
	}
</script>
<noscript>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=1,1,1,1" width="60%" height="100%" id="preloader" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="allowFullScreen" value="true" />
	<param name="movie" value="/preloader.swf?<?php echo $connStr ?>" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />	<embed src="/preloader.swf?<?php echo $connStr ?>" quality="high" bgcolor="#ffffff" width="100%" height="100%" name="preloader" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
</noscript>
<?php
	}
	else
	{
	
?>
   
<?php
}
?>
</body>
</html>