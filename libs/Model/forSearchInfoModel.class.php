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
	}

	/**
	 * 获得会员一览表
	 * @return array
	 */
	function getVipList(){
		$this->_table = 'Vip';
		$_whereCount = "WEIXIN_ID = $this->weixinID AND Vip_isDeleted = 0";
		$_whereInfo = "WEIXIN_ID = $this->weixinID AND Vip_isDeleted = 0 order by Vip_id DESC";

		$arr = parent::getList($this->_table,$_whereCount,$_whereInfo);
		if($arr['class_list']){
			for($i = 0; $i<count($arr['class_list']); $i++){
				if($arr['class_list'][$i]['Vip_sex'] == 1){
					$arr['class_list'][$i]['Vip_sex'] = '男';
				}elseif($arr['class_list'][$i]['Vip_sex'] == 0){
					$arr['class_list'][$i]['Vip_sex'] = '女';
				}else{
					$arr['class_list'][$i]['Vip_sex'] = '未知';
				}
			}
			return $arr;
		}else{
			return array();
		}
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

	/**
	 * 取得分类信息
	 * 公有方法
	 * @return array
	 */
	function getQAClassInfoList(){
		$this->_table = 'question_class';
		$_whereCount = "WEIXIN_ID = $this->weixinID";
		$_whereInfo = "WEIXIN_ID = $this->weixinID order by id DESC";

		$arr = parent::getList($this->_table,$_whereCount,$_whereInfo);

		if($arr['class_list']){
			$arr['class_list'] = $this->setQAClassUseredInfo($arr['class_list']);
			return $arr;
		}else{
			return array();
		}
	}

	/**
	 * 根据传入的ID传输会员信息
	 * @param $id
	 * @return bool
	 */
	function delQAClassByID($id){

		$sql = "delete from question_class where id = $id AND WEIXIN_ID = $this->weixinID";;
		$errno = DB::query($sql);
		if(!$errno){
			return false;
		}
		return true;
	}

	/**
	 * 判断根据题目的分类，判断是否分类被使用，并追加如分类的数组中
	 * 私有方法
	 * @param $class_list
	 * @return mixed
	 */
	private function setQAClassUseredInfo($class_list){
		for($i=0;$i<count($class_list);$i++){
			$theTitle = $class_list[$i]['question_class_title'];
			$sql = "select COUNT(*) from question_master
                where WEIXIN_ID = $this->weixinID
                AND QUESTION_CLASS = '$theTitle'";
			$class_list[$i]['stateList'] = DB::findResult($sql);
		}
		return $class_list;
	}



	function getQuestionOkCountList(){
		$sql = "select * from question_master
        where WEIXIN_ID = $this->weixinID
        AND QUESTION_SATUS <> -1 ";
		return DB::findAll($sql);
	}


	function getQuestionOk200(){

		$masterID = addslashes($_POST["masterID"]);
		if(!isset($masterID)){
			$arr['success'] = 0;
			$arr['msg'] = '无法获得参数值';
			return $arr;
		}

		//取得前所有信息 并且根据答对题目数降序，用时升序排列
		$sql = "select distinct answer_recorded_openid from answer_recorded
        where WEIXIN_ID = $this->weixinID
        AND question_master_ID = '$masterID'
        AND status = 0
        AND answer_recorded_OKCount = 10
        order by answer_recorded_editTime ASC
        limit 200";

		$record =  DB::findAll($sql);

		//取得前所有信息 并且根据答对题目数降序，用时升序排列
		if(!$record){
			$arr['success'] = 0;
			$arr['msg'] = '未能取得数据'.$sql;
			return $arr;
		}


		$vipIDStr = "";
		$vipNameStr = "";
		$vipTelStr = "";
		$dataStr = "";

		$recordCount = count($record);
		for($i=0; $i<$recordCount; $i++){

			$sql = "select Vip_id,Vip_name,Vip_tel from Vip
            where  Vip_isDeleted = 0
            AND Vip_openid = '".$record[$i]['answer_recorded_openid']."'";
			$getVipData = DB::findOne($sql);

			$sql = "select answer_recorded_editTime from answer_recorded
            where WEIXIN_ID = $this->weixinID
            AND question_master_ID = $masterID
            AND status = 0
            AND answer_recorded_OKCount = 10
            AND answer_recorded_openid = '".$record[$i]['answer_recorded_openid']."'
            order by answer_recorded_editTime ASC";
			$OKCountData = DB::findAll($sql);

			if($vipIDStr == ""){

				$vipIDStr = $getVipData['Vip_id'];
				$vipNameStr = $getVipData['Vip_name'];
				$vipTelStr = $getVipData['Vip_tel'];

				$dataStr = $OKCountData[0]['answer_recorded_editTime'];
			}else{

				$vipIDStr = $vipIDStr.",".$getVipData['Vip_id'];
				$vipNameStr = $vipNameStr.",".$getVipData['Vip_name'];
				$vipTelStr = $vipTelStr.",".$getVipData['Vip_tel'];

				$dataStr = $dataStr.",".$OKCountData[0]['answer_recorded_editTime'];
			}
		}
		$arr['success'] = "OK";
		$arr['vipIDStr'] = $vipIDStr;
		$arr['vipNameStr'] = $vipNameStr;
		$arr['vipTelStr'] = $vipTelStr;
		$arr['dataStr'] = $dataStr;

		return $arr;

	}

}