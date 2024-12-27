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

    $page = isset($_GET['page'])?$_GET['page']:1;
    $pageSize = isset($_GET['pageSize'])?$_GET['pageSize']:30;
    if (!(isInteger($pageSize)&&isInteger($page))){
        echo $jsonResult->fail1('参数错误');
        exit();
    }
    if ($pageSize>30)$pageSize=30;
    $skipNum = ($page-1)*$pageSize;

	$querySql = "select * from doc where sub < 1 order by cttime desc limit $skipNum, $pageSize";
	$res = $db->execute($querySql);
	if($res !=-1) {
	    $count=0;
	    $countSql = "select count(*) ct from doc where sub < 1";
	    $countRes = $db->execute($countSql);
	    if ($countRes!=-1)$count = $countRes[0]['ct'];
		echo $jsonResult->succ1(array('list'=>$res,'count'=>$count));
	} else {
		echo $jsonResult->fail0();
	}
	exit;
} else if($type==2) {
	//detail页面使用
	$id = $_GET['id'];
	$querySql = "select * from doc where id = ".$id;
	$res = $db->execute($querySql);
    $mainDoc= array();
	if($res !=-1 && count($res)>0) {
		$mainDoc = $res[0];
	} else {
		echo $jsonResult->fail1("未查询到主文档");
		exit;
	}
	if($mainDoc['sub']>0) {
		// sub大于0说明是子文档，需查询主文档
		$querySql = "select * from doc where id = ".$mainDoc['sub'];
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
		$querySql = "select * from doc where sub = '".$mainDoc['id']."' order by sort ASC";
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

function isInteger($input){
    return(ctype_digit(strval($input)));
}