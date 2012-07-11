<?php
/**
 * 分页函数
 * @param int $page
 * @param int $total
 * @param string $phpfile
 * @param int $pagesize
 * @param int $pagelen
 * @return string
 */
function makepage($page, $total, $phpfile, $pagesize = 3, $pagelen = 10) {
	$pagecode = ''; //定义变量，存放分页生成的HTML
	$page = intval ( $page ); //避免非数字页码
	$total = intval ( $total ); //保证总记录数值类型正确
	if (! $total)
		return ''; //总记录数为零返回空数组
		
	$pages = ceil ( $total / $pagesize ); //计算总分页
	if($pages == 1){
		return '';
	}
	
	//处理页码合法性
	if ($page < 1)
		$page = 1;
		
	if ($page > $pages)
		$page = $pages;
	
	//计算查询偏移量
	$offset = $pagesize * ($page - 1);
	
	//页码范围计算
	$init = 1; //起始页码数
	$max = $pages; //结束页码数
	$pagelen = ($pagelen % 2) ? $pagelen : $pagelen + 1; //页码个数
	$pageoffset = ($pagelen - 1) / 2; //页码个数左右偏移量
	

	//生成html
	$pagecode = '<div class="page">';
	$pagecode .= "<span class='totalNum' title='总页数'>{$pages}</span><span class=' last'></span>"; //总页数
	//如果是第一页，则不显示第一页和上一页的连接
	if ($page != 1) {
		$pagecode.="<span class='arrow'><a href='{$phpfile}?page=1' title='第一页'>&lt;&lt;</a></span>";//第一页
		$pagecode.="<span class='arrow'><a href='{$phpfile}?page=".($page-1)."' title='上一页'>&lt;</a></span>"; //上一页
	}
	//分页数大于页码个数时可以偏移
	if ($pages > $pagelen) {
		//如果当前页小于等于左偏移
		if ($page <= $pageoffset) {
			$init = 1;
			$max = $pagelen;
		} else { //如果当前页大于左偏移
			//如果当前页码右偏移超出最大分页数
			if ($page + $pageoffset >= $pages + 1) {
				$init = $pages - $pagelen + 1;
			} else {
				//左右偏移都存在时的计算
				$init = $page - $pageoffset;
				$max = $page + $pageoffset;
			}
		}
	}
	//生成html
	for($i = $init; $i <= $max; $i ++) {
		if ($i == $page) {
			$pagecode .= '<span class="selected">' . $i . '</span>';
		} else {
			$pagecode.="<span><a href='{$phpfile}?page={$i}'>$i</a></span>";
		}
	}
	if ($page != $pages) {
		$pagecode.="<span class='arrow'><a href='{$phpfile}?page=".($page+1)."' title='下一页'>&gt;</a></span>"; //下一页
		$pagecode.="<span class='arrow'><a href='{$phpfile}?page={$pages}' title='最后一页'>&gt;&gt;</a></span>";//最后一页
	}
	$phpfile = explode('?', $phpfile);
	$params = $phpfile[1];
	if(!empty($params)){
		preg_replace("|&page=\d+|", '', $params);
		$phpfile = $phpfile[0].'?'.$params.($params?'&':'');
	}else{
		$phpfile = $phpfile[0].'?';
	}
//	$pagecode.='<span class="last"><input type="text" name="" onclick="window.location.href=\"'.$phpfile.'\""/></span></div>';
	$pagecode.='</div>';
	return $pagecode;
}