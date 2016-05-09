<?php
session_start();
class exchangeModel{

	private $weixinID;

	/**
	 * 初始化weixinID
	 */
	public function __construct(){
		$this->weixinID = $_SESSION['weixinID'];
	}

	/**
	 * 公有函数
	 * searchBill的情况 ：查询对应的获奖
	 * Awarded的情况 ：更新兑奖状态
	 * @return array
	 */
	public function doActionMod(){

		$arr = array();

		if(!isset($_GET['action'])){
			$arr['success'] = 0;
			$arr['msg'] = "取不到参数！";
			return $arr;
		}
		$action = addslashes($_GET['action']);

		if($action == "searchBill"){

			//取得set页面传递过来的数据
			if(!isset($_POST["SNLast6"])){
				$arr['success'] = 0;
				$arr['msg'] = "取不到输入的六位兑换码！";
				return $arr;
			}

			//根据SN取得取得该会员的中奖信息
			$billDataArr = $this->getWinningInfoBySNCode(addslashes($_POST["SNLast6"]));

			if(!$billDataArr){
				$arr['success'] = 0;
				$arr['msg'] = "无该兑换码所对应的中奖信息，请确认！";
				return $arr;
			}

			//取得中奖信息
			$Bill_type = $billDataArr['Bill_type'];

			if($Bill_type == "001"){
				$billDataArr['Bill_type'] = "积分商城";
			}else if($Bill_type == "002"){
				$billDataArr['Bill_type'] = "大转盘";
			}else if($Bill_type == "003"){
				$billDataArr['Bill_type'] = "刮刮卡";
			}else if($Bill_type == "004"){
				$billDataArr['Bill_type'] = "印章";
			}else{
				$billDataArr['Bill_type'] = "未知";
			}

			$arr['success'] = 1;
			$arr['Bill_type'] = $billDataArr['Bill_type'];
			$arr['bill_Name'] = $billDataArr['Vip_name'];
			$arr['bill_Tel'] = $billDataArr['Vip_tel'];
			$arr['Bill_GoodsName'] = $billDataArr['Bill_GoodsName'];
			$arr['Bill_GoodsDescription'] = $billDataArr['Bill_GoodsDescription'];
			$arr['Bill_SN'] = $billDataArr['Bill_SN'];
			$arr['Bill_insertDate'] = $billDataArr['Bill_insertDate'];
			$arr['Bill_goods_beginDate'] = $billDataArr['Bill_goods_beginDate'];
			$arr['Bill_goods_endDate'] = $billDataArr['Bill_goods_endDate'];
			$arr['Bill_goods_expirationDate'] = $billDataArr['Bill_goods_expirationDate'];
			$arr['Bill_Status'] = $billDataArr['Bill_Status'];

			return $arr;

		}else if($action == "Awarded"){

			if(!isset($_POST['SNCode'])){
				$arr['success'] = 0;
				$arr['msg'] = "取不到兑换码！";
				return $arr;
			}

			if($this->setExchangeInfo(addslashes($_POST['SNCode']))){
				$arr['success'] = 1;
				$arr['msg'] = "兑换成功！两秒后跳转...";
			}else{
				$arr['success'] = 0;
				$arr['msg'] = "兑换失败，请确认兑换码！";
			}
			return $arr;
		}
	}

	/**
	 * 根据SN取得取得该会员的中奖信息
	 * @param $SnCode
	 * @return mixed
	 */
	private function getWinningInfoBySNCode($SnCode)
	{
		$sql = "select b.*,v.* from  bill b,Vip v
			where b.WEIXIN_ID = $this->weixinID
			AND b.Bill_openid = v.Vip_openid
			AND b.Bill_SN
			LIKE '%$SnCode'
			order by b.Bill_id";
		return DB::findOne($sql);
	}

	private function setExchangeInfo($SNCode){
		$nowtime=date("Y/m/d H:i:s",time());
		$updateBill = "update bill
			   set Bill_editDate = '$nowtime',
				   Bill_Status = 1
			   where Bill_SN = '$SNCode'
			   AND WEIXIN_ID = $this->weixinID";
		return DB::query($updateBill);
	}


}