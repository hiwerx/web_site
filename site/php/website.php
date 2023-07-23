<?php
//导入必要的包
include ('../../php/JsonResult.php');
include ('../../php/DataBasic.php');
$jsonResult = new JsonResult();
$db = new DataBasic();
$sql ='';
$res = -1;
if(!isset($_GET['type'])) {
	echo $jsonResult->fail1('类型不匹配');
	exit();
}
$type = $_GET['type'];
if($type==1) {
	// 查询普通
	$page= isset($_GET['page'])?$_GET['page']:1;
	$pageNum=20;
	$skipSize = ($page-1)*$pageNum;
	$sql = 'SELECT name,url,info,icon FROM web_site WHERE TYPE !=9 LIMIT '.$skipSize.','.$pageNum;
	$res = $db->execute($sql);
}
if($res!=-1) {
	echo $jsonResult->succ2($res,"操作成功");
} else {
	echo $jsonResult->fail1("操作失败");
}