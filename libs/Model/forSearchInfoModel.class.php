<?php
session_start();
require_once('commonModel.class.php');
header("Content-Type: text/html; charset=UTF-8");
class forSearchInfoModel extends commonModel{

	private $_table;
	private $weixinID;
	private $weixinName;

	public function __construct(){
		$this->weixinID = $_SESSION['weixinID'];
		$this->weixinName = $_SESSION['weixinName'];
		$this->_table = 'Vip';
	}

	function getVipList(){
		//取得会员用户的总数
		$count = $this->getVipCount();
		//如果数据表里有数据
		if($count){
			//每页显示记录数
			$multiArr = parent::getMulti(); //取得分页信息(公共Model类里抽取)

			$class_list = $this->getVipWithMulti($multiArr); //根据分页信息取得会员人员信息

			for($i = 0; $i<count($class_list); $i++){
				if($class_list[$i]['Vip_sex'] == 1){
					$class_list[$i]['Vip_sex'] = '男';
				}elseif($class_list[$i]['Vip_sex'] == 0){
					$class_list[$i]['Vip_sex'] = '女';
				}else{
					$class_list[$i]['Vip_sex'] = '未知';
				}
			}
			$retArr = array(
				'count' => $count,
				'page_num' => $multiArr['showCount'],
				'page' => $multiArr['page'],
				'showCount' => $multiArr['showCount'],
				'class_list' => $class_list,
				'weixinName' => $this->weixinName
			);
			return $retArr;
		}else{
			return array();
		}
	}


	/**
	 * 取得所用用户的总数
	 * private
	 * @return mixed
	 */
	private function getVipCount(){
		$sql="select COUNT(*) from ".$this->_table." where WEIXIN_ID = $this->weixinID AND Vip_isDeleted = 0 ";
		return $count = DB::findResult($sql);
	}

	/**
	 * 根据分页信息取得相关会员信息
	 * private
	 * @param $arr
	 * @return mixed
	 */
	private function getVipWithMulti($arr){
		//获取符合条件的数据
		$sql = "select * from Vip
				where WEIXIN_ID = $this->weixinID
				AND Vip_isDeleted = 0
				order by Vip_id DESC
				limit $arr[from_record],$arr[showCount]";
		return DB::findAll($sql);
	}

	function delVipByID($id){

		$nowtime=date("Y/m/d H:i:s",time());

		$sql = "update Vip
            set Vip_isDeleted = 1,
                Vip_edittime = '$nowtime'
            where Vip_id = $id
            AND WEIXIN_ID = $this->weixinID";;
		$errno = DB::query($sql);
		if(!$errno){
			return false;
		}
		return true;
	}
}