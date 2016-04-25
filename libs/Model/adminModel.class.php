<?php
	class adminModel{

		public $_table = 'AdminUser';

		function findOne_by_username($username){
			$sql = "select * from ".$this->_table." where username='$username' and isdeleted = 0";
			return DB::findOne($sql);
		}

		/**
		 * 根据分页取得所有后台用户信息
		 * public
		 * @return array
		 */
		function getUserByAdmin(){

			//取得所用用户的总数
			$count = $this->getUserCountByAdmin();
			//如果数据表里有数据
			if($count){
				//每页显示记录数
				$multiArr = $this->getMulti(); //取得分页信息
				$class_list = $this->getUserWithMulti($multiArr); //根据分页信息取得相关人员信息
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
		 * 取得所用用户的总数
		 * private
		 * @return mixed
		 */
		private function getUserCountByAdmin(){
			$sql="select COUNT(*) from ".$this->_table." where isdeleted = 0 ";
			return $count = DB::findResult($sql);
		}

		/**
		 * 取得分页信息
		 * private
		 * @return array
		 */
		private function getMulti(){
			if(!isset($_GET["page"])){
				$page = 1;
			}else{
				$page=intval(addslashes($_GET["page"]));
			}
			if(isset($_GET['showCount'])){
				$showCount = intval(addslashes($_GET['showCount']));
			}else{
				$showCount = 5;
			}
			return array(
				'page' => $page,
				'showCount' =>$showCount,
				'from_record' =>($page - 1) * $showCount  //计算开始的记录序号
			);
		}

		/**
		 * 根据分页信息取得相关人员信息
		 * private
		 * @param $arr
		 * @return mixed
		 */
		private function getUserWithMulti($arr){

			//获取符合条件的数据
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