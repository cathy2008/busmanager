<?php
require 'dbaccess.php';
header ( 'content-type:text/html;charset=utf-8' );
$openid = "1";
$db = new DB ();
$currentdate = date ( 'Y-m-d', time () );
$from = $currentdate . " 00:00:00";
$sql_name = "select a.FName as employee, b.FName as company from t_hs_employee a inner join t_hs_company b on a.FCompanyID=b.FID where a.FID='{$openid}'";
$result_name = $db->getrow ( $sql_name );
echo $result_name ['employee'];
echo $result_name ['company'];
/**
 * ****************预约查看*********************
 */
$sql_check = "select * from t_hs_overwork_reserv where FEmployeeID='{$openid}' and FRDate>='{$from}'";
$result_check = $db->execsql ( $sql_check );
$num = count ( $result_check );
if ($num) {
	for($i = 0; $i < $num; $i ++) {
		$sql_state = "select FName from  t_hs_stop where FID='{$result_check[$i]['FStopID']}'";
		$result_state = $db->getrow ( $sql_state );
		// print_r($result_state);
		$result_check [$i] ['FStopID'] = $result_state ['FName'];
		print_r ( $result_check [$i] );
	}
} else {
	echo "无预约记录";
}
$act = $_GET ['act'];
if ($act == 'modify') {
	/**
	 * *****************预约修改******************
	 */
	
	/*
	 * ajax({
	 * url:busmanage.php?act='modify',
	 * dataType:,
	 * Type:,
	 * data:{"FSTOP":fstop,//下车站点
	 * "BTIME":btime,//预约班车时间
	 * "BDATE":bdate},//预约班车日期
	 *
	 * });
	 *
	 */
	$BTime = $_POST ['BTIME'];
	$BDate = $_POST ['BDATE'];
	$FStop = $_POST ['FSTOP'];
	$sql_stop = "select FID from t_hs_stop where FName='{$FStop}' ";
	$result_stop = $db->getrow ( $sql_stop );
	$time = date ( 'Y-m-d H:i:s', time () );
	// echo $time;
	$sql_mod = "update into t_hs_overwork_reserv set FStopID='{$result_stop['FID']}' and FRTime='{$FStop}' and FRDate='{$BDate}' and FDate='{$time}' and FType='{}'";
	$reslut_mod = $db->execsql ( $sql_mod );
	if ($reslut_mod) {
		echo 1; // 修改成功
	} else {
		echo 0; // 修改失败
	}
} else {
	/**
	 * *****************预约删除******************
	 */
	/*
	 * ajax({
	 * url:busmanage.php?act='delete',
	 * dataType:,
	 * Type:,
	 * data:{"FNAME":fname,//职工姓名
	 * "BDATE":bdate},//预约班车日期
	 *
	 * });
	 */
	$FName = $_POST ['FNAME'];
	$BDate = $_POST ['BDATE'];
	$sql_id = "select FID from t_hs_employee where FName='{$FName}' ";
	$result_id = $db->getrow ( $sql_name );
	$sql_del = "delete t_hs_overwork_reserv where FEmployeeID='{$result_id['FID']}' and FRDate='{$BDate}'";
	$result_del = $db->execsql ( $sql_del );
	if ($result_del) {
		echo 1; // 删除成功
	} else {
		echo 0; // 删除失败
	}
}
?>
