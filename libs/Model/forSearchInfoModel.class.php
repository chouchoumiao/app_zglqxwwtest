<?php
session_start();
require_once('commonModel.class.php');
header("Content-Type: text/html; charset=UTF-8");
class forSearchInfoModel extends commonModel{

	private $_table;
	private $weixinID;

	public function __construct(){
		$this->weixinID = $_SESSION['weixinID'];
		$this->_table = 'Vip';
	}

	function getVipList(){
		//ȡ�û�Ա�û�������    ---------
		$count = $this->getVipCount();
		//�����ݱ��������
		if($count){
			//ÿҳ��ʾ��¼��
			$multiArr = parent::getMulti();

			$class_list = $this->getVipWithMulti($multiArr);

			for($i = 0; $i<count($class_list); $i++){
				if($class_list[$i]['Vip_sex'] == 1){
					$class_list[$i]['Vip_sex'] = '男';
				}elseif($class_list[$i]['Vip_sex'] == 0){
					$class_list[$i]['Vip_sex'] == '男';
				}else{
					$class_list[$i]['Vip_sex'] == '未知';
				}
			}
			$retArr = array(
				'count' => $count,
				'page_num' => $multiArr['showCount'],
				'page' => $multiArr['page'],
				'showCount' => $multiArr['showCount'],
				'class_list' => $class_list,
			);
			return $retArr;
		}else{
			return array();
		}
	}


	/**
	 * ȡ�������û�������
	 * private
	 * @return mixed
	 */
	private function getVipCount(){
		$sql="select COUNT(*) from ".$this->_table." where WEIXIN_ID = $this->weixinID AND Vip_isDeleted = 0 ";
		return $count = DB::findResult($sql);
	}


	/**
	 * ��ݷ�ҳ��Ϣȡ����ػ�Ա��Ϣ
	 * private
	 * @param $arr
	 * @return mixed
	 */
	private function getVipWithMulti($arr){
		//��ȡ������������
		$sql = "select * from Vip
				where WEIXIN_ID = $this->weixinID
				AND Vip_isDeleted = 0
				order by Vip_id DESC
				limit $arr[from_record],$arr[showCount]";
		return DB::findAll($sql);
	}
}