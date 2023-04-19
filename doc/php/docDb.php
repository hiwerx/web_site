<?php
//导入必要的包
include ('../../php/DataBasic.php');
include ('../../php/JsonResult.php');
$db = new DataBasic();
$jsonResult = new JsonResult();
//取参数值
$type = $_GET['type'];
$querySql=null;
if($type==0) {
	// book页面使用
	$id = $_GET['id'];
	$querySql = "select * from doc where id = ".$id;
	$res = $db->execute($querySql);
	if($res !=-1 && count($res)>0) {
		echo $jsonResult->succ1($res[0]);
	} else {
		echo $jsonResult->fail0();
	}
	exit;
} else if($type==1) {
	// list页面使用
	$querySql = "select * from doc where sub < 1 limit 0 , 30";
	$res = $db->execute($querySql);
	if($res !=-1) {
		echo $jsonResult->succ1($res);
	} else {
		echo $jsonResult->fail0();
	}
	exit;
} else if($type==2) {
	//detail页面使用
	$id = $_GET['id'];
	$querySql = "select * from doc where id = ".$id;
	$res = $db->execute($querySql);
	$mainDoc;
	if($res !=-1 && count($res)>0) {
		$mainDoc = $res[0];
	} else {
		echo $jsonResult->fail1("未查询到主文档");
		exit;
	}
	if($mainDoc['sub']>0) {
		// sub大于0说明是子文档，需查询主文档
		$querySql = "select * from doc where id = ".$sub;
		$res = $db->execute($querySql);
		if($res !=-1 && count($res)>0) {
			$mainDoc = $res[0];
		} else {
			echo $jsonResult->fail1("未查询到主文档");
			exit;
		}
	}
	$subDocs = array();
	// 查询子文档
	if($mainDoc['sub']==-1) {
		$querySql = "select * from doc where sub = '".$mainDoc['id']."' order by sort ";
		$res = $db->execute($querySql);
		if($res!=-1) {
			$subDocs=$res;
		}
	}
	// 订单详情页使用
	$detail = array('doc'=>$mainDoc,'list'=>$subDocs);
	echo $jsonResult->succ1($detail);
} else {
	echo $jsonResult->fail1("bussy not support");
	exit;
}