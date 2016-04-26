<?php
require_once('commonModel.class.php');
class adminModel extends commonModel{

	public $_table = 'AdminUser';

	function findOne_by_username($username){
		$sql = "select * from ".$this->_table." where username='$username' and isdeleted = 0";
		return DB::findOne($sql);
	}

	/**
	 * ���ݷ�ҳȡ�����к�̨�û���Ϣ
	 * public
	 * @return array
	 */
	function getUserByAdmin(){
		//ȡ�������û�������
		$count = $this->getUserCountByAdmin();
		//������ݱ���������
		if($count){
			//ÿҳ��ʾ��¼��
			$multiArr = parent::getMulti(); //ȡ�÷�ҳ��Ϣ(����Model�����ȡ)

			$class_list = $this->getUserWithMulti($multiArr); //���ݷ�ҳ��Ϣȡ�������Ա��Ϣ
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
	private function getUserCountByAdmin(){
		$sql="select COUNT(*) from ".$this->_table." where isdeleted = 0 ";
		return $count = DB::findResult($sql);
	}

	/**
	 * ���ݷ�ҳ��Ϣȡ�������Ա��Ϣ
	 * private
	 * @param $arr
	 * @return mixed
	 */
	private function getUserWithMulti($arr){

		//��ȡ��������������
		$sql = "select * from AdminUser
					where isdeleted = 0
					order by id asc
					limit $arr[from_record],$arr[showCount]";
		return DB::findAll($sql);
	}

	function delUserByID($id){

		$nowtime=date("Y/m/d H:i:s",time());
		$sql = "update AdminUser set isdeleted = 1,editTime = '$nowtime' where id = $id";
		$errno = DB::query($sql);
		if(!$errno){
			return false;
		}
		return true;
	}
}