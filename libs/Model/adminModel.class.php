<?php
	class adminModel{

		public $_table = 'AdminUser';

		function findOne_by_username($username){
			$sql = "select * from ".$this->_table." where username='$username' and isdeleted = 0";
			return DB::findOne($sql);
		}

		function getUserByAdmin(){

			if(!isset($_GET["page"])){
				$page = 0;
			}else{
				$page=intval(addslashes($_GET["page"]));
			}

			if(isset($_GET['showCount'])){
				$showCount = intval(addslashes($_GET['showCount']));
			}else{
				$showCount = 0;
			}

			if($showCount == 0){
				$showCount = 5; //默认为5条
			}
			$count = $this->getUserCountByAdmin();
			//如果数据表里有数据
			if($count){
				//每页显示记录数
				$page_num = $showCount;
				//如果无页码参数则为第一页
				if ($page == 0) $page = 1;
				//计算开始的记录序号
				$from_record = ($page - 1) * $page_num;
				//获取符合条件的数据
				$sql = "select * from AdminUser
						where isdeleted = 0
						order by id asc
						limit $from_record,$page_num";
				$class_list =  DB::findAll($sql);
				return $class_list;

			}
		}

		private function getUserCountByAdmin(){
			$sql="select COUNT(*) from ".$this->_table." where isdeleted = 0 ";
			return $count = DB::findResult($sql);
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