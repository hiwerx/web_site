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
} else if($type==1024) {
    // 查询类型2站点
    $page= isset($_GET['page'])?$_GET['page']:1;
    $pageNum=20;
    $skipSize = ($page-1)*$pageNum;
    $sql = 'SELECT name,url,info,icon FROM web_site WHERE TYPE =9 LIMIT '.$skipSize.','.$pageNum;
    $res = $db->execute($sql);
} else if($type==3) {
    // 插入数据
    $json = file_get_contents('php://input');
    // 将其转换为 PHP 变量
    $reqData = json_decode($json, true);
    $name = $reqData['name'];
    $url = $reqData['url'];
    $type = $reqData['type'];
    $love = $reqData['love'];
    $info = $reqData['info'];
    if($url==null||$name==null||$info==null) {
        echo $jsonResult->fail1('不能送空值！');
        exit();
    }
    $res=$db->save('web_site',$reqData);
} else if($type==4) {
    // 更新数据
    $json = file_get_contents('php://input');
    // 将其转换为 PHP 变量
    $reqData = json_decode($json, true);
    if(isset($reqData['id'])){
        $res=$db->update('web_site',$reqData,array('id'=>$reqData['id']));
    }else{
        $res=-1;
    }

}
if($res!=-1) {
	echo $jsonResult->succ2($res,"操作成功");
} else {
	echo $jsonResult->fail1("操作失败");
}