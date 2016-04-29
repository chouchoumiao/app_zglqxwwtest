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

	/**
	 * 获得会员一览表
	 * @return array
	 */
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

	/**
	 * 根据传入的ID传输会员信息
	 * @param $id
	 * @return bool
	 */
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

	/**
	 * 获取签到码
	 * @return mixed
	 */
	function getDailyCodeWithWeixinID(){

		//根据微信号从数据库中取得有效的签到码
		$data = $this->getCode();

		if(!$data['dailyCode']){
			$code = $this->randomkeys(12);
		}else{
			$code = $data['dailyCode'];
		}

		//数据库表中已经存在当天的数据
		if(!$data['dailyCode']){
			//将原来的有效的数据都设置为无效
			if(!$this->setCodeEnable()){
				$arr['success'] = "NG";
				$arr['msg'] = "原有签到码状态设置失败！";
				return $arr;
			}

			//追加新的签到码到数据库
			if($this->addNewCode($code)){
				$arr['success'] = "OK";
				$arr['msg'] = $code;
				$arr['date'] = date("Y-m-d",time());
			}else{
				$arr['success'] = "NG";
				$arr['msg'] = "取得失败！";
			}
			return $arr;

		}else{
			$arr['success'] = "OK";
			$arr['msg'] = $code;
			$arr['date'] = date("Y-m-d",time());

			return $arr;
		}
	}

	/**
	 * 根据微信号从数据库中取得有效的签到码
	 * @return mixed
	 */
	private function getCode(){
		$nowDate = date("Y-m-d",time());

		$sql = "select * from vipDailySet
        where editDate = '$nowDate'
        and WEIXIN_ID = '$this->weixinID'
        and flag = 1";
		return DB::findOne($sql);
	}

	/**
	 * 设置原有签到码的状态都为无效
	 * @return mixed
	 */
	private function setCodeEnable(){
		$sql = "update vipDailySet set flag = 0 where WEIXIN_ID = '$this->weixinID'";
		return DB::query($sql);
	}

	/**
	 * 追加新的签到码到数据库
	 * @param $code
	 * @return mixed
	 */
	private function addNewCode($code){

		$nowDate = date("Y-m-d",time());
		$sql = "insert into vipDailySet
                        (WEIXIN_ID,
                        dailyCode,
                        editDate,flag
                        ) values (
                        $this->weixinID,
                        '$code',
                        '$nowDate',
                        1
                        )";
		return DB::query($sql);
	}
	/**
	 * 做成签到码
	 * @param $length
	 * @return string
	 */
	private function randomkeys($length)
	{
		$key ="";
		$pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
		for($i=0;$i<$length;$i++)
		{
			$key .= $pattern{mt_rand(0,35)};    //生成php随机数
		}
		return $key;
	}



}